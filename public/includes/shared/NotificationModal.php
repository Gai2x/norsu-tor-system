<?php
$notificationModalId = $notificationModalId ?? 'notificationModal';
$notificationBadgeCount = (int) ($notificationBadgeCount ?? 0);
$notificationEndpoint = $notificationEndpoint ?? '/Norsu_Tor/public/AjaxNotifications.php';
$notificationBasePath = $notificationBasePath ?? '';
?>

<div class="relative">
    <button
        type="button"
        class="relative text-gray-500 hover:text-blue-600 transition p-2 rounded-lg hover:bg-gray-100"
        data-notification-trigger
        data-notification-target="<?php echo htmlspecialchars($notificationModalId); ?>"
        data-notification-endpoint="<?php echo htmlspecialchars($notificationEndpoint); ?>"
        data-notification-base-path="<?php echo htmlspecialchars($notificationBasePath); ?>"
        aria-haspopup="true"
        aria-expanded="false"
        aria-label="Open notifications"
    >
        <i class="fas fa-bell text-xl"></i>
        <?php if ($notificationBadgeCount > 0): ?>
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center shadow-md"><?php echo min($notificationBadgeCount, 9); ?></span>
        <?php endif; ?>
    </button>

    <div id="<?php echo htmlspecialchars($notificationModalId); ?>" class="notification-dropdown hidden absolute right-0 top-full z-50 mt-3 w-screen max-w-sm rounded-3xl border border-slate-200 bg-white shadow-2xl ring-1 ring-black/5">
        <div class="flex items-center justify-between border-b px-4 py-3">
            <div>
                <h3 class="text-base font-semibold text-gray-900">Notifications</h3>
                <p class="text-xs text-gray-500">Latest updates appear here.</p>
            </div>
            <button type="button" class="text-gray-400 hover:text-gray-700 rounded-lg p-2" data-notification-close aria-label="Close notifications">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="max-h-[66vh] min-h-[10rem] overflow-y-auto" data-notification-list>
            <div class="flex items-center justify-center py-12 text-sm text-gray-500">
                <i class="fas fa-spinner fa-spin mr-2"></i>Loading notifications...
            </div>
        </div>
        <div class="border-t px-4 py-3 text-xs text-gray-400">Click a notification to view details.</div>
    </div>
</div>

<script src="/Norsu_Tor/public/js/notification-modal.js"></script>
