<?php

namespace App\Services;

use App\Mail\WelcomeStudentMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

class StudentEmailService
{
    /**
     * Envoyer un email de bienvenue à un étudiant
     *
     * @param array $studentData
     * @param array $formations
     * @return array
     */
    public function sendWelcomeEmail(array $studentData, array $formations): array
    {
        Log::info('🚀 StudentEmailService: Début envoi email de bienvenue', [
            'student_email' => $studentData['email'],
            'student_name' => $studentData['first_name'] . ' ' . $studentData['last_name'],
            'formations' => $formations
        ]);

        $result = [
            'success' => false,
            'method' => null,
            'message' => '',
            'error' => null
        ];

        // Méthode 1: Envoi SMTP standard
        $result = $this->trySmtpSending($studentData, $formations);
        if ($result['success']) {
            return $result;
        }

        // Méthode 2: Sauvegarde en logs (fallback)
        $result = $this->saveEmailToLogs($studentData, $formations);
        if ($result['success']) {
            return $result;
        }

        // Méthode 3: Notification admin (fallback ultime)
        return $this->createAdminNotification($studentData, $formations);
    }

    /**
     * Tentative d'envoi via SMTP
     */
    private function trySmtpSending(array $studentData, array $formations): array
    {
        try {
            Log::info('📧 Tentative envoi SMTP');

            // Vérifier la classe Mail
            if (!class_exists(WelcomeStudentMail::class)) {
                throw new Exception('Classe WelcomeStudentMail introuvable');
            }

            // Configuration timeout
            $originalTimeout = ini_get('default_socket_timeout');
            ini_set('default_socket_timeout', 10);

            $startTime = microtime(true);

            // Créer et envoyer l'email
            $welcomeMail = new WelcomeStudentMail($studentData, $formations, 'password123');
            Mail::to($studentData['email'])->send($welcomeMail);

            $endTime = microtime(true);
            $duration = round(($endTime - $startTime) * 1000, 2);

            // Restaurer timeout
            ini_set('default_socket_timeout', $originalTimeout);

            Log::info('✅ Email SMTP envoyé avec succès', [
                'duration_ms' => $duration,
                'recipient' => $studentData['email']
            ]);

            return [
                'success' => true,
                'method' => 'smtp',
                'message' => "Email envoyé via SMTP en {$duration}ms",
                'error' => null
            ];

        } catch (Exception $e) {
            Log::warning('⚠️ Échec envoi SMTP', [
                'error' => $e->getMessage(),
                'recipient' => $studentData['email']
            ]);

            return [
                'success' => false,
                'method' => 'smtp',
                'message' => 'Échec envoi SMTP',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Sauvegarde email dans les logs
     */
    private function saveEmailToLogs(array $studentData, array $formations): array
    {
        try {
            Log::info('📝 Sauvegarde email en logs');

            $emailContent = $this->generateEmailContent($studentData, $formations);

            Log::info('📧 EMAIL DE BIENVENUE SAUVEGARDÉ', [
                'destinataire' => $studentData['email'],
                'nom_complet' => $studentData['first_name'] . ' ' . $studentData['last_name'],
                'formations' => $formations,
                'sujet' => 'Bienvenue à l\'École Virtuelle des Créatifs',
                'contenu_html' => $emailContent,
                'identifiants' => [
                    'email' => $studentData['email'],
                    'mot_de_passe_temporaire' => 'password123'
                ],
                'liens' => [
                    'connexion' => url('/auth/evc/login'),
                    'confirmation' => $this->generateConfirmationUrl($studentData['email'])
                ],
                'date_creation' => now()->format('Y-m-d H:i:s'),
                'instructions' => 'Copier le contenu HTML et envoyer manuellement à l\'étudiant'
            ]);

            return [
                'success' => true,
                'method' => 'logs',
                'message' => 'Email sauvegardé dans les logs Laravel',
                'error' => null
            ];

        } catch (Exception $e) {
            Log::error('❌ Échec sauvegarde logs', [
                'error' => $e->getMessage(),
                'recipient' => $studentData['email']
            ]);

            return [
                'success' => false,
                'method' => 'logs',
                'message' => 'Échec sauvegarde logs',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Créer notification admin
     */
    private function createAdminNotification(array $studentData, array $formations): array
    {
        try {
            Log::info('🔔 Création notification admin');

            Log::info('🚨 NOTIFICATION ADMIN - ACTION REQUISE', [
                'type' => 'email_etudiant_manuel',
                'priorite' => 'haute',
                'etudiant' => [
                    'nom' => $studentData['first_name'] . ' ' . $studentData['last_name'],
                    'email' => $studentData['email'],
                    'formations' => implode(', ', $formations)
                ],
                'action_requise' => 'Envoyer email de bienvenue manuellement',
                'identifiants_temporaires' => [
                    'email' => $studentData['email'],
                    'mot_de_passe' => 'password123'
                ],
                'contenu_email' => $this->generateEmailContent($studentData, $formations),
                'date_notification' => now()->format('Y-m-d H:i:s')
            ]);

            return [
                'success' => true,
                'method' => 'admin_notification',
                'message' => 'Notification admin créée - Email à envoyer manuellement',
                'error' => null
            ];

        } catch (Exception $e) {
            Log::error('❌ Échec notification admin', [
                'error' => $e->getMessage(),
                'recipient' => $studentData['email']
            ]);

            return [
                'success' => false,
                'method' => 'admin_notification',
                'message' => 'Échec notification admin',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Générer le contenu HTML de l'email
     */
    private function generateEmailContent(array $studentData, array $formations): string
    {
        $formationsText = implode(', ', $formations);
        $loginUrl = url('/auth/evc/login');
        $confirmationUrl = $this->generateConfirmationUrl($studentData['email']);

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <title>Bienvenue à l'École Virtuelle des Créatifs</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <h2 style='color: #007bff; text-align: center;'>Bienvenue à l'École Virtuelle des Créatifs !</h2>
                
                <p>Bonjour <strong>{$studentData['first_name']} {$studentData['last_name']}</strong>,</p>
                
                <p>Félicitations ! Votre inscription à l'École Virtuelle des Créatifs a été validée avec succès.</p>
                
                <div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                    <h3 style='color: #007bff; margin-top: 0;'>Vos informations de connexion :</h3>
                    <ul style='list-style: none; padding: 0;'>
                        <li style='margin: 10px 0;'><strong>Email :</strong> {$studentData['email']}</li>
                        <li style='margin: 10px 0;'><strong>Mot de passe temporaire :</strong> password123</li>
                        <li style='margin: 10px 0;'><strong>Formation(s) :</strong> {$formationsText}</li>
                    </ul>
                </div>
                
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='{$loginUrl}' style='background: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;'>Se connecter à la plateforme</a>
                </div>
                
                <p><strong>Important :</strong> Nous vous recommandons de changer votre mot de passe lors de votre première connexion.</p>
                
                <p>Si vous avez des questions, n'hésitez pas à nous contacter.</p>
                
                <p>Bienvenue dans notre communauté de créatifs !</p>
                
                <hr style='margin: 30px 0; border: none; border-top: 1px solid #eee;'>
                <p style='text-align: center; color: #666; font-size: 12px;'>
                    École Virtuelle des Créatifs<br>
                    Formation professionnelle en ligne<br>
                    <a href='{$confirmationUrl}' style='color: #007bff;'>Confirmer votre inscription</a>
                </p>
            </div>
        </body>
        </html>";
    }

    /**
     * Générer URL de confirmation
     */
    private function generateConfirmationUrl(string $email): string
    {
        $token = base64_encode($email . '|' . time() . '|' . md5($email . config('app.key')));
        return url('/student/confirm-registration/' . $token);
    }
}
