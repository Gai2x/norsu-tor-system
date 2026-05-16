<?php include __DIR__ . '/../../public/admin/includes/AdminHeader.php'; ?>

<!-- CONTENT -->
<div class="p-4 sm:p-6 lg:p-10 space-y-8">
    <div class="mb-8 rounded-3xl bg-white p-6 shadow sm:p-8">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-800 mb-2">Admin Dashboard</h2>
        <p class="text-gray-500 text-base sm:text-lg lg:text-2xl">
            Manage student requests and appointments with a cleaner, more focused queue view.
        </p>
    </div>

    <?php include __DIR__ . '/partials/statsCards.php'; ?>

    <div class="bg-white rounded-3xl shadow overflow-hidden">
        <div class="px-4 sm:px-6 lg:px-10 py-5 sm:py-6 lg:py-8 border-b flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <div>
                <h3 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800">Recent Request Queue</h3>
                <p class="mt-1 text-sm text-gray-500">A quick snapshot of incoming requests.</p>
            </div>
            <a href="Requests.php" class="inline-flex min-h-[44px] items-center justify-center rounded-2xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white no-underline transition hover:bg-blue-800">
                Open All Requests
            </a>
        </div>

        <div class="overflow-x-auto">
            <table id="requests-queue-table" class="w-full min-w-[980px] text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                    <tr>
                        <th class="px-8 py-5">Reference No.</th>
                        <th class="px-8 py-5">Student Name</th>
                        <th class="px-8 py-5">Student ID</th>
                        <th class="px-8 py-5">Service</th>
                        <th class="px-8 py-5">Date Submitted</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5">Action</th>
                    </tr>
                </thead>
                <tbody id="requests-queue-body">
                <?php $requestCount = 0; if (isset($requests) && mysqli_num_rows($requests) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($requests) and $requestCount < 3): $requestCount++; ?>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-8 py-5 font-semibold">REQ-<?php echo str_pad($row['id'], 5, '0', STR_PAD_LEFT); ?></td>
                        <td class="px-8 py-5"><?php echo htmlspecialchars($row['name']); ?></td>
                        <td class="px-8 py-5"><?php echo htmlspecialchars($row['student_id']); ?></td>
                        <td class="px-8 py-5"><?php echo htmlspecialchars($row['service_type']); ?></td>
                        <td class="px-8 py-5"><?php echo date("M d, Y", strtotime($row['created_at'])); ?></td>
                        <td class="px-8 py-5">
                            <?php if ($row['status'] == "pending"): ?>
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-semibold">Pending</span>
                            <?php elseif ($row['status'] == "approved"): ?>
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">Approved</span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">Rejected</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-5">
                            <a href="RequestView.php?id=<?php echo $row['id']; ?>" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition no-underline">
                                <i class="fas fa-eye"></i>View
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-10 text-gray-500">No requests found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-3xl shadow overflow-hidden">
            <div class="px-4 sm:px-6 lg:px-10 py-5 sm:py-6 border-b flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <div>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-800">Recent Appointments</h3>
                    <p class="mt-1 text-sm text-gray-500">Latest 3 scheduled appointments.</p>
                </div>
                <a href="Appointments.php" class="inline-flex min-h-[44px] items-center justify-center rounded-2xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white no-underline transition hover:bg-blue-800">
                    View All
                </a>
            </div>
            <div class="overflow-x-auto">
                <table id="appointments-table" class="w-full min-w-[600px] text-left text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Student</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody id="appointments-table-body">
                    <?php if (isset($appointments) && mysqli_num_rows($appointments) > 0): ?>
                        <?php while ($apt = mysqli_fetch_assoc($appointments)): ?>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-6 py-4 font-semibold">APT-<?php echo str_pad($apt['id'], 5, '0', STR_PAD_LEFT); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($apt['name']); ?></td>
                            <td class="px-6 py-4"><?php echo date("M d, Y", strtotime($apt['appointment_date'])); ?></td>
                            <td class="px-6 py-4">
                                <?php $status = strtolower($apt['status'] ?? 'pending'); ?>
                                <?php if ($status === 'pending'): ?>
                                    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-semibold">Pending</span>
                                <?php elseif ($status === 'approved'): ?>
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-semibold">Approved</span>
                                <?php else: ?>
                                    <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-semibold">Rejected</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-500">No appointments found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow overflow-hidden">
            <div class="px-4 sm:px-6 lg:px-10 py-5 sm:py-6 border-b flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <div>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-800">Recent Students</h3>
                    <p class="mt-1 text-sm text-gray-500">Latest 3 registered students.</p>
                </div>
                <a href="Students.php" class="inline-flex min-h-[44px] items-center justify-center rounded-2xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white no-underline transition hover:bg-blue-800">
                    View All
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[600px] text-left text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">Student ID</th>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Course</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($students) && mysqli_num_rows($students) > 0): ?>
                        <?php while ($stu = mysqli_fetch_assoc($students)): ?>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-6 py-4 font-semibold"><?php echo htmlspecialchars($stu['student_id']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($stu['name']); ?></td>
                            <td class="px-6 py-4 truncate max-w-[150px]"><?php echo htmlspecialchars($stu['email']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($stu['course'] ?? 'N/A'); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-500">No students found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if (!empty($monthlyTrends)): ?>
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 sm:p-6 lg:p-8 overflow-hidden">
        <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-chart-line text-blue-500"></i>
                    Request Trends
                </h3>
                <p class="text-sm text-gray-500 mt-1">Regular and one-time request volume for the last 6 months.</p>
            </div>
            <a href="Requests.php" class="inline-flex min-h-[44px] items-center justify-center rounded-2xl border border-blue-200 px-5 py-3 text-sm font-semibold text-blue-700 no-underline transition hover:bg-blue-50">
                View Requests
            </a>
        </div>

        <div class="w-full overflow-x-auto">
            <div class="min-w-[420px] sm:min-w-0">
                <div class="flex h-56 items-end gap-3 sm:gap-4">
                    <?php foreach ($monthlyTrends as $trend): ?>
                        <?php $barHeight = min(((int) ($trend['count'] ?? 0)) * 20, 170); ?>
                        <div class="flex min-w-[56px] flex-1 flex-col items-center">
                            <div class="group relative flex h-44 w-full items-end rounded-t-xl bg-blue-100">
                                <div class="w-full rounded-t-xl bg-blue-600 transition-all duration-300 hover:bg-blue-700" style="height: <?php echo max($barHeight, 8); ?>px"></div>
                                <div class="absolute -top-12 left-1/2 z-10 -translate-x-1/2 rounded-lg bg-gray-800 px-2 py-1 text-center text-xs text-white opacity-0 shadow transition group-hover:opacity-100 whitespace-nowrap">
                                    <?php echo (int) ($trend['count'] ?? 0); ?> requests<br>
                                    <?php echo (int) ($trend['approved'] ?? 0); ?> approved
                                </div>
                            </div>
                            <p class="mt-2 text-center text-xs text-gray-500"><?php echo date('M Y', strtotime(($trend['month'] ?? date('Y-m')) . '-01')); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const AUTO_REFRESH_INTERVAL = 7000; // 7 seconds
    let lastRefreshTime = new Date();
    let refreshTimer = null;

    /**
     * Format appointment ID with padding
     */
    const formatAppointmentId = function(id) {
        return 'APT-' + String(id).padStart(5, '0');
    };

    /**
     * Format request ID with padding
     */
    const formatRequestId = function(id) {
        return 'REQ-' + String(id).padStart(5, '0');
    };

    /**
     * Format date to "M dd, Y" format
     */
    const formatDate = function(dateString) {
        try {
            const date = new Date(dateString);
            const options = { month: 'short', day: '2-digit', year: 'numeric' };
            return date.toLocaleDateString('en-US', options);
        } catch (e) {
            return 'N/A';
        }
    };

    /**
     * Get status badge HTML
     */
    const getStatusBadge = function(status) {
        const statusLower = (status || 'pending').toLowerCase();
        let classes = 'px-3 py-1 rounded-full text-sm font-semibold ';
        
        if (statusLower === 'approved') {
            classes += 'bg-green-100 text-green-700';
        } else if (statusLower === 'rejected') {
            classes += 'bg-red-100 text-red-700';
        } else if (statusLower === 'pending') {
            classes += 'bg-yellow-100 text-yellow-700';
        } else {
            classes += 'bg-gray-100 text-gray-700';
        }
        
        return '<span class="' + classes + '">' + (statusLower.charAt(0).toUpperCase() + statusLower.slice(1)) + '</span>';
    };

    /**
     * Update requests table
     */
    const updateRequestsTable = function(requests) {
        const tbody = document.getElementById('requests-queue-body');
        if (!tbody) return;

        if (!requests || requests.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-10 text-gray-500">No requests found.</td></tr>';
            return;
        }

        let html = '';
        let count = 0;
        for (let i = 0; i < requests.length && count < 3; i++) {
            const req = requests[i];
            html += `
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-8 py-5 font-semibold">${formatRequestId(req.id)}</td>
                    <td class="px-8 py-5">${escapeHtml(req.name || 'N/A')}</td>
                    <td class="px-8 py-5">${escapeHtml(req.student_id || 'N/A')}</td>
                    <td class="px-8 py-5">${escapeHtml(req.service_type || 'N/A')}</td>
                    <td class="px-8 py-5">${formatDate(req.created_at)}</td>
                    <td class="px-8 py-5">${getStatusBadge(req.status)}</td>
                    <td class="px-8 py-5">
                        <a href="RequestView.php?id=${req.id}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition no-underline">
                            <i class="fas fa-eye"></i>View
                        </a>
                    </td>
                </tr>
            `;
            count++;
        }
        tbody.innerHTML = html;
    };

    /**
     * Update appointments table
     */
    const updateAppointmentsTable = function(appointments) {
        const tbody = document.getElementById('appointments-table-body');
        if (!tbody) return;

        if (!appointments || appointments.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center py-6 text-gray-500">No appointments found.</td></tr>';
            return;
        }

        let html = '';
        for (let apt of appointments) {
            html += `
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-6 py-4 font-semibold">${formatAppointmentId(apt.id)}</td>
                    <td class="px-6 py-4">${escapeHtml(apt.name || 'N/A')}</td>
                    <td class="px-6 py-4">${formatDate(apt.appointment_date)}</td>
                    <td class="px-6 py-4">${getStatusBadge(apt.status)}</td>
                </tr>
            `;
        }
        tbody.innerHTML = html;
    };

    /**
     * Escape HTML to prevent XSS
     */
    const escapeHtml = function(text) {
        const div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    };

    /**
     * Fetch fresh dashboard data
     */
    const refreshDashboard = async function() {
        try {
            const response = await fetch('/Norsu_Tor/admin/ajax-dashboard-refresh?lastTimestamp=' + encodeURIComponent(lastRefreshTime.toISOString()), {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                console.error('Dashboard refresh failed:', response.status);
                return;
            }

            const data = await response.json();

            if (data.success) {
                // Update tables
                if (data.requests && data.requests.length > 0) {
                    updateRequestsTable(data.requests);
                }
                
                if (data.appointments && data.appointments.length > 0) {
                    updateAppointmentsTable(data.appointments);
                }

                // Update stats cards if they exist
                if (data.counts) {
                    updateStatsCards(data.counts);
                }

                lastRefreshTime = new Date();
            }
        } catch (error) {
            console.error('Error refreshing dashboard:', error);
        }
    };

    /**
     * Update stats cards (if applicable)
     */
    const updateStatsCards = function(counts) {
        // You can add stats card updates here if needed
        // For now, we'll just update what's displayed on page load
    };

    /**
     * Start auto-refresh
     */
    const startAutoRefresh = function() {
        if (refreshTimer) clearInterval(refreshTimer);
        
        // Refresh immediately first
        refreshDashboard();
        
        // Then refresh at intervals
        refreshTimer = setInterval(refreshDashboard, AUTO_REFRESH_INTERVAL);
    };

    /**
     * Stop auto-refresh
     */
    const stopAutoRefresh = function() {
        if (refreshTimer) {
            clearInterval(refreshTimer);
            refreshTimer = null;
        }
    };

    // Start auto-refresh when page loads
    startAutoRefresh();

    // Stop refresh if user leaves the page
    window.addEventListener('beforeunload', stopAutoRefresh);
    
    // Pause refresh when page is hidden, resume when visible
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopAutoRefresh();
        } else {
            startAutoRefresh();
        }
    });
});
</script>

</div>

<?php include __DIR__ . '/../../public/admin/includes/AdminFooter.php'; ?>
