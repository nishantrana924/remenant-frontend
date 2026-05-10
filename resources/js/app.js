import './bootstrap';
import 'unpoly';
import 'unpoly/unpoly.css';

// Unpoly configuration for icons
// This runs whenever a new fragment is inserted into the DOM
up.compiler('[data-lucide]', (element) => {
    if (window.lucide) {
        lucide.createIcons({
            root: element.parentElement,
            searchAround: element
        });
    }
});

// Global fallback for any missed icons on fragment insert
up.on('up:fragment:inserted', () => {
    if (window.lucide) lucide.createIcons();
});

// Faster navigation
up.link.config.followSelectors.push('a[href]');
up.form.config.submitSelectors.push('form');

// Disable default loading bar
up.network.config.progressBar = false;

// Ensure Alpine.js re-initializes on fragment updates
up.on('up:fragment:inserted', () => {
    if (window.Alpine) {
        // Alpine 3.x handles most mutations, but we can force a discovery if needed
    }
});

document.addEventListener('DOMContentLoaded', () => {
    // Initial icon creation
    if (window.lucide) lucide.createIcons();
});
