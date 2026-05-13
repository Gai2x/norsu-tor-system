<?php
if(!isset($pageTitle)) {
    $pageTitle = "NORSU Admin Panel";
}

if(!isset($stats)) {
    $stats = [
        'pending_requests' => 0,
        'pending_appointments' => 0
    ];
}

$currentPage = strtolower(pathinfo(basename($_SERVER['PHP_SELF']), PATHINFO_FILENAME));
$currentPageKey = strtolower(trim((string) ($currentPageKey ?? '')));
$currentPageKey = $currentPageKey !== '' ? $currentPageKey : $currentPage;

if (!function_exists('adminNavClasses')) {
    function adminNavClasses(string $target, array $aliases = []): string
    {
        global $currentPageKey;

        $pages = array_merge([$target], $aliases);
        return in_array($currentPageKey, $pages, true) ? 'bg-blue-700 text-white shadow-lg shadow-blue-950/20' : '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; overflow-x: hidden; }
        .stat-card { transition: all 0.3s ease; border: 1px solid #e2e8f0; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.02); }
        .icon-container { transition: all 0.3s ease; }
        .stat-card:hover .icon-container { transform: scale(1.1); }
        .quick-action-btn { transition: all 0.3s ease; }
        .quick-action-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .status-badge { padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.25rem; }
        .status-pending { background-color: #fef3c7; color: #d97706; }
        .status-approved { background-color: #d1fae5; color: #059669; }
        .status-rejected { background-color: #fee2e2; color: #dc2626; }
        .status-cancelled { background-color: #f3f4f6; color: #6b7280; }
        .status-completed { background-color: #e0e7ff; color: #4338ca; }
        .welcome-banner { background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out; }
        .sidebar { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: fixed; top: 0; left: 0; height: 100vh; z-index: 40; overflow-y: auto; overflow-x: hidden; scrollbar-width: thin; }
        .sidebar.collapsed { width: 80px !important; }
        .sidebar.collapsed .sidebar-text, .sidebar.collapsed .nav-text, .sidebar.collapsed .brand-text { display: none !important; }
        .sidebar.collapsed .sidebar-link { justify-content: center; padding: 0.75rem; }
        .sidebar.collapsed .sidebar-link i { margin-right: 0 !important; }
        .sidebar.collapsed .sidebar-logo { justify-content: center; }
        .sidebar.collapsed .badge-count { display: none; }
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: #1e3a8a; }
        .sidebar::-webkit-scrollbar-thumb { background: #3b82f6; border-radius: 4px; }
        .sidebar-link { transition: all 0.2s ease; display: flex; align-items: center; gap: 0.75rem; border-radius: 0.75rem; padding: 0.75rem 1rem; color: white; text-decoration: none; }
        .sidebar-link:hover { background-color: rgba(255, 255, 255, 0.1); transform: translateX(4px); }
        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }
        .badge-pulse { animation: pulse 2s infinite; }
        .main-content { transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1); min-height: 100vh; width: 100%; }
        .mobile-menu-btn { transition: all 0.2s ease; }
        .mobile-menu-btn:hover { transform: scale(1.05); }
        .sidebar-overlay { transition: opacity 0.3s ease; display: none; }
        .sidebar-overlay.active { display: block; }
        .toggle-sidebar-btn { transition: all 0.3s ease; cursor: pointer; }
        .toggle-sidebar-btn:hover { transform: scale(1.05); background-color: rgba(255, 255, 255, 0.2); }
        @media (max-width: 1024px) { .desktop-toggle { display: none; } }
        .message-slide { animation: slideDown 0.3s ease-out; }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        .admin-card { transition: all 0.3s ease; }
        .admin-card:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .flex { display: flex; }
        .min-h-screen { min-height: 100vh; }
        .w-64 { width: 256px; }
        .fixed { position: fixed; }
        .relative { position: relative; }
        .no-underline { text-decoration: none; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { text-align: left; padding: 0.75rem 1rem; background-color: #f9fafb; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb; }
        .data-table td { padding: 0.75rem 1rem; border-bottom: 1px solid #f3f4f6; }
        .data-table tr:hover { background-color: #f9fafb; }
    </style>
</head>
<body>

<div class="flex min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 bg-black bg-opacity-50 z-30"></div>
    <aside id="sidebar" class="sidebar w-64 bg-gradient-to-b from-blue-900 to-blue-800 text-white flex flex-col shadow-xl">
        <div class="p-5 border-b border-blue-700 flex items-center justify-between">
            <div class="sidebar-logo flex items-center gap-3">
                <div class="w-10 h-10 bg-yellow-400 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                    <img src="../img/logo.png" class="h-7" alt="NORSU Logo">
                </div>
                <div class="brand-text">
                    <h1 class="text-lg font-bold tracking-tight">NORSU Admin</h1>
                    <p class="text-xs text-blue-200">Management Panel</p>
                </div>
            </div>
            <button id="toggleSidebarBtn" class="desktop-toggle toggle-sidebar-btn flex items-center justify-center w-8 h-8 rounded-lg bg-blue-800 hover:bg-blue-700 transition-all duration-200 text-blue-200 hover:text-white shadow-md">
                <i id="toggleIcon" class="fas fa-chevron-left text-sm"></i>
            </button>
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="Dashboard.php" class="sidebar-link <?php echo adminNavClasses('dashboard'); ?>">
                <i class="fas fa-chart-line w-5 text-center"></i>
                <span class="nav-text flex-1">Dashboard</span>
            </a>
            <a href="Requests.php" class="sidebar-link <?php echo adminNavClasses('requests', ['requestview']); ?>">
                <i class="fas fa-file-alt w-5 text-center"></i>
                <span class="nav-text flex-1">All Requests</span>
                <?php if(isset($stats['pending_requests']) && $stats['pending_requests'] > 0): ?>
                <span class="badge-count bg-orange-400 text-blue-900 text-xs font-bold px-2 py-1 rounded-full"><?php echo $stats['pending_requests']; ?></span>
                <?php endif; ?>
            </a>
            <a href="Appointments.php" class="sidebar-link <?php echo adminNavClasses('appointments'); ?>">
                <i class="fas fa-calendar-alt w-5 text-center"></i>
                <span class="nav-text flex-1">Appointments</span>
                <?php if(isset($stats['pending_appointments']) && $stats['pending_appointments'] > 0): ?>
                <span class="badge-count bg-yellow-400 text-blue-900 text-xs font-bold px-2 py-1 rounded-full badge-pulse"><?php echo $stats['pending_appointments']; ?></span>
                <?php endif; ?>
            </a>
            <a href="Students.php" class="sidebar-link <?php echo adminNavClasses('students', ['studentview', 'studentedit']); ?>">
                <i class="fas fa-users w-5 text-center"></i>
                <span class="nav-text flex-1">Students</span>
            </a>
            <a href="Schedule.php" class="sidebar-link <?php echo adminNavClasses('schedule', ['addschedule']); ?>">
                <i class="fas fa-chart-bar w-5 text-center"></i>
                <span class="nav-text flex-1">Schedule</span>
            </a>
            <a href="profile.php" class="sidebar-link <?php echo adminNavClasses('profile'); ?>">
                <i class="fas fa-user-circle w-5 text-center"></i>
                <span class="nav-text flex-1">Profile</span>
            </a>
        </nav>

        <div class="p-4 border-t border-blue-700">
            <a href="../Logout.php" class="sidebar-link hover:bg-red-600 transition">
                <i class="fas fa-sign-out-alt w-5 text-center"></i>
                <span class="nav-text flex-1">Logout</span>
            </a>
        </div>
    </aside>

    <main id="mainContent" class="main-content flex-1 w-full">
        <div class="bg-white shadow-sm border-b border-gray-200 px-4 sm:px-6 py-3 flex justify-between items-center sticky top-0 z-20">
            <div class="flex items-center gap-3">
                <button id="mobileMenuBtn" class="lg:hidden mobile-menu-btn text-gray-600 hover:text-blue-700 text-2xl transition p-2 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-bars"></i>
                </button>
                <h2 class="text-base sm:text-lg font-semibold text-gray-700 hidden sm:block"><?php echo isset($pageSubtitle) ? $pageSubtitle : "Admin Management Panel"; ?></h2>
            </div>
            
            <div class="flex items-center gap-3 sm:gap-4">
                <?php
                $notificationModalId = 'adminNotificationModal';
                $notificationBadgeCount = ($stats['pending_requests'] ?? 0) + ($stats['pending_appointments'] ?? 0);
                include __DIR__ . '/../../includes/shared/NotificationModal.php';
                ?>

                <div class="flex items-center gap-2 sm:gap-3 cursor-pointer hover:bg-gray-50 p-1 sm:p-2 rounded-xl transition" onclick="window.location.href='profile.php'">
                    <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 w-9 h-9 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-blue-900 shadow-md flex-shrink-0">
                        <i class="fas fa-user-shield text-blue-900"></i>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-semibold text-gray-800"><?= htmlspecialchars($_SESSION['name'] ?? 'Admin'); ?></p>
                        <p class="text-xs text-gray-500">Administrator</p>
                    </div>
                </div>
            </div>
        </div>
