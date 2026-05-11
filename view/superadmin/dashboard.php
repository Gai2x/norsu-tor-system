<?php include __DIR__ . '/../../public/superadmin/includes/SuperAdminHeader.php'; ?>

<div class="p-4 sm:p-6 lg:p-10 space-y-8">
    <div class="welcome-banner rounded-3xl p-6 sm:p-8 text-white shadow-lg">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-blue-100 text-sm font-semibold uppercase tracking-wide">Super Admin</p>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold mt-2">System Dashboard</h2>
                <p class="text-blue-100 text-base sm:text-lg mt-3 max-w-3xl">View system totals, monitor pending work, and audit admin actions.</p>
            </div>
        </div>
    </div>

    <?php if (!empty($createUserNotice)): ?>
        <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700 font-semibold"><?php echo htmlspecialchars($createUserNotice); ?></div>
    <?php endif; ?>

    <?php if (!empty($createUserErrors)): ?>
        <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700 space-y-1">
            <?php foreach ($createUserErrors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6">
        <?php
        $cards = [
            ['label' => 'Total Users', 'value' => $systemStats['total_users'] ?? 0, 'icon' => 'fa-users', 'color' => 'bg-blue-500'],
            ['label' => 'Admins', 'value' => $systemStats['total_admins'] ?? 0, 'icon' => 'fa-user-shield', 'color' => 'bg-indigo-500'],
            ['label' => 'Pending Requests', 'value' => $systemStats['pending_requests'] ?? 0, 'icon' => 'fa-file-circle-exclamation', 'color' => 'bg-yellow-500'],
            ['label' => 'Appointments', 'value' => $systemStats['total_appointments'] ?? 0, 'icon' => 'fa-calendar-check', 'color' => 'bg-green-500'],
        ];
        ?>
        <?php foreach ($cards as $card): ?>
            <div class="stat-card bg-white rounded-3xl shadow p-5 sm:p-6 flex items-center justify-between gap-4">
                <div>
                    <p class="text-gray-500 text-sm sm:text-base"><?php echo htmlspecialchars($card['label']); ?></p>
                    <h3 class="text-3xl sm:text-4xl font-bold mt-3"><?php echo (int) $card['value']; ?></h3>
                </div>
                <div class="<?php echo $card['color']; ?> w-14 h-14 sm:w-16 sm:h-16 rounded-2xl flex items-center justify-center text-white text-2xl shrink-0">
                    <i class="fas <?php echo $card['icon']; ?>"></i>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <section class="xl:col-span-2 bg-white rounded-3xl shadow overflow-hidden">
            <div class="px-5 sm:px-8 py-6 border-b">
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-800">Students Overview</h3>
                <p class="text-sm text-gray-500 mt-1">Quick search and filter student accounts.</p>
            </div>
            <div class="p-6 space-y-6">
                <form class="flex flex-col gap-3 sm:flex-row sm:items-end">
                    <div class="flex-1">
                        <label for="dashboard-student-search" class="sr-only">Search students</label>
                        <input id="dashboard-student-search" type="search" placeholder="Search name, email, student ID, or course" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-100" />
                    </div>
                    <div class="flex gap-3">
                        <select id="dashboard-course-filter" class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-100">
                            <option value="">All Courses</option>
                            <option value="BSIT">BS Information Technology</option>
                            <option value="BSCS">BS Computer Science</option>
                            <option value="BSBA">BS Business Administration</option>
                            <option value="BSF">BS Fisheries</option>
                            <option value="BSC">BS Criminology</option>
                            <option value="BSOA">BS Office Administration</option>
                            <option value="BEED">BS Elementary Education</option>
                            <option value="BSED">BS Secondary Education</option>
                        </select>
                        <select id="dashboard-year-filter" class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-100">
                            <option value="">All Years</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                        </select>
                        <select id="dashboard-status-filter" class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-100">
                            <option value="">All Statuses</option>
                            <option value="has_id">Has student ID</option>
                            <option value="missing_id">Missing student ID</option>
                        </select>
                        <select id="dashboard-request-status-filter" class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-100">
                            <option value="">All Requests</option>
                            <option value="pending">Pending Requests</option>
                            <option value="approved">Approved Requests</option>
                            <option value="rejected">Rejected Requests</option>
                        </select>
                    </div>
                </form>
                <div id="dashboard-students-container" class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <!-- Students will be loaded here via AJAX -->
                </div>
            </div>
        </section>

        <section class="bg-white rounded-3xl shadow overflow-hidden">
            <div class="px-5 sm:px-8 py-6 border-b">
                <h3 class="text-2xl font-bold text-gray-800">Admin Logs</h3>
                <p class="text-sm text-gray-500 mt-1">Recent super-admin actions.</p>
            </div>
            <div class="divide-y">
                <?php if (!empty($recentActivity)): ?>
                    <?php foreach ($recentActivity as $log): ?>
                        <div class="p-5">
                            <p class="text-sm font-semibold text-gray-800"><?php echo htmlspecialchars($log['action']); ?></p>
                            <p class="text-sm text-gray-500 mt-1"><?php echo htmlspecialchars($log['details'] ?? ''); ?></p>
                            <p class="text-xs text-gray-400 mt-2"><?php echo date('M d, Y h:i A', strtotime($log['created_at'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-8 text-center text-gray-500">No admin actions logged yet.</div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<script src="/Norsu_Tor/public/superadmin/js/superadmin-search.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const search = new SuperAdminSearch({
        endpoint: '/Norsu_Tor/superadmin/ajax-search-students',
        container: document.getElementById('dashboard-students-container'),
        searchInput: document.getElementById('dashboard-student-search'),
        filters: {
            course: 'dashboard-course-filter',
            year_level: 'dashboard-year-filter',
            status: 'dashboard-status-filter',
            request_status: 'dashboard-request-status-filter',
        },
        pageSize: 6,
        itemTemplate: function(student) {
            return `
                <article class="rounded-2xl border border-slate-200 bg-slate-50 p-4 shadow-sm transition hover:-translate-y-1 hover:border-slate-300 hover:bg-white">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-slate-400">Student ID</p>
                            <p class="text-lg font-semibold text-slate-900 mt-1">${student.student_id || 'N/A'}</p>
                        </div>
                    </div>
                    <div class="mt-4 space-y-2 text-sm text-slate-600">
                        <p class="font-medium text-slate-900">${student.name}</p>
                        <p>${student.email}</p>
                        <p>${student.course || 'N/A'}</p>
                    </div>
                </article>
            `;
        },
        onResults: function(data) {
            // Optional callback
        }
    });

    // Initial load
    search.search();
});
</script>

<?php include __DIR__ . '/../../public/superadmin/includes/SuperAdminFooter.php'; ?>
