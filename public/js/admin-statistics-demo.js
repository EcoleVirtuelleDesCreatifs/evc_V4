/**
 * DEMO ET TESTS - ADMIN STATISTICS SYSTEM
 * Script de démonstration et validation du système modulaire
 * Version: 1.0
 */

class AdminStatisticsDemo {
    constructor() {
        this.isRunning = false;
        this.demoInterval = null;
        this.testResults = [];
        
        this.log('AdminStatisticsDemo initialized');
    }

    /**
     * Lance la démonstration complète
     */
    async startDemo() {
        if (this.isRunning) {
            this.log('Demo already running');
            return;
        }

        this.log('🚀 Starting Admin Statistics Demo...');
        this.isRunning = true;

        try {
            // Attendre que le système soit initialisé
            await this.waitForSystem();
            
            // Tests de validation
            await this.runValidationTests();
            
            // Démonstration des animations
            await this.demonstrateAnimations();
            
            // Démonstration des mises à jour
            await this.demonstrateUpdates();
            
            // Démonstration des graphiques
            await this.demonstrateCharts();
            
            // Démonstration de l'auto-refresh
            this.demonstrateAutoRefresh();
            
            this.log('✅ Demo completed successfully');
            
        } catch (error) {
            this.log('❌ Demo failed:', error);
        }
    }

    /**
     * Arrête la démonstration
     */
    stopDemo() {
        if (this.demoInterval) {
            clearInterval(this.demoInterval);
            this.demoInterval = null;
        }
        
        if (window.adminStats) {
            window.adminStats.stopAutoRefresh();
        }
        
        this.isRunning = false;
        this.log('🛑 Demo stopped');
    }

    /**
     * Attend que le système soit prêt
     */
    waitForSystem() {
        return new Promise((resolve, reject) => {
            const timeout = setTimeout(() => {
                reject(new Error('System initialization timeout'));
            }, 10000);

            const checkSystem = () => {
                if (window.adminStats && window.adminStats.isInitialized) {
                    clearTimeout(timeout);
                    this.log('✅ System ready');
                    resolve();
                } else {
                    setTimeout(checkSystem, 100);
                }
            };

            checkSystem();
        });
    }

    /**
     * Tests de validation du système
     */
    async runValidationTests() {
        this.log('🧪 Running validation tests...');
        
        const tests = [
            this.testSystemInitialization,
            this.testStatisticsDiscovery,
            this.testUpdateFunctionality,
            this.testAnimations,
            this.testCharts,
            this.testEventSystem
        ];

        for (const test of tests) {
            try {
                await test.call(this);
                this.testResults.push({ test: test.name, status: 'PASS' });
            } catch (error) {
                this.testResults.push({ test: test.name, status: 'FAIL', error: error.message });
                this.log(`❌ Test failed: ${test.name} - ${error.message}`);
            }
        }

        this.displayTestResults();
    }

    /**
     * Test d'initialisation du système
     */
    testSystemInitialization() {
        if (!window.adminStats) {
            throw new Error('AdminStatisticsManager not found');
        }
        
        if (!window.adminStats.isInitialized) {
            throw new Error('System not initialized');
        }
        
        this.log('✅ System initialization test passed');
    }

    /**
     * Test de découverte des statistiques
     */
    testStatisticsDiscovery() {
        const statsCount = window.adminStats.statistics.size;
        
        if (statsCount === 0) {
            throw new Error('No statistics discovered');
        }
        
        this.log(`✅ Statistics discovery test passed (${statsCount} statistics found)`);
    }

    /**
     * Test de mise à jour
     */
    testUpdateFunctionality() {
        const testId = 'total-students';
        const testValue = 9999;
        
        const result = window.adminStats.updateStatistic(testId, testValue);
        
        if (!result) {
            throw new Error('Update functionality failed');
        }
        
        this.log('✅ Update functionality test passed');
    }

