/**
 * ADMIN STATISTICS MANAGER - EVC Dashboard
 * Gestionnaire modulaire et dynamique des statistiques
 * Version: 1.0
 */

class AdminStatisticsManager {
    constructor(options = {}) {
        this.config = {
            autoRefresh: true,
            refreshInterval: 30000, // 30 secondes
            animationDuration: 600,
            enableAnimations: true,
            enableCharts: true,
            debug: true,
            ...options
        };

        this.statistics = new Map();
        this.charts = new Map();
        this.refreshTimer = null;
        this.isInitialized = false;
        this.observers = new Map();

        this.log('AdminStatisticsManager initialized');
    }

    /**
     * Initialise le gestionnaire de statistiques
     */
    init() {
        if (this.isInitialized) {
            this.log('Statistics manager already initialized');
            return;
        }

        this.log('Initializing statistics manager...');
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }

    /**
     * Configuration initiale
     */
    setup() {
        this.log('Setting up statistics system...');
        
        // Découvrir toutes les cartes de statistiques
        this.discoverStatCards();
        
        // Initialiser les animations
        if (this.config.enableAnimations) {
            this.initializeAnimations();
        }
        
        // Initialiser les graphiques
        if (this.config.enableCharts) {
            this.initializeCharts();
        }
        
        // Configurer l'auto-refresh
        if (this.config.autoRefresh) {
            this.startAutoRefresh();
        }
        
        // Configurer les observateurs
        this.setupObservers();
        
        this.isInitialized = true;
        this.log('✅ Statistics system setup complete');
        
        // Émettre événement d'initialisation
        this.emitEvent('statistics:initialized');
    }

    /**
     * Découvre toutes les cartes de statistiques
     */
    discoverStatCards() {
        const cards = document.querySelectorAll('.stats-card');
        this.log(`Found ${cards.length} statistics cards`);

        cards.forEach((card, index) => {
            const id = card.dataset.statId || `stat-${index}`;
            const type = card.dataset.statType || 'number';
            const valueElement = card.querySelector('.stats-value');
            const changeElement = card.querySelector('.stats-change-value');
            const progressElement = card.querySelector('.stats-progress-bar');
            const chartElement = card.querySelector('.stats-chart');

            const statData = {
                id,
                type,
                element: card,
                valueElement,
                changeElement,
                progressElement,
                chartElement,
                currentValue: this.parseValue(valueElement?.textContent),
                targetValue: null,
                isAnimating: false
            };

            this.statistics.set(id, statData);
            this.log(`Registered statistic: ${id} (${type})`);
        });
    }

