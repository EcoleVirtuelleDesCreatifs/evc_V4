/**
 * ADMIN 3D INTERFACE INITIALIZER - REVOLUTIONARY SYSTEM
 * Script d'initialisation pour l'interface 3D révolutionnaire
 * Version: 1.0 - Revolutionary Edition
 */

class Admin3DInitializer {
    constructor() {
        this.visualizer = null;
        this.isInitialized = false;
        this.fallbackMode = false;
        this.performanceMonitor = null;
        
        this.log('Admin3DInitializer ready');
    }

    /**
     * Initialise l'interface 3D révolutionnaire
     */
    async init() {
        this.log('🚀 Starting Revolutionary 3D Interface...');
        
        try {
            // Vérifier les prérequis
            await this.checkRequirements();
            
            // Initialiser l'interface 3D
            await this.initializeVisualization();
            
            // Configurer les contrôles
            this.setupControls();
            
            // Démarrer le monitoring de performance
            this.startPerformanceMonitoring();
            
            // Masquer le loader
            this.hideLoader();
            
            this.isInitialized = true;
            this.log('✅ Revolutionary 3D Interface initialized successfully');
            
        } catch (error) {
            this.log('❌ Failed to initialize 3D interface:', error);
            await this.enableFallbackMode();
        }
    }

    /**
     * Vérifie les prérequis pour l'interface 3D
     */
    async checkRequirements() {
        const requirements = [
            { name: 'WebGL', check: () => this.checkWebGLSupport() },
            { name: 'Three.js', check: () => typeof THREE !== 'undefined' },
            { name: 'Container', check: () => document.getElementById('stats-3d-container') !== null }
        ];

        for (const req of requirements) {
            if (!req.check()) {
                throw new Error(`${req.name} not available`);
            }
            this.log(`✅ ${req.name} available`);
        }
    }

    /**
     * Vérifie le support WebGL
     */
    checkWebGLSupport() {
        try {
            const canvas = document.createElement('canvas');
            const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
            return !!gl;
        } catch (e) {
            return false;
        }
    }

    /**
     * Initialise la visualisation 3D
     */
    async initializeVisualization() {
        this.visualizer = new Admin3DVisualizer({
            enableParticles: true,
            enableHolographicCards: true,
            particleCount: 1000,
            autoRotate: true,
            debug: true
        });

        await this.visualizer.init('#stats-3d-container');
        
        // Synchroniser avec les données existantes
        this.syncWithExistingData();
    }

    /**
     * Synchronise avec les données existantes du dashboard
     */
    syncWithExistingData() {
        const dataMapping = [
            { id: 'total-students', selector: '#info-students' },
            { id: 'completion-rate', selector: '#info-completion' },
            { id: 'total-tps', selector: '#info-tps' },
            { id: 'monthly-revenue', selector: '#info-revenue' }
        ];

        dataMapping.forEach(({ id, selector }) => {
            const element = document.querySelector(selector);
            if (element) {
                const value = parseInt(element.textContent) || 0;
                this.visualizer.updateStatistic(id, value);
            }
        });
    }

    /**
     * Configure les contrôles de l'interface
     */
    setupControls() {
        // Bouton Mode 3D
        const toggle3D = document.getElementById('toggle-3d');
        if (toggle3D) {
            toggle3D.addEventListener('click', () => {
                this.toggle3DMode();
            });
        }

        // Bouton Particules
        const toggleParticles = document.getElementById('toggle-particles');
        if (toggleParticles) {
            toggleParticles.addEventListener('click', () => {
                this.toggleParticles();
            });
        }

        // Bouton Rotation Auto
        const toggleRotation = document.getElementById('toggle-rotation');
        if (toggleRotation) {
            toggleRotation.addEventListener('click', () => {
                this.toggleAutoRotation();
            });
        }

        // Bouton Plein Écran
        const fullscreen = document.getElementById('fullscreen-3d');
        if (fullscreen) {
            fullscreen.addEventListener('click', () => {
                this.toggleFullscreen();
            });
        }

        // Événements 3D
        this.setupVisualizerEvents();
    }

    /**
     * Configure les événements du visualiseur 3D
     */
    setupVisualizerEvents() {
        // Événement de survol de carte
        document.addEventListener('card:hover', (event) => {
            const { cardId } = event.detail;
            this.highlightInfoPanel(cardId);
        });

        // Événement de clic sur carte
        document.addEventListener('card:click', (event) => {
            const { cardId, cardData } = event.detail;
            this.showCardDetails(cardId, cardData);
        });

        // Événement de mise à jour de statistique
        document.addEventListener('statistic:updated-3d', (event) => {
            const { cardId, newValue } = event.detail;
            this.updateInfoPanel(cardId, newValue);
        });
    }

