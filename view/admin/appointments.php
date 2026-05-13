<?php include __DIR__ . '/../../public/admin/includes/AdminHeader.php'; ?>

<div class="p-4 sm:p-6 lg:p-10 space-y-8">
    <div>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-800 mb-2">Appointments Management</h2>
        <p class="text-gray-500 text-base sm:text-lg lg:text-2xl">Manage student appointments and schedules</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white rounded-3xl shadow p-5 sm:p-6 flex items-center justify-between gap-4">
            <div>
                <p class="text-gray-500 text-sm sm:text-base lg:text-lg">Total Appointments</p>
                <h3 class="text-3xl sm:text-4xl font-bold mt-3"><?php echo $totalAppointments; ?></h3>
            </div>
            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-blue-500 rounded-2xl flex items-center justify-center text-white text-2xl shrink-0"><i class="fa-regular fa-calendar"></i></div>
        </div>

        <div class="bg-white rounded-3xl shadow p-5 sm:p-6 flex items-center justify-between gap-4">
            <div>
                <p class="text-gray-500 text-sm sm:text-base lg:text-lg">Pending</p>
                <h3 class="text-3xl sm:text-4xl font-bold mt-3"><?php echo $pendingAppointments; ?></h3>
            </div>
            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-yellow-500 rounded-2xl flex items-center justify-center text-white text-2xl shrink-0"><i class="fa-regular fa-clock"></i></div>
        </div>

        <div class="bg-white rounded-3xl shadow p-5 sm:p-6 flex items-center justify-between gap-4">
            <div>
                <p class="text-gray-500 text-sm sm:text-base lg:text-lg">Approved</p>
                <h3 class="text-3xl sm:text-4xl font-bold mt-3"><?php echo $approvedAppointments; ?></h3>
            </div>
            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-green-500 rounded-2xl flex items-center justify-center text-white text-2xl shrink-0"><i class="fa-solid fa-check"></i></div>
        </div>

        <div class="bg-white rounded-3xl shadow p-5 sm:p-6 flex items-center justify-between gap-4">
            <div>
                <p class="text-gray-500 text-sm sm:text-base lg:text-lg">Rejected</p>
                <h3 class="text-3xl sm:text-4xl font-bold mt-3"><?php echo $rejectedAppointments; ?></h3>
            </div>
            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-red-500 rounded-2xl flex items-center justify-center text-white text-2xl shrink-0"><i class="fa-solid fa-xmark"></i></div>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow overflow-hidden">
        <div class="px-4 sm:px-6 lg:px-8 py-5 sm:py-6 border-b space-y-4">
            <div>
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-800">All Appointments</h3>
                <p class="text-sm text-gray-500 mt-1">Review, approve, and reject appointment bookings.</p>
            </div>
            <form id="admin-appointment-search-form" class="grid gap-3 xl:grid-cols-[minmax(0,1.5fr)_auto_repeat(5,minmax(0,1fr))_auto]">
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input id="admin-appointment-search" type="search" placeholder="Search student, ID, email, course, type, or purpose" class="w-full rounded-2xl border border-gray-300 py-3 pl-11 pr-4 text-sm min-h-[46px] focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="button" id="admin-appointment-search-button" class="inline-flex min-h-[46px] items-center justify-center rounded-2xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-800">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                <select id="admin-appointment-status-filter" class="rounded-2xl border border-gray-300 px-4 py-3 text-sm min-h-[46px] focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <select id="admin-appointment-type-filter" class="rounded-2xl border border-gray-300 px-4 py-3 text-sm min-h-[46px] focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Types</option>
                    <?php foreach (($appointmentTypes ?? []) as $type): ?>
                        <option value="<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($type); ?></option>
                    <?php endforeach; ?>
                </select>
                <input id="admin-appointment-date-filter" type="date" class="rounded-2xl border border-gray-300 px-4 py-3 text-sm min-h-[46px] focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <select id="admin-appointment-course-filter" class="rounded-2xl border border-gray-300 px-4 py-3 text-sm min-h-[46px] focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Courses</option>
                    <?php foreach (($courses ?? []) as $course): ?>
                        <option value="<?php echo htmlspecialchars($course); ?>"><?php echo htmlspecialchars($course); ?></option>
                    <?php endforeach; ?>
                </select>
                <select id="admin-appointment-year-filter" class="rounded-2xl border border-gray-300 px-4 py-3 text-sm min-h-[46px] focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Years</option>
                    <?php foreach (($yearLevels ?? []) as $year): ?>
                        <option value="<?php echo htmlspecialchars($year); ?>"><?php echo htmlspecialchars($year); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="button" id="admin-appointment-reset" class="inline-flex min-h-[46px] items-center justify-center rounded-2xl border border-gray-300 px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    <i class="fas fa-redo mr-2"></i>Reset
                </button>
            </form>
        </div>

        <div id="admin-appointments-mobile" class="md:hidden p-4 space-y-4 bg-gray-50/70">
            <?php if (!empty($appointments)) : ?>
                <?php foreach ($appointments as $app) : ?>
                    <?php $status = strtolower($app['status'] ?? 'pending'); ?>
                    <article class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold tracking-wide text-gray-400 uppercase">APT-<?php echo str_pad($app['id'], 5, '0', STR_PAD_LEFT); ?></p>
                                <h4 class="text-lg font-semibold text-gray-800 mt-1"><?php echo htmlspecialchars($app['name']); ?></h4>
                                <p class="text-sm text-gray-500"><?php echo htmlspecialchars($app['student_id'] ?? 'N/A'); ?></p>
                            </div>
                            <span class="<?php echo $status == 'approved' ? 'bg-green-100 text-green-700' : ($status == 'rejected' ? 'bg-red-100 text-red-700' : ($status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700')); ?> px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                <?php echo ucfirst($app['status']); ?>
                            </span>
                        </div>

                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                            <div>
                                <dt class="text-gray-400">Date</dt>
                                <dd class="text-gray-700 font-medium"><?php echo date("M d, Y", strtotime($app['appointment_date'])); ?></dd>
                            </div>
                            <div>
                                <dt class="text-gray-400">Time</dt>
                                <dd class="text-gray-700 font-medium"><?php echo date("h:i A", strtotime($app['appointment_time'])); ?></dd>
                            </div>
                            <div>
                                <dt class="text-gray-400">Service</dt>
                                <dd class="text-gray-700 font-medium"><?php echo htmlspecialchars($app['service_type'] ?? $app['appointment_type'] ?? 'N/A'); ?></dd>
                            </div>
                            <div>
                                <dt class="text-gray-400">Office</dt>
                                <dd class="text-gray-700 font-medium"><?php echo htmlspecialchars($app['office'] ?? 'Unassigned'); ?></dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-gray-400">Reason</dt>
                                <dd class="text-gray-700"><?php echo htmlspecialchars($app['purpose']); ?></dd>
                            </div>
                        </dl>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <?php if ($status === 'pending'): ?>
                                <a href="../../controller/user/AppointmentController.php?approve=<?= $app['id']; ?>" onclick="return confirm('Approve this appointment and assign the best matching schedule?')" class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-green-600 px-4 py-3 text-sm font-semibold text-white no-underline transition hover:bg-green-700">Approve</a>
                                <a href="../../controller/user/AppointmentController.php?reject=<?= $app['id']; ?>" onclick="return confirm('Reject this appointment?')" class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-red-600 px-4 py-3 text-sm font-semibold text-white no-underline transition hover:bg-red-700">Reject</a>
                            <?php else: ?>
                                <span class="sm:col-span-2 text-sm text-gray-400">No action available</span>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="rounded-2xl border border-dashed border-gray-200 bg-white p-8 text-center text-gray-500">No appointments found.</div>
            <?php endif; ?>
        </div>

        <div class="hidden md:block overflow-x-auto">
            <table class="w-full min-w-[920px] text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                    <tr>
                        <th class="px-6 lg:px-8 py-4 sticky left-0 bg-gray-50">ID</th>
                        <th class="px-6 lg:px-8 py-4">Student Name</th>
                        <th class="px-6 lg:px-8 py-4">Student ID</th>
                        <th class="px-6 lg:px-8 py-4">Service</th>
                        <th class="px-6 lg:px-8 py-4">Date</th>
                        <th class="px-6 lg:px-8 py-4">Time</th>
                        <th class="px-6 lg:px-8 py-4">Office</th>
                        <th class="px-6 lg:px-8 py-4">Status</th>
                        <th class="px-6 lg:px-8 py-4">Reason</th>
                        <th class="px-6 lg:px-8 py-4">Action</th>
                    </tr>
                </thead>
                <tbody id="admin-appointments-table-body">
                <?php if (!empty($appointments)) : ?>
                    <?php foreach ($appointments as $app) : ?>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 lg:px-8 py-5 font-semibold sticky left-0 bg-white">APT-<?php echo str_pad($app['id'], 5, '0', STR_PAD_LEFT); ?></td>
                        <td class="px-6 lg:px-8 py-5"><?php echo htmlspecialchars($app['name']); ?></td>
                        <td class="px-6 lg:px-8 py-5"><?php echo htmlspecialchars($app['student_id'] ?? 'N/A'); ?></td>
                        <td class="px-6 lg:px-8 py-5"><?php echo htmlspecialchars($app['service_type'] ?? $app['appointment_type'] ?? 'N/A'); ?></td>
                        <td class="px-6 lg:px-8 py-5"><?php echo date("M d, Y", strtotime($app['appointment_date'])); ?></td>
                        <td class="px-6 lg:px-8 py-5"><?php echo date("h:i A", strtotime($app['appointment_time'])); ?></td>
                        <td class="px-6 lg:px-8 py-5"><?php echo htmlspecialchars($app['office'] ?? 'Unassigned'); ?></td>
                        <td class="px-6 lg:px-8 py-5">
                            <?php if (strtolower($app['status']) == "pending") : ?>
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-semibold">Pending</span>
                            <?php elseif (strtolower($app['status']) == "approved") : ?>
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">Approved</span>
                            <?php elseif (strtolower($app['status']) == "rejected") : ?>
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">Rejected</span>
                            <?php else : ?>
                                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-semibold"><?php echo ucfirst($app['status']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 lg:px-8 py-5 max-w-xs"><?php echo htmlspecialchars($app['purpose']); ?></td>
                        <td class="px-6 lg:px-8 py-5">
                            <div class="flex flex-wrap gap-2">
                                <?php if (strtolower($app['status'] ?? '') === 'pending'): ?>
                                    <a href="../../controller/user/AppointmentController.php?approve=<?= $app['id']; ?>" onclick="return confirm('Approve this appointment and assign the best matching schedule?')" class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white no-underline transition hover:bg-green-700">Approve</a>
                                    <a href="../../controller/user/AppointmentController.php?reject=<?= $app['id']; ?>" onclick="return confirm('Reject this appointment?')" class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white no-underline transition hover:bg-red-700">Reject</a>
                                <?php else: ?>
                                    <span class="text-sm text-gray-400">Processed</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="10" class="text-center py-10 text-gray-500">No appointments found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div id="admin-appointments-pagination" class="hidden md:block"></div>
    </div>
