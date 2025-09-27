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

            document.getElementById('preRegistrationForm').addEventListener('submit', e => {
                e.preventDefault();
                // Handle form submission logic here
                console.log('Form submitted!');
                // You can add AJAX submission here
            });
        }
    } catch (e) {
        console.error('Pre-inscription Modal Error:', e);
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
