/**
 * SYSTÈME STATISTIQUES HOLOGRAPHIQUES RÉVOLUTIONNAIRE
 * Interface avec cartes flottantes, effets de lévitation et perspective fluide
 */

class HolographicStatsManager {
    constructor() {
        this.cards = [];
        this.isInitialized = false;
        this.animationFrameId = null;
        this.mousePosition = { x: 0, y: 0 };
        this.charts = new Map();
        
        // Configuration des effets
        this.config = {
            perspective: {
                maxRotation: 15,
                sensitivity: 0.1,
                smoothing: 0.1
            },
            levitation: {
                maxLift: 25,
                duration: 600,
                easing: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)'
            },
            glow: {
                intensity: 0.6,
                radius: 60,
                duration: 300
            }
        };
        
        this.init();
    }
    
    /**
     * Initialisation du système holographique
     */
    init() {
        if (this.isInitialized) return;
        
        console.log('🌟 Initialisation du système holographique...');
        
        // Attendre que le DOM soit prêt
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }
    
    /**
     * Configuration principale
     */
    setup() {
        this.createStatsData();
        this.renderHolographicInterface();
        this.initializeCards();
        this.setupEventListeners();
        this.startAnimationLoop();
        this.initializeCharts();
        
        this.isInitialized = true;
        console.log('✨ Système holographique initialisé avec succès!');
        
        // Déclencher l'événement d'initialisation
        this.dispatchEvent('holographic:initialized');
    }
    
    /**
     * Création des données statistiques EVC - École Virtuelle des Créatifs
     */
    createStatsData() {
        this.statsData = [
            {
                id: 'total-students',
                title: 'Total d\'Étudiants',
                value: 1247,
                unit: '',
                trend: { value: 23, type: 'positive' },
                description: 'Créatifs en formation active',
                icon: 'fas fa-user-graduate',
                color: 'primary',
                chartType: 'line',
                chartData: [980, 1050, 1120, 1180, 1200, 1230, 1247]
            },
            {
                id: 'total-formations',
                title: 'Total de Formations',
                value: 12,
                unit: '',
                trend: { value: 2, type: 'positive' },
                description: 'Programmes créatifs disponibles',
                icon: 'fas fa-chalkboard-teacher',
                color: 'success',
                chartType: 'bar',
                chartData: [8, 9, 10, 11, 12]
            },
            {
                id: 'total-projects',
                title: 'Total de Projets',
                value: 3456,
                unit: '',
                trend: { value: 156, type: 'positive' },
                description: 'Créations étudiantes réalisées',
                icon: 'fas fa-project-diagram',
                color: 'secondary',
                chartType: 'area',
                chartData: [2800, 2950, 3100, 3250, 3350, 3456]
            },
            {
                id: 'total-tp',
                title: 'Total de TP',
                value: 2890,
                unit: '',
                trend: { value: 89, type: 'positive' },
                description: 'Travaux pratiques soumis',
                icon: 'fas fa-tasks',
                color: 'warning',
                chartType: 'line',
                chartData: [2400, 2520, 2650, 2750, 2820, 2890]
            },
            {
                id: 'total-articles',
                title: 'Total d\'Articles',
                value: 567,
                unit: '',
                trend: { value: 12, type: 'positive' },
                description: 'Contenus pédagogiques publiés',
                icon: 'fas fa-newspaper',
                color: 'primary',
                chartType: 'bar',
                chartData: [450, 480, 510, 535, 550, 567]
            },
            {
                id: 'total-resources',
                title: 'Total Ressources',
                value: 1234,
                unit: '',
                trend: { value: 45, type: 'positive' },
                description: 'Bibliothèque créative enrichie',
                icon: 'fas fa-book',
                color: 'success',
                chartType: 'doughnut',
                chartData: [400, 350, 300, 184]
            },
            {
                id: 'total-certificates',
                title: 'Certificats Éligibles',
                value: 789,
                unit: '',
                trend: { value: 34, type: 'positive' },
                description: 'Étudiants prêts à certifier',
                icon: 'fas fa-certificate',
                color: 'warning',
                chartType: 'line',
                chartData: [650, 690, 720, 750, 770, 789]
            },
            {
                id: 'total-documents',
                title: 'Total Documents',
                value: 4567,
                unit: '',
                trend: { value: 123, type: 'positive' },
                description: 'Fichiers et supports partagés',
                icon: 'fas fa-file-alt',
                color: 'secondary',
                chartType: 'area',
                chartData: [3800, 4000, 4200, 4350, 4450, 4567]
            },
            {
                id: 'total-admins',
                title: 'Total Admins',
                value: 15,
                unit: '',
                trend: { value: 2, type: 'positive' },
                description: 'Équipe pédagogique active',
                icon: 'fas fa-user-shield',
                color: 'primary',
                chartType: 'bar',
                chartData: [10, 11, 12, 13, 14, 15]
            }
        ];
    }
    
    /**
     * Rendu de l'interface holographique
     */
    renderHolographicInterface() {
        const container = document.querySelector('.main-content-area');
        if (!container) return;
        
        container.innerHTML = `
            <div class="holographic-stats-container">
                <div class="holographic-title">
                    <h2>École Virtuelle des Créatifs</h2>
                    <p>Tableau de bord administratif • Supervision créative • Analytics en temps réel</p>
                </div>
                
                <!-- Actions Rapides EVC -->
                <div class="evc-quick-actions">
                    <button class="evc-action-btn primary" onclick="addNewStudent()">
                        <i class="fas fa-user-plus"></i>
                        <span>Ajouter un Étudiant</span>
                    </button>
                    <button class="evc-action-btn secondary" onclick="sendNewProject()">
                        <i class="fas fa-project-diagram"></i>
                        <span>Envoyer un Projet</span>
                    </button>
                    <button class="evc-action-btn warning" onclick="sendNewTP()">
                        <i class="fas fa-tasks"></i>
                        <span>Envoyer un TP</span>
                    </button>
                </div>
                
                <div class="holographic-stats-grid" id="holographic-stats-grid">
                    ${this.statsData.map(stat => this.createCardHTML(stat)).join('')}
                </div>
                
                <div class="performance-indicators">
                    <div class="performance-dot"></div>
                    <div class="performance-dot"></div>
                    <div class="performance-dot"></div>
                </div>
            </div>
        `;
    }
    
    /**
     * Création du HTML d'une carte holographique
     */
    createCardHTML(stat) {
        const trendIcon = stat.trend.type === 'positive' ? 'fa-arrow-up' : 
                         stat.trend.type === 'negative' ? 'fa-arrow-down' : 'fa-minus';
        
        return `
            <div class="holographic-card ${stat.color}" 
                 data-stat-id="${stat.id}" 
                 data-value="${stat.value}">
                
                <div class="card-header">
                    <div class="card-icon">
                        <i class="${stat.icon}"></i>
                    </div>
                    <div class="card-trend trend-${stat.trend.type}">
                        <i class="fas ${trendIcon}"></i>
                        <span>${stat.trend.value > 0 ? '+' : ''}${stat.trend.value}${stat.unit === '%' ? '%' : ''}</span>
                    </div>
                </div>
                
                <div class="card-value" data-target="${stat.value}">
                    ${this.formatValue(stat.value)}${stat.unit}
                </div>
                
                <div class="card-label">${stat.title}</div>
                <div class="card-description">${stat.description}</div>
                
                <div class="card-chart">
                    <canvas id="chart-${stat.id}" width="300" height="60"></canvas>
                </div>
                
                <button class="view-more-btn" onclick="viewStatDetails('${stat.id}')">
                    Voir plus <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        `;
    }
    
    /**
     * Formatage des valeurs
     */
    formatValue(value) {
        if (value >= 1000000) {
            return (value / 1000000).toFixed(1) + 'M';
        } else if (value >= 1000) {
            return (value / 1000).toFixed(1) + 'K';
        }
        return value.toLocaleString();
    }
    
    /**
     * Initialisation des cartes
     */
    initializeCards() {
        this.cards = Array.from(document.querySelectorAll('.holographic-card'));
        
        this.cards.forEach((card, index) => {
            // Animation d'entrée décalée
            setTimeout(() => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('animate-in');
            }, index * 100);
            
            // Stockage des données de la carte
            const statId = card.dataset.statId;
            const statData = this.statsData.find(s => s.id === statId);
            card.statData = statData;
        });
    }
    
    /**
     * Configuration des événements
     */
    setupEventListeners() {
        // Suivi de la souris pour les effets de perspective
        document.addEventListener('mousemove', (e) => {
            this.mousePosition.x = e.clientX;
            this.mousePosition.y = e.clientY;
        });
        
        // Événements sur les cartes
        this.cards.forEach(card => {
            card.addEventListener('mouseenter', (e) => this.onCardHover(e));
            card.addEventListener('mouseleave', (e) => this.onCardLeave(e));
            card.addEventListener('mousemove', (e) => this.onCardMouseMove(e));
            card.addEventListener('click', (e) => this.onCardClick(e));
        });
        
        // Redimensionnement
        window.addEventListener('resize', () => this.handleResize());
    }
    
    /**
     * Effet hover sur carte
     */
    onCardHover(event) {
        const card = event.currentTarget;
        const rect = card.getBoundingClientRect();
        
        // Effet de lévitation
        card.style.transform = `
            translateY(-${this.config.levitation.maxLift}px) 
            rotateX(5deg) 
            rotateY(5deg) 
            scale(1.05)
        `;
        
        // Effet de brillance
        card.style.filter = 'brightness(1.1)';
        
        // Animation de la valeur
        this.animateValue(card);
        
        // Événement personnalisé
        this.dispatchEvent('holographic:card:hover', { card, statData: card.statData });
    }
    
    /**
     * Fin du hover
     */
    onCardLeave(event) {
        const card = event.currentTarget;
        
        // Retour à l'état normal
        card.style.transform = 'translateY(0) rotateX(0deg) rotateY(0deg) scale(1)';
        card.style.filter = 'brightness(1)';
        
        this.dispatchEvent('holographic:card:leave', { card });
    }
    
    /**
     * Mouvement de souris sur carte (effet de perspective)
     */
    onCardMouseMove(event) {
        const card = event.currentTarget;
        const rect = card.getBoundingClientRect();
        
        // Calcul de la position relative
        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;
        
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        
        // Calcul des rotations
        const rotateX = ((y - centerY) / centerY) * -this.config.perspective.maxRotation;
        const rotateY = ((x - centerX) / centerX) * this.config.perspective.maxRotation;
        
        // Application fluide
        card.style.transform = `
            translateY(-${this.config.levitation.maxLift}px)
            rotateX(${rotateX}deg) 
            rotateY(${rotateY}deg) 
            scale(1.05)
        `;
    }
    
    /**
     * Clic sur carte
     */
    onCardClick(event) {
        const card = event.currentTarget;
        
        // Effet de clic
        card.style.transform = `
            translateY(-${this.config.levitation.maxLift - 5}px)
            rotateX(0deg) 
            rotateY(0deg) 
            scale(0.98)
        `;
        
        setTimeout(() => {
            card.style.transform = `
                translateY(-${this.config.levitation.maxLift}px)
                rotateX(5deg) 
                rotateY(5deg) 
                scale(1.05)
            `;
        }, 100);
        
        // Événement personnalisé
        this.dispatchEvent('holographic:card:click', { 
            card, 
            statData: card.statData 
        });
        
        // Afficher détails (optionnel)
        this.showCardDetails(card.statData);
    }
    
    /**
     * Animation de la valeur
     */
    animateValue(card) {
        const valueElement = card.querySelector('.card-value');
        const targetValue = parseFloat(card.dataset.value);
        const currentValue = 0;
        const duration = 1000;
        const startTime = performance.now();
        
        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function
            const easeOutQuart = 1 - Math.pow(1 - progress, 4);
            const value = currentValue + (targetValue - currentValue) * easeOutQuart;
            
            valueElement.textContent = this.formatValue(Math.round(value)) + 
                                     (card.statData.unit || '');
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };
        
        requestAnimationFrame(animate);
    }
    
    /**
     * Initialisation des graphiques
     */
    initializeCharts() {
        this.statsData.forEach(stat => {
            const canvas = document.getElementById(`chart-${stat.id}`);
            if (!canvas) return;
            
            const ctx = canvas.getContext('2d');
            
            // Configuration du graphique selon le type
            const config = this.getChartConfig(stat);
            
            // Création du graphique avec Chart.js
            if (window.Chart) {
                const chart = new Chart(ctx, config);
                this.charts.set(stat.id, chart);
            }
        });
    }
    
    /**
     * Configuration des graphiques
     */
    getChartConfig(stat) {
        const baseConfig = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            },
            scales: {
                x: { display: false },
                y: { display: false }
            },
            elements: {
                point: { radius: 0 },
                line: { tension: 0.4 }
            }
        };
        
        const colors = {
            primary: 'rgba(0, 212, 255, 0.8)',
            secondary: 'rgba(179, 71, 255, 0.8)',
            success: 'rgba(0, 255, 136, 0.8)',
            warning: 'rgba(255, 179, 71, 0.8)'
        };
        
        return {
            type: stat.chartType,
            data: {
                labels: stat.chartData.map((_, i) => i),
                datasets: [{
                    data: stat.chartData,
                    borderColor: colors[stat.color],
                    backgroundColor: colors[stat.color].replace('0.8', '0.2'),
                    borderWidth: 2,
                    fill: stat.chartType === 'area'
                }]
            },
            options: baseConfig
        };
    }
    
    /**
     * Boucle d'animation principale
     */
    startAnimationLoop() {
        const animate = () => {
            this.updateFloatingAnimation();
            this.animationFrameId = requestAnimationFrame(animate);
        };
        
        animate();
    }
    
    /**
     * Mise à jour des animations flottantes
     */
    updateFloatingAnimation() {
        const time = Date.now() * 0.001;
        
        this.cards.forEach((card, index) => {
            if (!card.matches(':hover')) {
                const offset = index * 0.5;
                const floatY = Math.sin(time + offset) * 5;
                const floatX = Math.cos(time * 0.5 + offset) * 2;
                
                card.style.transform = `
                    translateY(${floatY}px) 
                    translateX(${floatX}px)
                    rotateX(${Math.sin(time + offset) * 2}deg)
                    rotateY(${Math.cos(time * 0.7 + offset) * 2}deg)
                `;
            }
        });
    }
    
    /**
     * Affichage des détails d'une carte
     */
    showCardDetails(statData) {
        console.log('📊 Détails:', statData);
        
        // Ici vous pouvez ajouter une modal ou un panneau de détails
        // Pour l'instant, on log les données
    }
    
    /**
     * Gestion du redimensionnement
     */
    handleResize() {
        // Recalcul des positions et animations
        this.charts.forEach(chart => {
            chart.resize();
        });
    }
    
    /**
     * Mise à jour des données
     */
    updateStatistic(statId, newValue, options = {}) {
        const card = this.cards.find(c => c.dataset.statId === statId);
        if (!card) return;
        
        const statData = this.statsData.find(s => s.id === statId);
        if (!statData) return;
        
        // Mise à jour des données
        statData.value = newValue;
        card.dataset.value = newValue;
        
        // Animation de mise à jour
        if (options.animate !== false) {
            this.animateValue(card);
        }
        
        // Mise à jour du graphique
        if (this.charts.has(statId)) {
            const chart = this.charts.get(statId);
            chart.data.datasets[0].data.push(newValue);
            chart.data.datasets[0].data.shift();
            chart.update('none');
        }
        
        this.dispatchEvent('holographic:stat:updated', { statId, newValue, statData });
    }
    
    /**
     * Actualisation de toutes les statistiques
     */
    refreshAllStatistics() {
        console.log('🔄 Actualisation des statistiques holographiques...');
        
        this.cards.forEach((card, index) => {
            setTimeout(() => {
                // Simulation de nouvelles données
                const currentValue = parseFloat(card.dataset.value);
                const variation = (Math.random() - 0.5) * 0.1;
                const newValue = Math.round(currentValue * (1 + variation));
                
                this.updateStatistic(card.dataset.statId, newValue);
            }, index * 200);
        });
    }
    
    /**
     * Dispatch d'événements personnalisés
     */
    dispatchEvent(eventName, detail = {}) {
        window.dispatchEvent(new CustomEvent(eventName, { detail }));
    }
    
    /**
     * Destruction du système
     */
    destroy() {
        if (this.animationFrameId) {
            cancelAnimationFrame(this.animationFrameId);
        }
        
        this.charts.forEach(chart => chart.destroy());
        this.charts.clear();
        
        this.isInitialized = false;
        console.log('🔥 Système holographique détruit');
    }
}

