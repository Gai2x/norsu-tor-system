<?php
$notificationModalId = $notificationModalId ?? 'notificationModal';
$notificationBadgeCount = (int) ($notificationBadgeCount ?? 0);
$notificationEndpoint = $notificationEndpoint ?? '/Norsu_Tor/public/AjaxNotifications.php';
?>

<button
    type="button"
    class="relative text-gray-500 hover:text-blue-600 transition p-2 rounded-lg hover:bg-gray-100"
    data-notification-trigger
    data-notification-target="<?php echo htmlspecialchars($notificationModalId); ?>"
    data-notification-endpoint="<?php echo htmlspecialchars($notificationEndpoint); ?>"
    aria-label="Open notifications"
>
    <i class="fas fa-bell text-xl"></i>
    <?php if ($notificationBadgeCount > 0): ?>
        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center shadow-md"><?php echo min($notificationBadgeCount, 9); ?></span>
    <?php endif; ?>
</button>

<div id="<?php echo htmlspecialchars($notificationModalId); ?>" class="fixed inset-0 z-50 hidden bg-black/40 px-4 py-6">
    <div class="mx-auto flex min-h-full max-w-lg items-center justify-center">
        <section class="w-full overflow-hidden rounded-2xl bg-white shadow-2xl">
            <header class="flex items-center justify-between border-b px-5 py-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Notifications</h3>
                    <p class="text-sm text-gray-500">Recent account and request activity</p>
                </div>
                <button type="button" class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-700" data-notification-close aria-label="Close notifications">
                    <i class="fas fa-times"></i>
                </button>
            </header>
            <div class="max-h-[70vh] overflow-y-auto" data-notification-list>
                <div class="flex items-center justify-center py-12 text-sm text-gray-500">
                    <i class="fas fa-spinner fa-spin mr-2"></i>Loading notifications...
                </div>
            </div>
        </section>
    </div>
</div>

<script src="/Norsu_Tor/public/js/notification-modal.js"></script>
