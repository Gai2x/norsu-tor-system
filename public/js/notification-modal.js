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

    function updateBadge(trigger, unreadCount) {
        const badge = trigger.querySelector('span.absolute');
        if (!badge) return;
        if (unreadCount > 0) {
            badge.textContent = Math.min(unreadCount, 9);
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
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
            const url = item.url ? item.url : '#';

            return `
                <a href="${escapeHtml(url)}" data-notification-id="${escapeHtml(item.id || '')}" class="notification-item block border-b ${wrapper} p-4 transition hover:bg-slate-50">
                    <div class="flex gap-3">
                        <div class="mt-1 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-white text-blue-700 shadow-sm">
                            <i class="fas ${iconFor(item.type)}"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start gap-2">
                                <h4 class="font-semibold text-gray-800 truncate">${escapeHtml(item.title)}</h4>
                                ${dot}
                            </div>
                            <p class="mt-1 text-sm leading-5 text-gray-600 truncate">${escapeHtml(item.message)}</p>
                            <time class="mt-2 block text-xs text-gray-400">${escapeHtml(formatTime(item.created_at))}</time>
                        </div>
                    </div>
                </a>
            `;
        }).join('');
    }

    async function fetchNotifications(endpoint) {
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

        return response.json();
    }

    async function markNotificationRead(endpoint, notificationId) {
        const formData = new FormData();
        formData.append('action', 'mark_read');
        formData.append('id', notificationId);

        const response = await fetch(endpoint, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error('Unable to mark notification as read.');
        }

        return response.json();
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-notification-trigger]').forEach(trigger => {
            const targetId = trigger.dataset.notificationTarget || 'notificationModal';
            const dropdown = document.getElementById(targetId);
            if (!dropdown) return;

            const list = dropdown.querySelector('[data-notification-list]');
            const closeButtons = dropdown.querySelectorAll('[data-notification-close]');
            const endpoint = trigger.dataset.notificationEndpoint || '/Norsu_Tor/public/AjaxNotifications.php';
            let isOpen = false;
            let latestNotifications = [];

            const openDropdown = async function () {
                dropdown.classList.remove('hidden');
                trigger.setAttribute('aria-expanded', 'true');
                isOpen = true;

                if (list) {
                    list.innerHTML = `
                        <div class="flex items-center justify-center py-12 text-sm text-gray-500">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Loading notifications...
                        </div>
                    `;
                }

                try {
                    const data = await fetchNotifications(endpoint);
                    latestNotifications = data.notifications || [];
                    renderList(list, latestNotifications);
                    updateBadge(trigger, data.unread || 0);
                } catch (error) {
                    list.innerHTML = `
                        <div class="p-8 text-center text-red-600">
                            <i class="fas fa-exclamation-circle text-3xl mb-3"></i>
                            <p class="font-medium">${escapeHtml(error.message)}</p>
                        </div>
                    `;
                }
            };

            const closeDropdown = function () {
                dropdown.classList.add('hidden');
                trigger.setAttribute('aria-expanded', 'false');
                isOpen = false;
            };

            trigger.addEventListener('click', async function (event) {
                event.stopPropagation();
                if (isOpen) {
                    closeDropdown();
                    return;
                }
                await openDropdown();
            });

            dropdown.addEventListener('click', function (event) {
                event.stopPropagation();
                const item = event.target.closest('.notification-item');
                if (!item) return;

                const notificationId = item.dataset.notificationId;
                const href = item.getAttribute('href');
                if (!notificationId || !href || href === '#') {
                    return;
                }

                event.preventDefault();
                markNotificationRead(endpoint, notificationId)
                    .then(data => {
                        item.classList.remove('bg-blue-50');
                        item.classList.add('bg-white');
                        if (data && typeof data.unread === 'number') {
                            updateBadge(trigger, data.unread);
                        }
                    })
                    .catch(() => {})
                    .finally(() => {
                        window.location.href = href;
                    });
            });

            closeButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    event.stopPropagation();
                    closeDropdown();
                });
            });

            document.addEventListener('click', function (event) {
                if (isOpen && !dropdown.contains(event.target) && event.target !== trigger) {
                    closeDropdown();
                }
            });
        });
    });
})();
