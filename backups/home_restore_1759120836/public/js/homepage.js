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

        // Initialize logic if the form container exists (works for modal and standalone page)
        if (formContainer) {
            const nextBtn = document.getElementById('nextBtn');
            const prevBtn = document.getElementById('prevBtn');
            const submitBtn = document.getElementById('submitBtn');
            const progressBar = document.getElementById('progressBar');
            const formSteps = formContainer.querySelectorAll('.form-step');
            const totalSteps = formSteps.length;
            let currentStep = 1;

            const syncSidebar = () => {
                // Optional sidebar stepper on dedicated page
                const sidebar = document.getElementById('steps-sidebar');
                if (!sidebar) return;
                const items = sidebar.querySelectorAll('.step-item');
                items.forEach(li => {
                    const stepNum = parseInt(li.getAttribute('data-step'));
                    const isActive = stepNum === currentStep;
                    const isCompleted = stepNum < currentStep;
                    li.classList.toggle('ring-1', isActive);
                    li.classList.toggle('ring-evc-orange', isActive);
                    li.classList.toggle('bg-white/5', isActive);
                    li.classList.toggle('opacity-60', !isActive && !isCompleted);
                    const checkIcon = li.querySelector('.completed-icon');
                    if (checkIcon) checkIcon.classList.toggle('hidden', !isCompleted);
                });
            };

            const updateFormState = () => {
                formSteps.forEach(step => {
                    step.classList.toggle('active', parseInt(step.dataset.step) === currentStep);
                });

                progressBar.style.width = `${(currentStep / totalSteps) * 100}%`;
                prevBtn.style.display = currentStep > 1 ? 'inline-block' : 'none';
                nextBtn.style.display = currentStep < totalSteps ? 'inline-block' : 'none';
                submitBtn.style.display = currentStep === totalSteps ? 'inline-block' : 'none';

                // Sync sidebar states when present
                syncSidebar();
            };

            const openAdmissionsModal = () => {
                // Close mobile menu if open
                try {
                    const mobileMenu = document.getElementById('mobile-menu');
                    if (mobileMenu) mobileMenu.classList.add('hidden');
                } catch (_) {}
                formModal.classList.remove('hidden');
                setTimeout(() => {
                    formModal.classList.remove('opacity-0');
                    formContainer.classList.remove('scale-95');
                }, 10);
                updateFormState();
            };

            // Header desktop button (optional)
            if (openModalBtn) {
                openModalBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    openAdmissionsModal();
                });
            }

            // Mobile menu link(s)
            document.querySelectorAll('.open-form-modal').forEach(el => {
                el.addEventListener('click', (e) => {
                    e.preventDefault();
                    openAdmissionsModal();
                });
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
                    // Smooth scroll to form container (account for fixed header)
                    const headerOffset = 100;
                    const y = formContainer.getBoundingClientRect().top + window.pageYOffset - headerOffset;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }
            });

            prevBtn.addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    updateFormState();
                    const headerOffset = 100;
                    const y = formContainer.getBoundingClientRect().top + window.pageYOffset - headerOffset;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }
            });

        };
    }
} catch (e) {
    console.error('Pre-inscription Modal Error:', e);
}

// Existing site scripts below (tabs, particles, etc.) remain unchanged above in the file
});
