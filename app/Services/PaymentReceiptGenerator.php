<?php

namespace App\Services;

use setasign\Fpdf\Fpdf;

class PaymentReceiptGenerator
{
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
        $pdf = new Fpdf('P', 'mm', 'A4');
        $pdf->SetAutoPageBreak(true, 18);
        $pdf->AddPage();

        // Header
        $pdf->SetFont('Helvetica', 'B', 16);
        $pdf->Cell(0, 8, $this->toLatin('EVC - École Virtuelle des Créatifs'), 0, 1, 'L');

        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell(0, 6, $this->toLatin('Reçu de paiement'), 0, 1, 'L');
        $pdf->SetTextColor(0, 0, 0);

        $pdf->Ln(2);

        // Receipt meta
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(0, 5, $this->toLatin('N° Reçu : ') . $this->toLatin($data['receipt_number'] ?? ''), 0, 1, 'L');
        $pdf->Cell(0, 5, $this->toLatin('Date : ') . $this->toLatin($data['issued_at'] ?? ''), 0, 1, 'L');

        $pdf->Ln(4);

        // Student block
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

        // Summary block
        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->Cell(0, 6, $this->toLatin('Récapitulatif'), 0, 1, 'L');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(0, 5, $this->toLatin('Montant total : ') . $this->toLatin($this->money($data['total_amount'] ?? 0)), 0, 1, 'L');
        $pdf->Cell(0, 5, $this->toLatin('Montant payé : ') . $this->toLatin($this->money($data['amount_paid'] ?? 0)), 0, 1, 'L');
        $pdf->Cell(0, 5, $this->toLatin('Reste à payer : ') . $this->toLatin($this->money($data['remaining'] ?? 0)), 0, 1, 'L');

        $pdf->Ln(6);

        // Table header
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
