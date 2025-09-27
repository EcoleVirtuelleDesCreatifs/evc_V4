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
        const heroTextSwiper = new Swiper('.hero-text-slider', { effect: 'fade', fadeEffect: { crossFade: true }, loop: true, allowTouchMove: false });
        const heroBgSwiper = new Swiper('.hero-bg-slider', { effect: 'fade', fadeEffect: { crossFade: true }, loop: true, autoplay: { delay: 4000, disableOnInteraction: false }, allowTouchMove: false, controller: { control: heroTextSwiper } });
        heroTextSwiper.controller.control = heroBgSwiper;

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
        Fancybox.bind('[data-fancybox="gallery"]', { buttons: ["zoom", "slideShow", "thumbs", "close"], loop: true });
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
});