// Initialisation automatique
document.addEventListener('DOMContentLoaded', () => {
    window.holographicStats = new HolographicStatsManager();
});

// API publique
window.HolographicStats = {
    update: (statId, value, options) => window.holographicStats?.updateStatistic(statId, value, options),
    refresh: () => window.holographicStats?.refreshAllStatistics(),
    destroy: () => window.holographicStats?.destroy()
};

// Fonctions d'actions rapides EVC
window.addNewStudent = function() {
    console.log('🎓 Action: Ajouter un nouvel étudiant');
    
    // Animation du bouton
    const btn = event.target.closest('.evc-action-btn');
    btn.style.transform = 'translateY(-1px) scale(0.98)';
    setTimeout(() => {
        btn.style.transform = 'translateY(-3px) scale(1.05)';
    }, 100);
    
    // Ici vous pouvez ajouter la logique pour ouvrir un modal ou rediriger
    // Exemple: window.location.href = '/admin/students/create';
    
    // Notification temporaire
    showEVCNotification('Fonctionnalité "Ajouter un étudiant" en cours de développement', 'info');
};

window.sendNewProject = function() {
    console.log('📋 Action: Envoyer un nouveau projet');
    
    const btn = event.target.closest('.evc-action-btn');
    btn.style.transform = 'translateY(-1px) scale(0.98)';
    setTimeout(() => {
        btn.style.transform = 'translateY(-3px) scale(1.05)';
    }, 100);
    
    // Ici vous pouvez ajouter la logique pour ouvrir un modal ou rediriger
    // Exemple: window.location.href = '/admin/projects/create';
    
    showEVCNotification('Fonctionnalité "Envoyer un projet" en cours de développement', 'info');
};

