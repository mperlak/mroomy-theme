/**
 * Top Stats Mobile Carousel
 * Transforms grid layout into carousel on mobile devices
 */

(function() {
    'use strict';

    class TopStatsCarousel {
        constructor(element) {
            this.container = element;
            this.wrapper = element.querySelector('.stats-wrapper');
            this.items = element.querySelectorAll('.stat-item');
            this.dots = element.querySelectorAll('.carousel-dot');
            this.currentSlide = 0;
            this.totalSlides = this.items.length;
            this.touchStartX = 0;
            this.touchEndX = 0;
            this.isMobile = window.innerWidth < 640; // sm breakpoint

            this.init();
        }

        init() {
            if (this.totalSlides <= 1) return;

            // Bind events
            this.bindEvents();

            // Handle initial state immediately
            if (this.isMobile) {
                this.enableCarousel();
            }

            // Setup responsive behavior
            this.setupResponsive();
        }

        setupResponsive() {
            // Add resize listener with debounce
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => this.handleResize(), 150);
            });
        }

        handleResize() {
            const wasMobile = this.isMobile;
            this.isMobile = window.innerWidth < 640;

            if (this.isMobile !== wasMobile) {
                if (this.isMobile) {
                    this.enableCarousel();
                } else {
                    this.disableCarousel();
                }
            }
        }

        enableCarousel() {
            // Transform grid into carousel for mobile
            this.wrapper.classList.remove('sm:grid', 'sm:grid-cols-2', 'lg:grid-cols-3', 'sm:gap-10');
            this.wrapper.classList.add('flex', 'transition-transform', 'duration-300');

            // Set up carousel track styles
            this.wrapper.style.display = 'flex';
            this.wrapper.style.overflow = 'hidden';
            this.wrapper.style.position = 'relative';
            this.wrapper.style.width = '100%';

            // Set up slide styles WITHOUT transition initially
            this.items.forEach((item, index) => {
                item.style.position = 'absolute';
                item.style.top = '0';
                item.style.left = '0';
                item.style.width = '100%';
                item.style.transform = `translateX(${index * 100}%)`;
                // Don't add transition yet to prevent animation on load
            });

            // Set wrapper height to match first item
            if (this.items[0]) {
                const firstItemHeight = this.items[0].offsetHeight;
                this.wrapper.style.height = `${firstItemHeight}px`;
            }

            // Show dots
            const dotsContainer = this.container.querySelector('.carousel-dots');
            if (dotsContainer) {
                dotsContainer.style.display = 'flex';
            }

            // Mark as initialized
            this.container.classList.add('carousel-initialized');

            // Add transitions after a brief delay to prevent initial animation
            setTimeout(() => {
                this.items.forEach(item => {
                    item.style.transition = 'transform 0.3s ease-in-out';
                });
            }, 100);

            // Go to first slide
            this.goToSlide(0);
        }

        disableCarousel() {
            // Restore grid layout for desktop
            this.wrapper.classList.add('sm:grid', 'sm:grid-cols-2', 'lg:grid-cols-3', 'sm:gap-10');
            this.wrapper.classList.remove('flex', 'transition-transform', 'duration-300');

            // Remove initialized class
            this.container.classList.remove('carousel-initialized');

            // Reset styles
            this.wrapper.style.display = '';
            this.wrapper.style.overflow = '';
            this.wrapper.style.position = '';
            this.wrapper.style.width = '';
            this.wrapper.style.height = '';

            // Reset slide styles
            this.items.forEach(item => {
                item.style.position = '';
                item.style.top = '';
                item.style.left = '';
                item.style.width = '';
                item.style.transform = '';
                item.style.transition = '';
            });

            // Hide dots
            const dotsContainer = this.container.querySelector('.carousel-dots');
            if (dotsContainer) {
                dotsContainer.style.display = '';
            }
        }

        bindEvents() {
            // Dot navigation
            this.dots.forEach((dot, index) => {
                dot.addEventListener('click', () => this.goToSlide(index));
            });

            // Touch events for swipe
            this.wrapper.addEventListener('touchstart', (e) => this.handleTouchStart(e), { passive: true });
            this.wrapper.addEventListener('touchmove', (e) => this.handleTouchMove(e), { passive: true });
            this.wrapper.addEventListener('touchend', (e) => this.handleTouchEnd(e));

            // Keyboard navigation
            this.container.addEventListener('keydown', (e) => {
                if (!this.isMobile) return;

                if (e.key === 'ArrowLeft') {
                    this.prevSlide();
                    e.preventDefault();
                } else if (e.key === 'ArrowRight') {
                    this.nextSlide();
                    e.preventDefault();
                }
            });
        }

        goToSlide(index) {
            if (!this.isMobile) return;

            if (index < 0) {
                index = this.totalSlides - 1;
            } else if (index >= this.totalSlides) {
                index = 0;
            }

            this.currentSlide = index;
            this.updateSlidePosition();
            this.updateDots();
            this.updateAriaStates();
            this.updateHeight();
        }

        nextSlide() {
            this.goToSlide(this.currentSlide + 1);
        }

        prevSlide() {
            this.goToSlide(this.currentSlide - 1);
        }

        updateSlidePosition() {
            // Move all slides based on current index
            this.items.forEach((item, index) => {
                const offset = (index - this.currentSlide) * 100;
                item.style.transform = `translateX(${offset}%)`;
            });
        }

        updateDots() {
            this.dots.forEach((dot, index) => {
                const isActive = index === this.currentSlide;
                dot.setAttribute('aria-selected', isActive);

                // Update dot classes
                if (isActive) {
                    dot.className = 'carousel-dot w-8 h-3 rounded-[16px] bg-primary transition-all duration-300';
                } else {
                    dot.className = 'carousel-dot w-3 h-3 rounded-full bg-neutral-field-border transition-all duration-300';
                }
            });
        }

        updateAriaStates() {
            this.items.forEach((item, index) => {
                item.setAttribute('aria-hidden', index !== this.currentSlide);
            });
        }

        updateHeight() {
            // Adjust wrapper height to current slide
            if (this.items[this.currentSlide]) {
                const currentHeight = this.items[this.currentSlide].offsetHeight;
                this.wrapper.style.height = `${currentHeight}px`;
            }
        }

        handleTouchStart(e) {
            if (!this.isMobile) return;
            this.touchStartX = e.touches[0].clientX;
        }

        handleTouchMove(e) {
            if (!this.isMobile) return;
            this.touchEndX = e.touches[0].clientX;
        }

        handleTouchEnd(e) {
            if (!this.isMobile) return;
            if (!this.touchStartX || !this.touchEndX) return;

            const diff = this.touchStartX - this.touchEndX;
            const threshold = 50;

            if (Math.abs(diff) > threshold) {
                if (diff > 0) {
                    this.nextSlide();
                } else {
                    this.prevSlide();
                }
            }

            this.touchStartX = 0;
            this.touchEndX = 0;
        }
    }

    // Initialize on DOM ready
    function initTopStatsCarousels() {
        const containers = document.querySelectorAll('.mroomy-top-stats-container[data-carousel="mobile-only"]');
        containers.forEach(container => {
            new TopStatsCarousel(container);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTopStatsCarousels);
    } else {
        initTopStatsCarousels();
    }

    // Export for potential use
    window.TopStatsCarousel = TopStatsCarousel;
})();