</div>

<script src="/Norsu_Tor/public/js/search-filter-manager.js"></script>
<?php $additionalScripts = ($additionalScripts ?? '') . <<<'HTML'
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('admin-appointment-search-form');
    const tableBody = document.getElementById('admin-appointments-table-body');
    const resetButton = document.getElementById('admin-appointment-reset');

    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
        });
    }

    const escapeHtml = function(value) {
        const div = document.createElement('div');
        div.textContent = value == null ? '' : String(value);
        return div.innerHTML;
    };

    const formatDate = function(value) {
        if (!value) {
            return 'N/A';
        }
        const date = new Date(String(value).replace(' ', 'T'));
        return Number.isNaN(date.getTime()) ? 'N/A' : date.toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
    };

    const formatTime = function(value) {
        if (!value) {
            return 'N/A';
        }
        const date = new Date('1970-01-01T' + value);
        return Number.isNaN(date.getTime()) ? value : date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
    };

    const statusBadge = function(statusValue, textSize = 'text-sm') {
        const status = String(statusValue || 'pending').toLowerCase();
        const classes = status === 'approved'
            ? 'bg-green-100 text-green-700'
            : status === 'rejected'
                ? 'bg-red-100 text-red-700'
                : status === 'pending'
                    ? 'bg-yellow-100 text-yellow-700'
                    : 'bg-gray-100 text-gray-700';
        return `<span class="${classes} px-3 py-1 rounded-full ${textSize} font-semibold">${escapeHtml(status.charAt(0).toUpperCase() + status.slice(1))}</span>`;
    };

    const actions = function(appointment) {
        const id = encodeURIComponent(appointment.id);
        if (String(appointment.status || '').toLowerCase() !== 'pending') {
            return '<span class="text-sm text-gray-400">Processed</span>';
        }
        return `
            <div class="flex flex-wrap gap-2">
                <a href="../../controller/user/AppointmentController.php?approve=${id}" onclick="return confirm('Approve this appointment and assign the best matching schedule?')" class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white no-underline transition hover:bg-green-700">Approve</a>
                <a href="../../controller/user/AppointmentController.php?reject=${id}" onclick="return confirm('Reject this appointment?')" class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white no-underline transition hover:bg-red-700">Reject</a>
            </div>
        `;
    };

    const search = new SearchFilterManager({
        endpoint: '/Norsu_Tor/admin/ajax-search-appointments',
        container: document.getElementById('admin-appointments-mobile'),
        paginationContainer: document.getElementById('admin-appointments-pagination'),
        searchInput: document.getElementById('admin-appointment-search'),
        searchButton: document.getElementById('admin-appointment-search-button'),
        filters: {
            status: 'admin-appointment-status-filter',
            appointment_type: 'admin-appointment-type-filter',
            date: 'admin-appointment-date-filter',
            course: 'admin-appointment-course-filter',
            year_level: 'admin-appointment-year-filter'
        },
        pageSize: 15,
        onLoading: function() {
            if (tableBody) {
                tableBody.innerHTML = '<tr><td colspan="10" class="text-center py-10 text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i>Loading...</td></tr>';
            }
        },
        itemTemplate: function(appointment) {
            const id = escapeHtml(appointment.id);
            const name = escapeHtml(appointment.name || 'N/A');
            const studentId = escapeHtml(appointment.student_id || 'N/A');
            const service = escapeHtml(appointment.service_type || appointment.appointment_type || 'N/A');
            const office = escapeHtml(appointment.office || 'Unassigned');
            const purpose = escapeHtml(appointment.purpose || '');

            return `
                <article class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 space-y-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold tracking-wide text-gray-400 uppercase">#APT-${id}</p>
                            <h4 class="text-lg font-semibold text-gray-800 mt-1">${name}</h4>
                            <p class="text-sm text-gray-500">${studentId}</p>
                        </div>
                        ${statusBadge(appointment.status, 'text-xs')}
                    </div>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div><dt class="text-gray-400">Date</dt><dd class="text-gray-700 font-medium">${formatDate(appointment.appointment_date)}</dd></div>
                        <div><dt class="text-gray-400">Time</dt><dd class="text-gray-700 font-medium">${formatTime(appointment.appointment_time)}</dd></div>
                        <div><dt class="text-gray-400">Service</dt><dd class="text-gray-700 font-medium">${service}</dd></div>
                        <div><dt class="text-gray-400">Office</dt><dd class="text-gray-700 font-medium">${office}</dd></div>
                        <div class="sm:col-span-2"><dt class="text-gray-400">Reason</dt><dd class="text-gray-700">${purpose}</dd></div>
                    </dl>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">${actions(appointment)}</div>
                </article>
            `;
        },
        onResults: function(data) {
            const items = data.items || [];
            if (!tableBody) {
                return;
            }
            if (!items.length) {
                tableBody.innerHTML = '<tr><td colspan="10" class="text-center py-10 text-gray-500">No appointments found.</td></tr>';
                return;
            }
            tableBody.innerHTML = items.map(function(appointment) {
                const id = escapeHtml(appointment.id);
                return `
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 lg:px-8 py-5 font-semibold sticky left-0 bg-white">#APT-${id}</td>
                        <td class="px-6 lg:px-8 py-5">${escapeHtml(appointment.name || 'N/A')}</td>
                        <td class="px-6 lg:px-8 py-5">${escapeHtml(appointment.student_id || 'N/A')}</td>
                        <td class="px-6 lg:px-8 py-5">${escapeHtml(appointment.service_type || appointment.appointment_type || 'N/A')}</td>
                        <td class="px-6 lg:px-8 py-5">${formatDate(appointment.appointment_date)}</td>
                        <td class="px-6 lg:px-8 py-5">${formatTime(appointment.appointment_time)}</td>
                        <td class="px-6 lg:px-8 py-5">${escapeHtml(appointment.office || 'Unassigned')}</td>
                        <td class="px-6 lg:px-8 py-5">${statusBadge(appointment.status)}</td>
                        <td class="px-6 lg:px-8 py-5 max-w-xs">${escapeHtml(appointment.purpose || '')}</td>
                        <td class="px-6 lg:px-8 py-5">${actions(appointment)}</td>
                    </tr>
                `;
            }).join('');
        }
    });

    if (resetButton) {
        resetButton.addEventListener('click', function() {
            search.reset();
        });
    }
});
</script>
HTML; ?>

<?php include __DIR__ . '/../../public/admin/includes/AdminFooter.php'; ?>
