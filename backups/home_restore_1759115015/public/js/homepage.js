document.addEventListener('DOMContentLoaded', () => {

    // --- Preloader --- 
    try {
        const preloader = document.getElementById('preloader');
        if (preloader) {
            window.addEventListener('load', () => {
                setTimeout(() => {
                    preloader.style.opacity = '0';
                    setTimeout(() => {
                        preloader.style.display = 'none';
                    }, 1000); // Match transition duration
                }, 200); // Short delay
            });
        }
    } catch (e) {
        console.error('Preloader Error:', e);
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
        // Hero Sliders
        const heroTextSwiper = new Swiper('.hero-text-slider', { 
            loop: true,
            autoplay: { delay: 4000, disableOnInteraction: false },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            controller: {
                control: new Swiper('.hero-bg-slider', {
                    loop: true,
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true
                    },
                })
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

        if (openModalBtn && formModal && formContainer) {
            const nextBtn = document.getElementById('nextBtn');
            const prevBtn = document.getElementById('prevBtn');
            const submitBtn = document.getElementById('submitBtn');
            const progressBar = document.getElementById('progressBar');
            const formSteps = formContainer.querySelectorAll('.form-step');
            const totalSteps = formSteps.length;
            let currentStep = 1;

            // --- Toast Utility ---
            const showToast = (type, message) => {
                const container = document.getElementById('toast-container');
                const sr = document.getElementById('toast-sr');
                if (!container) {
                    // Fallback
                    alert(message);
                    return;
                }
                const colors = {
                    success: 'bg-emerald-600 border-emerald-500 text-white',
                    error: 'bg-red-600 border-red-500 text-white',
                    warning: 'bg-amber-600 border-amber-500 text-white',
                    info: 'bg-sky-600 border-sky-500 text-white',
                };
                const icon = {
                    success: '✔',
                    error: '✖',
                    warning: '⚠',
                    info: 'ℹ',
                }[type] || 'ℹ';

                const toast = document.createElement('div');
                toast.className = `flex items-start gap-3 px-4 py-3 rounded-lg shadow-lg border ${colors[type] || colors.info} animate-fade-in`;
                toast.innerHTML = `
                    <span class="text-xl leading-none">${icon}</span>
                    <div class="text-sm">${message}</div>
                    <button type="button" class="ml-4 text-white/80 hover:text-white">×</button>
                `;

                const closeBtn = toast.querySelector('button');
                const remove = () => {
                    toast.classList.add('opacity-0', 'translate-y-1');
                    setTimeout(() => toast.remove(), 200);
                };
                closeBtn.addEventListener('click', remove);

                container.appendChild(toast);
                if (sr) sr.textContent = message;

                setTimeout(remove, 5000);
            };

            const markInvalid = (el) => {
                el.classList.add('ring-2', 'ring-red-500');
            };
            const clearInvalid = (el) => {
                el.classList.remove('ring-2', 'ring-red-500');
            };

            const validateCurrentStep = () => {
                const activeStep = Array.from(formSteps).find(s => s.classList.contains('active'));
                if (!activeStep) return true;
                let valid = true;
                // Inputs, selects, textareas
                const fields = activeStep.querySelectorAll('input[required], select[required], textarea[required]');
                fields.forEach(field => {
                    // Special case for radio groups
                    if (field.type === 'radio') {
                        const group = activeStep.querySelectorAll(`input[type="radio"][name="${field.name}"]`);
                        const oneChecked = Array.from(group).some(r => r.checked);
                        if (!oneChecked) {
                            valid = false;
                            group.forEach(r => markInvalid(r));
                        } else {
                            group.forEach(r => clearInvalid(r));
                        }
                    } else {
                        if (!field.value || field.value.trim() === '') {
                            valid = false;
                            markInvalid(field);
                        } else {
                            clearInvalid(field);
                        }
                    }
                });
                return valid;
            };

            const updateFormState = () => {
                formSteps.forEach(step => {
                    step.classList.toggle('active', parseInt(step.dataset.step) === currentStep);
                });

                progressBar.style.width = `${(currentStep / totalSteps) * 100}%`;
                prevBtn.style.display = currentStep > 1 ? 'inline-block' : 'none';
                nextBtn.style.display = currentStep < totalSteps ? 'inline-block' : 'none';
                submitBtn.style.display = currentStep === totalSteps ? 'inline-block' : 'none';
            };

            openModalBtn.addEventListener('click', () => {
                formModal.classList.remove('hidden');
                setTimeout(() => {
                    formModal.classList.remove('opacity-0');
                    formContainer.classList.remove('scale-95');
                }, 10);
                updateFormState();
            });

            const closeModal = () => {
                formModal.classList.add('opacity-0');
                formContainer.classList.add('scale-95');
                setTimeout(() => formModal.classList.add('hidden'), 300);
            };

            closeModalBtn.addEventListener('click', closeModal);

            nextBtn.addEventListener('click', () => {
                // validate before moving forward
                if (!validateCurrentStep()) return;
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

            progressBar.style.width = `${(currentStep / totalSteps) * 100}%`;
            prevBtn.style.display = currentStep > 1 ? 'inline-block' : 'none';
            nextBtn.style.display = currentStep < totalSteps ? 'inline-block' : 'none';
            submitBtn.style.display = currentStep === totalSteps ? 'inline-block' : 'none';
        };

        document.getElementById('preRegistrationForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!validateCurrentStep()) return;

            // Prepare submission
            submitBtn.disabled = true;
            const submitTextDefault = submitBtn.textContent;
            submitBtn.textContent = 'Envoi...';

            // Show loading overlay (email sending)
            const overlay = document.getElementById('mail-loading-overlay');
            const showOverlay = () => {
                if (!overlay) return;
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
                overlay.style.display = 'flex';
            };
            const hideOverlay = () => {
                if (!overlay) return;
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
                overlay.style.display = 'none';
            };
            showOverlay();

            try {
                const form = e.currentTarget;
                const formData = new FormData(form);
                // Ensure unchecked radio groups still submit a value? Backend requires them, but HTML will send selected only.
                // Rely on required validation to guarantee selection before submit.
                const csrf = form.querySelector('input[name="_token"]').value;
                const resp = await fetch('/pre-registration', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    body: formData
                });

                    if (!resp.ok) {
                        const contentType = resp.headers.get('content-type') || '';
                        const data = contentType.includes('application/json') ? await resp.json().catch(() => ({})) : { message: await resp.text().catch(() => '') };
                        // Display validation errors
                        if (data && data.errors) {
                            Object.entries(data.errors).forEach(([name, messages]) => {
                                const field = form.querySelector(`[name="${name}"]`);
                                if (field) markInvalid(field);
                            });
                            showToast('error', 'Veuillez corriger les erreurs du formulaire.');
                        } else if (resp.status === 419) {
                            showToast('warning', 'Votre session a expiré. Veuillez recharger la page puis réessayer.');
                        } else {
                            console.error('Submission failed', { status: resp.status, data });
                            showToast('error', "Une erreur s'est produite. Veuillez réessayer.");
                        }
                    } else {
                        const data = await resp.json();
                        showToast('success', data.success || 'Votre demande a été envoyée avec succès.');
                        // Reset & close
                        form.reset();
                        currentStep = 1;
                        updateFormState();
                        closeModal();
                    }
                } catch (err) {
                    console.error('Submission error:', err);
                    showToast('error', "Impossible d'envoyer pour le moment. Vérifiez votre connexion et réessayez.");
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = submitTextDefault;
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

});
