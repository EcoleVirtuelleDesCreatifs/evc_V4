<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;

/**
 * FPDI étendu avec primitives de dessin (rectangles arrondis, cercles)
 * via commandes PDF brutes (courbes de Bézier).
 */
class EvcReceiptPdf extends Fpdi
{
    public function roundedRect(float $x, float $y, float $w, float $h, float $r, string $style = 'F'): void
    {
        $k  = $this->k;
        $hp = $this->h;
        $op = match ($style) {
            'F'             => 'f',
            'FD', 'DF', 'B' => 'B',
            default         => 'S',
        };
        $arc = 4 / 3 * (sqrt(2) - 1);

        $this->_out(sprintf('%.2F %.2F m', ($x + $r) * $k, ($hp - $y) * $k));

        $xc = $x + $w - $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - $y) * $k));
        $this->arc($xc + $r * $arc, $yc - $r, $xc + $r, $yc - $r * $arc, $xc + $r, $yc);

        $xc = $x + $w - $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $yc) * $k));
        $this->arc($xc + $r, $yc + $r * $arc, $xc + $r * $arc, $yc + $r, $xc, $yc + $r);

        $xc = $x + $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - ($y + $h)) * $k));
        $this->arc($xc - $r * $arc, $yc + $r, $xc - $r, $yc + $r * $arc, $xc - $r, $yc);

        $xc = $x + $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', $x * $k, ($hp - $yc) * $k));
        $this->arc($xc - $r, $yc - $r * $arc, $xc - $r * $arc, $yc - $r, $xc, $yc - $r);

        $this->_out($op);
    }

    public function circle(float $cx, float $cy, float $r, string $style = 'F'): void
    {
        $this->roundedRect($cx - $r, $cy - $r, 2 * $r, 2 * $r, $r, $style);
    }

    /**
     * Décale tout le contenu dessiné ensuite (tx, ty en mm ; ty positif = vers le haut).
     */
    public function beginTranslate(float $txMm, float $tyUpMm): void
    {
        $this->_out(sprintf('q 1 0 0 1 %.2F %.2F cm', $txMm * $this->k, $tyUpMm * $this->k));
    }

    public function endTranslate(): void
    {
        $this->_out('Q');
    }

    private function arc(float $x1, float $y1, float $x2, float $y2, float $x3, float $y3): void
    {
        $h = $this->h;
        $k = $this->k;
        $this->_out(sprintf(
            '%.2F %.2F %.2F %.2F %.2F %.2F c',
            $x1 * $k,
            ($h - $y1) * $k,
            $x2 * $k,
            ($h - $y2) * $k,
            $x3 * $k,
            ($h - $y3) * $k
        ));
    }
}
