<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;

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
     * Génère un reçu de paiement professionnel sur une seule page A4.
     *
     * @param array $data
     * @return array{path:string, filename:string}
     */
    public function generate(array $data): array
    {
        $pdf = new Fpdi('P', 'mm', 'A4');
        $pdf->SetAutoPageBreak(false);
        $pdf->SetMargins(0, 0, 0);
        $pdf->AddPage('P', 'A4');

        // ---------- Palette ----------
        $navy   = [30, 60, 114];   // #1e3c72
        $blue   = [42, 82, 152];   // #2a5298
        $cyan   = [79, 195, 247];  // #4fc3f7
        $green  = [16, 185, 129];
        $orange = [245, 158, 11];
        $gray   = [100, 116, 139];
        $light  = [241, 245, 249];
        $border = [203, 213, 225];

        // ---------- Données ----------
        $receiptNumber    = (string) ($data['receipt_number'] ?? '');
        $issuedAt         = (string) ($data['issued_at'] ?? '');
        $studentName      = (string) ($data['student_name'] ?? '');
        $studentEmail     = (string) ($data['student_email'] ?? '');
        $formation        = (string) ($data['formation'] ?? '');
        $paymentReference = (string) ($data['payment_reference'] ?? '');
        $studentId        = (string) ($data['student_id'] ?? '');
        $registrationDate = (string) ($data['registration_date'] ?? '');

        $grossTotalAmount  = $this->money($data['gross_total_amount'] ?? ($data['total_amount'] ?? 0));
        $discountAmountRaw = (float) ($data['discount_amount'] ?? 0);
        $discountAmount    = $this->money($discountAmountRaw);
        $totalAmount       = $this->money($data['total_amount'] ?? 0);
        $amountPaid        = $this->money($data['amount_paid'] ?? 0);
        $remainingRaw      = (float) ($data['remaining'] ?? 0);
        $remaining         = $this->money($remainingRaw);
        $isFullyPaid       = $remainingRaw <= 0;

        $pageW = 210.0;
        $margin = 15.0;
        $contentW = $pageW - 2 * $margin;

        // ---------- Header bandeau ----------
        $pdf->SetFillColor($navy[0], $navy[1], $navy[2]);
        $pdf->Rect(0, 0, $pageW, 42, 'F');
        $pdf->SetFillColor($cyan[0], $cyan[1], $cyan[2]);
        $pdf->Rect(0, 42, $pageW, 1.4, 'F');

        // Logo texte + nom école (gauche)
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

        // Titre document (droite)
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Helvetica', 'B', 17);
        $pdf->SetXY($pageW - $margin - 90, 9);
        $pdf->Cell(90, 9, $this->toLatin('REÇU DE PAIEMENT'), 0, 0, 'R');

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetTextColor(200, 220, 245);
        $pdf->SetXY($pageW - $margin - 90, 20);
        $pdf->Cell(90, 5, $this->toLatin('N° ' . $receiptNumber), 0, 0, 'R');
        $pdf->SetXY($pageW - $margin - 90, 26);
        $pdf->Cell(90, 5, $this->toLatin('Établi le ' . $issuedAt), 0, 0, 'R');

        // ---------- Badge statut ----------
        $badgeY = 48;
        $badgeW = 46;
        $badgeX = $pageW - $margin - $badgeW;
        if ($isFullyPaid) {
            $pdf->SetFillColor($green[0], $green[1], $green[2]);
        } else {
            $pdf->SetFillColor($orange[0], $orange[1], $orange[2]);
        }
        $pdf->Rect($badgeX, $badgeY, $badgeW, 8, 'F');
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->SetXY($badgeX, $badgeY + 1.4);
        $pdf->Cell($badgeW, 5, $this->toLatin($isFullyPaid ? 'SOLDÉ' : 'EN COURS'), 0, 0, 'C');

        // ---------- Bloc informations (2 colonnes) ----------
        $boxY = 60;
        $boxH = 46;
        $leftX = $margin;
        $leftW = 96;
        $rightX = $margin + $leftW + 6;
        $rightW = $contentW - $leftW - 6;

        // Box gauche : étudiant
        $pdf->SetFillColor($light[0], $light[1], $light[2]);
        $pdf->SetDrawColor($border[0], $border[1], $border[2]);
        $pdf->SetLineWidth(0.3);
        $pdf->Rect($leftX, $boxY, $leftW, $boxH, 'DF');
        $pdf->SetFillColor($blue[0], $blue[1], $blue[2]);
        $pdf->Rect($leftX, $boxY, $leftW, 7, 'F');
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Helvetica', 'B', 8.5);
        $pdf->SetXY($leftX + 3, $boxY + 1.4);
        $pdf->Cell($leftW - 6, 4.5, $this->toLatin('INFORMATIONS ÉTUDIANT'), 0, 0, 'L');

        $infoLine = function (float $x, float $y, float $w, string $label, string $value) use ($pdf, $gray) {
            $pdf->SetFont('Helvetica', '', 8);
            $pdf->SetTextColor($gray[0], $gray[1], $gray[2]);
            $pdf->SetXY($x + 3, $y);
            $pdf->Cell(26, 4.5, $this->toLatin($label), 0, 0, 'L');
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->SetTextColor(30, 41, 59);
            $pdf->SetXY($x + 30, $y);
            $pdf->Cell($w - 33, 4.5, $this->toLatin($value !== '' ? $value : '-'), 0, 0, 'L');
        };

        $iy = $boxY + 10;
        $infoLine($leftX, $iy, $leftW, 'Nom', $studentName);
        $iy += 7;
        $infoLine($leftX, $iy, $leftW, 'Email', $studentEmail);
        $iy += 7;
        $infoLine($leftX, $iy, $leftW, 'ID étudiant', $studentId);
        $iy += 7;
        $infoLine($leftX, $iy, $leftW, 'Inscrit le', $registrationDate);

        // Box droite : reçu
        $pdf->SetFillColor($light[0], $light[1], $light[2]);
        $pdf->Rect($rightX, $boxY, $rightW, $boxH, 'DF');
        $pdf->SetFillColor($blue[0], $blue[1], $blue[2]);
        $pdf->Rect($rightX, $boxY, $rightW, 7, 'F');
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Helvetica', 'B', 8.5);
        $pdf->SetXY($rightX + 3, $boxY + 1.4);
        $pdf->Cell($rightW - 6, 4.5, $this->toLatin('DÉTAILS DU REÇU'), 0, 0, 'L');

        $iy = $boxY + 10;
        $infoLine($rightX, $iy, $rightW, 'Formation', $formation);
        $iy += 7;
        $infoLine($rightX, $iy, $rightW, 'Référence', $paymentReference);
        $iy += 7;
        $infoLine($rightX, $iy, $rightW, 'N° reçu', $receiptNumber);
        $iy += 7;
        $infoLine($rightX, $iy, $rightW, 'Date', $issuedAt);

        // ---------- Tableau des paiements ----------
        $payments = array_values((array) ($data['payments'] ?? []));
        $maxRows = 10;
        $extraCount = max(0, count($payments) - $maxRows);
        $rows = array_slice($payments, 0, $maxRows);

        $tableY = $boxY + $boxH + 10;
        $rowH = 7;
        $wDate = 24;
        $wLib = 46;
        $wRef = 55;
        $wAmount = 33;
        $wStatus = 22;

        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetTextColor($navy[0], $navy[1], $navy[2]);
        $pdf->SetXY($margin, $tableY - 6);
        $pdf->Cell($contentW, 5, $this->toLatin('DÉTAIL DES PAIEMENTS'), 0, 0, 'L');

        // Header
        $pdf->SetFillColor($navy[0], $navy[1], $navy[2]);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Helvetica', 'B', 8.5);
        $pdf->SetXY($margin, $tableY);
        $pdf->Cell($wDate, $rowH, $this->toLatin('Date'), 1, 0, 'L', true);
        $pdf->Cell($wLib, $rowH, $this->toLatin('Libellé'), 1, 0, 'L', true);
        $pdf->Cell($wRef, $rowH, $this->toLatin('Référence'), 1, 0, 'L', true);
        $pdf->Cell($wAmount, $rowH, $this->toLatin('Montant'), 1, 0, 'R', true);
        $pdf->Cell($wStatus, $rowH, $this->toLatin('Statut'), 1, 1, 'C', true);

        // Lignes
        $pdf->SetFont('Helvetica', '', 8.5);
        $y = $tableY + $rowH;
        foreach ($rows as $i => $p) {
            $date   = (string) (($p['paid_at'] ?? '') ?: ($p['created_at'] ?? ''));
            $lib    = (string) (($p['installment_label'] ?? '') ?: 'Paiement');
            $ref    = (string) ($p['payment_reference'] ?? '');
            $amt    = $this->money($p['amount'] ?? 0);
            $status = (string) (($p['status_label'] ?? ($p['status'] ?? '')) ?: '');

            $fill = ($i % 2) === 1;
            if ($fill) {
                $pdf->SetFillColor($light[0], $light[1], $light[2]);
            } else {
                $pdf->SetFillColor(255, 255, 255);
            }
            $pdf->SetTextColor(30, 41, 59);

            $pdf->SetXY($margin, $y);
            $pdf->Cell($wDate, $rowH, $this->toLatin($date), 1, 0, 'L', $fill);
            $pdf->Cell($wLib, $rowH, $this->toLatin($lib), 1, 0, 'L', $fill);
            $pdf->Cell($wRef, $rowH, $this->toLatin($ref !== '' ? $ref : '-'), 1, 0, 'L', $fill);
            $pdf->Cell($wAmount, $rowH, $this->toLatin($amt), 1, 0, 'R', $fill);

            // Statut coloré
            $statusColor = [30, 41, 59];
            if (($p['status'] ?? '') === 'completed') {
                $statusColor = $green;
            } elseif (in_array(($p['status'] ?? ''), ['failed', 'cancelled'], true)) {
                $statusColor = [220, 38, 38];
            } elseif (($p['status'] ?? '') === 'pending') {
                $statusColor = $orange;
            }
            $pdf->SetTextColor($statusColor[0], $statusColor[1], $statusColor[2]);
            $pdf->SetFont('Helvetica', 'B', 8.5);
            $pdf->Cell($wStatus, $rowH, $this->toLatin($status), 1, 1, 'C', $fill);
            $pdf->SetFont('Helvetica', '', 8.5);

            $y += $rowH;
        }

        if ($extraCount > 0) {
            $pdf->SetFillColor($light[0], $light[1], $light[2]);
            $pdf->SetTextColor($gray[0], $gray[1], $gray[2]);
            $pdf->SetXY($margin, $y);
            $pdf->Cell($wDate + $wLib + $wRef + $wAmount + $wStatus, $rowH, $this->toLatin('+ ' . $extraCount . ' autre(s) paiement(s)'), 1, 1, 'C', true);
            $y += $rowH;
        }

        if (count($rows) === 0) {
            $pdf->SetTextColor($gray[0], $gray[1], $gray[2]);
            $pdf->SetXY($margin, $y);
            $pdf->Cell($wDate + $wLib + $wRef + $wAmount + $wStatus, $rowH, $this->toLatin('Aucun paiement enregistré'), 1, 1, 'C');
            $y += $rowH;
        }

        // ---------- Totaux (colonne droite) ----------
        $totalsW = 80;
        $totalsX = $pageW - $margin - $totalsW;
        $totalsY = $y + 6;
        $tRowH = 6.5;
        $labelW = 44;
        $valW = $totalsW - $labelW;

        $totalLine = function (string $label, string $value, bool $bold = false, ?array $color = null, ?array $fillColor = null) use ($pdf, $totalsX, $labelW, $valW, $tRowH) {
            if ($fillColor !== null) {
                $pdf->SetFillColor($fillColor[0], $fillColor[1], $fillColor[2]);
                $fill = true;
            } else {
                $pdf->SetFillColor(255, 255, 255);
                $fill = false;
            }
            $pdf->SetX($totalsX);
            $pdf->SetFont('Helvetica', $bold ? 'B' : '', 9);
            $pdf->SetTextColor(30, 41, 59);
            $pdf->Cell($labelW, $tRowH, $this->toLatin($label), 1, 0, 'L', $fill);
            if ($color !== null) {
                $pdf->SetTextColor($color[0], $color[1], $color[2]);
            }
            $pdf->Cell($valW, $tRowH, $this->toLatin($value), 1, 1, 'R', $fill);
            $pdf->SetTextColor(30, 41, 59);
        };

        $pdf->SetXY($totalsX, $totalsY);
        $pdf->SetFillColor($navy[0], $navy[1], $navy[2]);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell($totalsW, $tRowH, $this->toLatin('RÉCAPITULATIF'), 1, 1, 'L', true);

        if ($discountAmountRaw > 0) {
            $totalLine('Coût formation', $grossTotalAmount);
            $totalLine('Remise', '- ' . $discountAmount, false, $green);
            $totalLine('Total dû', $totalAmount, true);
        } else {
            $totalLine('Total dû', $totalAmount, true);
        }
        $totalLine('Total payé', $amountPaid, false, $green);
        $totalLine('Reste à solder', $remaining, true, $isFullyPaid ? $green : [220, 38, 38], $isFullyPaid ? [220, 252, 231] : [254, 226, 226]);

        // ---------- Note + signature ----------
        $noteY = max($y + 8, $totalsY + 8);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetTextColor($gray[0], $gray[1], $gray[2]);
        $pdf->SetXY($margin, $noteY);
        $pdf->MultiCell(105, 4, $this->toLatin("Ce reçu atteste des paiements enregistrés dans le système EVC. Document original — pour toute vérification, contactez l'administration EVC en indiquant le numéro de reçu."));

        // Zone signature (droite)
        $sigY = 252;
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetTextColor(30, 41, 59);
        $pdf->SetXY($totalsX, $sigY);
        $pdf->Cell($totalsW, 5, $this->toLatin('Signature et cachet'), 0, 0, 'C');
        $pdf->SetDrawColor($border[0], $border[1], $border[2]);
        $pdf->Line($totalsX + 5, $sigY + 22, $totalsX + $totalsW - 5, $sigY + 22);

        // ---------- Footer ----------
        $pdf->SetFillColor($navy[0], $navy[1], $navy[2]);
        $pdf->Rect(0, 285, $pageW, 12, 'F');
        $pdf->SetTextColor(200, 220, 245);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetXY(0, 288);
        $pdf->Cell($pageW, 5, $this->toLatin('EVC - École Virtuelle des Créatifs  |  Abidjan, Côte d\'Ivoire  |  www.ecolevirtuelledescreatifs.com'), 0, 0, 'C');

        // ---------- Output ----------
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