    /**
     * Test des animations
     */
    testAnimations() {
        const cards = document.querySelectorAll('.stats-card');
        
        if (cards.length === 0) {
            throw new Error('No stats cards found for animation test');
        }
        
        // Vérifier que les classes d'animation sont appliquées
        let animatedCards = 0;
        cards.forEach(card => {
            if (card.classList.contains('stats-animate-in')) {
                animatedCards++;
            }
        });
        
        if (animatedCards === 0) {
            throw new Error('No animated cards found');
        }
        
        this.log('✅ Animations test passed');
    }

    /**
     * Test des graphiques
     */
    testCharts() {
        const chartsCount = window.adminStats.charts.size;
        
        if (chartsCount === 0) {
            throw new Error('No charts initialized');
        }
        
        this.log(`✅ Charts test passed (${chartsCount} charts found)`);
    }

    /**
     * Test du système d'événements
     */
    testEventSystem() {
        let eventReceived = false;
        
        const eventHandler = () => {
            eventReceived = true;
        };
        
        document.addEventListener('statistic:updated', eventHandler, { once: true });
        
        // Déclencher une mise à jour
        window.adminStats.updateStatistic('total-students', 1234);
        
        // Vérifier que l'événement a été reçu
        setTimeout(() => {
            if (!eventReceived) {
                throw new Error('Event system not working');
            }
            
            this.log('✅ Event system test passed');
        }, 100);
    }

    /**
     * Affiche les résultats des tests
     */
    displayTestResults() {
        console.group('📊 Test Results Summary');
        
        this.testResults.forEach(result => {
            const icon = result.status === 'PASS' ? '✅' : '❌';
            console.log(`${icon} ${result.test}: ${result.status}`);
            
            if (result.error) {
                console.error(`   Error: ${result.error}`);
            }
        });
        
        const passedTests = this.testResults.filter(r => r.status === 'PASS').length;
        const totalTests = this.testResults.length;
        
        console.log(`\n📈 Results: ${passedTests}/${totalTests} tests passed`);
        console.groupEnd();
    }

