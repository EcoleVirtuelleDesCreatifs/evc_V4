#!/bin/bash

echo "🛒 Activation de Chariow..."
echo ""

# Vérifier si .env existe
if [ ! -f .env ]; then
    echo "❌ Fichier .env introuvable"
    exit 1
fi

# Vérifier si CHARIOW_ENABLED existe déjà
if grep -q "CHARIOW_ENABLED" .env; then
    echo "✅ CHARIOW_ENABLED existe déjà dans .env"
    echo ""
    echo "Valeur actuelle :"
    grep "CHARIOW_ENABLED" .env
    echo ""
    read -p "Voulez-vous la modifier en 'true' ? (o/n) " -n 1 -r
    echo ""
    if [[ $REPLY =~ ^[Oo]$ ]]; then
        # Remplacer la valeur existante
        if [[ "$OSTYPE" == "darwin"* ]]; then
            # macOS
            sed -i '' 's/CHARIOW_ENABLED=.*/CHARIOW_ENABLED=true/' .env
        else
            # Linux
            sed -i 's/CHARIOW_ENABLED=.*/CHARIOW_ENABLED=true/' .env
        fi
        echo "✅ CHARIOW_ENABLED modifié à 'true'"
    fi
else
    echo "⚠️  CHARIOW_ENABLED n'existe pas dans .env"
    echo "Ajout de CHARIOW_ENABLED=true..."
    echo "" >> .env
    echo "# Chariow - Paiement" >> .env
    echo "CHARIOW_ENABLED=true" >> .env
    echo "✅ CHARIOW_ENABLED ajouté au fichier .env"
fi

echo ""
echo "🧹 Vidage des caches..."
php artisan config:clear > /dev/null 2>&1
php artisan cache:clear > /dev/null 2>&1
echo "✅ Caches vidés"

echo ""
echo "🔍 Vérification de la configuration..."
php artisan tinker --execute="
echo 'Chariow activé : ' . (config('chariow.enabled') ? '✅ OUI' : '❌ NON') . PHP_EOL;
echo 'Lien Design Graphique : ' . config('chariow.payment_links.design_graphique.tranche_1', 'NON CONFIGURÉ') . PHP_EOL;
"

echo ""
echo "============================================"
echo "✅ Chariow est maintenant activé !"
echo "============================================"
echo ""
echo "🧪 Pour tester, ouvrez ce lien :"
echo "http://127.0.0.1:8000/evc/payment/EVC-PAY-20251210-D759645B"
echo ""
echo "Puis cliquez sur 'Procéder au paiement'"
echo "Vous serez redirigé vers :"
echo "https://ecolevirtuelle.mychariow.shop/prd_ngqtqy/checkout"
echo ""
