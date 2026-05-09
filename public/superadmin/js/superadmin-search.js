class SuperAdminSearch {
    constructor(options) {
        this.endpoint = options.endpoint;
        this.container = options.container;
        this.searchInput = options.searchInput;
        this.filters = options.filters || {};
        this.pageSize = options.pageSize || 12;
        this.currentPage = 1;
        this.loadingTemplate = options.loadingTemplate || '<div class="flex justify-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div></div>';
        this.emptyTemplate = options.emptyTemplate || '<div class="col-span-full rounded-[2rem] border border-dashed border-slate-300 bg-slate-50 p-10 text-center text-slate-500">No results found.</div>';
        this.itemTemplate = options.itemTemplate;
        this.paginationTemplate = options.paginationTemplate;
        this.onResults = options.onResults || (() => {});

        this.debounceTimer = null;
        this.init();
    }

    init() {
        this.searchInput.addEventListener('input', this.debounceSearch.bind(this));
        Object.keys(this.filters).forEach(key => {
            const element = document.getElementById(this.filters[key]);
            if (element) {
                element.addEventListener('change', this.search.bind(this));
            }
        });
    }

    debounceSearch() {
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(() => this.search(), 300);
    }

    async search(page = 1) {
        this.currentPage = page;
        this.showLoading();

        const params = new URLSearchParams({
            search: this.searchInput.value.trim(),
            page: this.currentPage,
            pageSize: this.pageSize,
        });

        Object.keys(this.filters).forEach(key => {
            const element = document.getElementById(this.filters[key]);
            if (element && element.value) {
                params.append(key, element.value);
            }
        });

        try {
            const response = await fetch(`${this.endpoint}?${params}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();
            this.renderResults(data);
            this.onResults(data);
        } catch (error) {
            console.error('Search error:', error);
            this.showError();
        }
    }

    showLoading() {
        this.container.innerHTML = this.loadingTemplate;
    }

    showError() {
        this.container.innerHTML = '<div class="col-span-full rounded-[2rem] border border-red-200 bg-red-50 p-10 text-center text-red-700">An error occurred while searching.</div>';
    }

    renderResults(data) {
        if (!data.students || data.students.length === 0) {
            this.container.innerHTML = this.emptyTemplate;
            return;
        }

        let html = '';
        data.students.forEach(student => {
            html += this.itemTemplate(student);
        });

        if (data.pagination && data.pagination.totalPages > 1) {
            html += this.renderPagination(data.pagination);
        }

        this.container.innerHTML = html;
        this.attachPaginationEvents();
    }

    renderPagination(pagination) {
        let html = '<div class="flex flex-wrap items-center justify-center gap-2 border-t border-slate-200 bg-slate-50 px-6 py-5">';
        for (let page = 1; page <= pagination.totalPages; page++) {
            const activeClass = page === pagination.currentPage ? 'border-blue-700 bg-blue-700 text-white' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300';
            html += `<button class="inline-flex min-h-[44px] items-center justify-center rounded-2xl border px-4 py-2 text-sm font-semibold ${activeClass}" data-page="${page}">${page}</button>`;
        }
        html += '</div>';
        return html;
    }

    attachPaginationEvents() {
        this.container.querySelectorAll('[data-page]').forEach(button => {
            button.addEventListener('click', (e) => {
                const page = parseInt(e.target.dataset.page);
                this.search(page);
            });
        });
    }
}

// Export for use in other scripts
window.SuperAdminSearch = SuperAdminSearch;
