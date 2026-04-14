import './bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    // Dropdown functionality
    function initializeDropdowns() {
        document.querySelectorAll('[data-dropdown]').forEach(dropdown => {
            // Skip if already initialized
            if (dropdown._initialized) return;
            dropdown._initialized = true;
            
            const trigger = dropdown.querySelector('[data-dropdown-trigger]');
            const menu = dropdown.querySelector('[data-dropdown-menu]');
            
            if (!trigger || !menu) return;
            
            let isOpen = false;
            
            function toggleDropdown() {
                isOpen = !isOpen;
                if (isOpen) {
                    menu.classList.remove('hidden');
                    menu.classList.add('block');
                    setTimeout(() => {
                        menu.style.opacity = '1';
                        menu.style.transform = 'scale(1)';
                    }, 10);
                } else {
                    menu.style.opacity = '0';
                    menu.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        menu.classList.remove('block');
                        menu.classList.add('hidden');
                    }, 200);
                }
            }
            
            function closeDropdown() {
                if (isOpen) {
                    isOpen = false;
                    menu.style.opacity = '0';
                    menu.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        menu.classList.remove('block');
                        menu.classList.add('hidden');
                    }, 200);
                }
            }
            
            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleDropdown();
            });
            
            // Close when clicking outside
            document.addEventListener('click', (e) => {
                if (!dropdown.contains(e.target)) {
                    closeDropdown();
                }
            });
            
            // Close when clicking inside menu
            menu.addEventListener('click', (e) => {
                if (e.target.tagName === 'A') {
                    closeDropdown();
                }
            });
        });
    }
    
    // Mobile navigation toggle
    const mobileNavButton = document.querySelector('[data-mobile-nav-toggle]');
    const mobileNavMenu = document.querySelector('[data-mobile-nav-menu]');
    
    if (mobileNavButton && mobileNavMenu) {
        const openIcon = mobileNavButton.querySelector('.mobile-menu-open-icon');
        const closeIcon = mobileNavButton.querySelector('.mobile-menu-close-icon');
        
        mobileNavButton.addEventListener('click', () => {
            const isHidden = mobileNavMenu.classList.contains('hidden');
            if (isHidden) {
                mobileNavMenu.classList.remove('hidden');
                mobileNavMenu.classList.add('block');
                if (openIcon) openIcon.classList.add('hidden');
                if (closeIcon) closeIcon.classList.remove('hidden');
            } else {
                mobileNavMenu.classList.remove('block');
                mobileNavMenu.classList.add('hidden');
                if (openIcon) openIcon.classList.remove('hidden');
                if (closeIcon) closeIcon.classList.add('hidden');
            }
        });
    }
    
    // Modal functionality
    function initializeModals() {
        document.querySelectorAll('[data-modal]').forEach(modal => {
            const modalId = modal.getAttribute('data-modal');
            const openButtons = document.querySelectorAll(`[data-open-modal="${modalId}"]`);
            const closeButtons = modal.querySelectorAll('[data-close-modal]');
            const overlay = modal.querySelector('[data-modal-overlay]');
            
            // Check if modal should be shown initially
            if (!modal.classList.contains('hidden')) {
                document.body.classList.add('overflow-hidden');
            }
            
            function openModal() {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
            
            function closeModal() {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
            
            openButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    openModal();
                });
            });
            
            closeButtons.forEach(button => {
                button.addEventListener('click', closeModal);
            });
            
            if (overlay) {
                overlay.addEventListener('click', closeModal);
            }
            
            // Store functions for global access
            modal._openModal = openModal;
            modal._closeModal = closeModal;
        });
        
        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('[data-modal]').forEach(modal => {
                    if (!modal.classList.contains('hidden') && modal._closeModal) {
                        modal._closeModal();
                    }
                });
            }
        });
        
        // Global modal event listeners
        window.dispatchOpenModal = function(modalId) {
            const modal = document.querySelector(`[data-modal="${modalId}"]`);
            if (modal && modal._openModal) {
                modal._openModal();
            }
        };
        
        window.dispatchCloseModal = function() {
            document.querySelectorAll('[data-modal]').forEach(modal => {
                if (!modal.classList.contains('hidden') && modal._closeModal) {
                    modal._closeModal();
                }
            });
        };
    }
    
    // Auto-hide success messages
    document.querySelectorAll('[data-auto-hide]').forEach(element => {
        const delay = parseInt(element.getAttribute('data-auto-hide')) || 2000;
        setTimeout(() => {
            element.style.transition = 'opacity 0.3s ease';
            element.style.opacity = '0';
            setTimeout(() => {
                element.classList.add('hidden');
            }, 300);
        }, delay);
    });
    
    // Initialize components
    initializeDropdowns();
    initializeModals();
    
    // Re-initialize after dynamic content is added
    const observer = new MutationObserver(() => {
        initializeDropdowns();
        initializeModals();
    });
    
    observer.observe(document.body, { childList: true, subtree: true });
});
