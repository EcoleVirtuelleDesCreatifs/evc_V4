<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;

class PaymentReceiptGenerator
{
    private function resolveTemplatePath(string $relativePath): ?string
    {
        $relativePath = ltrim($relativePath, '/');

        $assetsPrefixed = str_starts_with($relativePath, 'assets/')
            ? $relativePath
            : ('assets/' . $relativePath);

        $candidates = [
            public_path($relativePath),
            public_path($assetsPrefixed),
            base_path('public/' . $relativePath),
            base_path('public/' . $assetsPrefixed),
        ];

        foreach ($candidates as $path) {
            if (is_string($path) && $path !== '' && is_file($path) && is_readable($path)) {
                return $path;
            }
        }

        return null;
    }

    private function templatePath(): ?string
    {
        // Template voulu: celui de la facture (public/assets/facture/Template_Facture.pdf)
        // Fallback: template reçu dédié si présent
        return $this->resolveTemplatePath('assets/facture/Template_Facture.pdf')
            ?: $this->resolveTemplatePath('assets/recu/template_recu.pdf');
    }

    private function toLatin(string $text): string
    {
        $converted = @iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $text);
        return $converted !== false ? $converted : $text;
    }

    private function money(int|float $amount): string
    {
        return number_format((float) $amount, 0, ',', ' ') . ' FCFA';
    }

    /**
     * @param array $data
     * @return array{path:string, filename:string}
     */
    public function generate(array $data): array
    {
        $pdf = new Fpdi('P', 'mm');
        $pdf->SetAutoPageBreak(true, 18);

        $templatePath = $this->templatePath();

        if (is_string($templatePath) && is_file($templatePath)) {
            $pageCount = $pdf->setSourceFile($templatePath);
            $tplId = $pdf->importPage(1);
            $size = $pdf->getTemplateSize($tplId);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tplId, 0, 0, $size['width'], $size['height']);

            $pdf->SetTextColor(0, 0, 0);

            $receiptNumber = (string) ($data['receipt_number'] ?? '');
            $issuedAt = (string) ($data['issued_at'] ?? '');
            $studentName = (string) ($data['student_name'] ?? '');
            $studentEmail = (string) ($data['student_email'] ?? '');
            $formation = (string) ($data['formation'] ?? '');
            $paymentReference = (string) ($data['payment_reference'] ?? '');
            $studentId = (string) ($data['student_id'] ?? '');
            $registrationDate = (string) ($data['registration_date'] ?? '');

            $totalAmount = $this->money($data['total_amount'] ?? 0);
            $amountPaid = $this->money($data['amount_paid'] ?? 0);
            $remaining = $this->money($data['remaining'] ?? 0);

            $pageW = (float) ($size['width'] ?? 210);

            // En-tête: N° reçu + date établissement
            $pdf->SetFont('Helvetica', 'B', 11);
            $pdf->SetXY($pageW - 70, 28);
            $pdf->Cell(60, 6, $this->toLatin($receiptNumber), 0, 0, 'R');

            $pdf->SetFont('Helvetica', '', 9);
            $pdf->SetXY($pageW - 70, 35);
            $pdf->Cell(60, 6, $this->toLatin($issuedAt), 0, 0, 'R');

            // Titre principal (≈35px)
            $pdf->SetFont('Helvetica', 'B', 26);
            $pdf->SetXY(0, 46);
            $pdf->Cell($pageW, 12, $this->toLatin("RECU D'INSCRIPTION"), 0, 0, 'C');

            // Sous-titre / promesse (persuasif)
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->SetTextColor(80, 80, 80);
            $pdf->SetXY(0, 58);
            $pdf->Cell($pageW, 6, $this->toLatin("Merci pour votre confiance. Ce document confirme votre inscription."), 0, 0, 'C');
            $pdf->SetTextColor(0, 0, 0);

            // Bloc informations (structure comptable)
            $boxX = 15;
            $boxY = 68;
            $boxW = $pageW - 30;
            $lineH = 6.2;

            $pdf->SetDrawColor(200, 200, 200);
            $pdf->SetLineWidth(0.2);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Rect($boxX, $boxY, $boxW, 44, 'D');

            $leftX = $boxX + 4;
            $rightX = $boxX + ($boxW / 2) + 2;
            $y = $boxY + 4;

            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->SetXY($leftX, $y);
            $pdf->Cell(45, $lineH, $this->toLatin('Nom'), 0, 0, 'L');
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->SetXY($leftX + 24, $y);
            $pdf->Cell(($boxW / 2) - 28, $lineH, $this->toLatin($studentName), 0, 0, 'L');

            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->SetXY($rightX, $y);
            $pdf->Cell(45, $lineH, $this->toLatin('Formation'), 0, 0, 'L');
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->SetXY($rightX + 28, $y);
            $pdf->Cell(($boxW / 2) - 32, $lineH, $this->toLatin($formation), 0, 0, 'L');

            $y += $lineH;
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->SetXY($leftX, $y);
            $pdf->Cell(45, $lineH, $this->toLatin('ID étudiant'), 0, 0, 'L');
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->SetXY($leftX + 24, $y);
            $pdf->Cell(($boxW / 2) - 28, $lineH, $this->toLatin($studentId !== '' ? $studentId : '—'), 0, 0, 'L');

            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->SetXY($rightX, $y);
            $pdf->Cell(45, $lineH, $this->toLatin('Référence'), 0, 0, 'L');
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->SetXY($rightX + 28, $y);
            $pdf->Cell(($boxW / 2) - 32, $lineH, $this->toLatin($paymentReference !== '' ? $paymentReference : '—'), 0, 0, 'L');

            $y += $lineH;
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->SetXY($leftX, $y);
            $pdf->Cell(45, $lineH, $this->toLatin("Date d'inscription"), 0, 0, 'L');
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->SetXY($leftX + 24, $y);
            $pdf->Cell(($boxW / 2) - 28, $lineH, $this->toLatin($registrationDate !== '' ? $registrationDate : '—'), 0, 0, 'L');

            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->SetXY($rightX, $y);
            $pdf->Cell(45, $lineH, $this->toLatin("Date d'établissement"), 0, 0, 'L');
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->SetXY($rightX + 28, $y);
            $pdf->Cell(($boxW / 2) - 32, $lineH, $this->toLatin($issuedAt), 0, 0, 'L');

            $y += $lineH;
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->SetXY($leftX, $y);
            $pdf->Cell(45, $lineH, $this->toLatin('Coût formation'), 0, 0, 'L');
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->SetXY($leftX + 24, $y);
            $pdf->Cell(($boxW / 2) - 28, $lineH, $this->toLatin($totalAmount), 0, 0, 'L');

            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->SetXY($rightX, $y);
            $pdf->Cell(45, $lineH, $this->toLatin('Montant payé'), 0, 0, 'L');
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->SetXY($rightX + 28, $y);
            $pdf->Cell(($boxW / 2) - 32, $lineH, $this->toLatin($amountPaid), 0, 0, 'L');

            $y += $lineH;
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->SetXY($leftX, $y);
            $pdf->Cell(45, $lineH, $this->toLatin('Reste à solder'), 0, 0, 'L');
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->SetXY($leftX + 24, $y);
            $pdf->Cell(($boxW / 2) - 28, $lineH, $this->toLatin($remaining), 0, 0, 'L');

            // Optionnel: email (si on veut)
            if ($studentEmail !== '') {
                $pdf->SetFont('Helvetica', '', 9);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->SetXY($rightX, $y);
                $pdf->Cell(45, $lineH, $this->toLatin('Email'), 0, 0, 'L');
                $pdf->SetXY($rightX + 28, $y);
                $pdf->Cell(($boxW / 2) - 32, $lineH, $this->toLatin($studentEmail), 0, 0, 'L');
                $pdf->SetTextColor(0, 0, 0);
            }

            $payments = (array) ($data['payments'] ?? []);
            $tableX = 15;
            $tableY = 118;
            $rowH = 7;
            $maxRows = 10;

            $wDate = 25;
            $wLib = 55;
            $wRef = 55;
            $wAmount = 25;
            $wStatus = 25;

            $pdf->SetDrawColor(180, 180, 180);
            $pdf->SetLineWidth(0.2);

            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetXY($tableX, $tableY);
            $pdf->Cell($wDate, $rowH, $this->toLatin('Date'), 1, 0, 'L', true);
            $pdf->Cell($wLib, $rowH, $this->toLatin('Libellé'), 1, 0, 'L', true);
            $pdf->Cell($wRef, $rowH, $this->toLatin('Référence'), 1, 0, 'L', true);
            $pdf->Cell($wAmount, $rowH, $this->toLatin('Montant'), 1, 0, 'R', true);
            $pdf->Cell($wStatus, $rowH, $this->toLatin('Statut'), 1, 1, 'L', true);

            $pdf->SetFont('Helvetica', '', 9);
            $y = $tableY + $rowH;
            for ($i = 0; $i < min(count($payments), $maxRows); $i++) {
                $p = $payments[$i];

                $date = (string) (($p['paid_at'] ?? '') ?: ($p['created_at'] ?? ''));
                $lib = (string) (($p['installment_label'] ?? '') ?: 'Paiement');
                $ref = (string) ($p['payment_reference'] ?? '');
                $amt = $this->money($p['amount'] ?? 0);
                $status = (string) (($p['status_label'] ?? ($p['status'] ?? '')) ?: '');

                $fill = ($i % 2) === 1;
                if ($fill) {
                    $pdf->SetFillColor(250, 250, 250);
                } else {
                    $pdf->SetFillColor(255, 255, 255);
                }

                $pdf->SetXY($tableX, $y);
                $pdf->Cell($wDate, $rowH, $this->toLatin($date), 1, 0, 'L', $fill);
                $pdf->Cell($wLib, $rowH, $this->toLatin($lib), 1, 0, 'L', $fill);
                $pdf->Cell($wRef, $rowH, $this->toLatin($ref), 1, 0, 'L', $fill);
                $pdf->Cell($wAmount, $rowH, $this->toLatin($amt), 1, 0, 'R', $fill);
                $pdf->Cell($wStatus, $rowH, $this->toLatin($status), 1, 1, 'L', $fill);

                $y += $rowH;
            }

            $totalsY = $y + 8;
            $totalsX = 120;
            $totalsW = 80;
            $lineH = 6;

            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetXY($totalsX, $totalsY);
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell($totalsW, $lineH, $this->toLatin('Récapitulatif'), 1, 1, 'L', true);

            $pdf->SetFont('Helvetica', '', 9);
            $pdf->SetXY($totalsX, $totalsY + $lineH);
            $pdf->Cell(45, $lineH, $this->toLatin('Total dû'), 1, 0, 'L');
            $pdf->Cell($totalsW - 45, $lineH, $this->toLatin($totalAmount), 1, 1, 'R');

            $pdf->SetX($totalsX);
            $pdf->Cell(45, $lineH, $this->toLatin('Total payé'), 1, 0, 'L');
            $pdf->Cell($totalsW - 45, $lineH, $this->toLatin($amountPaid), 1, 1, 'R');

            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->SetX($totalsX);
            $pdf->Cell(45, $lineH, $this->toLatin('Solde'), 1, 0, 'L');
            $pdf->Cell($totalsW - 45, $lineH, $this->toLatin($remaining), 1, 1, 'R');

            $pdf->SetFont('Helvetica', '', 8);
            $pdf->SetTextColor(90, 90, 90);
            $pdf->SetXY(15, min($totalsY + 26, 265));
            $pdf->MultiCell(185, 4.5, $this->toLatin("Ce reçu est un document ORIGINAL. Pour toute vérification, veuillez contacter l'administration EVC avec la référence ci-dessus."));
            $pdf->SetTextColor(0, 0, 0);
        } else {
            $pdf->AddPage('P', 'A4');

            $pdf->SetFont('Helvetica', 'B', 16);
            $pdf->Cell(0, 8, $this->toLatin('EVC - École Virtuelle des Créatifs'), 0, 1, 'L');

            $pdf->SetFont('Helvetica', '', 11);
            $pdf->SetTextColor(80, 80, 80);
            $pdf->Cell(0, 6, $this->toLatin('Reçu de paiement'), 0, 1, 'L');
            $pdf->SetTextColor(0, 0, 0);

            $pdf->Ln(2);

            $pdf->SetFont('Helvetica', '', 10);
            $pdf->Cell(0, 5, $this->toLatin('N° Reçu : ') . $this->toLatin($data['receipt_number'] ?? ''), 0, 1, 'L');
            $pdf->Cell(0, 5, $this->toLatin('Date : ') . $this->toLatin($data['issued_at'] ?? ''), 0, 1, 'L');

            $pdf->Ln(4);

            $pdf->SetFont('Helvetica', 'B', 11);
            $pdf->Cell(0, 6, $this->toLatin('Informations étudiant'), 0, 1, 'L');
            $pdf->SetFont('Helvetica', '', 10);

            $pdf->Cell(0, 5, $this->toLatin('Nom : ') . $this->toLatin($data['student_name'] ?? ''), 0, 1, 'L');
            $pdf->Cell(0, 5, $this->toLatin('Email : ') . $this->toLatin($data['student_email'] ?? ''), 0, 1, 'L');
            $pdf->Cell(0, 5, $this->toLatin('Formation : ') . $this->toLatin($data['formation'] ?? ''), 0, 1, 'L');

            if (!empty($data['payment_reference'])) {
                $pdf->Cell(0, 5, $this->toLatin('Référence principale : ') . $this->toLatin($data['payment_reference']), 0, 1, 'L');
            }

            $pdf->Ln(4);

            $pdf->SetFont('Helvetica', 'B', 11);
            $pdf->Cell(0, 6, $this->toLatin('Récapitulatif'), 0, 1, 'L');

            $pdf->SetFont('Helvetica', '', 10);
            $pdf->Cell(0, 5, $this->toLatin('Montant total : ') . $this->toLatin($this->money($data['total_amount'] ?? 0)), 0, 1, 'L');
            $pdf->Cell(0, 5, $this->toLatin('Montant payé : ') . $this->toLatin($this->money($data['amount_paid'] ?? 0)), 0, 1, 'L');
            $pdf->Cell(0, 5, $this->toLatin('Reste à payer : ') . $this->toLatin($this->money($data['remaining'] ?? 0)), 0, 1, 'L');

            $pdf->Ln(6);

            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->SetFillColor(240, 240, 240);
            $pdf->Cell(32, 7, $this->toLatin('Date'), 1, 0, 'L', true);
            $pdf->Cell(30, 7, $this->toLatin('Tranche'), 1, 0, 'L', true);
            $pdf->Cell(35, 7, $this->toLatin('Statut'), 1, 0, 'L', true);
            $pdf->Cell(45, 7, $this->toLatin('Référence'), 1, 0, 'L', true);
            $pdf->Cell(38, 7, $this->toLatin('Montant'), 1, 1, 'R', true);

            $pdf->SetFont('Helvetica', '', 9);
            $pdf->SetFillColor(255, 255, 255);

            foreach (($data['payments'] ?? []) as $p) {
                $date = $p['paid_at'] ?: ($p['created_at'] ?? '');
                $type = $p['installment_label'] ?? '';
                $status = $p['status_label'] ?? ($p['status'] ?? '');
                $ref = $p['payment_reference'] ?? '';
                $amt = $this->money($p['amount'] ?? 0);

                $pdf->Cell(32, 7, $this->toLatin($date), 1, 0, 'L');
                $pdf->Cell(30, 7, $this->toLatin($type), 1, 0, 'L');
                $pdf->Cell(35, 7, $this->toLatin($status), 1, 0, 'L');
                $pdf->Cell(45, 7, $this->toLatin($ref), 1, 0, 'L');
                $pdf->Cell(38, 7, $this->toLatin($amt), 1, 1, 'R');
            }

            $pdf->Ln(6);

            $pdf->SetFont('Helvetica', '', 9);
            $pdf->SetTextColor(90, 90, 90);
            $pdf->MultiCell(0, 5, $this->toLatin('Ce reçu atteste des paiements enregistrés dans le système EVC. En cas de contestation, veuillez contacter EVC avec la référence indiquée.'));
            $pdf->SetTextColor(0, 0, 0);
        }

        // Output
        $outputDir = storage_path('app/receipts');
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $filename = $data['filename'] ?? ('recu_' . uniqid() . '_' . time() . '.pdf');
        $outputPath = $outputDir . '/' . $filename;

        $pdf->Output('F', $outputPath);

        return [
            'path' => $outputPath,
            'filename' => $filename,
        ];
    }
}