    /**
     * Démonstration des animations
     */
    async demonstrateAnimations() {
        this.log('🎨 Demonstrating animations...');
        
        const cards = document.querySelectorAll('.stats-card');
        
        // Animation de pulsation
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.transform = 'scale(1.05)';
                card.style.transition = 'transform 0.3s ease';
                
                setTimeout(() => {
                    card.style.transform = 'scale(1)';
                }, 300);
            }, index * 200);
        });
        
        await this.delay(2000);
        this.log('✅ Animations demonstration completed');
    }

    /**
     * Démonstration des mises à jour
     */
    async demonstrateUpdates() {
        this.log('🔄 Demonstrating updates...');
        
        const updates = [
            { id: 'total-students', value: 1250, change: +12 },
            { id: 'completion-rate', value: 85, change: +5 },
            { id: 'total-tps', value: 456, change: -2 },
            { id: 'monthly-revenue', value: 15750, change: +18 }
        ];
        
        for (const update of updates) {
            window.adminStats.updateStatistic(update.id, update.value, {
                animate: true,
                change: update.change,
                changeType: update.change > 0 ? 'positive' : 'negative'
            });
            
            await this.delay(800);
        }
        
        this.log('✅ Updates demonstration completed');
    }

    /**
     * Démonstration des graphiques
     */
    async demonstrateCharts() {
        this.log('📈 Demonstrating charts...');
        
        // Mettre à jour tous les graphiques avec de nouvelles données
        window.adminStats.refreshCharts();
        
        await this.delay(1000);
        this.log('✅ Charts demonstration completed');
    }

    /**
     * Démonstration de l'auto-refresh
     */
    demonstrateAutoRefresh() {
        this.log('🔄 Demonstrating auto-refresh...');
        
        // Configurer un auto-refresh rapide pour la démo
        window.adminStats.config.refreshInterval = 5000;
        window.adminStats.startAutoRefresh();
        
        this.log('✅ Auto-refresh demonstration started (5s interval)');
    }

    /**
     * Génère des données de test aléatoires
     */
    generateTestData() {
        return {
            'total-students': {
                value: Math.floor(Math.random() * 500) + 800,
                options: {
                    change: Math.floor(Math.random() * 20) - 10,
                    changeType: Math.random() > 0.5 ? 'positive' : 'negative'
                }
            },
            'completion-rate': {
                value: Math.floor(Math.random() * 20) + 75,
                options: {
                    change: Math.floor(Math.random() * 10) - 5,
                    changeType: 'positive'
                }
            },
            'total-tps': {
                value: Math.floor(Math.random() * 200) + 300,
                options: {
                    change: Math.floor(Math.random() * 15) - 7,
                    changeType: Math.random() > 0.3 ? 'positive' : 'negative'
                }
            },
            'monthly-revenue': {
                value: Math.floor(Math.random() * 10000) + 10000,
                options: {
                    change: Math.floor(Math.random() * 25) - 5,
                    changeType: 'positive'
                }
            }
        };
    }

    /**
     * Test de performance
     */
    async performanceTest() {
        this.log('⚡ Running performance test...');
        
        const iterations = 100;
        const startTime = performance.now();
        
        for (let i = 0; i < iterations; i++) {
            window.adminStats.updateStatistic('total-students', Math.random() * 1000);
        }
        
        const endTime = performance.now();
        const duration = endTime - startTime;
        const avgTime = duration / iterations;
        
        this.log(`📊 Performance Results:`);
        this.log(`   Total time: ${duration.toFixed(2)}ms`);
        this.log(`   Average per update: ${avgTime.toFixed(2)}ms`);
        this.log(`   Updates per second: ${(1000 / avgTime).toFixed(0)}`);
        
        if (avgTime > 10) {
            this.log('⚠️ Performance warning: Updates taking longer than expected');
        } else {
            this.log('✅ Performance test passed');
        }
    }

    /**
     * Test de mémoire
     */
    memoryTest() {
        if (performance.memory) {
            const memory = performance.memory;
            this.log(`💾 Memory Usage:`);
            this.log(`   Used: ${(memory.usedJSHeapSize / 1024 / 1024).toFixed(2)} MB`);
            this.log(`   Total: ${(memory.totalJSHeapSize / 1024 / 1024).toFixed(2)} MB`);
            this.log(`   Limit: ${(memory.jsHeapSizeLimit / 1024 / 1024).toFixed(2)} MB`);
        } else {
            this.log('💾 Memory API not available');
        }
    }

    /**
     * Utilitaire de délai
     */
    delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    /**
     * Logging avec préfixe
     */
    log(message, ...args) {
        console.log(`[AdminStatsDemo] ${message}`, ...args);
    }
}

// Instance globale
window.AdminStatisticsDemo = AdminStatisticsDemo;

// Commandes de console pour tests manuels
window.demoStats = {
    start: () => {
        if (!window.statsDemo) {
            window.statsDemo = new AdminStatisticsDemo();
        }
        window.statsDemo.startDemo();
    },
    
    stop: () => {
        if (window.statsDemo) {
            window.statsDemo.stopDemo();
        }
    },
    
    test: () => {
        if (window.statsDemo) {
            window.statsDemo.runValidationTests();
        }
    },
    
    performance: () => {
        if (window.statsDemo) {
            window.statsDemo.performanceTest();
        }
    },
    
    memory: () => {
        if (window.statsDemo) {
            window.statsDemo.memoryTest();
        }
    },
    
    update: (id, value) => {
        if (window.adminStats) {
            window.adminStats.updateStatistic(id, value, { animate: true });
        }
    },
    
    refresh: () => {
        if (window.adminStats) {
            window.adminStats.refreshAllStatistics();
        }
    }
};

// Message d'aide
console.log(`
🎯 ADMIN STATISTICS DEMO COMMANDS:
   demoStats.start()      - Lancer la démonstration complète
   demoStats.stop()       - Arrêter la démonstration
   demoStats.test()       - Exécuter les tests de validation
   demoStats.performance() - Test de performance
   demoStats.memory()     - Vérifier l'usage mémoire
   demoStats.update(id, value) - Mettre à jour une statistique
   demoStats.refresh()    - Actualiser toutes les statistiques
`);

// Auto-initialisation en mode développement
if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            if (window.adminStats && window.adminStats.isInitialized) {
                console.log('🚀 Demo system ready! Type demoStats.start() to begin.');
            }
        }, 2000);
    });
}
