<?php

namespace App\Services;

class InvoiceGenerator
{
    protected $pdf;
    protected $useTemplate = false;
    protected $templatePath;
    protected $templatePageId;

    public function __construct()
    {
        // FPDF n'utilise pas de namespace, inclure directement
        require_once base_path('vendor/setasign/fpdf/fpdf.php');

        $this->templatePath = public_path('assets/facture/Template_Facture.pdf');

        // Si FPDI est disponible et que le template existe, utiliser le template
        if (is_file($this->templatePath) && is_readable($this->templatePath)) {
            try {
                // FPDI a un namespace, il est autoloadé par Composer
                $this->pdf = new \setasign\Fpdi\Fpdi('P', 'mm', 'A4');
                $this->useTemplate = true;
            } catch (\Throwable $e) {
                $this->pdf = new \FPDF('P', 'mm', 'A4');
                $this->useTemplate = false;
            }
        } else {
            $this->pdf = new \FPDF('P', 'mm', 'A4');
            $this->useTemplate = false;
        }
    }

    /**
     * Générer une facture de paiement
     */
    public function generateInvoice($payment, $student, $preRegistration)
    {
        $this->pdf->AddPage();
        $this->pdf->SetAutoPageBreak(true, 20);

        if ($this->useTemplate) {
            $this->applyTemplateBackground();
            $this->overlayTemplateFields($payment, $student, $preRegistration);
        } else {
            // Fallback: génération simple sans template
            $this->addHeader();
            $this->addInvoiceInfo($payment);
            $this->addClientInfo($student, $preRegistration);
            $this->addPaymentDetails($payment);
            $this->addFooter();
        }

        return $this->pdf;
    }

    protected function applyTemplateBackground(): void
    {
        try {
            $pageCount = $this->pdf->setSourceFile($this->templatePath);
            if ($pageCount >= 1) {
                $this->templatePageId = $this->pdf->importPage(1);
                $this->pdf->useTemplate($this->templatePageId, 0, 0, 210);
            }
        } catch (\Throwable $e) {
            $this->useTemplate = false;
        }
    }

