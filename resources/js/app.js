import './bootstrap';

// Defer all Unpoly configuration until DOMContentLoaded,
// because `up` is loaded via CDN in the <head> — not bundled here.
// This prevents "up is not defined" errors from the compiled bundle.
document.addEventListener('DOMContentLoaded', () => {
    if (typeof window.up === 'undefined') return;

    // Reinitialize Lucide icons whenever Unpoly swaps a fragment
    up.on('up:fragment:inserted', () => {
        if (window.lucide && typeof lucide.createIcons === 'function') {
            lucide.createIcons();
        }
    });

    // Faster navigation — follow all standard links & forms via Unpoly
    up.link.config.followSelectors.push('a[href]');
    up.form.config.submitSelectors.push('form');

    // Disable Unpoly's built-in progress bar (we use NProgress instead)
    up.network.config.progressBar = false;
});
