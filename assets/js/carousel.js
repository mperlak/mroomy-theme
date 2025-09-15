/**
 * Mroomy Carousel Component JavaScript
 *
 * Handles carousel functionality including:
 * - Touch/swipe gestures on mobile
 * - Keyboard navigation
 * - Auto-play
 * - Dot navigation
 */

(function() {
    'use strict';

    class MroomyCarousel {
        constructor(element) {
            this.carousel = element;
            this.track = element.querySelector('.carousel-track');
            this.slides = element.querySelectorAll('.carousel-slide');
            this.dots = element.querySelectorAll('.carousel-dot');
            this.currentSlide = 0;
            this.totalSlides = this.slides.length;
            this.autoplay = element.dataset.autoplay === 'true';
            this.autoplayDelay = parseInt(element.dataset.autoplayDelay) || 5000;
            this.autoplayTimer = null;
            this.touchStartX = 0;
            this.touchEndX = 0;
            this.isDragging = false;

            this.init();
        }

        init() {
            if (this.totalSlides <= 1) return;

            // Bind event handlers
            this.bindEvents();

            // Start autoplay if enabled
            if (this.autoplay) {
                this.startAutoplay();
            }

            // Set initial ARIA states
            this.updateAriaStates();
        }

        bindEvents() {
            // Dot navigation
            this.dots.forEach((dot, index) => {
                dot.addEventListener('click', () => this.goToSlide(index));
            });

            // Touch events for mobile swipe
            this.track.addEventListener('touchstart', (e) => this.handleTouchStart(e), { passive: true });
            this.track.addEventListener('touchmove', (e) => this.handleTouchMove(e), { passive: true });
            this.track.addEventListener('touchend', (e) => this.handleTouchEnd(e));

            // Mouse events for desktop drag
            this.track.addEventListener('mousedown', (e) => this.handleMouseDown(e));
            this.track.addEventListener('mousemove', (e) => this.handleMouseMove(e));
            this.track.addEventListener('mouseup', (e) => this.handleMouseUp(e));
            this.track.addEventListener('mouseleave', (e) => this.handleMouseUp(e));

            // Keyboard navigation
            this.carousel.addEventListener('keydown', (e) => this.handleKeyDown(e));

            // Pause autoplay on hover
            if (this.autoplay) {
                this.carousel.addEventListener('mouseenter', () => this.stopAutoplay());
                this.carousel.addEventListener('mouseleave', () => this.startAutoplay());
            }

            // Handle visibility change (pause when tab is not visible)
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    this.stopAutoplay();
                } else if (this.autoplay) {
                    this.startAutoplay();
                }
            });
        }

        goToSlide(index) {
            if (index < 0) {
                index = this.totalSlides - 1;
            } else if (index >= this.totalSlides) {
                index = 0;
            }

            this.currentSlide = index;
            this.updateSlidePosition();
            this.updateDots();
            this.updateAriaStates();

            // Reset autoplay timer
            if (this.autoplay) {
                this.stopAutoplay();
                this.startAutoplay();
            }
        }

        nextSlide() {
            this.goToSlide(this.currentSlide + 1);
        }

        prevSlide() {
            this.goToSlide(this.currentSlide - 1);
        }

        updateSlidePosition() {
            const offset = -this.currentSlide * 100;
            this.track.style.transform = `translateX(${offset}%)`;
        }

        updateDots() {
            this.dots.forEach((dot, index) => {
                const isActive = index === this.currentSlide;
                dot.setAttribute('aria-selected', isActive);

                // Update dot classes based on state
                const size = this.getDotsSize();
                const activeClasses = this.getDotClasses(size, true);
                const inactiveClasses = this.getDotClasses(size, false);

                if (isActive) {
                    dot.className = `carousel-dot ${activeClasses}`;
                } else {
                    dot.className = `carousel-dot ${inactiveClasses}`;
                }
            });
        }

        getDotsSize() {
            // Get size from first dot's initial classes or default to 'large'
            const firstDot = this.dots[0];
            if (firstDot) {
                if (firstDot.classList.contains('w-3') && firstDot.classList.contains('h-1')) return 'small';
                if (firstDot.classList.contains('w-5')) return 'medium';
            }
            return 'large';
        }

        getDotClasses(size, isActive) {
            const baseClasses = 'transition-all duration-300';

            if (isActive) {
                const sizeClasses = {
                    'small': 'w-3 h-1 rounded-[2px]',
                    'medium': 'w-5 h-1.5 rounded-[8px]',
                    'large': 'w-8 h-3 rounded-[16px]'
                };
                return `${baseClasses} bg-primary ${sizeClasses[size] || sizeClasses['large']}`;
            } else {
                const sizeClasses = {
                    'small': 'w-1 h-1 rounded-full',
                    'medium': 'w-1.5 h-1.5 rounded-full',
                    'large': 'w-3 h-3 rounded-full'
                };
                return `${baseClasses} bg-neutral-field-border ${sizeClasses[size] || sizeClasses['large']}`;
            }
        }

        updateAriaStates() {
            this.slides.forEach((slide, index) => {
                slide.setAttribute('aria-hidden', index !== this.currentSlide);
            });
        }

        handleTouchStart(e) {
            this.touchStartX = e.touches[0].clientX;
        }

        handleTouchMove(e) {
            this.touchEndX = e.touches[0].clientX;
        }

        handleTouchEnd(e) {
            if (!this.touchStartX || !this.touchEndX) return;

            const diff = this.touchStartX - this.touchEndX;
            const threshold = 50; // Minimum swipe distance

            if (Math.abs(diff) > threshold) {
                if (diff > 0) {
                    // Swiped left
                    this.nextSlide();
                } else {
                    // Swiped right
                    this.prevSlide();
                }
            }

            this.touchStartX = 0;
            this.touchEndX = 0;
        }

        handleMouseDown(e) {
            this.isDragging = true;
            this.touchStartX = e.clientX;
            this.track.style.cursor = 'grabbing';
            e.preventDefault();
        }

        handleMouseMove(e) {
            if (!this.isDragging) return;
            this.touchEndX = e.clientX;
        }

        handleMouseUp(e) {
            if (!this.isDragging) return;

            this.isDragging = false;
            this.track.style.cursor = 'grab';

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

        handleKeyDown(e) {
            switch (e.key) {
                case 'ArrowLeft':
                    this.prevSlide();
                    e.preventDefault();
                    break;
                case 'ArrowRight':
                    this.nextSlide();
                    e.preventDefault();
                    break;
            }
        }

        startAutoplay() {
            if (!this.autoplay) return;

            this.stopAutoplay(); // Clear any existing timer
            this.autoplayTimer = setInterval(() => {
                this.nextSlide();
            }, this.autoplayDelay);
        }

        stopAutoplay() {
            if (this.autoplayTimer) {
                clearInterval(this.autoplayTimer);
                this.autoplayTimer = null;
            }
        }
    }

    // Initialize all carousels on page
    function initCarousels() {
        const carousels = document.querySelectorAll('.mroomy-carousel');
        carousels.forEach(carousel => {
            new MroomyCarousel(carousel);
        });
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCarousels);
    } else {
        initCarousels();
    }

    // Export for use in other scripts
    window.MroomyCarousel = MroomyCarousel;
})();