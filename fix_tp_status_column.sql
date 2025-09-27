-- Script SQL pour corriger la colonne status de la table tp
-- Exécuter ce script dans phpMyAdmin ou via ligne de commande MySQL
-- NOUVEAUX STATUTS: 'pending', 'validate', 'rejected'

USE v4_evc;

-- Vérifier la structure actuelle de la table tp
DESCRIBE tp;

-- Ajouter/Modifier la colonne status avec les nouveaux statuts
ALTER TABLE tp 
ADD COLUMN status ENUM('pending', 'validate', 'rejected') 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci 
NOT NULL DEFAULT 'pending';

-- Si la colonne existe déjà, utiliser MODIFY au lieu de ADD:
-- ALTER TABLE tp MODIFY COLUMN status ENUM('pending', 'validate', 'rejected') DEFAULT 'pending';

-- Vérifier que la modification a bien été appliquée
DESCRIBE tp;

-- Optionnel: Mettre à jour les TP existants avec les nouveaux statuts
-- UPDATE tp SET status = 'pending' WHERE status IS NULL OR status = '';

SELECT 'Colonne status corrigée avec les nouveaux statuts: pending, validate, rejected!' as message;
