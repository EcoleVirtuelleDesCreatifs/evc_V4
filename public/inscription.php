<?php
// Traitement de l'inscription directement en PHP (contournement Laravel)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validation des données
        $prenom = trim($_POST['prenom'] ?? '');
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $pays = trim($_POST['pays'] ?? '');
        $ville = trim($_POST['ville'] ?? '');
        $niveau = trim($_POST['niveau'] ?? '');
        $formation = trim($_POST['formation'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirmation = $_POST['password_confirmation'] ?? '';
        $terms = isset($_POST['terms']) ? 1 : 0;

        // Validations basiques
        $errors = [];
        if (empty($prenom)) $errors[] = "Le prénom est requis";
        if (empty($nom)) $errors[] = "Le nom est requis";
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide";
        if (empty($telephone)) $errors[] = "Le téléphone est requis";
        if (empty($pays)) $errors[] = "Le pays est requis";
        if (empty($ville)) $errors[] = "La ville est requise";
        if (empty($niveau)) $errors[] = "Le niveau est requis";
        if (empty($formation)) $errors[] = "La formation est requise";
        if (empty($password) || strlen($password) < 6) $errors[] = "Le mot de passe doit faire au moins 6 caractères";
        if ($password !== $password_confirmation) $errors[] = "Les mots de passe ne correspondent pas";
        if (!$terms) $errors[] = "Vous devez accepter les conditions d'utilisation";
        
        // Validation obligatoire de la photo
        if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "La photo de profil est obligatoire";
        }

        if (!empty($errors)) {
            throw new Exception(implode(', ', $errors));
        }

        // Gestion de l'upload de photo (maintenant obligatoire)
        $photoPath = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
            $fileType = $_FILES['photo']['type'];
            $fileSize = $_FILES['photo']['size'];
            
            if (in_array($fileType, $allowedTypes) && $fileSize <= 2097152) { // 2MB max
                $photoName = time() . '_' . basename($_FILES['photo']['name']);
                $uploadDir = 'uploads/photos/';
                
                // Créer le dossier s'il n'existe pas
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $photoName)) {
                    $photoPath = $uploadDir . $photoName;
                }
            }
        }

        // Connexion à la base de données
        $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=v4_evc;charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            throw new Exception("Cet email est déjà utilisé");
        }

        // Insérer l'utilisateur dans la nouvelle table users
        $stmt = $pdo->prepare("
            INSERT INTO users (
                first_name, last_name, email, phone, country, city, current_level, 
                formation_souhaitee, password, profile_photo, 
                date_inscription, status, accepte_conditions
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'Actif', 1)
        ");

        $stmt->execute([
            $prenom, $nom, $email, $telephone, $pays, $ville, $niveau,
            $formation, password_hash($password, PASSWORD_DEFAULT), $photoPath
        ]);

        // Enregistrer l'activité
        $userId = $pdo->lastInsertId();

        // Enregistrer l'activité d'inscription dans la nouvelle table
        $stmt = $pdo->prepare("
            INSERT INTO user_activities (user_id, activity_type, description, created_at)
            VALUES (?, 'Inscription', 'Création du compte utilisateur', NOW())
        ");
        $stmt->execute([$userId]);

        // Initialiser les statistiques utilisateur dans la nouvelle table
        $stmt = $pdo->prepare("
            INSERT INTO user_statistics (
                user_id, total_tp_completed, total_projects_completed, total_hours_studied,
                completion_percentage, login_count, last_activity
            ) VALUES (?, 0, 0, 0, 0.0, 1, NOW())
        ");
        $stmt->execute([$userId]);

        // Retourner une réponse JSON de succès
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Inscription réussie ! Redirection vers la page de connexion...',
            'redirect' => '/auth/login'
        ]);
        exit;

    } catch (PDOException $e) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erreur base de données: ' . $e->getMessage()
        ]);
        exit;
    } catch (Exception $e) {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
        exit;
    }
}

// Si ce n'est pas une requête POST, rediriger vers le formulaire
header('Location: /register-final.html');
exit;
?>