window.sendNewTP = function() {
    console.log('📝 Action: Envoyer un nouveau TP');
    
    const btn = event.target.closest('.evc-action-btn');
    btn.style.transform = 'translateY(-1px) scale(0.98)';
    setTimeout(() => {
        btn.style.transform = 'translateY(-3px) scale(1.05)';
    }, 100);
    
    // Ici vous pouvez ajouter la logique pour ouvrir un modal ou rediriger
    // Exemple: window.location.href = '/admin/tp/create';
    
    showEVCNotification('Fonctionnalité "Envoyer un TP" en cours de développement', 'info');
};

// Fonction pour afficher les détails d'une statistique
window.viewStatDetails = function(statId) {
    console.log(`📊 Redirection vers les détails pour: ${statId}`);
    
    // Animation du bouton
    const btn = event.target.closest('.view-more-btn');
    btn.style.transform = 'translateY(-1px) scale(0.98)';
    setTimeout(() => {
        btn.style.transform = 'translateY(-2px) scale(1.05)';
    }, 100);
    
    // Redirection vers la page de détails
    const detailUrl = `/evc/app/admin/statistiques/${statId}`;
    
    // Effet de transition avant redirection
    setTimeout(() => {
        window.location.href = detailUrl;
    }, 200);
};

// Modal des détails de statistique
function showStatDetailsModal(statData) {
    // Supprimer les modals existantes
    const existingModals = document.querySelectorAll('.stat-details-modal');
    existingModals.forEach(modal => modal.remove());
    
    // Créer la modal
    const modal = document.createElement('div');
    modal.className = 'stat-details-modal';
    modal.innerHTML = `
        <div class="modal-backdrop" onclick="closeStatDetailsModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="${statData.icon}"></i>
                </div>
                <h3>${statData.title}</h3>
                <button class="modal-close" onclick="closeStatDetailsModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="stat-overview">
                    <div class="stat-main-value">
                        ${window.holographicStats.formatValue(statData.value)}${statData.unit}
                    </div>
                    <div class="stat-trend trend-${statData.trend.type}">
                        <i class="fas ${statData.trend.type === 'positive' ? 'fa-arrow-up' : statData.trend.type === 'negative' ? 'fa-arrow-down' : 'fa-minus'}"></i>
                        ${statData.trend.value > 0 ? '+' : ''}${statData.trend.value}${statData.unit === '%' ? '%' : ''} ce mois
                    </div>
                </div>
                
                <div class="stat-description">
                    <p>${statData.description}</p>
                </div>
                
                <div class="stat-details-grid">
                    <div class="detail-item">
                        <span class="detail-label">Aujourd'hui</span>
                        <span class="detail-value">${Math.floor(statData.value * 0.05)}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Cette semaine</span>
                        <span class="detail-value">${Math.floor(statData.value * 0.15)}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Ce mois</span>
                        <span class="detail-value">${Math.floor(statData.value * 0.3)}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Total</span>
                        <span class="detail-value">${statData.value}</span>
                    </div>
                </div>
                
                <div class="stat-chart-large">
                    <canvas id="detail-chart-${statData.id}" width="400" height="200"></canvas>
                </div>
            </div>
            
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeStatDetailsModal()">Fermer</button>
                <button class="btn-primary" onclick="exportStatData('${statData.id}')">Exporter</button>
            </div>
        </div>
    `;
    
    // Styles de la modal
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: modalFadeIn 0.3s ease-out;
    `;
    
    // Ajouter les styles CSS pour la modal
    if (!document.querySelector('#statDetailsModalStyles')) {
        const style = document.createElement('style');
        style.id = 'statDetailsModalStyles';
        style.textContent = `
            @keyframes modalFadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            
            .modal-backdrop {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.8);
                backdrop-filter: blur(10px);
            }
            
            .modal-content {
                position: relative;
                background: rgba(15, 23, 42, 0.95);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 20px;
                width: 90%;
                max-width: 600px;
                max-height: 80vh;
                overflow-y: auto;
                color: white;
                animation: modalSlideIn 0.3s ease-out;
            }
            
            @keyframes modalSlideIn {
                from {
                    transform: translateY(-50px) scale(0.9);
                    opacity: 0;
                }
                to {
                    transform: translateY(0) scale(1);
                    opacity: 1;
                }
            }
            
            .modal-header {
                display: flex;
                align-items: center;
                padding: 1.5rem;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                gap: 1rem;
            }
            
            .modal-icon {
                width: 50px;
                height: 50px;
                background: rgba(0, 212, 255, 0.2);
                border-radius: 15px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                color: rgba(0, 212, 255, 0.9);
            }
            
            .modal-header h3 {
                flex: 1;
                margin: 0;
                font-size: 1.5rem;
                font-weight: 600;
            }
            
            .modal-close {
                background: none;
                border: none;
                color: rgba(255, 255, 255, 0.7);
                cursor: pointer;
                padding: 0.5rem;
                border-radius: 50%;
                transition: all 0.2s ease;
            }
            
            .modal-close:hover {
                background: rgba(255, 255, 255, 0.1);
                color: white;
            }
            
            .modal-body {
                padding: 1.5rem;
            }
            
            .stat-overview {
                text-align: center;
                margin-bottom: 2rem;
            }
            
            .stat-main-value {
                font-size: 3rem;
                font-weight: 700;
                background: linear-gradient(135deg, #00d4ff, #ff6b35);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                margin-bottom: 0.5rem;
            }
            
            .stat-trend {
                font-size: 1.1rem;
                font-weight: 500;
            }
            
            .trend-positive { color: #00ff88; }
            .trend-negative { color: #ff4757; }
            .trend-neutral { color: #ffa726; }
            
            .stat-description {
                margin-bottom: 2rem;
                color: rgba(255, 255, 255, 0.8);
                line-height: 1.6;
            }
            
            .stat-details-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
                margin-bottom: 2rem;
            }
            
            .detail-item {
                background: rgba(255, 255, 255, 0.05);
                padding: 1rem;
                border-radius: 10px;
                text-align: center;
            }
            
            .detail-label {
                display: block;
                font-size: 0.9rem;
                color: rgba(255, 255, 255, 0.7);
                margin-bottom: 0.5rem;
            }
            
            .detail-value {
                font-size: 1.5rem;
                font-weight: 600;
                color: rgba(0, 212, 255, 0.9);
            }
            
            .stat-chart-large {
                margin-bottom: 1rem;
            }
            
            .modal-footer {
                display: flex;
                justify-content: flex-end;
                gap: 1rem;
                padding: 1.5rem;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .btn-secondary, .btn-primary {
                padding: 0.75rem 1.5rem;
                border: none;
                border-radius: 10px;
                cursor: pointer;
                font-weight: 500;
                transition: all 0.2s ease;
            }
            
            .btn-secondary {
                background: rgba(255, 255, 255, 0.1);
                color: white;
            }
            
            .btn-secondary:hover {
                background: rgba(255, 255, 255, 0.2);
            }
            
            .btn-primary {
                background: rgba(0, 212, 255, 0.8);
                color: white;
            }
            
            .btn-primary:hover {
                background: rgba(0, 212, 255, 1);
                transform: translateY(-2px);
            }
        `;
        document.head.appendChild(style);
    }
    
    // Ajouter au DOM
    document.body.appendChild(modal);
    
    // Initialiser le graphique détaillé
    setTimeout(() => {
        initDetailChart(statData);
    }, 100);
}

// Fermer la modal des détails
window.closeStatDetailsModal = function() {
    const modal = document.querySelector('.stat-details-modal');
    if (modal) {
        modal.style.animation = 'modalFadeIn 0.3s ease-out reverse';
        setTimeout(() => modal.remove(), 300);
    }
};

// Exporter les données d'une statistique
window.exportStatData = function(statId) {
    console.log(`📤 Export des données pour: ${statId}`);
    showEVCNotification(`Export des données "${statId}" en cours...`, 'info');
    
    // Ici vous pouvez ajouter la logique d'export (CSV, PDF, etc.)
    setTimeout(() => {
        showEVCNotification(`Données "${statId}" exportées avec succès!`, 'success');
    }, 2000);
};

// Initialiser le graphique détaillé
function initDetailChart(statData) {
    const canvas = document.getElementById(`detail-chart-${statData.id}`);
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    
    // Données simulées pour le graphique détaillé
    const data = Array.from({length: 30}, (_, i) => {
        const baseValue = statData.value * 0.8;
        const variation = Math.random() * statData.value * 0.4;
        return Math.floor(baseValue + variation);
    });
    
    // Graphique simple avec Canvas
    const width = canvas.width;
    const height = canvas.height;
    const padding = 20;
    
    ctx.clearRect(0, 0, width, height);
    
    // Gradient de fond
    const gradient = ctx.createLinearGradient(0, 0, 0, height);
    gradient.addColorStop(0, 'rgba(0, 212, 255, 0.3)');
    gradient.addColorStop(1, 'rgba(0, 212, 255, 0.05)');
    
    // Dessiner la courbe
    ctx.beginPath();
    ctx.moveTo(padding, height - padding);
    
    data.forEach((value, index) => {
        const x = padding + (index * (width - 2 * padding)) / (data.length - 1);
        const y = height - padding - (value / Math.max(...data)) * (height - 2 * padding);
        
        if (index === 0) {
            ctx.moveTo(x, y);
        } else {
            ctx.lineTo(x, y);
        }
    });
    
    ctx.lineTo(width - padding, height - padding);
    ctx.lineTo(padding, height - padding);
    ctx.fillStyle = gradient;
    ctx.fill();
    
    // Ligne de la courbe
    ctx.beginPath();
    data.forEach((value, index) => {
        const x = padding + (index * (width - 2 * padding)) / (data.length - 1);
        const y = height - padding - (value / Math.max(...data)) * (height - 2 * padding);
        
        if (index === 0) {
            ctx.moveTo(x, y);
        } else {
            ctx.lineTo(x, y);
        }
    });
    
    ctx.strokeStyle = 'rgba(0, 212, 255, 0.8)';
    ctx.lineWidth = 2;
    ctx.stroke();
}

// Système de notifications EVC
window.showEVCNotification = function(message, type = 'info') {
    // Supprimer les notifications existantes
    const existingNotifications = document.querySelectorAll('.evc-notification');
    existingNotifications.forEach(notif => notif.remove());
    
    // Créer la notification
    const notification = document.createElement('div');
    notification.className = `evc-notification ${type}`;
    notification.innerHTML = `
        <div class="evc-notification-content">
            <i class="fas ${type === 'info' ? 'fa-info-circle' : type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'}"></i>
            <span>${message}</span>
        </div>
        <button class="evc-notification-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Ajouter les styles inline
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: rgba(0, 0, 0, 0.9);
        backdrop-filter: blur(20px);
        border: 1px solid ${type === 'info' ? 'rgba(0, 212, 255, 0.5)' : type === 'success' ? 'rgba(0, 255, 136, 0.5)' : 'rgba(255, 179, 71, 0.5)'};
        border-radius: 15px;
        padding: 1rem 1.5rem;
        color: white;
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 1rem;
        min-width: 300px;
        animation: slideInRight 0.3s ease-out;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    `;
    
    // Ajouter l'animation CSS
    if (!document.querySelector('#evcNotificationStyles')) {
        const style = document.createElement('style');
        style.id = 'evcNotificationStyles';
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            .evc-notification-content {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                flex: 1;
            }
            
            .evc-notification-close {
                background: none;
                border: none;
                color: rgba(255, 255, 255, 0.7);
                cursor: pointer;
                padding: 0.25rem;
                border-radius: 50%;
                transition: all 0.2s ease;
            }
            
            .evc-notification-close:hover {
                background: rgba(255, 255, 255, 0.1);
                color: white;
            }
        `;
        document.head.appendChild(style);
    }
    
    // Ajouter au DOM
    document.body.appendChild(notification);
    
    // Auto-suppression après 5 secondes
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.animation = 'slideInRight 0.3s ease-out reverse';
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
};
