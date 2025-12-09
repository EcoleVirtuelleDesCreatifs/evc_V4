# Configuration des Certificats

## Structure des dossiers

```
public/assets/certificats/
├── cm_smm/
│   └── certificat_cm_smm.pdf        # Template Community Management / SMM
├── design/
│   └── certificat_design.pdf        # Template Design Graphique
└── README.md
```

## Ajout d'un nouveau template

1. **Créer un dossier** pour la formation (ex: `gestion_informatique/`)
2. **Ajouter le PDF template** dans ce dossier
3. **Configurer les positions** dans `config/certificates.php`

## Configuration des positions (config/certificates.php)

Les positions sont définies en **millimètres depuis le haut** de la page.

### Paramètres disponibles :

```php
'name' => [
    'y' => 120,              // Position verticale (mm depuis le haut)
    'font' => 'Helvetica',   // Police (Helvetica, Times, Courier)
    'font_style' => 'B',     // Style: '' = Normal, 'B' = Bold, 'I' = Italic, 'BI' = Bold+Italic
    'font_size' => 24,       // Taille de la police
    'color' => [0, 0, 0],    // Couleur RGB (0-255)
],
```

## Comment trouver les bonnes positions ?

### Méthode 1 : Mesure manuelle
1. Ouvrir le PDF template dans un éditeur PDF (Adobe Acrobat, Preview)
2. Utiliser l'outil de mesure pour trouver la position en mm
3. Ajuster les valeurs dans `config/certificates.php`

### Méthode 2 : Tests successifs
1. Générer un certificat de test
2. Ajuster les valeurs dans `config/certificates.php`
3. Regénérer et vérifier
4. Répéter jusqu'à obtenir le résultat souhaité

## Format A4 paysage

- **Largeur** : 297 mm
- **Hauteur** : 210 mm

Le texte est automatiquement **centré horizontalement**.

## Exemple de configuration

Pour un certificat où :
- Le nom doit apparaître à 120mm du haut
- La formation à 145mm
- La date à 250mm (20mm du bas)

```php
'positions' => [
    'name' => ['y' => 120, 'font_size' => 24],
    'formation' => ['y' => 145, 'font_size' => 16],
    'date' => ['y' => 250, 'font_size' => 12],
]
```

## Couleurs communes

```php
[0, 0, 0]         // Noir
[255, 0, 0]       // Rouge
[0, 0, 255]       // Bleu
[0, 128, 0]       // Vert
[128, 128, 128]   // Gris
[255, 255, 255]   // Blanc
```

## Notes importantes

- Les positions `y` sont en **millimètres**
- Le texte est toujours **centré horizontalement**
- La police `Helvetica` est recommandée pour une compatibilité maximale
- Tester avec différents noms (courts et longs) pour vérifier l'affichage
