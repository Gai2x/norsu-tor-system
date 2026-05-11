(function () {
    const defaultToastDuration = 5000;

    function ajaxFetch(url, options = {}) {
        const fetchOptions = {
            method: options.method || 'POST',
            headers: options.headers || {},
            body: options.body || null,
            credentials: 'same-origin',
        };

        return fetch(url, fetchOptions).then(async response => {
            const contentType = response.headers.get('Content-Type') || '';
            const isJson = contentType.includes('application/json');
            const body = isJson ? await response.json() : await response.text();

            if (!response.ok) {
                const error = new Error('Request failed');
                error.response = response;
                error.body = body;
                throw error;
            }

            return body;
        });
    }

    function createNotification(message, type = 'info', duration = defaultToastDuration) {
        const containerId = 'ajax-notification-container';
        let container = document.getElementById(containerId);
        if (!container) {
            container = document.createElement('div');
            container.id = containerId;
            container.className = 'fixed top-4 right-4 z-50 flex flex-col gap-3 items-end';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = [
            'max-w-sm',
            'rounded-3xl',
            'border',
            'p-4',
            'shadow-xl',
            'text-sm',
            'font-medium',
            'ring-1',
            'ring-slate-200',
            'transition-all',
            'duration-300',
            'ease-out',
            'opacity-0',
            'translate-y-2'
        ].join(' ');

        const palette = {
            success: ['bg-emerald-50', 'border-emerald-200', 'text-emerald-800'],
            error: ['bg-rose-50', 'border-rose-200', 'text-rose-800'],
            warning: ['bg-amber-50', 'border-amber-200', 'text-amber-800'],
            info: ['bg-slate-50', 'border-slate-200', 'text-slate-800'],
        };

        toast.classList.add(...(palette[type] || palette.info));
        toast.innerHTML = `<div class="flex items-center justify-between gap-3"><span class="break-words">${escapeHtml(message)}</span><button type="button" class="text-current opacity-70 hover:opacity-100">&times;</button></div>`;

        container.appendChild(toast);

        requestAnimationFrame(() => {
            toast.classList.remove('opacity-0', 'translate-y-2');
        });

        const closeBtn = toast.querySelector('button');
        closeBtn.addEventListener('click', () => removeToast(toast));

        setTimeout(() => removeToast(toast), duration);
    }

    function removeToast(toast) {
        if (!toast) return;
        toast.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => toast.remove(), 250);
    }

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = String(value);
        return div.innerHTML;
    }

    function setLoading(button, isLoading, label) {
        if (!button) return;
        if (isLoading) {
            const original = button.textContent.trim();
            button.dataset.__originalText = original;
            button.disabled = true;
            button.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i>${escapeHtml(label || original)}`;
        } else {
            button.disabled = false;
            button.innerHTML = button.dataset.__originalText ? escapeHtml(button.dataset.__originalText) : button.textContent;
            delete button.dataset.__originalText;
        }
    }

    function bindForm(form, options = {}) {
        if (!form || !options.url) return;

        form.addEventListener('submit', async function (event) {
            if (form.dataset.disableAjax === 'true') return;
            event.preventDefault();

            const submitButton = form.querySelector('[type="submit"]');
            const loadingText = submitButton?.dataset.loadingText || 'Saving...';
            setLoading(submitButton, true, loadingText);

            try {
                const formData = new FormData(form);
                const response = await ajaxFetch(options.url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (options.onSuccess && typeof options.onSuccess === 'function') {
                    options.onSuccess(response);
                }
            } catch (error) {
                if (options.onError && typeof options.onError === 'function') {
                    options.onError(error);
                } else {
                    createNotification(error?.message || 'Unable to save changes.', 'error');
                }
            } finally {
                setLoading(submitButton, false);
            }
        });
    }

    function bindModalForm(modalSelector, form, options = {}) {
        const modal = document.querySelector(modalSelector);
        if (!modal || !form) return;

        bindForm(form, {
            url: options.url,
            onSuccess: function (response) {
                if (options.onSuccess) {
                    options.onSuccess(response);
                }
                if (response.success && options.closeOnSuccess) {
                    modal.classList.add('hidden');
                }
            },
            onError: options.onError,
        });
    }

    window.AjaxUtils = {
        ajaxFetch,
        createNotification,
        setLoading,
        bindForm,
        bindModalForm,
    };
})();
