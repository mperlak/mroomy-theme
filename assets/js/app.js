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

    // Theme URI for swapping icons
    const themeUri = mobileMenu?.getAttribute('data-theme-uri') || '';

    function setMenuToggleIcon(open) {
        if (!mobileMenuToggle || !themeUri) return;
        const icon = open ? 'close.svg' : 'menu-hamburger.svg';
        mobileMenuToggle.innerHTML = `<img src="${themeUri}/assets/icons/${icon}" alt="" class="w-6 h-6" />`;
        mobileMenuToggle.setAttribute('aria-label', open ? 'Zamknij menu' : 'Menu');
        mobileMenuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    }
    
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
        setMenuToggleIcon(true);
        
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
        setMenuToggleIcon(false);
        
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
    
    // Close menu when clicking on menu links (but not parents with children on mobile)
    const menuLinks = mobileMenu?.querySelectorAll('a[href]');
    if (menuLinks) {
        menuLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                if (window.innerWidth < 1024) {
                    const li = link.closest('li');
                    if (li && li.classList.contains('menu-item-has-children')) {
                        // Let the submenu handler take over
                        return;
                    }
                }
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

    // ===========================
    // Mobile submenu interactions
    // ===========================
    const mobileNav = mobileMenu?.querySelector('nav[aria-label="Mobile navigation"]');
    const rootMenu = mobileNav?.querySelector(':scope > ul') || mobileNav?.querySelector('ul');

    // Create a dedicated container for the second-level view
    let submenuView = mobileNav?.querySelector('#mobile-submenu-view');
    if (mobileNav && !submenuView) {
        submenuView = document.createElement('div');
        submenuView.id = 'mobile-submenu-view';
        submenuView.className = 'hidden px-4 space-y-6';

        // Back link
        // Header row: back icon (24), centered title, right placeholder (24) for optical centering
        const headerRow = document.createElement('div');
        headerRow.className = 'flex items-center justify-between';

        const backBtn = document.createElement('button');
        backBtn.type = 'button';
        backBtn.className = 'icon-link size-6 shrink-0 text-primary';
        // Inline SVG from assets
        const themeUri = mobileMenu?.getAttribute('data-theme-uri') || '';
        backBtn.innerHTML = `<img src="${themeUri}/assets/icons/arrow-left.svg" alt="Wróć" class="w-6 h-6" />`;
        backBtn.addEventListener('click', function() {
            // Hide view and re-attach submenu back to its parent item
            const activeSubmenu = submenuView?.querySelector('ul.mobile-submenu');
            if (activeSubmenu && activeSubmenu.__ownerLi) {
                activeSubmenu.classList.add('hidden');
                activeSubmenu.__ownerLi.appendChild(activeSubmenu);
            }
            submenuView.classList.add('hidden');
            // Clear dynamic header
            const dynamicHeader = submenuView?.querySelector('.mobile-submenu-header');
            if (dynamicHeader) dynamicHeader.remove();
            const viewAll = submenuView?.querySelector('.mobile-submenu-viewall');
            if (viewAll) viewAll.remove();
            rootMenu?.classList.remove('hidden');
            mobileNav?.scrollTo({ top: 0, behavior: 'smooth' });
        });

        const titleEl = document.createElement('div');
        titleEl.className = 'font-nunito font-extrabold text-[24px] leading-[30px] text-[#222] text-nowrap';
        titleEl.innerHTML = '<span></span>';

        const rightGhost = document.createElement('div');
        rightGhost.className = 'opacity-[0.01] w-6 h-6';

        headerRow.appendChild(backBtn);
        headerRow.appendChild(titleEl);
        headerRow.appendChild(rightGhost);
        submenuView.appendChild(headerRow);
        mobileNav?.appendChild(submenuView);
    }

    function enterSubmenu(li) {
        const submenu = li.querySelector(':scope > ul.mobile-submenu');
        if (!submenu || !submenuView || !rootMenu) return;

        // Build dynamic header with parent title
        const parentTitle = submenu.getAttribute('data-parent-title') || li.querySelector(':scope > a')?.textContent?.trim() || '';
        const parentUrl = submenu.getAttribute('data-parent-url') || li.querySelector(':scope > a')?.getAttribute('href') || '#';

        // Set title text in header row
        const headerTitleSpan = submenuView.querySelector('div > div.font-nunito.font-extrabold span');
        if (headerTitleSpan) headerTitleSpan.textContent = parentTitle;

        // Add "Zobacz wszystkie ..." link under the heading
        if (parentUrl) {
            const viewAll = document.createElement('div');
            viewAll.className = 'mobile-submenu-viewall';
            const anchor = document.createElement('a');
            anchor.href = parentUrl;
            anchor.className = 'font-nunito text-body-2 text-neutral-text hover:text-primary inline-flex items-center gap-1';
            anchor.textContent = `Zobacz wszystkie projekty`;
            // add chevron-right icon
            const themeUri = mobileMenu?.getAttribute('data-theme-uri') || '';
            const icon = document.createElement('img');
            icon.src = `${themeUri}/assets/icons/chevron-right.svg`;
            icon.alt = '';
            icon.className = 'w-4 h-4';
            anchor.appendChild(icon);
            viewAll.appendChild(anchor);
            submenuView.appendChild(viewAll);
        }

        // Move submenu into the view and show
        submenu.classList.remove('hidden');
        submenu.__ownerLi = li; // remember owner to move back on exit
        submenuView.appendChild(submenu);
        rootMenu.classList.add('hidden');
        submenuView.classList.remove('hidden');
        mobileNav?.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Intercept clicks on root items with children
    if (rootMenu) {
        rootMenu.querySelectorAll(':scope > li.menu-item-has-children > a').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                // Only engage on small screens
                if (window.innerWidth >= 1024) return;
                e.preventDefault();
                const li = anchor.closest('li');
                if (li) enterSubmenu(li);
            });
        });
    }
});
