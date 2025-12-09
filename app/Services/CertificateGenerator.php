<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;
use setasign\Fpdf\Fpdf;

class CertificateGenerator
{
    /**
     * Générer un certificat à partir d'un PDF template
     *
     * @param string $templatePath Chemin vers le PDF template
     * @param array $data Données à insérer (nom, prénom, formation, date, etc.)
     * @return string Chemin vers le PDF généré
     */
    public function generate(string $templatePath, array $data): string
    {
        // Créer une instance FPDI
        $pdf = new Fpdi();

        // Importer le PDF template
        $pageCount = $pdf->setSourceFile($templatePath);

        // Importer la première page
        $templateId = $pdf->importPage(1);

        // Obtenir les dimensions de la page
        $size = $pdf->getTemplateSize($templateId);

        // Créer une page avec les mêmes dimensions
        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);

        // Utiliser le template importé
        $pdf->useTemplate($templateId);

        // Nom complet de l'étudiant (centré)
        $fullName = strtoupper($data['first_name'] . ' ' . $data['last_name']);
        // Convertir les caractères UTF-8 en ISO-8859-1 pour FPDF
        $fullName = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $fullName);

        // Position pour le nom - ajustée pour être sous "CE CERTIFICAT EST DÉCERNÉ À"
        $pdf->SetFont('Helvetica', 'B', 32);  // Police plus grande pour le nom
        $pdf->SetTextColor(0, 0, 0);

        $textWidth = $pdf->GetStringWidth($fullName);
        $x = ($size['width'] - $textWidth) / 2;
        $y = 92; // Position Y pour placer le nom (valeur plus petite = plus haut)

        // Ajouter le nom de l'étudiant
        $pdf->SetXY($x, $y);
        $pdf->Cell($textWidth, 10, $fullName, 0, 0, 'C');

        // Formation retirée - ne pas afficher sur le certificat

        // Ajouter la date (vers la droite et plus haut)
        if (isset($data['date'])) {
            $pdf->SetFont('Helvetica', '', 12);
            $dateText = 'Delivre le ' . $data['date'];
            $x = 127; // Position vers la droite (127mm de la gauche)
            $y = $size['height'] - 93; // 93mm du bas (monté légèrement)

            $pdf->SetXY($x, $y);
            $pdf->Cell($pdf->GetStringWidth($dateText), 10, $dateText, 0, 0, 'L');
        }

        // Ajouter le numéro étudiant (en bas de la page)
        if (isset($data['student_id'])) {
            $pdf->SetFont('Helvetica', '', 10);
            $studentIdText = 'N°  ' . $data['student_id'];
            // Convertir les caractères UTF-8 en ISO-8859-1 pour FPDF
            $studentIdText = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $studentIdText);
            $textWidth = $pdf->GetStringWidth($studentIdText);
            $x = ($size['width'] - $textWidth) / 2;
            $y = $size['height'] - 52; // 52mm du bas de la page (monté légèrement)

            $pdf->SetXY($x, $y);
            $pdf->Cell($textWidth, 10, $studentIdText, 0, 0, 'C');
        }

        // Créer le répertoire de sortie s'il n'existe pas
        $outputDir = storage_path('app/certificates');
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        // Générer un nom de fichier unique
        $filename = 'certificate_' . uniqid() . '_' . time() . '.pdf';
        $outputPath = $outputDir . '/' . $filename;

        // Sauvegarder le PDF
        $pdf->Output('F', $outputPath);

        return $outputPath;
    }

    /**
     * Générer un certificat pour Community Management / Social Media Marketing
     *
     * @param array $data
     * @return string
     */
    public function generateCommunityManagement(array $data): string
    {
        $templatePath = public_path('assets/certificats/cm_smm/certificat_cm_smm.pdf');

        if (!file_exists($templatePath)) {
            throw new \Exception('Template de certificat introuvable : ' . $templatePath);
        }

        return $this->generate($templatePath, $data);
    }

    /**
     * Télécharger le certificat généré
     *
     * @param string $filePath
     * @param string $downloadName
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(string $filePath, string $downloadName)
    {
        return response()->download($filePath, $downloadName)->deleteFileAfterSend(true);
    }
}
