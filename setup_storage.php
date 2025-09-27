<?php
// Script pour configurer le stockage Laravel
echo "<h2>Configuration du Stockage Laravel</h2>";

try {
    // 1. Créer le répertoire storage/app/public s'il n'existe pas
    $storageDir = __DIR__ . '/storage/app/public';
    if (!file_exists($storageDir)) {
        mkdir($storageDir, 0755, true);
        echo "<p>✅ Répertoire storage/app/public créé</p>";
    } else {
        echo "<p>✅ Répertoire storage/app/public existe déjà</p>";
    }
    
    // 2. Créer le répertoire projects
    $projectsDir = $storageDir . '/projects';
    if (!file_exists($projectsDir)) {
        mkdir($projectsDir, 0755, true);
        echo "<p>✅ Répertoire storage/app/public/projects créé</p>";
    } else {
        echo "<p>✅ Répertoire storage/app/public/projects existe déjà</p>";
    }
    
    // 3. Créer le lien symbolique public/storage -> storage/app/public
    $publicStorageLink = __DIR__ . '/public/storage';
    $storageTarget = __DIR__ . '/storage/app/public';
    
    if (!file_exists($publicStorageLink)) {
        if (symlink($storageTarget, $publicStorageLink)) {
            echo "<p>✅ Lien symbolique public/storage créé</p>";
        } else {
            echo "<p>❌ Impossible de créer le lien symbolique</p>";
            echo "<p>Créez manuellement : ln -s " . $storageTarget . " " . $publicStorageLink . "</p>";
        }
    } else {
        echo "<p>✅ Lien symbolique public/storage existe déjà</p>";
    }
    
    // 4. Vérifier les permissions
    echo "<h3>Vérification des permissions:</h3>";
    echo "<ul>";
    echo "<li>storage/app/public: " . (is_writable($storageDir) ? "✅ Écriture autorisée" : "❌ Pas d'écriture") . "</li>";
    echo "<li>storage/app/public/projects: " . (is_writable($projectsDir) ? "✅ Écriture autorisée" : "❌ Pas d'écriture") . "</li>";
    echo "</ul>";
    
    // 5. Test de création de fichier
    $testFile = $projectsDir . '/test.txt';
    if (file_put_contents($testFile, 'Test de création de fichier')) {
        echo "<p>✅ Test de création de fichier réussi</p>";
        unlink($testFile); // Supprimer le fichier de test
    } else {
        echo "<p>❌ Impossible de créer un fichier de test</p>";
    }
    
    echo "<h3>Configuration terminée !</h3>";
    echo "<p>Vous pouvez maintenant tester l'upload d'images dans votre formulaire.</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Erreur: " . $e->getMessage() . "</p>";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2, h3 { color: #333; }
p { margin: 10px 0; }
ul { margin: 10px 0; }
li { margin: 5px 0; }
</style>