    /**
     * Initialise les animations
     */
    initializeAnimations() {
        this.log('Initializing animations...');
        
        // Animation d'entrée échelonnée
        const cards = document.querySelectorAll('.stats-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                card.style.transition = `all ${this.config.animationDuration}ms cubic-bezier(0.4, 0, 0.2, 1)`;
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
                card.classList.add('stats-animate-in');
            }, index * 100);
        });

        // Observer d'intersection pour animations au scroll
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('stats-animate-slide');
                    }
                });
            }, { threshold: 0.1 });

            cards.forEach(card => observer.observe(card));
            this.observers.set('intersection', observer);
        }
    }

    /**
     * Initialise les graphiques
     */
    initializeCharts() {
        this.log('Initializing charts...');
        
        const chartElements = document.querySelectorAll('.stats-chart');
        chartElements.forEach((element, index) => {
            const chartId = element.dataset.chartId || `chart-${index}`;
            const chartType = element.dataset.chartType || 'line';
            
            try {
                const chart = this.createChart(element, chartType, chartId);
                if (chart) {
                    this.charts.set(chartId, chart);
                    this.log(`Created chart: ${chartId} (${chartType})`);
                }
            } catch (error) {
                this.log(`Error creating chart ${chartId}:`, error);
            }
        });
    }

    /**
     * Crée un graphique
     */
    createChart(element, type, id) {
        if (typeof Chart === 'undefined') {
            this.log('Chart.js not available');
            return null;
        }

        const ctx = element.getContext('2d');
        const config = this.getChartConfig(type, id);
        
        return new Chart(ctx, config);
    }

    /**
     * Configuration des graphiques
     */
    getChartConfig(type, id) {
        const baseConfig = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    display: false
                },
                y: {
                    display: false
                }
            }
        };

        const configs = {
            line: {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                    datasets: [{
                        data: [12, 19, 3, 5, 2, 3],
                        borderColor: '#3399ff',
                        backgroundColor: 'rgba(51, 153, 255, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: baseConfig
            },
            bar: {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                    datasets: [{
                        data: [12, 19, 3, 5, 2, 3],
                        backgroundColor: [
                            '#3399ff', '#ff6633', '#28a745', 
                            '#ffc107', '#dc3545', '#17a2b8'
                        ]
                    }]
                },
                options: baseConfig
            },
            doughnut: {
                type: 'doughnut',
                data: {
                    labels: ['Actifs', 'Inactifs', 'En attente'],
                    datasets: [{
                        data: [65, 25, 10],
                        backgroundColor: ['#28a745', '#dc3545', '#ffc107']
                    }]
                },
                options: {
                    ...baseConfig,
                    cutout: '70%'
                }
            }
        };

        return configs[type] || configs.line;
    }

    /**
     * Met à jour une statistique
     */
    updateStatistic(id, newValue, options = {}) {
        const stat = this.statistics.get(id);
        if (!stat) {
            this.log(`Statistic ${id} not found`);
            return false;
        }

        const {
            animate = true,
            duration = this.config.animationDuration,
            change = null,
            changeType = 'neutral'
        } = options;

        this.log(`Updating statistic ${id}: ${stat.currentValue} → ${newValue}`);

        if (animate && stat.valueElement) {
            this.animateValue(stat, newValue, duration);
        } else if (stat.valueElement) {
            stat.valueElement.textContent = this.formatValue(newValue, stat.type);
        }

        // Mettre à jour le changement
        if (change !== null && stat.changeElement) {
            stat.changeElement.textContent = this.formatChange(change);
            const changeContainer = stat.changeElement.closest('.stats-change');
            if (changeContainer) {
                changeContainer.className = `stats-change ${changeType}`;
            }
        }

        // Mettre à jour la barre de progression
        if (stat.progressElement && stat.type === 'percentage') {
            stat.progressElement.style.width = `${newValue}%`;
        }

        stat.currentValue = newValue;
        
        // Émettre événement de mise à jour
        this.emitEvent('statistic:updated', { id, value: newValue, change });
        
        return true;
    }

    /**
     * Anime la transition d'une valeur
     */
    animateValue(stat, targetValue, duration) {
        if (stat.isAnimating) return;
        
        stat.isAnimating = true;
        const startValue = stat.currentValue || 0;
        const startTime = performance.now();

        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Fonction d'easing
            const easeProgress = this.easeOutCubic(progress);
            const currentValue = startValue + (targetValue - startValue) * easeProgress;
            
            if (stat.valueElement) {
                stat.valueElement.textContent = this.formatValue(currentValue, stat.type);
            }
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                stat.isAnimating = false;
            }
        };

        requestAnimationFrame(animate);
    }

    /**
     * Fonction d'easing
     */
    easeOutCubic(t) {
        return 1 - Math.pow(1 - t, 3);
    }

    /**
     * Formate une valeur selon son type
     */
    formatValue(value, type) {
        switch (type) {
            case 'currency':
                return new Intl.NumberFormat('fr-FR', {
                    style: 'currency',
                    currency: 'EUR'
                }).format(value);
            case 'percentage':
                return `${Math.round(value)}%`;
            case 'number':
                return new Intl.NumberFormat('fr-FR').format(Math.round(value));
            default:
                return value.toString();
        }
    }

    /**
     * Formate un changement
     */
    formatChange(change) {
        const sign = change >= 0 ? '+' : '';
        return `${sign}${change}%`;
    }

    /**
     * Parse une valeur depuis le texte
     */
    parseValue(text) {
        if (!text) return 0;
        return parseFloat(text.replace(/[^\d.-]/g, '')) || 0;
    }

    /**
     * Met à jour plusieurs statistiques
     */
    updateMultipleStatistics(updates) {
        Object.entries(updates).forEach(([id, data]) => {
            this.updateStatistic(id, data.value, data.options || {});
        });
    }

    /**
     * Actualise toutes les statistiques
     */
    async refreshAllStatistics() {
        this.log('Refreshing all statistics...');
        
        try {
            // Simuler un appel API
            const data = await this.fetchStatisticsData();
            
            // Mettre à jour toutes les statistiques
            this.updateMultipleStatistics(data);
            
            // Mettre à jour les graphiques
            this.refreshCharts();
            
            this.emitEvent('statistics:refreshed', data);
            this.log('✅ Statistics refreshed successfully');
            
        } catch (error) {
            this.log('Error refreshing statistics:', error);
            this.emitEvent('statistics:error', error);
        }
    }

    /**
     * Simule la récupération de données (à remplacer par un vrai appel API)
     */
    async fetchStatisticsData() {
        // Simuler un délai réseau
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        // Données simulées
        return {
            'total-students': {
                value: Math.floor(Math.random() * 1000) + 500,
                options: {
                    change: Math.floor(Math.random() * 20) - 10,
                    changeType: Math.random() > 0.5 ? 'positive' : 'negative'
                }
            },
            'active-courses': {
                value: Math.floor(Math.random() * 50) + 20,
                options: {
                    change: Math.floor(Math.random() * 10) - 5,
                    changeType: 'positive'
                }
            },
            'completion-rate': {
                value: Math.floor(Math.random() * 30) + 70,
                options: {
                    change: Math.floor(Math.random() * 5),
                    changeType: 'positive'
                }
            }
        };
    }

    /**
     * Actualise les graphiques
     */
    refreshCharts() {
        this.charts.forEach((chart, id) => {
            // Générer de nouvelles données
            const newData = Array.from({length: 6}, () => Math.floor(Math.random() * 20));
            chart.data.datasets[0].data = newData;
            chart.update('active');
        });
    }

    /**
     * Démarre l'actualisation automatique
     */
    startAutoRefresh() {
        if (this.refreshTimer) {
            clearInterval(this.refreshTimer);
        }
        
        this.refreshTimer = setInterval(() => {
            this.refreshAllStatistics();
        }, this.config.refreshInterval);
        
        this.log(`Auto-refresh started (${this.config.refreshInterval}ms)`);
    }

    /**
     * Arrête l'actualisation automatique
     */
    stopAutoRefresh() {
        if (this.refreshTimer) {
            clearInterval(this.refreshTimer);
            this.refreshTimer = null;
            this.log('Auto-refresh stopped');
        }
    }

    /**
     * Configure les observateurs
     */
    setupObservers() {
        // Observer de visibilité pour optimiser les performances
        if ('IntersectionObserver' in window) {
            const visibilityObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    const statId = entry.target.dataset.statId;
                    if (entry.isIntersecting) {
                        this.emitEvent('statistic:visible', { id: statId });
                    } else {
                        this.emitEvent('statistic:hidden', { id: statId });
                    }
                });
            });

            this.statistics.forEach(stat => {
                visibilityObserver.observe(stat.element);
            });

            this.observers.set('visibility', visibilityObserver);
        }
    }

    /**
     * Ajoute une nouvelle statistique dynamiquement
     */
    addStatistic(config) {
        const {
            id,
            type = 'number',
            title,
            value = 0,
            icon = 'fas fa-chart-line',
            variant = 'primary',
            container = '.stats-grid'
        } = config;

        const cardHTML = this.generateCardHTML(id, type, title, value, icon, variant);
        const containerElement = document.querySelector(container);
        
        if (containerElement) {
            containerElement.insertAdjacentHTML('beforeend', cardHTML);
            
            // Re-découvrir les cartes
            this.discoverStatCards();
            
            this.log(`Added new statistic: ${id}`);
            this.emitEvent('statistic:added', { id, config });
            
            return true;
        }
        
        return false;
    }

    /**
     * Génère le HTML d'une carte de statistique
     */
    generateCardHTML(id, type, title, value, icon, variant) {
        return `
            <div class="stats-card ${variant}" data-stat-id="${id}" data-stat-type="${type}">
                <div class="stats-card-header">
                    <div class="stats-card-icon">
                        <i class="${icon}"></i>
                    </div>
                    <h6 class="stats-card-title">${title}</h6>
                </div>
                <div class="stats-card-body">
                    <div class="stats-value">${this.formatValue(value, type)}</div>
                    <div class="stats-change neutral">
                        <i class="fas fa-minus stats-change-icon"></i>
                        <span class="stats-change-value">0%</span>
                        <span class="stats-change-period">vs mois dernier</span>
                    </div>
                </div>
                <div class="stats-card-footer">
                    <a href="#" class="stats-card-link">
                        Voir détails <i class="fas fa-arrow-right"></i>
                    </a>
                    <span class="stats-card-timestamp">Maintenant</span>
                </div>
            </div>
        `;
    }

    /**
     * Supprime une statistique
     */
    removeStatistic(id) {
        const stat = this.statistics.get(id);
        if (stat) {
            stat.element.remove();
            this.statistics.delete(id);
            this.log(`Removed statistic: ${id}`);
            this.emitEvent('statistic:removed', { id });
            return true;
        }
        return false;
    }

    /**
     * Exporte les données statistiques
     */
    exportData(format = 'json') {
        const data = {};
        this.statistics.forEach((stat, id) => {
            data[id] = {
                type: stat.type,
                value: stat.currentValue,
                timestamp: new Date().toISOString()
            };
        });

        switch (format) {
            case 'csv':
                return this.convertToCSV(data);
            case 'json':
            default:
                return JSON.stringify(data, null, 2);
        }
    }

    /**
     * Convertit les données en CSV
     */
    convertToCSV(data) {
        const headers = ['ID', 'Type', 'Value', 'Timestamp'];
        const rows = Object.entries(data).map(([id, stat]) => [
            id, stat.type, stat.value, stat.timestamp
        ]);
        
        return [headers, ...rows].map(row => row.join(',')).join('\n');
    }

    /**
     * Émet un événement personnalisé
     */
    emitEvent(eventName, detail = {}) {
        const event = new CustomEvent(eventName, { detail });
        document.dispatchEvent(event);
        this.log(`Event emitted: ${eventName}`);
    }

    /**
     * Logging avec préfixe
     */
    log(message, ...args) {
        if (this.config.debug) {
            console.log(`[AdminStats] ${message}`, ...args);
        }
    }

    /**
     * Détruit le gestionnaire
     */
    destroy() {
        this.log('Destroying statistics manager...');
        
        // Arrêter l'auto-refresh
        this.stopAutoRefresh();
        
        // Détruire les graphiques
        this.charts.forEach(chart => chart.destroy());
        this.charts.clear();
        
        // Détruire les observateurs
        this.observers.forEach(observer => observer.disconnect());
        this.observers.clear();
        
        // Nettoyer les références
        this.statistics.clear();
        this.isInitialized = false;
        
        this.log('✅ Statistics manager destroyed');
    }
}

// Instance globale
window.AdminStatisticsManager = AdminStatisticsManager;

// Auto-initialisation
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.adminStats === 'undefined') {
        window.adminStats = new AdminStatisticsManager();
        window.adminStats.init();
    }
});

// Export pour modules ES6
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AdminStatisticsManager;
}
