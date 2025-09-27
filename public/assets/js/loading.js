/**
 * Script pour la page de chargement EVC
 * Gère l'animation de progression et la redirection
 */

document.addEventListener('DOMContentLoaded', function() {
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const loadingText = document.getElementById('loadingText');
    
    // Étapes de chargement avec messages personnalisés (90 secondes au total - 1min30s)
    const steps = [
        { text: "Vérification des identifiants...", duration: 15000, icon: "fas fa-shield-alt" },
        { text: "Chargement de votre profil...", duration: 15000, icon: "fas fa-user-circle" },
        { text: "Préparation de votre espace...", duration: 15000, icon: "fas fa-cogs" },
        { text: "Chargement des statistiques...", duration: 15000, icon: "fas fa-chart-bar" },
        { text: "Synchronisation des données...", duration: 15000, icon: "fas fa-sync-alt" },
        { text: "Finalisation...", duration: 15000, icon: "fas fa-check-circle" }
    ];

    let currentStep = 0;
    let currentProgress = 0;
    let animationSpeed = 2; // Vitesse de l'animation (pixels par frame)

    // Messages de motivation aléatoires
    const motivationalMessages = [
        "Votre parcours créatif vous attend !",
        "Prêt à développer vos compétences ?",
        "L'excellence commence maintenant !",
        "Votre futur professionnel se dessine...",
        "Créativité et innovation au rendez-vous !"
    ];

    // Sélectionner un message de motivation aléatoire
    const randomMessage = motivationalMessages[Math.floor(Math.random() * motivationalMessages.length)];
    
    // Ajouter le message de motivation après 2 secondes
    setTimeout(() => {
        const motivationElement = document.createElement('div');
        motivationElement.className = 'motivation-message';
        motivationElement.innerHTML = `<i class="fas fa-star"></i> ${randomMessage}`;
        motivationElement.style.cssText = `
            margin-top: 1rem;
            font-size: 0.9rem;
            opacity: 0;
            color: #FFD700;
            font-weight: 500;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
            animation: fadeInUp 0.8s ease-out forwards;
        `;
        document.querySelector('.user-info').appendChild(motivationElement);
    }, 2000);

    /**
     * Met à jour l'icône du spinner selon l'étape
     */
    function updateSpinnerIcon(iconClass) {
        const logoIcon = document.querySelector('.logo i');
        if (logoIcon) {
            logoIcon.className = iconClass;
            logoIcon.style.animation = 'pulse 0.5s ease-in-out';
            setTimeout(() => {
                logoIcon.style.animation = '';
            }, 500);
        }
    }

    /**
     * Ajoute des effets visuels pendant le chargement
     */
    function addVisualEffects() {
        // Effet de particules supplémentaires
        const container = document.querySelector('.loading-container');
        for (let i = 0; i < 3; i++) {
            setTimeout(() => {
                const sparkle = document.createElement('div');
                sparkle.innerHTML = '✨';
                sparkle.style.cssText = `
                    position: absolute;
                    font-size: 1.5rem;
                    pointer-events: none;
                    animation: sparkle 2s ease-out forwards;
                    left: ${Math.random() * 100}%;
                    top: ${Math.random() * 100}%;
                `;
                container.appendChild(sparkle);
                
                setTimeout(() => sparkle.remove(), 2000);
            }, i * 800);
        }
    }

    /**
     * Fonction principale de mise à jour du progress
     */
    function updateProgress() {
        if (currentStep < steps.length) {
            const step = steps[currentStep];
            
            // Mettre à jour le texte et l'icône
            loadingText.textContent = step.text;
            updateSpinnerIcon(step.icon);
            
            const targetProgress = ((currentStep + 1) / steps.length) * 100;
            
            // Animation fluide de la barre de progression
            const progressInterval = setInterval(() => {
                currentProgress += animationSpeed;
                if (currentProgress >= targetProgress) {
                    currentProgress = targetProgress;
                    clearInterval(progressInterval);
                    
                    // Passer à l'étape suivante
                    currentStep++;
                    if (currentStep < steps.length) {
                        setTimeout(updateProgress, 200);
                    } else {
                        // Finaliser le chargement
                        finishLoading();
                    }
                }
                
                // Mettre à jour l'affichage
                progressBar.style.width = currentProgress + '%';
                progressText.textContent = Math.round(currentProgress) + '%';
                
                // Effet de couleur progressive
                updateProgressColor(currentProgress);
            }, 50);
            
            // Ajouter des effets visuels pendant certaines étapes
            if (currentStep === 2 || currentStep === 4) {
                addVisualEffects();
            }
            
            // Nettoyer l'intervalle après la durée de l'étape
            setTimeout(() => {
                clearInterval(progressInterval);
            }, step.duration);
        }
    }

    /**
     * Met à jour la couleur de la barre de progression
     */
    function updateProgressColor(progress) {
        let color;
        if (progress < 30) {
            color = 'linear-gradient(90deg, #ff6633, #FF9900)';
        } else if (progress < 70) {
            color = 'linear-gradient(90deg, #FF9900, #3399ff)';
        } else {
            color = 'linear-gradient(90deg, #3399ff, #00ff88)';
        }
        progressBar.style.background = color;
    }

    /**
     * Finalise le chargement et effectue la redirection
     */
    function finishLoading() {
        setTimeout(() => {
            loadingText.textContent = "Redirection vers votre espace...";
            updateSpinnerIcon("fas fa-rocket");
            
            // Effet de succès
            const successMessage = document.createElement('div');
            successMessage.innerHTML = '<i class="fas fa-check-circle"></i> Connexion réussie !';
            successMessage.style.cssText = `
                margin-top: 1rem;
                color: #00ff88;
                font-weight: 600;
                font-size: 1.1rem;
                opacity: 0;
                animation: fadeInUp 0.5s ease-out forwards;
                text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            `;
            document.querySelector('.loading-spinner').appendChild(successMessage);
            
            // Effet de fondu avant redirection
            setTimeout(() => {
                document.body.style.transition = 'all 0.8s ease-out';
                document.body.style.opacity = '0';
                document.body.style.transform = 'scale(1.05)';
                
                // Redirection après l'effet de fondu
                setTimeout(() => {
                    // Récupérer l'URL de redirection depuis la session
                    const redirectUrl = document.querySelector('meta[name="redirect-url"]')?.content;
                    if (redirectUrl) {
                        window.location.href = redirectUrl;
                    } else {
                        // Fallback vers l'espace design graphique
                        window.location.href = '/evc/compte/design-graphique/espace-etudiant';
                    }
                }, 800);
            }, 1000);
        }, 300);
    }

    /**
     * Gestion des erreurs de connexion
     */
    function handleConnectionError() {
        loadingText.textContent = "Erreur de connexion. Nouvelle tentative...";
        updateSpinnerIcon("fas fa-exclamation-triangle");
        
        setTimeout(() => {
            // Relancer le processus de chargement
            currentStep = 0;
            currentProgress = 0;
            updateProgress();
        }, 2000);
    }

    /**
     * Vérification de la connectivité
     */
    function checkConnection() {
        if (!navigator.onLine) {
            handleConnectionError();
            return false;
        }
        return true;
    }

    // Démarrer l'animation de progression après un délai initial
    setTimeout(() => {
        if (checkConnection()) {
            updateProgress();
        }
    }, 1000);

    // Écouter les changements de connectivité
    window.addEventListener('online', () => {
        if (currentStep === 0) {
            updateProgress();
        }
    });

    window.addEventListener('offline', handleConnectionError);

    // Ajouter les styles CSS pour les animations supplémentaires
    const additionalStyles = document.createElement('style');
    additionalStyles.textContent = `
        @keyframes sparkle {
            0% { opacity: 0; transform: scale(0) rotate(0deg); }
            50% { opacity: 1; transform: scale(1) rotate(180deg); }
            100% { opacity: 0; transform: scale(0) rotate(360deg); }
        }
        
        .motivation-message {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;
    document.head.appendChild(additionalStyles);
});

/**
 * Fonction utilitaire pour déboguer (peut être supprimée en production)
 */
function debugLoading() {
    console.log('🚀 Page de chargement EVC initialisée');
    console.log('📱 Appareil:', navigator.userAgent);
    console.log('🌐 Connexion:', navigator.onLine ? 'En ligne' : 'Hors ligne');
}
