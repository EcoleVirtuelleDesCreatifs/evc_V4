<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ChariowService
{
    protected $formation;
    protected $config;

    public function __construct($formation = null)
    {
        $this->formation = $formation;
        $this->config = config('chariow');
    }

    /**
     * Obtenir le lien de paiement pour une formation et une tranche
     */
    public function getPaymentLink($formation, $installmentNumber = 1)
    {
        $tranche = $installmentNumber == 1 ? 'tranche_1' : 'tranche_2';

        // Récupérer le lien de paiement depuis la config
        $link = $this->config['payment_links'][$formation][$tranche] ?? null;

        if (!$link) {
            Log::error('Lien de paiement Chariow introuvable', [
                'formation' => $formation,
                'tranche' => $tranche
            ]);

            // Fallback sur un lien générique
            $link = 'https://ecolevirtuelle.mychariow.shop/checkout';
        }

        Log::info('Lien de paiement Chariow généré', [
            'formation' => $formation,
            'installment' => $installmentNumber,
            'link' => $link
        ]);

        return $link;
    }

    /**
     * Générer l'URL de paiement avec paramètres
     */
    public function generatePaymentUrl($paymentData)
    {
        $formation = $paymentData['formation'] ?? $this->formation;
        $installmentNumber = $paymentData['installment_number'] ?? 1;

        $tranche = $installmentNumber == 1 ? 'tranche_1' : 'tranche_2';

        // Obtenir le lien de base
        $baseLink = $this->getPaymentLink($formation, $installmentNumber);

        // Déterminer le montant (priorité: paymentData > config formation_amounts > config amounts)
        $defaultAmount = $this->config['amounts'][$tranche] ?? 0;
        $formationAmount = $this->config['formation_amounts'][$formation][$tranche] ?? null;
        $amount = $paymentData['amount'] ?? ($formationAmount ?? $defaultAmount);

        // Ajouter des paramètres GET pour traçabilité
        $params = [
            'reference' => $paymentData['payment_reference'] ?? '',
            'email' => $paymentData['customer_email'] ?? '',
            'amount' => $amount,
            'return_url' => $this->config['return_url'],
            'cancel_url' => $this->config['cancel_url'],
        ];

        // Construire l'URL complète
        $url = $baseLink . '?' . http_build_query($params);

        Log::info('URL de paiement Chariow générée', [
            'url' => $url,
            'params' => $params
        ]);

        return $url;
    }

    /**
     * Vérifier le statut d'un paiement Chariow
     *
     * Note: Si Chariow ne fournit pas d'API de vérification,
     * on se base sur le callback/webhook ou la confirmation manuelle
     */
    public function checkPaymentStatus($transactionId)
    {
        // Si Chariow envoie des webhooks, les gérer dans PaymentController@chariowWebhook
        // Sinon, retourner un statut "en attente de confirmation"

        Log::info('Vérification statut paiement Chariow', [
            'transaction_id' => $transactionId
        ]);

        return [
            'success' => false,
            'status' => 'PENDING',
            'message' => 'En attente de confirmation du paiement Chariow'
        ];
    }

    /**
     * Traiter un webhook Chariow
     */
    public function handleWebhook($webhookData)
    {
        Log::info('Webhook Chariow reçu', $webhookData);

        // Adapter selon le format des webhooks Chariow
        // Exemple de structure attendue :
        // {
        //     "transaction_id": "CHARIOW-123456",
        //     "status": "success|failed|pending",
        //     "amount": 50000,
        //     "reference": "EVC-PAY-20251209-XXXXX",
        //     "customer_email": "client@example.com"
        // }

        $transactionId = $webhookData['transaction_id'] ?? null;
        $status = $webhookData['status'] ?? 'pending';
        $reference = $webhookData['reference'] ?? null;

        if (!$transactionId || !$reference) {
            Log::error('Webhook Chariow invalide : données manquantes');
            return false;
        }

        // Mapper les statuts Chariow vers notre système
        $statusMap = [
            'success' => 'ACCEPTED',
            'completed' => 'ACCEPTED',
            'paid' => 'ACCEPTED',
            'failed' => 'REFUSED',
            'declined' => 'REFUSED',
            'pending' => 'PENDING',
        ];

        $mappedStatus = $statusMap[strtolower($status)] ?? 'PENDING';

        return [
            'success' => $mappedStatus === 'ACCEPTED',
            'status' => $mappedStatus,
            'transaction_id' => $transactionId,
            'reference' => $reference,
            'amount' => $webhookData['amount'] ?? 0,
        ];
    }

    /**
     * Obtenir les montants configurés
     */
    public function getAmounts()
    {
        return $this->config['amounts'];
    }

    /**
     * Vérifier si Chariow est activé
     */
    public static function isEnabled()
    {
        return config('chariow.enabled', false);
    }
}
