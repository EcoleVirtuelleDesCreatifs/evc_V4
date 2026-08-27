<?php

namespace App\Services;

class StoreOrderInvoiceGenerator
{
    protected $pdf;

    public function __construct()
    {
        require_once base_path('vendor/setasign/fpdf/fpdf.php');
        $this->pdf = new \FPDF('P', 'mm', 'A4');
    }

    public function generateInvoice($order)
    {
        $this->pdf->AddPage();
        $this->pdf->SetAutoPageBreak(true, 20);

        $this->addHeader();
        $this->addInvoiceInfo($order);
        $this->addClientInfo($order);
        $this->addItems($order);
        $this->addTotals($order);
        $this->addFooter();

        return $this->pdf;
    }

    protected function addHeader(): void
    {
        $this->pdf->SetFont('Arial', 'B', 18);
        $this->pdf->SetTextColor(30, 60, 114);
        $this->pdf->Cell(0, 10, 'EVC STORE', 0, 1, 'C');

        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->SetTextColor(100, 100, 100);
        $this->pdf->Cell(0, 5, utf8_decode('Boutique officielle de l\'Ecole Virtuelle des Createurs'), 0, 1, 'C');
        $this->pdf->Cell(0, 5, 'contact@ecolevirtuelledescreateurs.com', 0, 1, 'C');
        $this->pdf->Ln(8);

        $this->pdf->SetDrawColor(30, 60, 114);
        $this->pdf->SetLineWidth(0.5);
        $this->pdf->Line(10, $this->pdf->GetY(), 200, $this->pdf->GetY());
        $this->pdf->Ln(8);
    }

    protected function addInvoiceInfo($order): void
    {
        $this->pdf->SetFont('Arial', 'B', 14);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->Cell(0, 10, utf8_decode('FACTURE DE COMMANDE'), 0, 1, 'C');
        $this->pdf->Ln(4);

        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->SetTextColor(100, 100, 100);

        $colX = 10;
        $this->pdf->SetX($colX);
        $this->pdf->Cell(45, 6, utf8_decode('Numéro de facture:'), 0, 0);
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->Cell(0, 6, $order->order_number ?? ('EVC-ORD-' . $order->id), 0, 1);

        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->SetTextColor(100, 100, 100);
        $this->pdf->SetX($colX);
        $this->pdf->Cell(45, 6, 'Date:', 0, 0);
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->SetTextColor(0, 0, 0);
        $date = $order->created_at ? date('d/m/Y H:i', strtotime($order->created_at)) : date('d/m/Y H:i');
        $this->pdf->Cell(0, 6, $date, 0, 1);

        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->SetTextColor(100, 100, 100);
        $this->pdf->SetX($colX);
        $this->pdf->Cell(45, 6, 'Statut:', 0, 0);
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->SetTextColor(0, 0, 0);
        $status = $order->status ?? 'pending';
        $this->pdf->Cell(0, 6, utf8_decode(ucfirst(str_replace('_', ' ', $status))), 0, 1);

        $this->pdf->Ln(4);
    }

    protected function addClientInfo($order): void
    {
        $this->pdf->SetFillColor(240, 240, 240);
        $this->pdf->SetFont('Arial', 'B', 11);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->Cell(0, 8, utf8_decode('INFORMATIONS CLIENT'), 0, 1, 'L', true);
        $this->pdf->Ln(2);

        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->SetTextColor(100, 100, 100);

        $clientName = trim(($order->prenoms ?? '') . ' ' . ($order->nom ?? ''));
        $this->pdf->Cell(40, 6, 'Nom:', 0, 0);
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->Cell(0, 6, utf8_decode($clientName ?: 'Non renseigné'), 0, 1);

        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->SetTextColor(100, 100, 100);
        $this->pdf->Cell(40, 6, utf8_decode('Téléphone:'), 0, 0);
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->Cell(0, 6, $order->numero ?? '—', 0, 1);

        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->SetTextColor(100, 100, 100);
        $this->pdf->Cell(40, 6, 'Adresse:', 0, 0);
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->Cell(0, 6, utf8_decode($order->lieu ?? '—'), 0, 1);

        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->SetTextColor(100, 100, 100);
        $this->pdf->Cell(40, 6, 'Livraison:', 0, 0);
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->SetTextColor(0, 0, 0);
        $delivery = ($order->delivery_mode ?? '') === 'pickup' ? 'Retrait sur place' : 'Livraison';
        $this->pdf->Cell(0, 6, utf8_decode($delivery), 0, 1);

        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->SetTextColor(100, 100, 100);
        $this->pdf->Cell(40, 6, 'Paiement:', 0, 0);
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->SetTextColor(0, 0, 0);
        $payment = ($order->payment_method ?? '') === 'mobile_money' ? 'Mobile Money' : 'Espèces';
        $this->pdf->Cell(0, 6, utf8_decode($payment), 0, 1);

        $this->pdf->Ln(4);
    }