    protected function overlayTemplateFields($payment, $student, $preRegistration): void
    {
        // NOTE: Les coordonnées ci-dessous sont génériques et peuvent être ajustées
        // selon la maquette du PDF Template_Facture.pdf.

        $invoiceNumber = $payment->payment_reference ?? 'N/A';
        $paidDate = $payment->paid_at ? date('d/m/Y', strtotime($payment->paid_at)) : date('d/m/Y');
        $transactionId = $payment->transaction_id ?? null;

        $studentName = '';
        $studentEmail = '';
        $studentPhone = '';
        $studentCountryCity = '';
        $formation = '';

        if ($student) {
            $studentName = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
            $studentEmail = $student->email ?? '';
            $studentPhone = $student->phone ?? $student->whatsapp ?? '';
            $city = $student->city ?? '';
            $country = $student->country ?? '';
            $studentCountryCity = trim(trim($country) . (trim($city) !== '' ? (' / ' . trim($city)) : ''));
            $formation = $student->program ?? '';
        }

        if ($studentName === '' && $preRegistration) {
            $studentName = trim(($preRegistration->prenom ?? '') . ' ' . ($preRegistration->nom ?? ''));
            $studentEmail = $studentEmail ?: ($preRegistration->email ?? '');
            $studentPhone = $studentPhone ?: ($preRegistration->whatsapp ?? $preRegistration->telephone ?? '');
            $city = $preRegistration->ville ?? '';
            $country = $preRegistration->pays ?? '';
            $studentCountryCity = $studentCountryCity ?: trim(trim($country) . (trim($city) !== '' ? (' / ' . trim($city)) : ''));
            $formation = $formation ?: ($preRegistration->programme ?? ($preRegistration->choix_formation ?? ''));
        }

        // Normaliser le label de formation (évite d'afficher community_management, etc.)
        $formationLabel = (string) $formation;
        $formationMap = [
            'design_graphique' => 'Design Graphique',
            'design-graphique' => 'Design Graphique',
            'community_management' => 'Community Management',
            'community-management' => 'Community Management',
            'gestion_informatique' => 'Gestion Informatique',
            'gestion-informatique' => 'Gestion Informatique',
            'intelligence_artificielle' => 'Intelligence Artificielle',
            'intelligence-artificielle' => 'Intelligence Artificielle',
            'design_cm' => 'Design Graphique & Community Management',
        ];
        if (isset($formationMap[$formationLabel])) {
            $formationLabel = $formationMap[$formationLabel];
        }

        // Positionnement sur le template (A4 mm)
        // Bloc "Numéro du reçu" + "Date d'émission"
        $this->writeText(16.5, 112, $invoiceNumber, 11, 'B');
        $this->writeText(155, 92, $paidDate, 11, 'B');

        // Ligne paiement (désignation / montant / statut)
        // NOTE: le template affiche déjà les libellés "Tranche" et colonnes, on remplit les valeurs.
        $designation = "Tranche {$payment->installment_number} / {$payment->total_installments}";
        if (($payment->payment_type ?? null) === 'full') {
            $designation = '';
        }
        $amountText = number_format((float) ($payment->amount ?? 0), 0, ',', ' ') . ' FCFA';
        $statusText = ($payment->status ?? '') === 'completed' ? 'Payé' : 'En attente';

        $this->writeText(20, 124, $designation, 10, '');
        $this->writeText(140, 124, $amountText, 10, 'B');
        $this->writeText(175, 124, $statusText, 10, '');

        // Bloc étudiant (ETUDIANT(E))
        // NOM / PRENOMS
        $this->writeText(20, 143.4, $studentName !== '' ? $studentName : 'Non renseigné', 12, 'B');
        // NUMERO
        if (!empty($studentPhone)) {
            $this->writeText(20, 158.5, $studentPhone, 11, '');
        }
        // Adresse E-mail
        if (!empty($studentEmail)) {
            $this->writeText(20, 164.8, $studentEmail, 11, 'B');
        }

        // Bloc formation (module)
        if (!empty($formationLabel)) {
            $this->writeText(20, 194, (string) $formationLabel, 11, 'B');
        }

        // Montant payé (sous le libellé "Montant payé (FCFA)")
        $this->writeText(20, 211.3, $amountText, 12, 'B');

    }

    protected function writeText(float $x, float $y, string $text, int $size = 10, string $style = ''): void
    {
        if ($text === '') {
            return;
        }
        $this->pdf->SetFont('Arial', $style, $size);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetXY($x, $y);
        $this->pdf->Cell(0, 5, utf8_decode($text), 0, 0, 'L');
    }

    protected function addHeader()
    {
        // Logo ou nom de l'école
        $this->pdf->SetFont('Arial', 'B', 20);
        $this->pdf->SetTextColor(30, 60, 114); // Bleu EVC
        $this->pdf->Cell(0, 10, 'ECOLE VIRTUELLE DES CREATEURS', 0, 1, 'C');

        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->SetTextColor(100, 100, 100);
        $this->pdf->Cell(0, 5, 'Formation professionnelle en ligne', 0, 1, 'C');
        $this->pdf->Cell(0, 5, utf8_decode('Email: contact@ecolevirtuelledescreateurs.com | Tél: +225 XX XX XX XX XX'), 0, 1, 'C');
        $this->pdf->Ln(10);

        // Ligne de séparation
        $this->pdf->SetDrawColor(30, 60, 114);
        $this->pdf->SetLineWidth(0.5);
        $this->pdf->Line(10, $this->pdf->GetY(), 200, $this->pdf->GetY());
        $this->pdf->Ln(10);
    }

