/**
 * ADMIN MENU MANAGER - EVC Dashboard
 * Gestion propre et modulaire des menus et sous-menus
 * Version: 1.0
 */

class AdminMenuManager {
    constructor() {
        this.dropdowns = [];
        this.activeDropdown = null;
        this.isInitialized = false;
        this.config = {
            animationDuration: 300,
            closeOnOutsideClick: true,
            singleDropdownOpen: true,
            debug: true
        };
        
        this.log('AdminMenuManager initialized');
    }

    /**
     * Initialise le gestionnaire de menus
     */
    init() {
        if (this.isInitialized) {
            this.log('Menu manager already initialized');
            return;
        }

        this.log('Initializing menu manager...');
        
        // Attendre que le DOM soit prêt
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }

    /**
     * Configuration initiale des menus
     */
    setup() {
        this.log('Setting up menu system...');
        
        // Trouver tous les dropdowns
        this.findDropdowns();
        
        // Attacher les événements
        this.attachEvents();
        
        // Configurer les états initiaux
        this.setupInitialStates();
        
        this.isInitialized = true;
        this.log('✅ Menu system setup complete');
    }

    /**
     * Trouve tous les éléments dropdown dans la sidebar
     */
    findDropdowns() {
        const dropdownElements = document.querySelectorAll('.nav-item.dropdown');
        this.dropdowns = [];

        dropdownElements.forEach((element, index) => {
            const link = element.querySelector('.nav-link');
            const menu = element.querySelector('.dropdown-menu');
            const arrow = element.querySelector('.nav-arrow');
            const badge = element.querySelector('.nav-badge');

            if (link && menu) {
                const dropdown = {
                    id: `dropdown-${index}`,
                    element: element,
                    link: link,
                    menu: menu,
                    arrow: arrow,
                    badge: badge,
                    isOpen: false,
                    items: menu.querySelectorAll('.dropdown-item')
                };

                this.dropdowns.push(dropdown);
                this.log(`Found dropdown: ${dropdown.id} with ${dropdown.items.length} items`);
            }
        });

        this.log(`Total dropdowns found: ${this.dropdowns.length}`);
    }

