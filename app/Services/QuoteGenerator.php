<?php

namespace App\Services;

/**
 * Génère un devis de formation en PDF (FPDI uniquement — pas de dépendance DomPDF).
 * Design autonome EVC sur une seule page A4.
 */
class QuoteGenerator
{
    /**
     * @param array $data {
     *   quote_number, issued_at, valid_until,
     *   candidate_name, candidate_email, candidate_phone,
     *   formation, level, duration,
     *   total_amount, items: [{label, detail, amount}],
     *   filename?
     * }
     * @return array{path:string, filename:string}
     */
    public function generate(array $data): array
    {
        $pdf = new EvcReceiptPdf('P', 'mm', 'A4');
        $pdf->SetAutoPageBreak(false);
        $pdf->SetMargins(0, 0, 0);
        $pdf->AddPage('P', 'A4');

        // ---------- Palette EVC ----------
        $navy   = [30, 60, 114];
        $blue   = [42, 82, 152];
        $cyan   = [79, 195, 247];
        $green  = [22, 163, 74];
        $gray   = [100, 116, 139];
        $light  = [241, 245, 249];
        $card   = [238, 243, 249];
        $border = [203, 213, 225];
        $ink    = [30, 41, 59];

        // ---------- Données ----------
        $quoteNumber    = (string) ($data['quote_number'] ?? '');
        $issuedAt       = (string) ($data['issued_at'] ?? '');
        $validUntil     = (string) ($data['valid_until'] ?? '');
        $candidateName  = (string) ($data['candidate_name'] ?? '');
        $candidateEmail = (string) ($data['candidate_email'] ?? '');
        $candidatePhone = (string) ($data['candidate_phone'] ?? '');
        $formation      = (string) ($data['formation'] ?? '');
        $level          = (string) ($data['level'] ?? '');
        $duration       = (string) ($data['duration'] ?? '');
        $totalAmount    = $this->money($data['total_amount'] ?? 0);
        $items          = array_values((array) ($data['items'] ?? []));

        $pageW    = 210.0;
        $margin   = 15.0;
        $contentW = $pageW - 2 * $margin;

        // ---------- Header bandeau ----------
        $pdf->SetFillColor($navy[0], $navy[1], $navy[2]);
        $pdf->Rect(0, 0, $pageW, 42, 'F');
        $pdf->SetFillColor($cyan[0], $cyan[1], $cyan[2]);
        $pdf->Rect(0, 42, $pageW, 1.4, 'F');

        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Helvetica', 'B', 24);
        $pdf->SetXY($margin, 9);
        $pdf->Cell(30, 10, 'EVC', 0, 0, 'L');

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetTextColor(200, 220, 245);
        $pdf->SetXY($margin, 20);
        $pdf->Cell(90, 5, $this->toLatin('École Virtuelle des Créatifs'), 0, 0, 'L');
        $pdf->SetXY($margin, 26);
        $pdf->Cell(90, 5, $this->toLatin('Formation professionnelle en ligne - Abidjan, Côte d\'Ivoire'), 0, 0, 'L');

        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Helvetica', 'B', 20);
        $pdf->SetXY($pageW - $margin - 90, 9);
        $pdf->Cell(90, 9, 'DEVIS', 0, 0, 'R');

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetTextColor(200, 220, 245);
        $pdf->SetXY($pageW - $margin - 90, 20);
        $pdf->Cell(90, 5, $this->toLatin('N° ' . $quoteNumber), 0, 0, 'R');
        $pdf->SetXY($pageW - $margin - 90, 26);
        $pdf->Cell(90, 5, $this->toLatin('Établi le ' . $issuedAt), 0, 0, 'R');

        // ---------- Carte informations candidat ----------
        $cardX = $margin;
        $cardY = 52;
        $cardW = $contentW;
        $cardH = 46;

        $pdf->SetFillColor($card[0], $card[1], $card[2]);
        $pdf->roundedRect($cardX, $cardY, $cardW, $cardH, 3, 'F');

        // Icône personne dans un cercle navy
        $pdf->SetFillColor($navy[0], $navy[1], $navy[2]);
        $pdf->circle($cardX + 8, $cardY + 9, 5, 'F');
        $pdf->SetFillColor(255, 255, 255);
        $pdf->circle($cardX + 8, $cardY + 7.6, 1.7, 'F');
        $pdf->roundedRect($cardX + 4.6, $cardY + 10.4, 6.8, 3.0, 1.4, 'F');

        $pdf->SetTextColor($navy[0], $navy[1], $navy[2]);
        $pdf->SetFont('Helvetica', 'B', 9.5);
        $pdf->SetXY($cardX + 15.5, $cardY + 6.5);
        $pdf->Cell(80, 5, $this->toLatin('INFORMATIONS CANDIDAT(E)'), 0, 0, 'L');

        $infoLine = function (float $x, float $y, float $w, string $label, string $value) use ($pdf, $gray, $navy) {
            $pdf->SetFont('Helvetica', '', 8.5);
            $pdf->SetTextColor($gray[0], $gray[1], $gray[2]);
            $pdf->SetXY($x, $y);
            $pdf->Cell(24, 4.5, $this->toLatin($label), 0, 0, 'L');
            $pdf->Cell(4, 4.5, ':', 0, 0, 'C');
            $pdf->SetFont('Helvetica', 'B', 8.5);
            $pdf->SetTextColor($navy[0], $navy[1], $navy[2]);
            $pdf->Cell($w - 28, 4.5, $this->toLatin($value !== '' ? $value : '-'), 0, 0, 'L');
        };

        $colW = ($cardW - 20) / 2;
        $iy = $cardY + 17;
        // Colonne gauche
        $infoLine($cardX + 6, $iy, $colW, 'Nom', $candidateName);
        $infoLine($cardX + 6, $iy + 8, $colW, 'Email', $candidateEmail);
        $infoLine($cardX + 6, $iy + 16, $colW, 'Téléphone', $candidatePhone);
        // Colonne droite
        $infoLine($cardX + 6 + $colW + 8, $iy, $colW, 'Formation', $formation);
        $infoLine($cardX + 6 + $colW + 8, $iy + 8, $colW, 'Niveau', $level);
        $infoLine($cardX + 6 + $colW + 8, $iy + 16, $colW, 'Durée', $duration);

        // ---------- Tableau des prestations ----------
        $tX = $margin;
        $tY = $cardY + $cardH + 10;
        $rowH = 9.0;
        $wLabel  = 40;
        $wDetail = 100;
        $wAmount = $contentW - $wLabel - $wDetail;
        $tableW  = $wLabel + $wDetail + $wAmount;

        // Header navy arrondi en haut
        $pdf->SetFillColor($navy[0], $navy[1], $navy[2]);
        $pdf->roundedRect($tX, $tY, $tableW, $rowH, 2, 'F');
        $pdf->Rect($tX, $tY + $rowH / 2, $tableW, $rowH / 2, 'F');
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Helvetica', 'B', 9.5);
        $pdf->SetXY($tX + 4, $tY + 2);
        $pdf->Cell($wLabel - 4, 5, $this->toLatin('Désignation'), 0, 0, 'L');
        $pdf->SetX($tX + $wLabel + 4);
        $pdf->Cell($wDetail - 4, 5, $this->toLatin('Détail'), 0, 0, 'L');
        $pdf->SetX($tX + $wLabel + $wDetail);
        $pdf->Cell($wAmount - 4, 5, $this->toLatin('Montant'), 0, 0, 'R');

        $pdf->SetDrawColor($border[0], $border[1], $border[2]);
        $pdf->SetLineWidth(0.2);
        $y = $tY + $rowH;
        foreach ($items as $i => $item) {
            $label  = (string) ($item['label'] ?? '');
            $detail = (string) ($item['detail'] ?? '');
            $amount = $this->money($item['amount'] ?? 0);

            if ($i % 2 === 1) {
                $pdf->SetFillColor($light[0], $light[1], $light[2]);
                $pdf->Rect($tX, $y, $tableW, $rowH, 'F');
            }

            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->SetTextColor($ink[0], $ink[1], $ink[2]);
            $pdf->SetXY($tX + 4, $y + 2);
            $pdf->Cell($wLabel - 4, 5, $this->toLatin($label), 0, 0, 'L');
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->SetX($tX + $wLabel + 4);
            $pdf->Cell($wDetail - 4, 5, $this->toLatin($detail), 0, 0, 'L');
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->SetX($tX + $wLabel + $wDetail);
            $pdf->Cell($wAmount - 4, 5, $this->toLatin($amount), 0, 0, 'R');

            $pdf->Line($tX, $y + $rowH, $tX + $tableW, $y + $rowH);
            $y += $rowH;
        }

        // Ligne Total TTC
        $pdf->SetFillColor(216, 230, 248);
        $pdf->Rect($tX, $y, $tableW, $rowH, 'F');
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetTextColor($navy[0], $navy[1], $navy[2]);
        $pdf->SetXY($tX + 4, $y + 2);
        $pdf->Cell($wLabel + $wDetail - 4, 5, 'TOTAL TTC', 0, 0, 'R');
        $pdf->SetTextColor($blue[0], $blue[1], $blue[2]);
        $pdf->SetX($tX + $wLabel + $wDetail);
        $pdf->Cell($wAmount - 4, 5, $this->toLatin($totalAmount), 0, 0, 'R');
        $y += $rowH;

        // Bordure extérieure arrondie du tableau
        $pdf->roundedRect($tX, $tY, $tableW, $y - $tY, 2, 'S');

        // ---------- Encart validité ----------
        $vY = $y + 10;
        $pdf->SetFillColor($light[0], $light[1], $light[2]);
        $pdf->roundedRect($margin, $vY, $contentW, 12, 2, 'F');

        // Icône calendrier
        $pdf->SetDrawColor($navy[0], $navy[1], $navy[2]);
        $pdf->SetLineWidth(0.35);
        $pdf->roundedRect($margin + 5, $vY + 3.4, 5, 4.6, 0.6, 'S');
        $pdf->Line($margin + 5, $vY + 5, $margin + 10, $vY + 5);
        $pdf->SetLineWidth(0.2);

        $pdf->SetFont('Helvetica', '', 8.5);
        $pdf->SetTextColor($gray[0], $gray[1], $gray[2]);
        $pdf->SetXY($margin + 13, $vY + 3.8);
        $pdf->Cell($contentW - 15, 4.5, $this->toLatin('Ce devis est valable jusqu\'au ' . $validUntil . ' (30 jours). Pour toute question, contactez l\'administration EVC.'), 0, 0, 'L');

        // ---------- Zone signature ----------
        $sigY = 252;
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetTextColor($ink[0], $ink[1], $ink[2]);
        $pdf->SetXY($pageW - $margin - 70, $sigY);
        $pdf->Cell(70, 5, $this->toLatin('Signature et cachet'), 0, 0, 'C');
        $pdf->SetDrawColor($border[0], $border[1], $border[2]);
        $pdf->Line($pageW - $margin - 65, $sigY + 22, $pageW - $margin - 5, $sigY + 22);

        // ---------- Footer ----------
        $pdf->SetFillColor($navy[0], $navy[1], $navy[2]);
        $pdf->Rect(0, 285, $pageW, 12, 'F');
        $pdf->SetTextColor(200, 220, 245);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetXY(0, 288);
        $pdf->Cell($pageW, 5, $this->toLatin('EVC - École Virtuelle des Créatifs  |  Abidjan, Côte d\'Ivoire  |  www.ecolevirtuelledescreatifs.com'), 0, 0, 'C');

        return $this->output($pdf, $data);
    }

    private function toLatin(string $value): string
    {
        $converted = @iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $value);
        return $converted !== false ? $converted : $value;
    }

    private function money($amount): string
    {
        return number_format((float) $amount, 0, ',', ' ') . ' FCFA';
    }

    /**
     * @return array{path:string, filename:string}
     */
    private function output(EvcReceiptPdf $pdf, array $data): array
    {
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