    protected function addItems($order): void
    {
        $this->pdf->SetFillColor(30, 60, 114);
        $this->pdf->SetTextColor(255, 255, 255);
        $this->pdf->SetFont('Arial', 'B', 10);

        $this->pdf->Cell(80, 10, utf8_decode('DÉSIGNATION'), 1, 0, 'L', true);
        $this->pdf->Cell(25, 10, 'QTE', 1, 0, 'C', true);
        $this->pdf->Cell(45, 10, 'PRIX UNIT.', 1, 0, 'C', true);
        $this->pdf->Cell(40, 10, 'TOTAL', 1, 1, 'C', true);

        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetFont('Arial', '', 10);

        $items = is_array($order->items) ? $order->items : (json_decode($order->items, true) ?? []);
        foreach ($items as $item) {
            $name = $item['name'] ?? 'Produit';
            $qty = (int) ($item['qty'] ?? 1);
            $price = (int) ($item['price'] ?? 0);
            $lineTotal = $qty * $price;

            $this->pdf->Cell(80, 10, utf8_decode($name), 1, 0, 'L');
            $this->pdf->Cell(25, 10, $qty, 1, 0, 'C');
            $this->pdf->Cell(45, 10, number_format($price, 0, ',', ' ') . ' FCFA', 1, 0, 'C');
            $this->pdf->Cell(40, 10, number_format($lineTotal, 0, ',', ' ') . ' FCFA', 1, 1, 'C');
        }

        $this->pdf->Ln(4);
    }

    protected function addTotals($order): void
    {
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->SetTextColor(100, 100, 100);

        $rightX = 110;
        $this->pdf->SetX($rightX);
        $this->pdf->Cell(45, 6, 'Sous-total:', 0, 0, 'L');
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->Cell(0, 6, number_format($order->subtotal ?? 0, 0, ',', ' ') . ' FCFA', 0, 1, 'R');

        if (($order->delivery_cost ?? 0) > 0) {
            $this->pdf->SetFont('Arial', '', 10);
            $this->pdf->SetTextColor(100, 100, 100);
            $this->pdf->SetX($rightX);
            $this->pdf->Cell(45, 6, 'Livraison:', 0, 0, 'L');
            $this->pdf->SetFont('Arial', 'B', 10);
            $this->pdf->SetTextColor(0, 0, 0);
            $this->pdf->Cell(0, 6, number_format($order->delivery_cost, 0, ',', ' ') . ' FCFA', 0, 1, 'R');
        }

        if (($order->discount ?? 0) > 0) {
            $this->pdf->SetFont('Arial', '', 10);
            $this->pdf->SetTextColor(100, 100, 100);
            $this->pdf->SetX($rightX);
            $this->pdf->Cell(45, 6, 'Remise:', 0, 0, 'L');
            $this->pdf->SetFont('Arial', 'B', 10);
            $this->pdf->SetTextColor(20, 120, 60);
            $this->pdf->Cell(0, 6, '-' . number_format($order->discount, 0, ',', ' ') . ' FCFA', 0, 1, 'R');
        }

        $this->pdf->Ln(2);
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(30, 60, 114);
        $this->pdf->SetX($rightX);
        $this->pdf->Cell(45, 8, 'TOTAL:', 0, 0, 'L');
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->Cell(0, 8, number_format($order->total ?? 0, 0, ',', ' ') . ' FCFA', 0, 1, 'R');

        $this->pdf->Ln(8);
    }

    protected function addFooter(): void
    {
        $this->pdf->SetFont('Arial', 'I', 9);
        $this->pdf->SetTextColor(100, 100, 100);
        $this->pdf->MultiCell(0, 5, utf8_decode("Merci pour votre commande.\nPour toute question, contactez l'administration EVC."), 0, 'C');

        $this->pdf->Ln(4);
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->SetTextColor(150, 150, 150);
        $this->pdf->Cell(0, 4, utf8_decode('Document généré automatiquement le ' . date('d/m/Y a H:i')), 0, 1, 'C');
    }

    public function download($filename = 'facture.pdf')
    {
        return $this->pdf->Output('D', $filename);
    }

    public function save($path)
    {
        return $this->pdf->Output('F', $path);
    }
}
