#!/bin/bash

# Script de mise à jour des coordonnées EVC
# Date: 12 Décembre 2024

echo "🔄 Mise à jour des coordonnées EVC..."

# Répertoires à traiter
VIEWS_DIR="resources/views"

# Anciennes coordonnées à remplacer par nouvelles
declare -A REPLACEMENTS=(
    # Emails
    ["contact@ecolevirtuelle.ci"]="info@ecolevirtuelledescreatifs.com"
    ["contact@evc.ci"]="info@ecolevirtuelledescreatifs.com"

    # Sites web
    ["www.ecolevirtuelle.ci"]="www.ecolevirtuelledescreatifs.com"
    ["https://ecolevirtuelle.ci"]="https://www.ecolevirtuelledescreatifs.com"
    ["www.evc.ci"]="www.ecolevirtuelledescreatifs.com"
    ["https://evc.ci"]="https://www.ecolevirtuelledescreatifs.com"

    # Téléphones (variantes à standardiser)
    ["+225 XX XX XX XX XX"]="+225 07 17 25 86 02"
    ["Tél: +225 XX XX XX XX XX"]="📞 (+225) 07 17 25 86 02"

    # Adresses (variantes)
    ["Abidjan, Côte d'Ivoire"]="Abidjan, Palmeraie"
    ["Cocody Riviera Palmeraie, Abidjan, Côte d'Ivoire"]="Abidjan, Palmeraie"
)

# Fonction de remplacement
replace_in_files() {
    local old="$1"
    local new="$2"

    echo "  Remplacement: '$old' → '$new'"

    # Rechercher et remplacer dans tous les fichiers .blade.php
    find "$VIEWS_DIR" -type f -name "*.blade.php" -exec sed -i '' "s|$old|$new|g" {} +
}

# Exécuter les remplacements
for old in "${!REPLACEMENTS[@]}"; do
    new="${REPLACEMENTS[$old]}"
    replace_in_files "$old" "$new"
done

echo ""
echo "✅ Mise à jour terminée!"
echo ""
echo "📋 Nouvelles coordonnées standardisées:"
echo "   📞 (+225) 07 17 25 86 02"
echo "   📍 Abidjan, Palmeraie"
echo "   🌐 www.ecolevirtuelledescreatifs.com"
echo "   📧 info@ecolevirtuelledescreatifs.com"
echo "   📧 contact@ecolevirtuelledescreatifs.com"
echo "   📱 WhatsApp: +225 07 47 25 95 07"
echo ""
