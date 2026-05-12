/**
 * SearchFilterManager
 * Reusable AJAX search and filter system for all dashboard pages
 * Handles: search, filters, AJAX calls, loading states, pagination, empty states
 */
class SearchFilterManager {
    constructor(options = {}) {
        // Required options
        this.endpoint = options.endpoint || null;
        this.container = options.container || null;

        // Input selectors
        this.searchInput = options.searchInput || null;
        this.filters = options.filters || {};

        // Configuration
        this.pageSize = options.pageSize || 15;
        this.debounceDelay = options.debounceDelay || 300;
        this.itemTemplate = options.itemTemplate || this.defaultTemplate;
        this.paginationTemplate = options.paginationTemplate || null;
        this.onResults = options.onResults || null;
        this.onError = options.onError || null;

        // State
        this.currentPage = 1;
        this.totalItems = 0;
        this.isLoading = false;
        this.debounceTimer = null;

        if (!this.endpoint || !this.container) {
            console.error('SearchFilterManager: Missing required options (endpoint, container)');
            return;
        }

        this.init();
    }

    init() {
        // Bind search input
        if (this.searchInput) {
            this.searchInput.addEventListener('input', () => this.debounceSearch());
        }

        // Bind filter selects
        Object.values(this.filters).forEach(filterId => {
            const element = document.getElementById(filterId);
            if (element) {
                element.addEventListener('change', () => this.search());
            }
        });
    }

    debounceSearch() {
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(() => this.search(), this.debounceDelay);
    }

    async search(page = 1) {
        this.currentPage = page;

        if (this.isLoading) return;
        this.isLoading = true;
        this.showLoading();

        try {
            const filters = this.getFilters();
            const params = new URLSearchParams({
                search: filters.search || '',
                page: page,
                pageSize: this.pageSize,
                ...Object.fromEntries(
                    Object.entries(filters)
                        .filter(([key, value]) => key !== 'search' && value)
                        .map(([key, value]) => [key, value])
                )
            });

            const response = await fetch(`${this.endpoint}?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const data = await response.json();

            const items = data.items || data.students || [];
            const total = data.total ?? data.pagination?.totalItems ?? items.length;

            this.totalItems = total;
            this.renderResults(items);

            if (this.onResults && typeof this.onResults === 'function') {
                this.onResults(data);
            }
        } catch (error) {
            console.error('Search error:', error);
            this.showError(error.message);

            if (this.onError && typeof this.onError === 'function') {
                this.onError(error);
            }
        } finally {
            this.isLoading = false;
        }
    }

    getFilters() {
        const filters = {};

        // Get search input
        if (this.searchInput) {
            filters.search = this.searchInput.value.trim();
        }

        // Get all filter values
        for (const [key, filterId] of Object.entries(this.filters)) {
            const element = document.getElementById(filterId);
            if (element) {
                const value = element.value.trim();
                if (value) {
                    filters[key] = value;
                }
            }
        }

        return filters;
    }

    renderResults(items) {
        if (!items || items.length === 0) {
            this.showEmpty();
            return;
        }

        let html = items.map(item => this.itemTemplate(item)).join('');
        html += this.renderPagination();
        this.container.innerHTML = html;

        // Re-initialize any event listeners if needed
        this.attachPaginationEvents();
        this.initItemListeners();
    }

    renderPagination() {
        const totalPages = this.getTotalPages();
        if (totalPages <= 1) {
            return '';
        }

        if (this.paginationTemplate && typeof this.paginationTemplate === 'function') {
            return this.paginationTemplate({
                currentPage: this.currentPage,
                totalPages,
                pageSize: this.pageSize,
                totalItems: this.totalItems
            });
        }

        let html = '<div class="col-span-full flex flex-wrap items-center justify-center gap-2 border-t border-slate-200 bg-slate-50 px-4 py-4">';
        for (let page = 1; page <= totalPages; page++) {
            const activeClass = page === this.currentPage
                ? 'border-blue-700 bg-blue-700 text-white'
                : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300';
            html += `<button type="button" class="inline-flex min-h-[40px] items-center justify-center rounded-xl border px-4 py-2 text-sm font-semibold ${activeClass}" data-search-page="${page}">${page}</button>`;
        }
        html += '</div>';
        return html;
    }

    attachPaginationEvents() {
        this.container.querySelectorAll('[data-search-page]').forEach(button => {
            button.addEventListener('click', () => {
                const page = parseInt(button.dataset.searchPage, 10);
                this.goToPage(page);
            });
        });
    }

    showLoading() {
        this.container.innerHTML = `
            <div class="col-span-full flex items-center justify-center py-12">
                <div class="flex flex-col items-center gap-3">
                    <div class="h-8 w-8 animate-spin rounded-full border-4 border-gray-300 border-t-blue-600"></div>
                    <p class="text-sm text-gray-500">Loading...</p>
                </div>
            </div>
        `;
    }

    showEmpty() {
        this.container.innerHTML = `
            <div class="col-span-full rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-10 text-center">
                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 font-medium">No results found</p>
                <p class="text-sm text-gray-400 mt-1">Try adjusting your search or filters</p>
            </div>
        `;
    }

    showError(message) {
        this.container.innerHTML = `
            <div class="col-span-full rounded-2xl border border-red-200 bg-red-50 p-6 text-center">
                <i class="fas fa-exclamation-circle text-2xl text-red-600 mb-3"></i>
                <p class="text-red-700 font-medium">Error loading results</p>
                <p class="text-sm text-red-600 mt-1">${message}</p>
            </div>
        `;
    }

    defaultTemplate(item) {
        return `
            <article class="rounded-2xl border border-slate-200 bg-slate-50 p-4 shadow-sm hover:shadow-md transition">
                <h3 class="font-semibold text-gray-800">${item.name || item.title || 'Item'}</h3>
                <p class="text-sm text-gray-500 mt-1">${item.description || ''}</p>
            </article>
        `;
    }

    initItemListeners() {
        // Override this in subclasses if needed
    }

    // Reset search and filters
    reset() {
        if (this.searchInput) this.searchInput.value = '';
        Object.values(this.filters).forEach(filterId => {
            const element = document.getElementById(filterId);
            if (element) element.value = '';
        });
        this.currentPage = 1;
        this.search();
    }

    // Go to specific page
    goToPage(page) {
        if (page < 1 || page > this.getTotalPages()) return;
        this.search(page);
    }

    // Get pagination info
    getTotalPages() {
        return Math.ceil(this.totalItems / this.pageSize);
    }

    getCurrentPage() {
        return this.currentPage;
    }

    getTotalItems() {
        return this.totalItems;
    }
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SearchFilterManager;
}