    /**
     * Met en surbrillance le panneau d'informations
     */
    highlightInfoPanel(cardId) {
        const mapping = {
            'total-students': '#info-students',
            'completion-rate': '#info-completion',
            'total-tps': '#info-tps',
            'monthly-revenue': '#info-revenue'
        };

        // Réinitialiser tous les éléments
        Object.values(mapping).forEach(selector => {
            const element = document.querySelector(selector);
            if (element) {
                element.classList.remove('highlighted');
            }
        });

        // Mettre en surbrillance l'élément actuel
        const selector = mapping[cardId];
        if (selector) {
            const element = document.querySelector(selector);
            if (element) {
                element.classList.add('highlighted');
            }
        }
    }

    /**
     * Affiche les détails d'une carte
     */
    showCardDetails(cardId, cardData) {
        this.log(`Card details: ${cardId}`, cardData);
        
        // Créer une notification révolutionnaire
        this.showNotification(`${cardData.title}: ${cardData.value}`, 'info');
    }

    /**
     * Met à jour le panneau d'informations
     */
    updateInfoPanel(cardId, newValue) {
        const mapping = {
            'total-students': '#info-students',
            'completion-rate': '#info-completion',
            'total-tps': '#info-tps',
            'monthly-revenue': '#info-revenue'
        };

        const selector = mapping[cardId];
        if (selector) {
            const element = document.querySelector(selector);
            if (element) {
                element.textContent = newValue;
                element.classList.add('updated');
                setTimeout(() => {
                    element.classList.remove('updated');
                }, 1000);
            }
        }
    }

    /**
     * Bascule le mode 3D
     */
    toggle3DMode() {
        const button = document.getElementById('toggle-3d');
        const container = document.getElementById('stats-3d-container');
        const fallback = document.getElementById('fallback-metrics');

        if (this.fallbackMode) {
            // Passer en mode 3D
            container.style.display = 'block';
            fallback.style.display = 'none';
            button.classList.add('active');
            this.fallbackMode = false;
            this.showNotification('Mode 3D activé', 'success');
        } else {
            // Passer en mode 2D
            container.style.display = 'none';
            fallback.style.display = 'block';
            button.classList.remove('active');
            this.fallbackMode = true;
            this.showNotification('Mode 2D activé', 'info');
        }
    }

    /**
     * Bascule les particules
     */
    toggleParticles() {
        const button = document.getElementById('toggle-particles');
        
        if (this.visualizer) {
            this.visualizer.config.enableParticles = !this.visualizer.config.enableParticles;
            button.classList.toggle('active');
            
            const status = this.visualizer.config.enableParticles ? 'activées' : 'désactivées';
            this.showNotification(`Particules ${status}`, 'info');
        }
    }

    /**
     * Bascule la rotation automatique
     */
    toggleAutoRotation() {
        const button = document.getElementById('toggle-rotation');
        
        if (this.visualizer && this.visualizer.controls) {
            this.visualizer.controls.autoRotate = !this.visualizer.controls.autoRotate;
            button.classList.toggle('active');
            
            const status = this.visualizer.controls.autoRotate ? 'activée' : 'désactivée';
            this.showNotification(`Rotation automatique ${status}`, 'info');
        }
    }

    /**
     * Bascule le mode plein écran
     */
    toggleFullscreen() {
        const container = document.getElementById('stats-3d-container');
        const button = document.getElementById('fullscreen-3d');

        if (container.classList.contains('fullscreen')) {
            container.classList.remove('fullscreen');
            button.querySelector('i').className = 'fas fa-expand';
            this.showNotification('Mode fenêtré activé', 'info');
        } else {
            container.classList.add('fullscreen');
            button.querySelector('i').className = 'fas fa-compress';
            this.showNotification('Mode plein écran activé', 'success');
        }

        // Redimensionner le renderer
        if (this.visualizer) {
            setTimeout(() => {
                this.visualizer.onWindowResize();
            }, 300);
        }
    }

    /**
     * Démarre le monitoring de performance
     */
    startPerformanceMonitoring() {
        this.performanceMonitor = setInterval(() => {
            this.updatePerformanceIndicators();
        }, 1000);
    }

