<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;

class TrainingQuoteGenerator
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
            if (is_string($path) && $path !== '' && file_exists($path)) {
                return $path;
            }
        }

        return null;
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
        $templatePath = null;
        if (!empty($data['template_path'])) {
            $templatePath = $this->resolveTemplatePath((string) $data['template_path']);
        }
        if (!$templatePath) {
            $templatePath = $this->resolveTemplatePath('assets/devis/template_devis.pdf');
        }
        if (!$templatePath) {
            $templatePath = $this->resolveTemplatePath('assets/facture/Template_Facture.pdf');
        }

        $pdf = new Fpdi('P', 'mm', 'A4');
        $pdf->SetAutoPageBreak(true, 18);

        if ($templatePath) {
            $pageCount = $pdf->setSourceFile($templatePath);
            $templateId = $pdf->importPage(1);
            $size = $pdf->getTemplateSize($templateId);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);
        } else {
            $pdf->AddPage();
        }

        $offsetY = 26.5;

        // Overlay text (positions par défaut - à ajuster selon le template)
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetFont('Helvetica', 'B', 24);
        $pdf->SetXY(15, 25 + $offsetY);
        $pdf->Cell(0, 10, $this->toLatin('FACTURE PRO FORMAT'), 0, 1, 'L');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetXY(15, 35 + $offsetY);
        $pdf->Cell(0, 5, $this->toLatin('N° Devis : ') . $this->toLatin($data['quote_number'] ?? ''), 0, 1, 'L');
        $pdf->SetXY(15, 41 + $offsetY);
        $pdf->Cell(0, 5, $this->toLatin('Date : ') . $this->toLatin($data['issued_at'] ?? ''), 0, 1, 'L');
        if (!empty($data['valid_until'])) {
            $pdf->SetXY(15, 47 + $offsetY);
            $pdf->Cell(0, 5, $this->toLatin('Validité : ') . $this->toLatin($data['valid_until']), 0, 1, 'L');
        }

        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetXY(15, 60 + $offsetY);
        $pdf->Cell(0, 5, $this->toLatin('CANDIDAT'), 0, 1, 'L');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetXY(15, 66 + $offsetY);
        $pdf->Cell(0, 5, $this->toLatin('Nom : ') . $this->toLatin($data['candidate_name'] ?? ''), 0, 1, 'L');
        $pdf->SetXY(15, 72 + $offsetY);
        $pdf->Cell(0, 5, $this->toLatin('Email : ') . $this->toLatin($data['candidate_email'] ?? ''), 0, 1, 'L');
        if (!empty($data['candidate_phone'])) {
            $pdf->SetXY(15, 78 + $offsetY);
            $pdf->Cell(0, 5, $this->toLatin('Numéro : ') . $this->toLatin($data['candidate_phone']), 0, 1, 'L');
        }

        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetXY(15, 92 + $offsetY);
        $pdf->Cell(0, 5, $this->toLatin('FORMATION'), 0, 1, 'L');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetXY(15, 98 + $offsetY);
        $pdf->Cell(0, 5, $this->toLatin('Formation choisie : ') . $this->toLatin($data['formation'] ?? ''), 0, 1, 'L');
        if (!empty($data['level'])) {
            $pdf->SetXY(15, 104 + $offsetY);
            $pdf->Cell(0, 5, $this->toLatin('Niveau : ') . $this->toLatin($data['level']), 0, 1, 'L');
        }
        $durationLabel = !empty($data['duration']) ? (string) $data['duration'] : '—';
        $pdf->SetXY(15, 110 + $offsetY);
        $pdf->Cell(0, 5, $this->toLatin('Durée de la formation : ') . $this->toLatin($durationLabel), 0, 1, 'L');

        $pdf->SetXY(15, 116 + $offsetY);
        $pdf->Cell(0, 5, $this->toLatin('Formation Principal : Bilé Bossombra (En savoir plus : www.bilebossombra.com)'), 0, 1, 'L');

        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetXY(15, 130 + $offsetY);
        $pdf->Cell(0, 5, $this->toLatin('MONTANT TOTAL : ') . $this->toLatin($this->money($data['total_amount'] ?? 0)), 0, 1, 'L');

        // Tranches (2 lignes)
        $startY = 146 + $offsetY;
        $rowHeight = 7;
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetXY(15, $startY);
        $pdf->Cell(60, $rowHeight, $this->toLatin('Modalité'), 1, 0, 'L');
        $pdf->Cell(85, $rowHeight, $this->toLatin('Détail'), 1, 0, 'L');
        $pdf->Cell(40, $rowHeight, $this->toLatin('Montant'), 1, 1, 'R');

        $pdf->SetFont('Helvetica', '', 10);
        $y = $startY + $rowHeight;
        foreach (($data['items'] ?? []) as $item) {
            $pdf->SetXY(15, $y);
            $pdf->Cell(60, $rowHeight, $this->toLatin($item['label'] ?? ''), 1, 0, 'L');
            $pdf->Cell(85, $rowHeight, $this->toLatin($item['detail'] ?? ''), 1, 0, 'L');
            $pdf->Cell(40, $rowHeight, $this->toLatin($this->money($item['amount'] ?? 0)), 1, 1, 'R');
            $y += $rowHeight;
        }

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetTextColor(90, 90, 90);
        $pdf->SetXY(15, max($y + 8, 170 + $offsetY));
        $pdf->MultiCell(180, 5, $this->toLatin('Ce devis est fourni à titre indicatif pour faciliter la validation et la prise en charge en entreprise.'));
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
