/**
 * Remenant Global AJAX System
 * Handles all interactive actions without page reloads.
 */

window.RemenantApp = {
    csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),

    init() {
        console.log('Remenant Global AJAX System Initialized');
        this.bindEvents();
    },

    bindEvents() {
        // Global Form Submission Interceptor
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form.dataset.ajax === 'true' || form.classList.contains('ajax-form')) {
                e.preventDefault();
                this.handleFormSubmit(form);
            }
        });

        // Global Button Click Interceptor (for buttons with data-action)
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-ajax-action]');
            if (btn) {
                e.preventDefault();
                this.handleActionClick(btn);
            }
        });
    },

    async handleFormSubmit(form) {
        const url = form.action;
        const method = form.method.toUpperCase();
        const formData = new FormData(form);
        const submitBtn = form.querySelector('[type="submit"]');

        this.setLoading(submitBtn, true);

        try {
            const response = await this.apiRequest(url, method, formData);
            this.handleSuccess(response, form);
        } catch (error) {
            this.handleError(error, form);
        } finally {
            this.setLoading(submitBtn, false);
        }
    },

    async handleActionClick(btn) {
        const url = btn.dataset.url || btn.href || btn.formAction;
        const method = btn.dataset.method || 'POST';
        const data = btn.dataset.payload ? JSON.parse(btn.dataset.payload) : {};

        this.setLoading(btn, true);

        try {
            const response = await this.apiRequest(url, method, data);
            this.handleSuccess(response);
        } catch (error) {
            this.handleError(error);
        } finally {
            this.setLoading(btn, false);
        }
    },

    async apiRequest(url, method, data = null) {
        const options = {
            method: method,
            headers: {
                'X-CSRF-TOKEN': this.csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        };

        if (data) {
            if (data instanceof FormData) {
                options.body = data;
            } else {
                options.headers['Content-Type'] = 'application/json';
                options.body = JSON.stringify(data);
            }
        }

        const response = await fetch(url, options);
        let result;
        
        try {
            result = await response.json();
        } catch (e) {
            // If response is not JSON, it might be an HTML error page
            console.error('Non-JSON response received:', await response.text());
            throw new Error('Server returned an invalid response. Please try again.');
        }

        if (!response.ok) {
            const error = new Error(result.message || 'Something went wrong');
            error.response = result;
            error.status = response.status;
            throw error;
        }

        return result;
    },

    handleSuccess(response, form = null) {
        if (response.message) {
            this.showToast('success', response.message);
        }

        if (response.redirect) {
            window.location.href = response.redirect;
        }

        if (response.reload) {
            window.location.reload();
        }

        // Custom event for specific page logic
        const event = new CustomEvent('ajax:success', { detail: { response, form } });
        document.dispatchEvent(event);

        // Optional: Update cart count globally if returned
        if (response.cart_count !== undefined) {
            this.updateCartCount(response.cart_count);
        }
    },

    handleError(error, form = null) {
        console.error('AJAX Error:', error);

        if (error.status === 422 && form) {
            this.displayValidationErrors(error.response.errors, form);
        } else {
            this.showToast('error', error.message || 'Internal Server Error');
        }

        const event = new CustomEvent('ajax:error', { detail: { error, form } });
        document.dispatchEvent(event);
    },

    displayValidationErrors(errors, form) {
        // Clear previous errors
        form.querySelectorAll('.error-message').forEach(el => el.remove());
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        Object.keys(errors).forEach(field => {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');
                const errorEl = document.createElement('p');
                errorEl.className = 'error-message text-red-500 text-xs mt-1 font-bold';
                errorEl.textContent = errors[field][0];
                input.parentNode.appendChild(errorEl);
            }
        });

        this.showToast('error', 'Please fix the errors in the form.');
    },

    showToast(type, message) {
        if (typeof window.showToast === 'function') {
            window.showToast(message, type);
        } else {
            // Fallback to console or alert if global showToast is missing
            console.log(`[${type}] ${message}`);
        }
    },

    setLoading(element, isLoading) {
        if (!element) return;

        if (isLoading) {
            element.disabled = true;
            element.dataset.originalHtml = element.innerHTML;
            element.innerHTML = `<svg class="animate-spin h-5 w-5 text-current inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...`;
        } else {
            element.disabled = false;
            if (element.dataset.originalHtml) {
                element.innerHTML = element.dataset.originalHtml;
            }
        }
    },

    updateCartCount(count) {
        const badges = document.querySelectorAll('.cart-count-badge');
        badges.forEach(badge => {
            badge.textContent = count;
            badge.classList.remove('hidden');
            if (count === 0) badge.classList.add('hidden');
        });
    }
};

// Auto-init on DOM load
document.addEventListener('DOMContentLoaded', () => window.RemenantApp.init());
