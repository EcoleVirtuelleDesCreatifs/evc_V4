document.addEventListener('DOMContentLoaded', () => {

    // --- Preloader ---
    try {
        const preloader = document.getElementById('preloader');
        if (preloader) {
            const hidePreloader = () => {
                preloader.style.opacity = '0';
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 200);
            };

            const showPreloader = () => {
                preloader.style.display = 'flex';
                preloader.style.opacity = '1';

                // Fallback: ne jamais bloquer l'utilisateur trop longtemps
                clearTimeout(window.__evcPreloaderTimeout);
                window.__evcPreloaderTimeout = setTimeout(() => {
                    hidePreloader();
                }, 800);
            };

            // Chargement initial: ne pas afficher de loader (site perçu instantané)
            hidePreloader();
            window.addEventListener('load', hidePreloader);

            // Afficher le loader lors de la navigation
            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', (e) => {
                    const href = link.getAttribute('href');
                    // Vérifier si c'est un lien interne (pas un anchor, pas externe, pas javascript, pas fancybox)
                    if (href &&
                        !href.startsWith('#') &&
                        !href.startsWith('javascript:') &&
                        !href.startsWith('mailto:') &&
                        !href.startsWith('tel:') &&
                        !link.hasAttribute('target') &&
                        !link.classList.contains('no-loader') &&
                        !link.hasAttribute('data-fancybox')) {

                        // Afficher le preloader uniquement pour les navigations internes
                        showPreloader();
                    }
                });
            });

            // Cacher le loader si l'utilisateur revient en arrière
            window.addEventListener('pageshow', (event) => {
                if (event.persisted) {
                    hidePreloader();
                }
            });
        }
    } catch (e) {
        console.error('Preloader Error:', e);
    }

    // --- Deferred background images (data-bg) ---
    try {
        const applyDeferredBackgrounds = () => {
            document.querySelectorAll('[data-bg]').forEach((el) => {
                const url = el.getAttribute('data-bg');
                if (!url) return;
                if (el.style.backgroundImage && el.style.backgroundImage !== 'none') return;
                el.style.backgroundImage = `url('${url}')`;
                el.removeAttribute('data-bg');
            });
        };

        if ('requestIdleCallback' in window) {
            requestIdleCallback(applyDeferredBackgrounds, { timeout: 1500 });
        } else {
            setTimeout(applyDeferredBackgrounds, 400);
        }
    } catch (e) {
        console.error('Deferred BG Error:', e);
    }

    // --- Header Scroll Effect ---
    try {
        const header = document.getElementById('main-header');
        const logo = header.querySelector('img');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('py-2', 'bg-gray-900/90', 'backdrop-blur-lg');
                header.classList.remove('py-4');
                logo.classList.remove('h-20', 'lg:h-24');
                logo.classList.add('h-16', 'lg:h-20');
            } else {
                header.classList.add('py-4');
                header.classList.remove('py-2', 'bg-gray-900/90', 'backdrop-blur-lg');
                logo.classList.remove('h-16', 'lg:h-20');
                logo.classList.add('h-20', 'lg:h-24');
            }
        });
    } catch (e) {
        console.error('Header Scroll Error:', e);
    }

    // --- Mobile Menu ---
    try {
        const mobileMenu = document.getElementById('mobile-menu');
        const openMenuButton = document.getElementById('mobile-menu-open-button');
        const closeMenuButton = document.getElementById('mobile-menu-close-button');
        const mobileMenuLinks = mobileMenu.querySelectorAll('a');

        openMenuButton.addEventListener('click', () => mobileMenu.classList.remove('hidden'));
        closeMenuButton.addEventListener('click', () => mobileMenu.classList.add('hidden'));
        mobileMenuLinks.forEach(link => link.addEventListener('click', () => mobileMenu.classList.add('hidden')));
    } catch (e) {
        console.error('Mobile Menu Error:', e);
    }

    // --- Swiper Carousels ---
    try {
        // Hero Sliders Desktop
        const heroBgSlider = new Swiper('.hero-bg-slider', {
            loop: true,
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
        });

        // Hero Slider Mobile
        const heroBgSliderMobile = new Swiper('.hero-bg-slider-mobile', {
            loop: true,
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
        });

        // Hero Text Slider (contrôle les deux sliders d'images)
        const heroTextSwiper = new Swiper('.hero-text-slider', {
            loop: true,
            autoplay: { delay: 4000, disableOnInteraction: false },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            controller: {
                control: [heroBgSlider, heroBgSliderMobile]
            }
        });

        // Travaux Carousel
        new Swiper('.travaux-carousel', { loop: true, slidesPerView: 1, spaceBetween: 30, grabCursor: true, pagination: { el: '.travaux-pagination', clickable: true }, navigation: { nextEl: '.travaux-next', prevEl: '.travaux-prev' }, breakpoints: { 640: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } } });


    } catch (e) {
        console.error('Swiper Error:', e);
    }

    // --- AOS (Animate on Scroll) ---
    try {
        AOS.init({ duration: 800, once: true, offset: 50 });
    } catch (e) {
        console.error('AOS Init Error:', e);
    }

    // --- Fancybox ---
    try {
        Fancybox.bind('[data-fancybox]', {
            Thumbs: false,
            buttons: ["zoom", "slideShow", "close"],
            loop: true
        });
    } catch (e) {
        console.error('Fancybox Error:', e);
    }

    // --- Pre-inscription Modal Logic ---
    try {
        const openModalBtn = document.getElementById('open-form-modal');
        const closeModalBtn = document.getElementById('close-form-modal');
        const formModal = document.getElementById('form-modal');
        const formContainer = document.getElementById('preinscription-form-container');

        if (formContainer) {
            const nextBtn = document.getElementById('nextBtn');
            const prevBtn = document.getElementById('prevBtn');
            const submitBtn = document.getElementById('submitBtn');
            const progressBar = document.getElementById('progressBar');
            const formSteps = formContainer.querySelectorAll('.form-step');
            const totalSteps = formSteps.length;
            let currentStep = 1;

            // Overlay helpers (works for modal-local overlay)
            const overlay = document.getElementById('mail-loading-overlay');
            const showOverlay = () => {
                if (!overlay) return;
                overlay.classList.remove('hidden');
                overlay.style.display = 'flex';
            };

            // Map programme -> choix_formation (enum backend)
            try {
                const programmeSelect = document.querySelector('select[name="programme"]');
                const hiddenChoix = document.querySelector('input[name="choix_formation"]');
                const mapProgramme = {
                    'infographie': 'design_graphique',
                    'community_management': 'community_management',
                    'informatique': 'gestion_informatique',
                    'infographie_cm': 'design_graphique', // fallback in enum
                };
                if (programmeSelect && hiddenChoix) {
                    programmeSelect.addEventListener('change', () => {
                        hiddenChoix.value = mapProgramme[programmeSelect.value] || '';
                    });
                }
            } catch (_) {}

            // Auto-detect country from ville_pays and prefix WhatsApp with the country dialing code if missing '+'
            try {
                const whatsappInput = document.querySelector('input[name="whatsapp"]');
                const villePaysInput = document.querySelector('input[name="ville_pays"]');
                const ccMap = [
                    { k: ['cote d\'ivoire','côte d\'ivoire','ivory coast','ci'], cc: '+225' },
                    { k: ['france','fr'], cc: '+33' },
                    { k: ['senegal','sénégal','sn'], cc: '+221' },
                    { k: ['benin','bénin','bj'], cc: '+229' },
                    { k: ['burkina faso','bf'], cc: '+226' },
                    { k: ['cameroun','cameroon','cm'], cc: '+237' },
                    { k: ['mali','ml'], cc: '+223' },
                    { k: ['togo','tg'], cc: '+228' },
                    { k: ['niger','ne'], cc: '+227' },
                    { k: ['guinee','guinée','gn'], cc: '+224' },
                    { k: ['rdc','congo','république démocratique du congo','cd'], cc: '+243' },
                    { k: ['maroc','ma'], cc: '+212' },
                    { k: ['tunisie','tn'], cc: '+216' },
                    { k: ['algerie','algérie','dz'], cc: '+213' },
                ];
                const detectCC = (text) => {
                    if (!text) return '+225';
                    const s = text.toLowerCase();
                    for (const entry of ccMap) {
                        if (entry.k.some(key => s.includes(key))) return entry.cc;
                    }
                    return '+225';
                };
                if (whatsappInput) {
                    const applyPrefix = () => {
                        const v = whatsappInput.value.trim();
                        if (v && !v.startsWith('+')) {
                            const cc = detectCC(villePaysInput ? villePaysInput.value : '');
                            whatsappInput.value = `${cc} ${v}`;
                        }
                    };
                    whatsappInput.addEventListener('blur', applyPrefix);
                    if (villePaysInput) villePaysInput.addEventListener('blur', applyPrefix);
                }
            } catch(_){}

            const hideOverlay = () => {
                if (!overlay) return;
                overlay.classList.add('hidden');
                overlay.style.display = 'none';
            };

            const updateFormState = () => {
                if (formSteps && formSteps.length) {
                    formSteps.forEach(step => {
                        step.classList.toggle('active', parseInt(step.dataset.step) === currentStep);
                    });
                }
                if (progressBar) progressBar.style.width = `${(currentStep / (totalSteps || 1)) * 100}%`;
                if (prevBtn) prevBtn.style.display = currentStep > 1 ? 'inline-block' : 'none';
                if (nextBtn) nextBtn.style.display = currentStep < (totalSteps || 1) ? 'inline-block' : 'none';
                if (submitBtn) submitBtn.style.display = currentStep === (totalSteps || 1) ? 'inline-block' : 'none';
            };

            if (openModalBtn && formModal) {
                openModalBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    formModal.classList.remove('hidden');
                    setTimeout(() => {
                        formModal.classList.remove('opacity-0');
                        formContainer.classList.remove('scale-95');
                    }, 10);
                    updateFormState();
                });
            }

            // Mobile / other triggers
            document.querySelectorAll('.open-form-modal').forEach(el => {
                el.addEventListener('click', (e) => {
                    if (!formModal) return; // dedicated page: no modal
                    e.preventDefault();
                    formModal.classList.remove('hidden');
                    setTimeout(() => {
                        formModal.classList.remove('opacity-0');
                        formContainer.classList.remove('scale-95');
                    }, 10);
                    updateFormState();
                });
            });

            const closeModal = () => {
                if (!formModal) return;
                formModal.classList.add('opacity-0');
                formContainer.classList.add('scale-95');
                setTimeout(() => formModal.classList.add('hidden'), 300);
            };

            if (closeModalBtn && formModal) closeModalBtn.addEventListener('click', closeModal);

            nextBtn.addEventListener('click', () => {
                if (currentStep < totalSteps) {
                    currentStep++;
                    updateFormState();
                }
            });

            prevBtn.addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    updateFormState();
                }
            });

            const formEl = document.getElementById('preRegistrationForm');

            // Flash banner (AJAX) for success
            const showFlashSuccess = (message) => {
                try {
                    const id = 'flash-success-ajax';
                    const old = document.getElementById(id);
                    if (old) old.remove();
                    const wrap = document.createElement('div');
                    wrap.id = id;
                    wrap.className = 'fixed top-4 left-1/2 -translate-x-1/2 z-[13000] bg-emerald-600 text-white px-4 py-3 rounded-full shadow-lg ring-1 ring-white/10 flex items-center gap-3';
                    wrap.innerHTML = `<i class="fas fa-check-circle"></i><span>${message || 'Votre candidature a été envoyée avec succès.'}</span>`;
                    document.body.appendChild(wrap);
                    setTimeout(() => { const el = document.getElementById(id); if (el) el.remove(); }, 6000);
                } catch(_){}
            };

            // Live progress calculation for required fields
            const progressLine = document.getElementById('form-progress-line');
            const progressText = document.getElementById('form-progress-text');
            const getRequiredFields = () => Array.from(formEl.querySelectorAll('[name][required]'));
            const computeProgress = () => {
                const req = getRequiredFields();
                if (req.length === 0) return 0;
                let filled = 0;
                req.forEach(f => {
                    if (f.type === 'checkbox') {
                        if (f.checked) filled++;
                    } else if (f.type === 'file') {
                        if (f.files && f.files.length) filled++;
                    } else if (f.value && f.value.trim() !== '') {
                        filled++;
                    }
                });
                return Math.round((filled / req.length) * 100);
            };
            const updateProgressUI = () => {
                const pct = computeProgress();
                if (progressLine) progressLine.style.width = `${pct}%`;
                if (progressText) progressText.textContent = `Progression: ${pct}%`;
            };

            // Inline error rendering helpers
            const clearFieldError = (field) => {
                try {
                    field.classList.remove('ring-2','ring-red-500');
                    field.setAttribute('aria-invalid', 'false');
                    // Remove a following helper if present
                    const next = field.nextElementSibling;
                    if (next && next.classList && next.classList.contains('field-error-text')) {
                        next.remove();
                    }
                } catch(_){}
            };
            const setFieldError = (field, msg) => {
                try {
                    clearFieldError(field);
                    field.classList.add('ring-2','ring-red-500');
                    field.setAttribute('aria-invalid', 'true');
                    const p = document.createElement('p');
                    p.className = 'field-error-text text-red-400 text-sm mt-1';
                    p.textContent = msg;
                    field.insertAdjacentElement('afterend', p);
                    field.addEventListener('input', () => clearFieldError(field), { once: true });
                    field.addEventListener('change', () => clearFieldError(field), { once: true });
                } catch(_){}
            };
            const clearAllErrors = (form) => {
                form.querySelectorAll('[name]').forEach(el => clearFieldError(el));
            };
            // Attach listeners
            formEl.addEventListener('input', updateProgressUI, true);
            formEl.addEventListener('change', updateProgressUI, true);
            updateProgressUI();

            // If the form is configured for native submission, skip AJAX handling
            const ajaxDisabled = formEl && (formEl.getAttribute('data-ajax') === 'false');
            if (!formEl || ajaxDisabled) {
                return; // let browser/Laravel handle everything (redirects, flashes)
            }

            formEl.addEventListener('submit', async (e) => {
                e.preventDefault();
                // Client-side validity check first
                if (!formEl.checkValidity()) {
                    clearAllErrors(formEl);
                    // Find first invalid required field
                    const invalid = formEl.querySelector('[name][required]:invalid');
                    if (invalid) {
                        setFieldError(invalid, 'Ce champ est requis.');
                        const rect = invalid.getBoundingClientRect();
                        const offsetY = window.scrollY + rect.top - 120;
                        window.scrollTo({ top: offsetY, behavior: 'smooth' });
                        invalid.focus({ preventScroll: true });
                    }
                    if (typeof showToast === 'function') showToast('warning', 'Merci de compléter les champs requis.');
                    return;
                }

                try {
                    const submitBtnEl = formEl.querySelector('button[type="submit"]');
                    const originalText = submitBtnEl ? submitBtnEl.textContent : '';
                    if (submitBtnEl) {
                        submitBtnEl.disabled = true;
                        submitBtnEl.textContent = 'Envoi en cours...';
                    }
                    showOverlay();

                    const form = e.currentTarget;
                    const formData = new FormData(form);
                    // CSRF token from meta if needed
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const resp = await fetch(form.getAttribute('action') || '/pre-registration', {
                        method: (form.getAttribute('method') || 'POST').toUpperCase(),
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData,
                    });

                    const isJson = (resp.headers.get('content-type')||'').includes('application/json');
                    const data = isJson ? await resp.json().catch(() => ({})) : {};

                    if (!resp.ok) {
                        clearAllErrors(form);
                        if (data && data.errors) {
                            let firstInvalid = null;
                            Object.entries(data.errors).forEach(([name, messages]) => {
                                const field = form.querySelector(`[name="${name}"]`);
                                if (field) {
                                    setFieldError(field, Array.isArray(messages) ? messages[0] : 'Champ invalide');
                                    if (!firstInvalid) firstInvalid = field;
                                }
                            });
                            if (typeof showToast === 'function') showToast('error', 'Veuillez corriger les erreurs du formulaire.');
                            // Scroll to first invalid
                            if (firstInvalid) {
                                const rect = firstInvalid.getBoundingClientRect();
                                const offsetY = window.scrollY + rect.top - 120; // leave room for sticky header
                                window.scrollTo({ top: offsetY, behavior: 'smooth' });
                                firstInvalid.focus({ preventScroll: true });
                            }
                            updateProgressUI();
                        } else if (resp.status === 419) {
                            if (typeof showToast === 'function') showToast('warning', 'Session expirée. Rechargez la page.');
                        } else {
                            if (typeof showToast === 'function') showToast('error', "Une erreur s'est produite. Veuillez réessayer.");
                        }
                        return;
                    }

                    const successMsg = (data && data.success) || 'Votre candidature a été envoyée avec succès. Nous vous contacterons prochainement.';
                    if (typeof showToast === 'function') showToast('success', successMsg);
                    showFlashSuccess(successMsg);
                    // reset and close
                    form.reset();
                    updateProgressUI();
                    if (formModal) {
                        formModal.classList.add('opacity-0');
                        formContainer.classList.add('scale-95');
                        setTimeout(() => formModal.classList.add('hidden'), 300);
                    }
                } catch (err) {
                    console.error('Pre-inscription submit error:', err);
                    if (typeof showToast === 'function') showToast('error', "Impossible d'envoyer pour le moment. Réessayez.");
                } finally {
                    const submitBtnEl = formEl.querySelector('button[type="submit"]');
                    if (submitBtnEl) {
                        submitBtnEl.disabled = false;
                        submitBtnEl.textContent = 'Soumettre ma candidature';
                    }
                    hideOverlay();
                }
            });
        }
    } catch (e) {
        console.error('Pre-inscription Modal Error:', e);
    }


    // --- Founder Card Flip ---
    try {
        const founderCard = document.querySelector('.founder-card-inner');
        if (founderCard) {
            founderCard.addEventListener('click', () => {
                founderCard.classList.toggle('is-flipped');
            });
        }
    } catch (e) {
        console.error('Founder card flip error:', e);
    }

    // --- Reusable Tab System ---
    const initTabs = (tabContainerId) => {
        try {
            const tabContainer = document.getElementById(tabContainerId);
            if (!tabContainer) return;

            const tabButtons = tabContainer.querySelectorAll('.tab-button');
            const tabPanels = tabContainer.parentElement.querySelectorAll('.tab-panel');

            if (tabButtons.length > 0) {
                tabButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        // Deactivate all buttons in this container
                        tabButtons.forEach(btn => btn.classList.remove('active'));

                        // Deactivate all panels associated with this tab set
                        const containerParent = tabContainer.parentElement;
                        containerParent.querySelectorAll('.tab-panel').forEach(panel => panel.classList.remove('active'));

                        // Activate the clicked button
                        button.classList.add('active');

                        // Activate the corresponding panel
                        const targetPanelId = button.dataset.target;
                        const targetPanel = containerParent.querySelector(`#${targetPanelId}`);
                        if (targetPanel) {
                            targetPanel.classList.add('active');
                        }
                    });
                });
            }
        } catch (e) {
            console.error(`Tab system error for #${tabContainerId}:`, e);
        }
    };

    initTabs('presentation-tabs');
    initTabs('founder-tabs');

    // --- Particles.js Initialization ---
    try {
        if (document.getElementById('particles-js')) {
            particlesJS('particles-js', {
                "particles": {
                    "number": {
                        "value": 80,
                        "density": {
                            "enable": true,
                            "value_area": 800
                        }
                    },
                    "color": {
                        "value": "#ffffff"
                    },
                    "shape": {
                        "type": "circle",
                    },
                    "opacity": {
                        "value": 0.5,
                        "random": false,
                        "anim": {
                            "enable": false
                        }
                    },
                    "size": {
                        "value": 3,
                        "random": true,
                        "anim": {
                            "enable": false
                        }
                    },
                    "line_linked": {
                        "enable": true,
                        "distance": 150,
                        "color": "#ffffff",
                        "opacity": 0.4,
                        "width": 1
                    },
                    "move": {
                        "enable": true,
                        "speed": 2,
                        "direction": "none",
                        "random": false,
                        "straight": false,
                        "out_mode": "out",
                        "bounce": false,
                    }
                },
                "interactivity": {
                    "detect_on": "canvas",
                    "events": {
                        "onhover": {
                            "enable": true,
                            "mode": "grab"
                        },
                        "onclick": {
                            "enable": true,
                            "mode": 'push'
                        },
                        "resize": true
                    },
                    "modes": {
                        "grab": {
                            "distance": 140,
                            "line_linked": {
                                "opacity": 1
                            }
                        },
                        "push": {
                            "particles_nb": 4
                        }
                    }
                },
                "retina_detect": true
            });
        }
    } catch(e) {
        console.error('Particles.js Error:', e);
    }

    // --- Scroll to Top Button ---
    try {
        const scrollToTopBtn = document.getElementById('scrollToTop');

        if (scrollToTopBtn) {
            // Afficher/masquer le bouton en fonction du scroll
            const toggleScrollButton = () => {
                if (window.scrollY > 300) {
                    scrollToTopBtn.classList.add('show');
                } else {
                    scrollToTopBtn.classList.remove('show');
                }
            };

            // Écouter le scroll
            window.addEventListener('scroll', toggleScrollButton);

            // Gérer le clic sur le bouton
            scrollToTopBtn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            // Vérifier la position initiale
            toggleScrollButton();
        }
    } catch(e) {
        console.error('Scroll to Top Error:', e);
    }

});
