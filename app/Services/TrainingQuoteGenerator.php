<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;

class TrainingQuoteGenerator
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
        $pdf = new Fpdi('P', 'mm', 'A4');
        $pdf->SetAutoPageBreak(true, 18);
        $pdf->AddPage();

        $pdf->SetFont('Helvetica', 'B', 16);
        $pdf->Cell(0, 8, $this->toLatin('EVC - École Virtuelle des Créatifs'), 0, 1, 'L');

        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell(0, 6, $this->toLatin('Devis de formation'), 0, 1, 'L');
        $pdf->SetTextColor(0, 0, 0);

        $pdf->Ln(2);

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(0, 5, $this->toLatin('N° Devis : ') . $this->toLatin($data['quote_number'] ?? ''), 0, 1, 'L');
        $pdf->Cell(0, 5, $this->toLatin('Date : ') . $this->toLatin($data['issued_at'] ?? ''), 0, 1, 'L');

        if (!empty($data['valid_until'])) {
            $pdf->Cell(0, 5, $this->toLatin('Validité : ') . $this->toLatin($data['valid_until']), 0, 1, 'L');
        }

        $pdf->Ln(4);

        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->Cell(0, 6, $this->toLatin('Informations candidat'), 0, 1, 'L');
        $pdf->SetFont('Helvetica', '', 10);

        $pdf->Cell(0, 5, $this->toLatin('Nom : ') . $this->toLatin($data['candidate_name'] ?? ''), 0, 1, 'L');
        $pdf->Cell(0, 5, $this->toLatin('Email : ') . $this->toLatin($data['candidate_email'] ?? ''), 0, 1, 'L');

        if (!empty($data['candidate_phone'])) {
            $pdf->Cell(0, 5, $this->toLatin('Téléphone / WhatsApp : ') . $this->toLatin($data['candidate_phone']), 0, 1, 'L');
        }

        $pdf->Ln(4);

        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->Cell(0, 6, $this->toLatin('Formation demandée'), 0, 1, 'L');
        $pdf->SetFont('Helvetica', '', 10);

        $pdf->Cell(0, 5, $this->toLatin('Formation : ') . $this->toLatin($data['formation'] ?? ''), 0, 1, 'L');

        if (!empty($data['level'])) {
            $pdf->Cell(0, 5, $this->toLatin('Niveau : ') . $this->toLatin($data['level']), 0, 1, 'L');
        }

        if (!empty($data['duration'])) {
            $pdf->Cell(0, 5, $this->toLatin('Durée : ') . $this->toLatin($data['duration']), 0, 1, 'L');
        }

        $pdf->Ln(6);

        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->Cell(0, 6, $this->toLatin('Récapitulatif'), 0, 1, 'L');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(0, 5, $this->toLatin('Montant total : ') . $this->toLatin($this->money($data['total_amount'] ?? 0)), 0, 1, 'L');

        $pdf->Ln(4);

        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->Cell(40, 7, $this->toLatin('Modalité'), 1, 0, 'L', true);
        $pdf->Cell(90, 7, $this->toLatin('Détail'), 1, 0, 'L', true);
        $pdf->Cell(60, 7, $this->toLatin('Montant'), 1, 1, 'R', true);

        $pdf->SetFont('Helvetica', '', 10);

        foreach (($data['items'] ?? []) as $item) {
            $label = $item['label'] ?? '';
            $detail = $item['detail'] ?? '';
            $amount = $item['amount'] ?? 0;

            $pdf->Cell(40, 7, $this->toLatin($label), 1, 0, 'L');
            $pdf->Cell(90, 7, $this->toLatin($detail), 1, 0, 'L');
            $pdf->Cell(60, 7, $this->toLatin($this->money($amount)), 1, 1, 'R');
        }

        $pdf->Ln(6);

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetTextColor(90, 90, 90);
        $pdf->MultiCell(0, 5, $this->toLatin('Ce devis est fourni à titre indicatif pour faciliter la validation et la prise en charge en entreprise. Pour procéder au paiement, veuillez contacter EVC ou utiliser le lien de paiement transmis après validation de la candidature.'));
        $pdf->SetTextColor(0, 0, 0);

        $outputDir = storage_path('app/quotes');
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $filename = $data['filename'] ?? ('devis_' . uniqid() . '_' . time() . '.pdf');
        $outputPath = $outputDir . '/' . $filename;

        $pdf->Output('F', $outputPath);

        return [
            'path' => $outputPath,
            'filename' => $filename,
        ];
    }
}
