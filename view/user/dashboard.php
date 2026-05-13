<?php include __DIR__ . '/../../public/includes/user/Header.php'; ?>

<div class="p-4 sm:p-6 space-y-6 animate-fade-in-up">
    <div class="welcome-banner rounded-2xl p-5 sm:p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold mb-1 sm:mb-2">
                    <?php echo $welcomeMessage; ?>, <?php echo htmlspecialchars($firstName); ?>!
                </h1>
                <p class="text-blue-100 text-sm sm:text-base">Here's what's happening with your academic requests today.</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-chalkboard-user text-4xl sm:text-5xl opacity-20"></i>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/partials/statsCards.php'; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <?php include __DIR__ . '/partials/quickActions.php'; ?>
        <?php include __DIR__ . '/partials/recentActivity.php'; ?>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm font-medium">Upcoming Appointments</p>
                    <p class="text-2xl sm:text-3xl font-bold text-blue-700 mt-1"><?php echo $stats['upcoming_appointments']; ?></p>
                </div>
                <div class="bg-blue-600 w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-check text-white text-lg sm:text-xl"></i>
                </div>
            </div>
            <a href="Appointments.php" class="text-xs sm:text-sm text-blue-600 hover:text-blue-700 font-medium mt-3 inline-flex items-center gap-1 no-underline">
                View schedule <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm font-medium">Completion Rate</p>
                    <p class="text-2xl sm:text-3xl font-bold text-green-700 mt-1"><?php echo $completionData['rate']; ?>%</p>
                </div>
                <div class="bg-green-600 w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-white text-lg sm:text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3">
                <?php echo $completionData['completed']; ?> out of <?php echo $completionData['total']; ?> processed
                <span class="block text-gray-400 mt-1"><?php echo $completionData['message']; ?></span>
            </p>
        </div>

        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-4 sm:p-6 sm:col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm font-medium">Account Status</p>
                    <p class="text-xl sm:text-2xl font-bold text-purple-700 mt-1">Active</p>
                </div>
                <div class="bg-purple-600 w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center">
                    <i class="fas fa-shield-alt text-white text-lg sm:text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3 truncate">ID: <?php echo htmlspecialchars($user_info['student_id'] ?? 'N/A'); ?></p>
            <?php if (!empty($user_info['email'])): ?>
            <p class="text-xs text-gray-500 mt-1 truncate">Email: <?php echo htmlspecialchars($user_info['email']); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($dashboardData['upcoming_appointment_details'])): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 sm:px-6 py-4 border-b bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-calendar-check text-blue-600"></i>
                Upcoming Appointment Schedule
            </h3>
        </div>
        <div class="divide-y">
            <?php foreach ($dashboardData['upcoming_appointment_details'] as $appointment): ?>
                <div class="p-5 sm:p-6 grid grid-cols-1 md:grid-cols-5 gap-4 items-center">
                    <div class="md:col-span-2">
                        <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($appointment['service_type'] ?? 'N/A'); ?></p>
                        <p class="text-sm text-gray-500 mt-1"><?php echo htmlspecialchars($appointment['purpose'] ?? ''); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold">Date</p>
                        <p class="text-sm text-gray-700 mt-1"><?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold">Time / Office</p>
                        <p class="text-sm text-gray-700 mt-1"><?php echo date('g:i A', strtotime($appointment['appointment_time'])); ?></p>
                        <p class="text-xs text-gray-500"><?php echo htmlspecialchars($appointment['office'] ?? 'N/A'); ?></p>
                    </div>
                    <div>
                        <span class="<?php echo strtolower($appointment['status']) === 'approved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'; ?> inline-flex rounded-full px-3 py-1 text-xs font-semibold">
                            <?php echo ucfirst(strtolower($appointment['status'])); ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/../../public/includes/user/Footer.php'; ?>
