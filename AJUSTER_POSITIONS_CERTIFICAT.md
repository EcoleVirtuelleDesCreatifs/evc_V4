# Comment ajuster les positions du texte sur le certificat

## 📍 Fichier à modifier

`app/Services/CertificateGenerator.php`

---

## 🎯 Positions actuelles

### **Nom de l'étudiant**
```php
Ligne 41: $pdf->SetFont('Helvetica', 'B', 32);  // Taille de la police
Ligne 46: $y = 125;  // Position verticale (en mm depuis le haut)
```

### **Formation**
```php
Ligne 54: $pdf->SetFont('Helvetica', '', 16);   // Taille de la police
Ligne 58: $y = $y + 20;  // 20mm en dessous du nom
```

### **Date de délivrance**
```php
Ligne 66: $pdf->SetFont('Helvetica', '', 12);   // Taille de la police
Ligne 70: $y = $size['height'] - 40;  // 40mm du bas de la page
```

### **Numéro étudiant**
```php
Ligne 78: $pdf->SetFont('Helvetica', '', 10);   // Taille de la police
Ligne 82: $y = $size['height'] - 20;  // 20mm du bas de la page
```

---

## 📏 Format A4 Paysage

- **Largeur** : 297 mm
- **Hauteur** : 210 mm
- **Position 0,0** : Coin supérieur gauche

---

## 🔧 Comment ajuster

### **Déplacer le nom PLUS BAS :**
```php
$y = 125;  // Changer 125 à 135 par exemple
```

### **Déplacer le nom PLUS HAUT :**
```php
$y = 125;  // Changer 125 à 115 par exemple
```

### **Changer la taille du nom :**
```php
$pdf->SetFont('Helvetica', 'B', 32);  // Changer 32 à 28 ou 36
```

---

## 📝 Exemple de modification

Si vous voulez que le nom soit :
- Plus bas : `$y = 135;`
- Plus petit : `SetFont('Helvetica', 'B', 28);`

### **Avant :**
```php
$pdf->SetFont('Helvetica', 'B', 32);
$y = 125;
```

### **Après :**
```php
$pdf->SetFont('Helvetica', 'B', 28);
$y = 135;
```

---

## 🧪 Tester les modifications

1. Modifiez `app/Services/CertificateGenerator.php`
2. Allez sur : http://127.0.0.1:8000/evc/app/admin/certificats/eligible
3. Cliquez sur "Voir" pour un étudiant
4. Vérifiez la position du nom
5. Ajustez si nécessaire et recommencez

---

## 💡 Astuces

- **Chaque 10 unités** = environ 1 ligne de texte
- **Position Y** :
  - 0 = Tout en haut
  - 105 (milieu) = Centre vertical
  - 210 = Tout en bas

- **Tailles de police recommandées** :
  - Nom : 28-36 pt
  - Formation : 14-18 pt
  - Date : 10-14 pt
  - Numéro : 8-12 pt

---

## 📍 Positions suggérées pour votre template

Si votre certificat dit "CE CERTIFICAT EST DÉCERNÉ À" vers le milieu :

```php
// NOM (grand, juste en dessous du texte)
$y = 125;  // Ajuster entre 120-135

// FORMATION (moyen, sous le nom)
$y = $y + 25;  // Ajuster l'espace entre 20-30

// DATE (petit, en bas)
$y = $size['height'] - 40;  // Ajuster entre 30-50

// NUMERO (très petit, tout en bas)
$y = $size['height'] - 20;  // Ajuster entre 15-25
```

---

## 🔄 Positions testées

Notez ici les positions qui fonctionnent pour votre template :

```
Nom : Y = _____  Taille = _____
Formation : Y = _____  Taille = _____
Date : Y = _____  Taille = _____
Numéro : Y = _____  Taille = _____
```