    /**
     * Met à jour les indicateurs de performance
     */
    updatePerformanceIndicators() {
        // FPS (simulé)
        const fpsElement = document.getElementById('perf-fps');
        if (fpsElement) {
            const fps = Math.floor(Math.random() * 10) + 55; // 55-65 FPS
            fpsElement.textContent = fps;
            
            // Couleur basée sur les performances
            if (fps >= 60) {
                fpsElement.className = 'performance-value';
            } else if (fps >= 30) {
                fpsElement.className = 'performance-value warning';
            } else {
                fpsElement.className = 'performance-value negative';
            }
        }

        // Objets 3D
        const objectsElement = document.getElementById('perf-objects');
        if (objectsElement && this.visualizer) {
            const objectCount = this.visualizer.scene ? this.visualizer.scene.children.length : 4;
            objectsElement.textContent = objectCount;
        }

        // Particules
        const particlesElement = document.getElementById('perf-particles');
        if (particlesElement) {
            const particleCount = this.visualizer && this.visualizer.config.enableParticles ? '1K' : '0';
            particlesElement.textContent = particleCount;
        }
    }

    /**
     * Active le mode de fallback 2D
     */
    async enableFallbackMode() {
        this.log('🔄 Enabling fallback mode...');
        
        const container = document.getElementById('stats-3d-container');
        const fallback = document.getElementById('fallback-metrics');
        const loader = document.getElementById('stats-3d-loader');

        // Masquer le container 3D et le loader
        if (container) container.style.display = 'none';
        if (loader) loader.style.display = 'none';
        
        // Afficher le fallback 2D
        if (fallback) fallback.style.display = 'block';
        
        this.fallbackMode = true;
        this.showNotification('Interface 2D de fallback activée', 'warning');
        
        // Initialiser les statistiques 2D classiques
        if (window.adminStats) {
            window.adminStats.init();
        }
    }

    /**
     * Masque le loader
     */
    hideLoader() {
        const loader = document.getElementById('stats-3d-loader');
        if (loader) {
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.style.display = 'none';
            }, 500);
        }
    }

    /**
     * Affiche une notification révolutionnaire
     */
    showNotification(message, type = 'info') {
        // Créer la notification
        const notification = document.createElement('div');
        notification.className = `notification-3d ${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${this.getNotificationIcon(type)}"></i>
                <span>${message}</span>
            </div>
        `;

        // Ajouter au DOM
        document.body.appendChild(notification);

        // Animation d'entrée
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);

        // Suppression automatique
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    /**
     * Retourne l'icône appropriée pour le type de notification
     */
    getNotificationIcon(type) {
        const icons = {
            success: 'check-circle',
            warning: 'exclamation-triangle',
            error: 'times-circle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    }

    /**
     * Logging avec préfixe
     */
    log(message, ...args) {
        console.log(`[3D Init] ${message}`, ...args);
    }

    /**
     * Détruit l'initializer
     */
    destroy() {
        if (this.performanceMonitor) {
            clearInterval(this.performanceMonitor);
        }
        
        if (this.visualizer) {
            this.visualizer.destroy();
        }
        
        this.isInitialized = false;
        this.log('3D Initializer destroyed');
    }
}

// Styles pour les notifications révolutionnaires
const notificationStyles = `
    .notification-3d {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        background: rgba(10, 10, 10, 0.9);
        border: 1px solid var(--neon-blue);
        border-radius: 10px;
        padding: 15px 20px;
        color: white;
        font-size: 14px;
        font-weight: 500;
        backdrop-filter: blur(15px);
        box-shadow: 0 0 20px rgba(0, 212, 255, 0.3);
        transform: translateX(100%);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .notification-3d.show {
        transform: translateX(0);
        opacity: 1;
    }
    
    .notification-3d.success {
        border-color: var(--neon-green);
        box-shadow: 0 0 20px rgba(0, 255, 136, 0.3);
    }
    
    .notification-3d.warning {
        border-color: var(--neon-orange);
        box-shadow: 0 0 20px rgba(255, 107, 53, 0.3);
    }
    
    .notification-3d.error {
        border-color: #ff4757;
        box-shadow: 0 0 20px rgba(255, 71, 87, 0.3);
    }
    
    .notification-content {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .notification-content i {
        font-size: 16px;
    }
    
    .info-value.highlighted {
        color: var(--neon-blue) !important;
        text-shadow: 0 0 10px var(--neon-blue) !important;
        animation: pulse 1s ease-in-out infinite;
    }
    
    .info-value.updated {
        animation: updatePulse 1s ease-out;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    @keyframes updatePulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
`;

// Injecter les styles
const styleSheet = document.createElement('style');
styleSheet.textContent = notificationStyles;
document.head.appendChild(styleSheet);

// Instance globale
window.Admin3DInitializer = Admin3DInitializer;

// Auto-initialisation
document.addEventListener('DOMContentLoaded', async function() {
    if (document.getElementById('stats-3d-container')) {
        window.admin3DInit = new Admin3DInitializer();
        
        // Délai pour laisser le temps aux autres scripts de se charger
        setTimeout(async () => {
            try {
                await window.admin3DInit.init();
            } catch (error) {
                console.error('Failed to initialize 3D interface:', error);
            }
        }, 1000);
    }
});
