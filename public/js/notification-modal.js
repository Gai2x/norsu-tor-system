(function () {
    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value == null ? '' : String(value);
        return div.innerHTML;
    }

    function formatTime(value) {
        if (!value) return 'Just now';
        const date = new Date(String(value).replace(' ', 'T'));
        if (Number.isNaN(date.getTime())) return 'Just now';
        return date.toLocaleString('en-US', {
            month: 'short',
            day: '2-digit',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit'
        });
    }

    function iconFor(type) {
        if (type === 'appointment') return 'fa-calendar-check';
        if (type === 'request') return 'fa-file-alt';
        if (type === 'warning') return 'fa-exclamation-circle';
        return 'fa-bell';
    }

    function renderList(list, items) {
        if (!items.length) {
            list.innerHTML = `
                <div class="p-8 text-center">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                    <p class="font-medium text-gray-600">No notifications yet</p>
                    <p class="mt-1 text-sm text-gray-400">Updates will appear here when there is activity.</p>
                </div>
            `;
            return;
        }

        list.innerHTML = items.map(item => {
            const unread = !item.is_read;
            const wrapper = unread ? 'bg-blue-50 border-blue-100' : 'bg-white border-gray-100';
            const dot = unread ? '<span class="mt-2 h-2.5 w-2.5 rounded-full bg-blue-600 flex-shrink-0"></span>' : '<span class="mt-2 h-2.5 w-2.5 rounded-full bg-gray-200 flex-shrink-0"></span>';

            return `
                <article class="flex gap-3 border-b ${wrapper} p-4">
                    <div class="mt-1 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-white text-blue-700 shadow-sm">
                        <i class="fas ${iconFor(item.type)}"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start gap-2">
                            <h4 class="font-semibold text-gray-800">${escapeHtml(item.title)}</h4>
                            ${dot}
                        </div>
                        <p class="mt-1 text-sm leading-5 text-gray-600">${escapeHtml(item.message)}</p>
                        <time class="mt-2 block text-xs text-gray-400">${escapeHtml(formatTime(item.created_at))}</time>
                    </div>
                </article>
            `;
        }).join('');
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-notification-trigger]').forEach(trigger => {
            const modal = document.getElementById(trigger.dataset.notificationTarget || 'notificationModal');
            if (!modal) return;

            const list = modal.querySelector('[data-notification-list]');
            const closeButtons = modal.querySelectorAll('[data-notification-close]');
            const endpoint = trigger.dataset.notificationEndpoint || '/Norsu_Tor/public/AjaxNotifications.php';

            const openModal = async function () {
                modal.classList.remove('hidden');
                if (list) {
                    list.innerHTML = `
                        <div class="flex items-center justify-center py-12 text-sm text-gray-500">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Loading notifications...
                        </div>
                    `;
                }

                try {
                    const response = await fetch(endpoint, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    });

                    if (!response.ok) {
                        throw new Error('Unable to load notifications.');
                    }

                    const data = await response.json();
                    renderList(list, data.notifications || []);
                } catch (error) {
                    list.innerHTML = `
                        <div class="p-8 text-center text-red-600">
                            <i class="fas fa-exclamation-circle text-3xl mb-3"></i>
                            <p class="font-medium">${escapeHtml(error.message)}</p>
                        </div>
                    `;
                }
            };

            trigger.addEventListener('click', openModal);

            closeButtons.forEach(button => {
                button.addEventListener('click', () => modal.classList.add('hidden'));
            });

            modal.addEventListener('click', event => {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    });
})();