    protected function addInvoiceInfo($payment)
    {
        $this->pdf->SetFont('Arial', 'B', 16);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->Cell(0, 10, 'FACTURE / RECU DE PAIEMENT', 0, 1, 'C');
        $this->pdf->Ln(5);

        // Informations de la facture
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->SetTextColor(100, 100, 100);

        $col1X = 10;
        $col2X = 120;

        $this->pdf->SetX($col1X);
        $this->pdf->Cell(50, 6, utf8_decode('Numéro de facture:'), 0, 0);
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->Cell(0, 6, $payment->payment_reference ?? 'N/A', 0, 1);

        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->SetTextColor(100, 100, 100);
        $this->pdf->SetX($col1X);
        $this->pdf->Cell(50, 6, 'Date de paiement:', 0, 0);
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->SetTextColor(0, 0, 0);
        $paidDate = $payment->paid_at ? date('d/m/Y', strtotime($payment->paid_at)) : date('d/m/Y');
        $this->pdf->Cell(0, 6, $paidDate, 0, 1);

        if ($payment->transaction_id) {
            $this->pdf->SetFont('Arial', '', 10);
            $this->pdf->SetTextColor(100, 100, 100);
            $this->pdf->SetX($col1X);
            $this->pdf->Cell(50, 6, 'ID Transaction:', 0, 0);
            $this->pdf->SetFont('Arial', 'B', 10);
            $this->pdf->SetTextColor(0, 0, 0);
            $this->pdf->Cell(0, 6, $payment->transaction_id, 0, 1);
        }

        $this->pdf->Ln(5);
    }

    protected function addClientInfo($student, $preRegistration)
    {
        $this->pdf->SetFillColor(240, 240, 240);
        $this->pdf->SetFont('Arial', 'B', 11);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->Cell(0, 8, utf8_decode('INFORMATIONS DE L\'ÉTUDIANT'), 0, 1, 'L', true);
        $this->pdf->Ln(2);

        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->SetTextColor(100, 100, 100);

        if ($student) {
            $this->pdf->Cell(40, 6, 'Nom complet:', 0, 0);
            $this->pdf->SetFont('Arial', 'B', 10);
            $this->pdf->SetTextColor(0, 0, 0);
            $this->pdf->Cell(0, 6, utf8_decode($student->first_name . ' ' . $student->last_name), 0, 1);

            $this->pdf->SetFont('Arial', '', 10);
            $this->pdf->SetTextColor(100, 100, 100);
            $this->pdf->Cell(40, 6, 'Formation:', 0, 0);
            $this->pdf->SetFont('Arial', 'B', 10);
            $this->pdf->SetTextColor(0, 0, 0);
            $this->pdf->Cell(0, 6, utf8_decode($student->program ?? 'Non spécifié'), 0, 1);
        } elseif ($preRegistration) {
            $this->pdf->Cell(40, 6, 'Nom complet:', 0, 0);
            $this->pdf->SetFont('Arial', 'B', 10);
            $this->pdf->SetTextColor(0, 0, 0);
            $this->pdf->Cell(0, 6, utf8_decode($preRegistration->prenom . ' ' . $preRegistration->nom), 0, 1);

            $this->pdf->SetFont('Arial', '', 10);
            $this->pdf->SetTextColor(100, 100, 100);
            $this->pdf->Cell(40, 6, 'Email:', 0, 0);
            $this->pdf->SetFont('Arial', 'B', 10);
            $this->pdf->SetTextColor(0, 0, 0);
            $this->pdf->Cell(0, 6, $preRegistration->email, 0, 1);

            if (isset($preRegistration->programme)) {
                $this->pdf->SetFont('Arial', '', 10);
                $this->pdf->SetTextColor(100, 100, 100);
                $this->pdf->Cell(40, 6, 'Formation:', 0, 0);
                $this->pdf->SetFont('Arial', 'B', 10);
                $this->pdf->SetTextColor(0, 0, 0);
                $this->pdf->Cell(0, 6, utf8_decode($preRegistration->programme), 0, 1);
            }
        }

        $this->pdf->Ln(5);
    }

