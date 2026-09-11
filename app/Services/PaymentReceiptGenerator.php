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
        // Template voulu: celui du dossier "recu" (public/assets/recu/*.pdf)
        // On prend template_recu.pdf en priorité, sinon le premier PDF trouvé dans le dossier.
        $recuDir = public_path('assets/recu');
        if (is_dir($recuDir)) {
            $preferred = $this->resolveTemplatePath('assets/recu/template_recu.pdf');
            if ($preferred) {
                return $preferred;
            }

            $pdfs = glob($recuDir . '/*.pdf') ?: [];
            if (!empty($pdfs)) {
                sort($pdfs);
                return $pdfs[0];
            }
        }

        // Fallback: template de la facture
        return $this->resolveTemplatePath('assets/facture/Template_Facture.pdf');
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

            $grossTotalAmount = $this->money($data['gross_total_amount'] ?? ($data['total_amount'] ?? 0));
            $discountAmountRaw = (float) ($data['discount_amount'] ?? 0);
            $discountAmount = $this->money($discountAmountRaw);
            $totalAmount = $this->money($data['total_amount'] ?? 0);
            $amountPaid = $this->money($data['amount_paid'] ?? 0);
            $remaining = $this->money($data['remaining'] ?? 0);

            $pageW = (float) ($size['width'] ?? 210);

            // Le gabarit contient déjà ses propres intitulés : on se contente
            // de renseigner les valeurs en face de chacun d'eux.
            $valueX = 70.0;
            $rightX = 120.0;
            $rightW = 72.0;

            $pdf->SetFont('Helvetica', '', 10);
            $pdf->SetXY(145.0, 90.5);
            $pdf->Cell(45, 5, $this->toLatin($issuedAt), 0, 0, 'R');

            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->SetXY(57.0, 107.5);
            $pdf->Cell(80, 5, $this->toLatin($receiptNumber), 0, 0, 'L');

            $pdf->SetFont('Helvetica', '', 11);
            $pdf->SetXY($valueX, 139.0);
            $pdf->Cell(80, 5, $this->toLatin($studentName), 0, 0, 'L');

            $pdf->SetXY($valueX, 151.5);
            $pdf->Cell(80, 5, $this->toLatin($studentId !== '' ? $studentId : ($paymentReference !== '' ? $paymentReference : '-')), 0, 0, 'L');

            $pdf->SetXY($valueX, 164.5);
            $pdf->Cell(80, 5, $this->toLatin($studentEmail), 0, 0, 'L');

            $pdf->SetXY($valueX, 191.0);
            $pdf->Cell(80, 5, $this->toLatin($formation), 0, 0, 'L');

            $pdf->SetFont('Helvetica', 'B', 12);
            $pdf->SetXY($valueX, 205.0);
            $pdf->Cell(80, 5, $this->toLatin($amountPaid), 0, 0, 'L');

            // Récapitulatif financier dans la colonne libre de droite
            $rowH = 6.0;
            $y = 132.0;

            $pdf->SetDrawColor(180, 180, 180);
            $pdf->SetLineWidth(0.2);
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->SetXY($rightX, $y);
            $pdf->Cell($rightW, $rowH, $this->toLatin('RECAPITULATIF'), 1, 1, 'C', true);

            $labelW = 40.0;
            $amountW = $rightW - $labelW;

            $line = function (string $label, string $value, bool $bold = false, ?array $color = null) use ($pdf, $rightX, $labelW, $amountW, $rowH) {
                $pdf->SetX($rightX);
                $pdf->SetFont('Helvetica', $bold ? 'B' : '', 9);
                $pdf->Cell($labelW, $rowH, $this->toLatin($label), 1, 0, 'L');
                if ($color !== null) {
                    $pdf->SetTextColor($color[0], $color[1], $color[2]);
                }
                $pdf->Cell($amountW, $rowH, $this->toLatin($value), 1, 1, 'R');
                $pdf->SetTextColor(0, 0, 0);
            };

            $line('Cout formation', $grossTotalAmount);
            if ($discountAmountRaw > 0) {
                $line('Remise', '- ' . $discountAmount, false, [0, 130, 70]);
            }
            $line('Total du', $totalAmount);
            $line('Total paye', $amountPaid);
            $line('Reste a solder', $remaining, true);

            if ($paymentReference !== '') {
                $pdf->SetFont('Helvetica', '', 8);
                $pdf->SetTextColor(90, 90, 90);
                $pdf->SetXY($rightX, $pdf->GetY() + 2);
                $pdf->Cell($rightW, 4.5, $this->toLatin('Reference : ' . $paymentReference), 0, 1, 'L');
                $pdf->SetTextColor(0, 0, 0);
            }

            // Détail des paiements sur une page dédiée
            $payments = array_values((array) ($data['payments'] ?? []));
            if (count($payments) > 0) {
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);

                $pdf->SetFont('Helvetica', 'B', 14);
                $pdf->SetTextColor(26, 35, 126);
                $pdf->SetXY(15, 20);
                $pdf->Cell(0, 8, $this->toLatin('DETAIL DES PAIEMENTS'), 0, 1, 'L');
                $pdf->SetTextColor(0, 0, 0);

                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetX(15);
                $pdf->Cell(0, 6, $this->toLatin($studentName . ' - ' . $formation), 0, 1, 'L');
                $pdf->SetX(15);
                $pdf->Cell(0, 6, $this->toLatin('Recu ' . $receiptNumber), 0, 1, 'L');

                $tableX = 15;
                $rowH = 7;
                $wDate = 25;
                $wLib = 45;
                $wRef = 55;
                $wAmount = 30;
                $wStatus = 25;

                $pdf->SetFont('Helvetica', 'B', 9);
                $pdf->SetFillColor(240, 240, 240);
                $pdf->SetXY($tableX, $pdf->GetY() + 4);
                $pdf->Cell($wDate, $rowH, $this->toLatin('Date'), 1, 0, 'L', true);
                $pdf->Cell($wLib, $rowH, $this->toLatin('Libelle'), 1, 0, 'L', true);
                $pdf->Cell($wRef, $rowH, $this->toLatin('Reference'), 1, 0, 'L', true);
                $pdf->Cell($wAmount, $rowH, $this->toLatin('Montant'), 1, 0, 'R', true);
                $pdf->Cell($wStatus, $rowH, $this->toLatin('Statut'), 1, 1, 'L', true);

                $pdf->SetFont('Helvetica', '', 9);
                foreach ($payments as $i => $p) {
                    $date = (string) (($p['paid_at'] ?? '') ?: ($p['created_at'] ?? ''));
                    $lib = (string) (($p['installment_label'] ?? '') ?: 'Paiement');
                    $ref = (string) ($p['payment_reference'] ?? '');
                    $amt = $this->money($p['amount'] ?? 0);
                    $status = (string) (($p['status_label'] ?? ($p['status'] ?? '')) ?: '');

                    $fill = ($i % 2) === 1;
                    $pdf->SetFillColor($fill ? 250 : 255, $fill ? 250 : 255, $fill ? 250 : 255);

                    $pdf->SetX($tableX);
                    $pdf->Cell($wDate, $rowH, $this->toLatin($date), 1, 0, 'L', $fill);
                    $pdf->Cell($wLib, $rowH, $this->toLatin($lib), 1, 0, 'L', $fill);
                    $pdf->Cell($wRef, $rowH, $this->toLatin($ref), 1, 0, 'L', $fill);
                    $pdf->Cell($wAmount, $rowH, $this->toLatin($amt), 1, 0, 'R', $fill);
                    $pdf->Cell($wStatus, $rowH, $this->toLatin($status), 1, 1, 'L', $fill);
                }

                $pdf->Ln(4);
                $pdf->SetFont('Helvetica', 'B', 10);
                $pdf->SetX($tableX + $wDate + $wLib);
                $pdf->Cell($wRef, $rowH, $this->toLatin('Total paye'), 1, 0, 'R');
                $pdf->Cell($wAmount, $rowH, $this->toLatin($amountPaid), 1, 0, 'R');
                $pdf->Cell($wStatus, $rowH, '', 0, 1, 'L');
                $pdf->SetX($tableX + $wDate + $wLib);
                $pdf->Cell($wRef, $rowH, $this->toLatin('Reste a solder'), 1, 0, 'R');
                $pdf->Cell($wAmount, $rowH, $this->toLatin($remaining), 1, 0, 'R');

                $pdf->SetFont('Helvetica', '', 8);
                $pdf->SetTextColor(90, 90, 90);
                $pdf->SetXY(15, $pdf->GetY() + 12);
                $pdf->MultiCell(180, 4.5, $this->toLatin("Ce recu est un document ORIGINAL.\nPour toute verification, veuillez contacter l'administration EVC avec la reference ci-dessus."));
                $pdf->SetTextColor(0, 0, 0);
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
            if ((float) ($data['discount_amount'] ?? 0) > 0) {
                $pdf->Cell(0, 5, $this->toLatin('Coût formation : ') . $this->toLatin($this->money($data['gross_total_amount'] ?? ($data['total_amount'] ?? 0))), 0, 1, 'L');
                $pdf->Cell(0, 5, $this->toLatin('Remise : - ') . $this->toLatin($this->money($data['discount_amount'] ?? 0)), 0, 1, 'L');
                $pdf->Cell(0, 5, $this->toLatin('Total dû après remise : ') . $this->toLatin($this->money($data['total_amount'] ?? 0)), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 5, $this->toLatin('Montant total : ') . $this->toLatin($this->money($data['total_amount'] ?? 0)), 0, 1, 'L');
            }
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
