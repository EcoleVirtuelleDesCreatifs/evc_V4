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
        // Même logique que la facture proforma (préinscriptions):
        // - Priorité au template devis si présent
        // - Sinon fallback sur la facture
        // - Sinon fallback sur le template reçu dédié
        return $this->resolveTemplatePath('assets/devis/template_devis.pdf')
            ?: $this->resolveTemplatePath('assets/facture/Template_Facture.pdf')
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

            $totalAmount = $this->money($data['total_amount'] ?? 0);
            $amountPaid = $this->money($data['amount_paid'] ?? 0);
            $remaining = $this->money($data['remaining'] ?? 0);

            $pdf->SetFont('Helvetica', 'B', 12);
            $pdf->SetXY(140, 28);
            $pdf->Cell(60, 6, $this->toLatin($receiptNumber), 0, 0, 'R');

            $pdf->SetFont('Helvetica', '', 10);
            $pdf->SetXY(140, 35);
            $pdf->Cell(60, 6, $this->toLatin($issuedAt), 0, 0, 'R');

            $pdf->SetFont('Helvetica', 'B', 11);
            $pdf->SetXY(18, 55);
            $pdf->Cell(120, 6, $this->toLatin($studentName), 0, 1, 'L');

            $pdf->SetFont('Helvetica', '', 10);
            $pdf->SetX(18);
            $pdf->Cell(120, 6, $this->toLatin($studentEmail), 0, 1, 'L');
            $pdf->SetX(18);
            $pdf->Cell(120, 6, $this->toLatin($formation), 0, 1, 'L');

            if ($paymentReference !== '') {
                $pdf->SetX(18);
                $pdf->Cell(120, 6, $this->toLatin($paymentReference), 0, 1, 'L');
            }

            $pdf->SetFont('Helvetica', 'B', 11);
            $pdf->SetXY(18, 86);
            $pdf->Cell(60, 6, $this->toLatin($totalAmount), 0, 0, 'L');
            $pdf->SetXY(80, 86);
            $pdf->Cell(60, 6, $this->toLatin($amountPaid), 0, 0, 'L');
            $pdf->SetXY(142, 86);
            $pdf->Cell(60, 6, $this->toLatin($remaining), 0, 0, 'L');

            $startY = 112;
            $rowH = 7;
            $maxRows = 10;
            $payments = (array) ($data['payments'] ?? []);

            $pdf->SetFont('Helvetica', '', 9);
            for ($i = 0; $i < min(count($payments), $maxRows); $i++) {
                $p = $payments[$i];
                $date = (string) (($p['paid_at'] ?? '') ?: ($p['created_at'] ?? ''));
                $type = (string) ($p['installment_label'] ?? '');
                $status = (string) ($p['status_label'] ?? ($p['status'] ?? ''));
                $ref = (string) ($p['payment_reference'] ?? '');
                $amt = $this->money($p['amount'] ?? 0);

                $y = $startY + ($i * $rowH);

                $pdf->SetXY(18, $y);
                $pdf->Cell(28, $rowH, $this->toLatin($date), 0, 0, 'L');
                $pdf->SetXY(47, $y);
                $pdf->Cell(28, $rowH, $this->toLatin($type), 0, 0, 'L');
                $pdf->SetXY(76, $y);
                $pdf->Cell(28, $rowH, $this->toLatin($status), 0, 0, 'L');
                $pdf->SetXY(105, $y);
                $pdf->Cell(55, $rowH, $this->toLatin($ref), 0, 0, 'L');
                $pdf->SetXY(162, $y);
                $pdf->Cell(35, $rowH, $this->toLatin($amt), 0, 0, 'R');
            }
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
