$(document).ready(function() {
    const searchInputs = $('[data-search-input]');
    let debounceTimer;

    searchInputs.on('input', function() {
        const input = $(this);
        const query = input.val().trim();
        const form = input.closest('[data-search-form]');
        const suggestionsContainer = form.find('[data-search-suggestions]');

        clearTimeout(debounceTimer);

        if (query.length < 2) {
            suggestionsContainer.addClass('hidden').html('');
            return;
        }

        debounceTimer = setTimeout(() => {
            fetchSuggestions(query, suggestionsContainer);
        }, 300);
    });

    // Hide suggestions when clicking outside or pressing Escape
    $(document).on('click', function(e) {
        if (!$(e.target).closest('[data-search-form]').length) {
            $('[data-search-suggestions]').addClass('hidden');
        }
    });

    $(document).on('click', '[data-search-suggestions] a', function() {
        $('[data-search-suggestions]').addClass('hidden');
    });

    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            $('[data-search-suggestions]').addClass('hidden');
        }
    });

    function fetchSuggestions(query, container) {
        $.ajax({
            url: '/products/search-suggestions',
            method: 'GET',
            data: { query: query },
            success: function(products) {
                if (products.length > 0) {
                    renderSuggestions(products, container, query);
                    container.removeClass('hidden');
                } else {
                    container.addClass('hidden').html('');
                }
            },
            error: function() {
                container.addClass('hidden').html('');
            }
        });
    }

    function highlightTerm(text, query) {
        if (!query) return text;
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<span class="text-gray-900 font-black">$1</span>');
    }

    function renderSuggestions(products, container, query) {
        let html = '<div class="py-2">';
        
        products.forEach(product => {
            const price = new Intl.NumberFormat('en-IN', {
                style: 'currency',
                currency: 'INR',
                maximumFractionDigits: 0
            }).format(product.price);

            const imagePath = product.image_url || '/images/placeholder.jpg';
            const highlightedTitle = highlightTerm(product.title, query);
            const highlightedTagline = highlightTerm(product.tagline || '', query);

            html += `
                <a href="/product/${product.slug}" class="flex items-center gap-4 px-4 py-3 hover:bg-gray-50 transition border-b border-gray-100 last:border-0 group" up-follow>
                    <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100 border border-gray-100">
                        <img src="${imagePath}" alt="${product.title}" class="h-full w-full object-contain group-hover:scale-110 transition duration-300">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 group-hover:text-[color:var(--primary)] mb-0.5 transition">${highlightedTagline}</p>
                        <h4 class="text-sm font-medium text-gray-500 group-hover:text-gray-900 truncate transition">${highlightedTitle}</h4>
                        <p class="text-xs font-black text-gray-400 mt-0.5">${price}</p>
                    </div>
                    <div class="text-gray-300 group-hover:text-[color:var(--primary)] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                    </div>
                </a>
            `;
        });

        // Add a "Show all results" link
        const currentQuery = container.closest('[data-search-form]').find('[data-search-input]').val();
        html += `
            <a href="/products?search=${encodeURIComponent(currentQuery)}" class="block px-4 py-3 text-center text-xs font-black uppercase tracking-widest text-gray-500 hover:bg-gray-50 hover:text-[color:var(--primary)] transition" up-follow>
                View all results for "${currentQuery}"
            </a>
        `;

        html += '</div>';
        container.html(html);
    }
});