    /**
     * Attache les événements aux éléments de menu
     */
    attachEvents() {
        this.log('Attaching events...');

        // Événements pour chaque dropdown
        this.dropdowns.forEach(dropdown => {
            // Supprimer les anciens événements
            dropdown.link.removeAttribute('onclick');
            
            // Ajouter le nouvel événement
            dropdown.link.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.toggleDropdown(dropdown);
            });

            // Événements sur les items du dropdown
            dropdown.items.forEach(item => {
                item.addEventListener('click', (e) => {
                    this.handleItemClick(item, dropdown, e);
                });
            });
        });

        // Fermer les dropdowns en cliquant à l'extérieur
        if (this.config.closeOnOutsideClick) {
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.nav-item.dropdown')) {
                    this.closeAllDropdowns();
                }
            });
        }

        // Gestion du clavier
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllDropdowns();
            }
        });

        this.log('✅ Events attached');
    }

    /**
     * Configure les états initiaux des menus
     */
    setupInitialStates() {
        this.dropdowns.forEach(dropdown => {
            dropdown.menu.style.display = 'none';
            dropdown.element.classList.remove('show');
            dropdown.isOpen = false;
            
            if (dropdown.arrow) {
                dropdown.arrow.style.transform = 'rotate(0deg)';
            }
        });

        this.log('✅ Initial states configured');
    }

    /**
     * Toggle un dropdown (ouvrir/fermer)
     */
    toggleDropdown(dropdown) {
        this.log(`Toggling dropdown: ${dropdown.id}`);

        if (dropdown.isOpen) {
            this.closeDropdown(dropdown);
        } else {
            // Fermer les autres dropdowns si configuré
            if (this.config.singleDropdownOpen) {
                this.closeAllDropdowns();
            }
            this.openDropdown(dropdown);
        }
    }

    /**
     * Ouvre un dropdown
     */
    openDropdown(dropdown) {
        this.log(`Opening dropdown: ${dropdown.id}`);

        // Marquer comme ouvert
        dropdown.isOpen = true;
        dropdown.element.classList.add('show');
        this.activeDropdown = dropdown;

        // Afficher le menu
        dropdown.menu.style.display = 'block';
        dropdown.menu.classList.add('opening');
        dropdown.menu.classList.remove('closing');

        // Animer la flèche
        if (dropdown.arrow) {
            dropdown.arrow.style.transform = 'rotate(90deg)';
        }

        // Forcer les styles d'ouverture
        setTimeout(() => {
            dropdown.menu.style.opacity = '1';
            dropdown.menu.style.transform = 'translateY(0) scale(1)';
            dropdown.menu.style.maxHeight = '500px';
            dropdown.menu.classList.remove('opening');
        }, 10);

        // Émettre un événement personnalisé
        this.emitEvent('dropdown:opened', { dropdown });
    }

    /**
     * Ferme un dropdown
     */
    closeDropdown(dropdown) {
        this.log(`Closing dropdown: ${dropdown.id}`);

        // Marquer comme fermé
        dropdown.isOpen = false;
        dropdown.element.classList.remove('show');
        
        if (this.activeDropdown === dropdown) {
            this.activeDropdown = null;
        }

        // Animer la fermeture
        dropdown.menu.classList.add('closing');
        dropdown.menu.classList.remove('opening');
        dropdown.menu.style.opacity = '0';
        dropdown.menu.style.transform = 'translateY(-10px) scale(0.95)';
        dropdown.menu.style.maxHeight = '0';

        // Animer la flèche
        if (dropdown.arrow) {
            dropdown.arrow.style.transform = 'rotate(0deg)';
        }

        // Cacher complètement après l'animation
        setTimeout(() => {
            dropdown.menu.style.display = 'none';
            dropdown.menu.classList.remove('closing');
        }, this.config.animationDuration);

        // Émettre un événement personnalisé
        this.emitEvent('dropdown:closed', { dropdown });
    }

    /**
     * Ferme tous les dropdowns
     */
    closeAllDropdowns() {
        this.dropdowns.forEach(dropdown => {
            if (dropdown.isOpen) {
                this.closeDropdown(dropdown);
            }
        });
    }

    /**
     * Gère le clic sur un item de dropdown
     */
    handleItemClick(item, dropdown, event) {
        const href = item.getAttribute('href');
        
        this.log(`Item clicked in ${dropdown.id}: ${href || 'no href'}`);

        // Ajouter un effet visuel
        item.style.transform = 'translateX(6px) scale(0.98)';
        setTimeout(() => {
            item.style.transform = '';
        }, 150);

        // Fermer le dropdown après un délai
        setTimeout(() => {
            this.closeDropdown(dropdown);
        }, 200);

        // Émettre un événement personnalisé
        this.emitEvent('dropdown:item-clicked', { 
            item, 
            dropdown, 
            href,
            event 
        });
    }

    /**
     * Met à jour le badge d'un dropdown
     */
    updateBadge(dropdownId, count) {
        const dropdown = this.dropdowns.find(d => d.id === dropdownId);
        if (dropdown && dropdown.badge) {
            dropdown.badge.textContent = count;
            dropdown.badge.style.display = count > 0 ? 'block' : 'none';
            this.log(`Updated badge for ${dropdownId}: ${count}`);
        }
    }

    /**
     * Active/désactive un dropdown
     */
    setDropdownEnabled(dropdownId, enabled) {
        const dropdown = this.dropdowns.find(d => d.id === dropdownId);
        if (dropdown) {
            dropdown.link.style.pointerEvents = enabled ? 'auto' : 'none';
            dropdown.link.style.opacity = enabled ? '1' : '0.5';
            this.log(`Dropdown ${dropdownId} ${enabled ? 'enabled' : 'disabled'}`);
        }
    }

    /**
     * Obtient l'état d'un dropdown
     */
    getDropdownState(dropdownId) {
        const dropdown = this.dropdowns.find(d => d.id === dropdownId);
        return dropdown ? {
            id: dropdown.id,
            isOpen: dropdown.isOpen,
            itemCount: dropdown.items.length,
            badgeCount: dropdown.badge ? dropdown.badge.textContent : null
        } : null;
    }

    /**
     * Émet un événement personnalisé
     */
    emitEvent(eventName, detail) {
        const event = new CustomEvent(eventName, { detail });
        document.dispatchEvent(event);
        this.log(`Event emitted: ${eventName}`);
    }

    /**
     * Logging avec préfixe
     */
    log(message) {
        if (this.config.debug) {
            console.log(`[AdminMenu] ${message}`);
        }
    }

    /**
     * Détruit le gestionnaire de menus
     */
    destroy() {
        this.log('Destroying menu manager...');
        
        // Supprimer tous les événements
        this.dropdowns.forEach(dropdown => {
            dropdown.link.removeEventListener('click', this.toggleDropdown);
        });

        // Nettoyer les références
        this.dropdowns = [];
        this.activeDropdown = null;
        this.isInitialized = false;

        this.log('✅ Menu manager destroyed');
    }
}

// Instance globale du gestionnaire de menus
window.AdminMenuManager = AdminMenuManager;

// Auto-initialisation
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.adminMenu === 'undefined') {
        window.adminMenu = new AdminMenuManager();
        window.adminMenu.init();
    }
});

// Export pour les modules ES6 si nécessaire
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AdminMenuManager;
}
