// Import CSS
import '../css/main.css';

// Mobile menu functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Theme loaded!');
    
    // Mobile menu elements
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('#mobile-menu');
    const mobileMenuClose = document.querySelector('#mobile-menu-close');
    const mobileMenuOverlay = document.querySelector('#mobile-menu-overlay');
    const body = document.body;
    const masthead = document.querySelector('#masthead');
    
    // Mobile menu state
    let isMenuOpen = false;
    
    // Open mobile menu
    function openMobileMenu() {
        if (isMenuOpen) return;
        
        // Set top offset so the panel starts just under the masthead
        const headerHeight = masthead ? masthead.getBoundingClientRect().bottom : 0;
        mobileMenu.style.top = headerHeight + 'px';
        mobileMenu.style.height = `calc(100dvh - ${headerHeight}px)`;

        mobileMenu.classList.remove('hidden');
        body.style.overflow = 'hidden'; // Prevent body scroll
        isMenuOpen = true;
        
        // Focus management
        setTimeout(() => {
            mobileMenuClose.focus();
        }, 100);
    }
    
    // Close mobile menu
    function closeMobileMenu() {
        if (!isMenuOpen) return;
        
        mobileMenu.classList.add('hidden');
        mobileMenu.style.top = '';
        mobileMenu.style.height = '';
        body.style.overflow = ''; // Restore body scroll
        isMenuOpen = false;
        
        // Return focus to toggle button
        mobileMenuToggle.focus();
    }
    
    // Toggle mobile menu
    function toggleMobileMenu() {
        if (isMenuOpen) {
            closeMobileMenu();
        } else {
            openMobileMenu();
        }
    }
    
    // Event listeners
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            toggleMobileMenu();
        });
    }
    
    if (mobileMenuClose) {
        mobileMenuClose.addEventListener('click', function(e) {
            e.preventDefault();
            closeMobileMenu();
        });
    }
    
    if (mobileMenuOverlay) {
        mobileMenuOverlay.addEventListener('click', function(e) {
            e.preventDefault();
            closeMobileMenu();
        });
    }
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isMenuOpen) {
            closeMobileMenu();
        }
    });
    
    // Close menu when clicking on menu links
    const menuLinks = mobileMenu?.querySelectorAll('a[href]');
    if (menuLinks) {
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Close menu after a short delay to allow navigation
                setTimeout(() => {
                    closeMobileMenu();
                }, 100);
            });
        });
    }
    
    // Handle window resize
    window.addEventListener('resize', function() {
        // Close menu on desktop
        if (window.innerWidth >= 1024 && isMenuOpen) {
            closeMobileMenu();
        }

        // Recompute panel offset if open
        if (isMenuOpen) {
            const headerHeight = masthead ? masthead.getBoundingClientRect().bottom : 0;
            mobileMenu.style.top = headerHeight + 'px';
            mobileMenu.style.height = `calc(100dvh - ${headerHeight}px)`;
        }
    });
    
    // Prevent menu from opening on desktop
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function(e) {
            if (window.innerWidth >= 1024) {
                e.preventDefault();
                return false;
            }
        });
    }
});
