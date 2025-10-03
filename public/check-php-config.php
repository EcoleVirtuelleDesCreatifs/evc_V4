<?php
/**
 * Script de diagnostic pour vérifier la configuration PHP
 */

echo "<h1>Configuration PHP - Limites d'Upload</h1>";

echo "<h2>Configuration Actuelle</h2>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr><th>Paramètre</th><th>Valeur Actuelle</th></tr>";

echo "<tr><td><strong>upload_max_filesize</strong></td><td style='font-size: 18px; color: blue;'>" . ini_get('upload_max_filesize') . "</td></tr>";
echo "<tr><td><strong>post_max_size</strong></td><td style='font-size: 18px; color: blue;'>" . ini_get('post_max_size') . "</td></tr>";
echo "<tr><td><strong>memory_limit</strong></td><td>" . ini_get('memory_limit') . "</td></tr>";
echo "<tr><td><strong>max_execution_time</strong></td><td>" . ini_get('max_execution_time') . " secondes</td></tr>";
echo "<tr><td><strong>max_input_time</strong></td><td>" . ini_get('max_input_time') . " secondes</td></tr>";
echo "<tr><td><strong>max_file_uploads</strong></td><td>" . ini_get('max_file_uploads') . "</td></tr>";

echo "</table>";

echo "<h2>Fichier php.ini Utilisé</h2>";
echo "<p style='font-size: 16px; background: #ffffcc; padding: 10px; border: 2px solid #ffcc00;'>";
echo "<strong>Fichier chargé :</strong> " . php_ini_loaded_file();
echo "</p>";

echo "<h2>Conversion en Bytes</h2>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr><th>Paramètre</th><th>En Bytes</th><th>En MB</th></tr>";

function convertToBytes($value) {
    $value = trim($value);
    $last = strtolower($value[strlen($value)-1]);
    $value = (int) $value;
    
    switch($last) {
        case 'g':
            $value *= 1024;
        case 'm':
            $value *= 1024;
        case 'k':
            $value *= 1024;
    }
    
    return $value;
}

$upload_max = convertToBytes(ini_get('upload_max_filesize'));
$post_max = convertToBytes(ini_get('post_max_size'));

echo "<tr><td>upload_max_filesize</td><td>" . number_format($upload_max) . " bytes</td><td>" . round($upload_max / 1024 / 1024, 2) . " MB</td></tr>";
echo "<tr><td>post_max_size</td><td style='background: " . ($post_max < 50000000 ? '#ffcccc' : '#ccffcc') . "'>" . number_format($post_max) . " bytes</td><td style='background: " . ($post_max < 50000000 ? '#ffcccc' : '#ccffcc') . "'>" . round($post_max / 1024 / 1024, 2) . " MB</td></tr>";
echo "<tr><td colspan='3'><em>Votre fichier fait : 18,357,200 bytes = 17.5 MB</em></td></tr>";

echo "</table>";

echo "<h2>Diagnostic</h2>";
echo "<div style='padding: 15px; border: 2px solid #cc0000; background: #ffeeee; margin: 20px 0;'>";
if ($post_max < 50000000) {
    echo "<p style='color: #cc0000; font-size: 16px;'><strong>❌ PROBLÈME DÉTECTÉ :</strong></p>";
    echo "<p>La limite <code>post_max_size</code> est actuellement à <strong>" . ini_get('post_max_size') . "</strong> (" . number_format($post_max) . " bytes).</p>";
    echo "<p>Votre fichier fait <strong>18,357,200 bytes</strong>, ce qui dépasse la limite.</p>";
    echo "<p><strong>SOLUTION :</strong> Apache n'a pas été redémarré après la modification du php.ini.</p>";
    echo "<p>Pour corriger :</p>";
    echo "<ol>";
    echo "<li>Ouvrez XAMPP Control Panel</li>";
    echo "<li>Cliquez sur <strong>Stop</strong> pour Apache</li>";
    echo "<li>Attendez 3 secondes</li>";
    echo "<li>Cliquez sur <strong>Start</strong> pour Apache</li>";
    echo "<li>Rafraîchissez cette page pour vérifier</li>";
    echo "</ol>";
} else {
    echo "<p style='color: #00cc00; font-size: 18px;'><strong>✅ Configuration OK !</strong></p>";
    echo "<p>Les limites sont suffisantes pour uploader votre fichier.</p>";
}
echo "</div>";

echo "<hr>";
echo "<p><a href='check-php-config.php' style='background: #0066cc; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔄 Rafraîchir la page</a></p>";
echo "<p><em>Créé le : " . date('Y-m-d H:i:s') . "</em></p>";