    protected function addPaymentDetails($payment)
    {
        // En-tête du tableau
        $this->pdf->SetFillColor(30, 60, 114);
        $this->pdf->SetTextColor(255, 255, 255);
        $this->pdf->SetFont('Arial', 'B', 10);

        $this->pdf->Cell(90, 10, utf8_decode('DÉSIGNATION'), 1, 0, 'L', true);
        $this->pdf->Cell(50, 10, 'MONTANT', 1, 0, 'C', true);
        $this->pdf->Cell(50, 10, 'STATUT', 1, 1, 'C', true);

        // Contenu du tableau
        $this->pdf->SetFillColor(255, 255, 255);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetFont('Arial', '', 10);

        $designation = "Tranche {$payment->installment_number} / {$payment->total_installments}";
        if ($payment->payment_type === 'full') {
            $designation = utf8_decode('Paiement intégral');
        }

        $this->pdf->Cell(90, 10, utf8_decode($designation), 1, 0, 'L');
        $this->pdf->Cell(50, 10, number_format($payment->amount, 0, ',', ' ') . ' FCFA', 1, 0, 'C');

        $statusText = $payment->status === 'completed' ? utf8_decode('Payé') : utf8_decode('En attente');
        $this->pdf->Cell(50, 10, $statusText, 1, 1, 'C');

        // Total
        $this->pdf->SetFont('Arial', 'B', 11);
        $this->pdf->Cell(90, 10, 'TOTAL', 1, 0, 'R');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(30, 60, 114);
        $this->pdf->Cell(100, 10, number_format($payment->amount, 0, ',', ' ') . ' FCFA', 1, 1, 'C');

        $this->pdf->Ln(10);

        // Méthode de paiement
        if ($payment->payment_method) {
            $this->pdf->SetFont('Arial', '', 10);
            $this->pdf->SetTextColor(100, 100, 100);
            $methodName = $this->getPaymentMethodName($payment->payment_method);
            $this->pdf->Cell(0, 6, utf8_decode('Méthode de paiement: ') . $methodName, 0, 1);
        }

        $this->pdf->Ln(10);
    }

    protected function addFooter()
    {
        // Note de remerciement
        $this->pdf->SetFont('Arial', 'I', 9);
        $this->pdf->SetTextColor(100, 100, 100);
        $this->pdf->MultiCell(0, 5, utf8_decode("Merci pour votre confiance. Ce document tient lieu de reçu de paiement.\nPour toute question, n'hésitez pas à nous contacter."), 0, 'C');

        $this->pdf->Ln(5);

        // Ligne de séparation
        $this->pdf->SetDrawColor(200, 200, 200);
        $this->pdf->SetLineWidth(0.2);
        $this->pdf->Line(10, $this->pdf->GetY(), 200, $this->pdf->GetY());
        $this->pdf->Ln(3);

        // Informations légales
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->SetTextColor(150, 150, 150);
        $this->pdf->Cell(0, 4, utf8_decode('Ecole Virtuelle des Créateurs - Document généré automatiquement le ' . date('d/m/Y à H:i')), 0, 1, 'C');
    }

    protected function getPaymentMethodName($method)
    {
        $methods = [
            'orange_money' => 'Orange Money',
            'mtn_mobile' => 'MTN Mobile Money',
            'wave' => 'Wave',
            'moov_money' => 'Moov Money',
            'carte_bancaire' => 'Carte Bancaire',
            'cinetpay' => 'CinetPay',
        ];

        return $methods[$method] ?? ucfirst(str_replace('_', ' ', $method));
    }

    /**
     * Télécharger la facture
     */
    public function download($filename = 'facture.pdf')
    {
        return $this->pdf->Output('D', $filename);
    }

    /**
     * Afficher la facture dans le navigateur
     */
    public function inline($filename = 'facture.pdf')
    {
        return $this->pdf->Output('I', $filename);
    }

    /**
     * Sauvegarder la facture
     */
    public function save($path)
    {
        return $this->pdf->Output('F', $path);
    }
}
