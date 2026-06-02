<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class CinetPayService
{
    protected $apiKey;
    protected $siteId;
    protected $secretKey;
    protected $apiUrl;
    protected $checkUrl;

    public function __construct($formation = null)
    {
        $this->apiKey = config('cinetpay.api_key');
        $this->apiUrl = config('cinetpay.api_url');
        $this->checkUrl = config('cinetpay.check_url');

        // Déterminer le site_id selon la formation
        if ($formation) {
            $formationKey = $this->getFormationKey($formation);
            $siteConfig = config("cinetpay.sites.{$formationKey}");

            $this->siteId = $siteConfig['site_id'] ?? config('cinetpay.sites.design_graphique.site_id');
            $this->secretKey = $siteConfig['secret_key'] ?? config('cinetpay.sites.design_graphique.secret_key');
        } else {
            // Par défaut, utiliser Design Graphique
            $this->siteId = config('cinetpay.sites.design_graphique.site_id');
            $this->secretKey = config('cinetpay.sites.design_graphique.secret_key');
        }
    }

    /**
     * Convertir le nom de formation en clé de config
     */
    protected function getFormationKey($formation)
    {
        $mapping = [
            // Versions avec majuscules
            'Design Graphique' => 'design_graphique',
            'Community Management' => 'community_management',
            'Gestion Informatique' => 'gestion_informatique',
            'Intelligence Artificielle' => 'intelligence_artificielle',
            // Versions en minuscules (depuis la BDD)
            'design_graphique' => 'design_graphique',
            'community_management' => 'community_management',
            'gestion_informatique' => 'gestion_informatique',
            'intelligence_artificielle' => 'intelligence_artificielle',
            'design_graphique_community_management' => 'design_graphique', // Combo
        ];

        return $mapping[$formation] ?? 'design_graphique';
    }

    /**
     * Initialiser un paiement
     */
    public function initiatePayment($data)
    {
        try {
            $payload = [
                'apikey' => $this->apiKey,
                'site_id' => $this->siteId,
                'transaction_id' => $data['transaction_id'],
                'amount' => $data['amount'],
                'currency' => config('cinetpay.currency'),
                'description' => $data['description'] ?? 'Paiement formation EVC',
                'customer_name' => $data['customer_name'] ?? '',
                'customer_surname' => $data['customer_surname'] ?? '',
                'customer_email' => $data['customer_email'] ?? '',
                'customer_phone_number' => $data['customer_phone'] ?? '',
                'customer_address' => $data['customer_address'] ?? '',
                'customer_city' => $data['customer_city'] ?? 'Abidjan',
                'customer_country' => $data['customer_country'] ?? 'CI',
                'customer_state' => $data['customer_state'] ?? 'CI',
                'customer_zip_code' => $data['customer_zip'] ?? '00225',
                'notify_url' => config('cinetpay.notify_url'),
                'return_url' => config('cinetpay.return_url'),
                'channels' => config('cinetpay.channels'),
                'metadata' => $data['metadata'] ?? '',
                'lang' => config('cinetpay.lang'),
                'invoice_data' => $data['invoice_data'] ?? [],
            ];

            Log::info('CinetPay - Initiation paiement', [
                'transaction_id' => $data['transaction_id'],
                'amount' => $data['amount'],
                'site_id' => $this->siteId,
                'api_url' => $this->apiUrl,
                'payload' => $payload
            ]);

            $response = Http::timeout(30)->post($this->apiUrl, $payload);

            if ($response->failed()) {
                Log::error('CinetPay - Erreur HTTP', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'payload' => $payload
                ]);
                throw new Exception('Erreur de connexion à CinetPay: ' . $response->body());
            }

            $result = $response->json();

            Log::info('CinetPay - Réponse API', $result);

            if (isset($result['code']) && $result['code'] == '201') {
                return [
                    'success' => true,
                    'payment_url' => $result['data']['payment_url'],
                    'payment_token' => $result['data']['payment_token'] ?? null,
                    'transaction_id' => $data['transaction_id'],
                ];
            }

            throw new Exception($result['message'] ?? 'Erreur lors de l\'initialisation du paiement');

        } catch (Exception $e) {
            Log::error('CinetPay - Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Vérifier le statut d'un paiement
     */
    public function checkPaymentStatus($transactionId)
    {
        try {
            $payload = [
                'apikey' => $this->apiKey,
                'site_id' => $this->siteId,
                'transaction_id' => $transactionId,
            ];

            Log::info('CinetPay - Vérification statut', [
                'transaction_id' => $transactionId,
                'site_id' => $this->siteId
            ]);

            $response = Http::timeout(30)->post($this->checkUrl, $payload);

            if ($response->failed()) {
                throw new Exception('Erreur de connexion à CinetPay');
            }

            $result = $response->json();

            Log::info('CinetPay - Statut paiement', $result);

            if (isset($result['code']) && $result['code'] == '00') {
                return [
                    'success' => true,
                    'status' => $result['data']['status'] ?? 'PENDING',
                    'operator_id' => $result['data']['operator_id'] ?? null,
                    'payment_method' => $result['data']['payment_method'] ?? null,
                    'data' => $result['data'] ?? [],
                ];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? 'Paiement non trouvé'
            ];

        } catch (Exception $e) {
            Log::error('CinetPay - Erreur vérification', [
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Valider la signature du webhook
     */
    public function validateWebhookSignature($data, $signature)
    {
        // CinetPay envoie un hash avec cpm_site_id + cpm_trans_id + cpm_trans_status + cpm_amount + cpm_currency + signature (apikey + site_id)
        $computedSignature = hash('sha256',
            $data['cpm_site_id'] .
            $data['cpm_trans_id'] .
            $data['cpm_trans_status'] .
            $data['cpm_amount'] .
            $data['cpm_currency'] .
            $this->apiKey .
            $this->siteId
        );

        return hash_equals($computedSignature, $signature);
    }

    /**
     * Obtenir le prix d'une formation
     */
    public static function getFormationPrice($formation, $registeredAt = null)
    {
        $prices = self::usesNewFormationPrices($registeredAt)
            ? config('cinetpay.prices', [])
            : config('cinetpay.old_prices', []);

        return $prices[$formation] ?? config("cinetpay.prices.{$formation}", 150000);
    }

    public static function getFormationInstallments($formation, $registeredAt = null): array
    {
        $installments = self::usesNewFormationPrices($registeredAt)
            ? config('cinetpay.installments', [])
            : config('cinetpay.old_installments', []);

        return $installments[$formation] ?? [75000, max(0, (int) self::getFormationPrice($formation, $registeredAt) - 75000)];
    }

    public static function usesNewFormationPrices($registeredAt = null): bool
    {
        if (empty($registeredAt)) {
            return true;
        }

        return strtotime((string) $registeredAt) >= strtotime((string) config('cinetpay.new_prices_effective_from', '2026-06-02 00:00:00'));
    }

    /**
     * Générer un ID de transaction unique
     */
    public static function generateTransactionId()
    {
        return 'EVC-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
    }
}
