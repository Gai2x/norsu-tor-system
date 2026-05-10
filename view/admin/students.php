<?php include __DIR__ . '/../../public/admin/includes/AdminHeader.php'; ?>

<?php
$studentRows = [];
if ($students && mysqli_num_rows($students) > 0) {
    while ($row = mysqli_fetch_assoc($students)) {
        $studentRows[] = $row;
    }
}
?>

<div class="p-4 sm:p-6 lg:p-10 space-y-8">
    <div>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-800 mb-2">All Students</h2>
        <p class="text-gray-500 text-base sm:text-lg lg:text-2xl">Manage registered students in the system</p>
    </div>

    <div class="bg-white rounded-3xl shadow overflow-hidden">
        <div class="px-4 sm:px-6 lg:px-8 py-5 sm:py-6 border-b space-y-4">
            <div>
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-800">Student List</h3>
                <p class="text-sm text-gray-500 mt-1">Browse student records and open profile actions.</p>
            </div>
            <form id="admin-student-search-form" class="grid gap-3 lg:grid-cols-[minmax(0,1.5fr)_repeat(3,minmax(0,1fr))_auto]">
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input id="admin-student-search" type="search" placeholder="Search name, student ID, email, or course" class="w-full rounded-2xl border border-gray-300 py-3 pl-11 pr-4 text-sm min-h-[46px] focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <select id="admin-student-course-filter" class="rounded-2xl border border-gray-300 px-4 py-3 text-sm min-h-[46px] focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Courses</option>
                    <?php foreach (($courses ?? []) as $course): ?>
                        <option value="<?php echo htmlspecialchars($course); ?>"><?php echo htmlspecialchars($course); ?></option>
                    <?php endforeach; ?>
                </select>
                <select id="admin-student-year-filter" class="rounded-2xl border border-gray-300 px-4 py-3 text-sm min-h-[46px] focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Years</option>
                    <?php foreach (($yearLevels ?? []) as $year): ?>
                        <option value="<?php echo htmlspecialchars($year); ?>"><?php echo htmlspecialchars($year); ?></option>
                    <?php endforeach; ?>
                </select>
                <select id="admin-student-status-filter" class="rounded-2xl border border-gray-300 px-4 py-3 text-sm min-h-[46px] focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <?php foreach (($statuses ?? []) as $value => $label): ?>
                        <option value="<?php echo htmlspecialchars($value); ?>"><?php echo htmlspecialchars($label); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="button" id="admin-student-reset" class="inline-flex min-h-[46px] items-center justify-center rounded-2xl border border-gray-300 px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    <i class="fas fa-redo mr-2"></i>Reset
                </button>
            </form>
        </div>

        <div id="admin-students-mobile" class="md:hidden p-4 space-y-4 bg-gray-50/70">
            <?php if (!empty($studentRows)): ?>
                <?php foreach ($studentRows as $row): ?>
                    <article class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($row['name']); ?></h4>
                                <p class="text-sm text-gray-500">ID: <?php echo htmlspecialchars($row['student_id']); ?></p>
                            </div>
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">Active</span>
                        </div>

                        <dl class="grid grid-cols-1 gap-3 text-sm">
                            <div>
                                <dt class="text-gray-400">Email</dt>
                                <dd class="text-gray-700 font-medium break-all"><?php echo htmlspecialchars($row['email']); ?></dd>
                            </div>
                            <div>
                                <dt class="text-gray-400">Course</dt>
                                <dd class="text-gray-700 font-medium"><?php echo htmlspecialchars($row['course'] ?? 'N/A'); ?></dd>
                            </div>
                            <div>
                                <dt class="text-gray-400">Date Registered</dt>
                                <dd class="text-gray-700 font-medium"><?php echo date("M d, Y", strtotime($row['created_at'] ?? date("Y-m-d"))); ?></dd>
                            </div>
                        </dl>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a href="StudentView.php?id=<?php echo $row['id']; ?>" class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white no-underline transition hover:bg-blue-700">View</a>
                            <a href="StudentEdit.php?id=<?php echo $row['id']; ?>" class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-amber-500 px-4 py-3 text-sm font-semibold text-white no-underline transition hover:bg-amber-600">Edit</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="rounded-2xl border border-dashed border-gray-200 bg-white p-8 text-center text-gray-500">No students found.</div>
            <?php endif; ?>
        </div>

        <div class="hidden md:block overflow-x-auto">
            <table class="w-full min-w-[1024px] text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                    <tr>
                        <th class="px-6 lg:px-8 py-4">Student ID</th>
                        <th class="px-6 lg:px-8 py-4 sticky left-0 bg-gray-50">Student Name</th>
                        <th class="px-6 lg:px-8 py-4">Email</th>
                        <th class="px-6 lg:px-8 py-4">Course</th>
                        <th class="px-6 lg:px-8 py-4">Date Registered</th>
                        <th class="px-6 lg:px-8 py-4">Action</th>
                    </tr>
                </thead>
                <tbody id="admin-students-table-body">
                <?php if (!empty($studentRows)): ?>
                    <?php foreach ($studentRows as $row): ?>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 lg:px-8 py-5"><?php echo htmlspecialchars($row['student_id']); ?></td>
                        <td class="px-6 lg:px-8 py-5 font-semibold sticky left-0 bg-white"><?php echo htmlspecialchars($row['name']); ?></td>
                        <td class="px-6 lg:px-8 py-5"><?php echo htmlspecialchars($row['email']); ?></td>
                        <td class="px-6 lg:px-8 py-5"><?php echo htmlspecialchars($row['course'] ?? 'N/A'); ?></td>
                        <td class="px-6 lg:px-8 py-5"><?php echo date("M d, Y", strtotime($row['created_at'] ?? date("Y-m-d"))); ?></td>
                        <td class="px-6 lg:px-8 py-5">
                            <div class="flex flex-wrap gap-2">
                                <a href="StudentView.php?id=<?php echo $row['id']; ?>" class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white no-underline transition hover:bg-blue-700">View</a>
                                <a href="StudentEdit.php?id=<?php echo $row['id']; ?>" class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white no-underline transition hover:bg-amber-600">Edit</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-10 text-gray-500">No students found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="/Norsu_Tor/public/js/search-filter-manager.js"></script>
<?php $additionalScripts = ($additionalScripts ?? '') . <<<'HTML'
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('admin-student-search-form');
    const tableBody = document.getElementById('admin-students-table-body');
    const resetButton = document.getElementById('admin-student-reset');

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

    const statusBadge = function(student) {
        return student.student_id
            ? '<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">Complete</span>'
            : '<span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">Missing ID</span>';
    };

    const actions = function(id) {
        const safeId = encodeURIComponent(id);
        return `
            <div class="flex flex-wrap gap-2">
                <a href="StudentView.php?id=${safeId}" class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white no-underline transition hover:bg-blue-700">View</a>
                <a href="StudentEdit.php?id=${safeId}" class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white no-underline transition hover:bg-amber-600">Edit</a>
            </div>
        `;
    };

    const search = new SearchFilterManager({
        endpoint: '/Norsu_Tor/admin/ajax-search-students',
        container: document.getElementById('admin-students-mobile'),
        searchInput: document.getElementById('admin-student-search'),
        filters: {
            course: 'admin-student-course-filter',
            year_level: 'admin-student-year-filter',
            status: 'admin-student-status-filter'
        },
        pageSize: 15,
        itemTemplate: function(student) {
            const id = escapeHtml(student.id);
            const name = escapeHtml(student.name || 'N/A');
            const studentId = escapeHtml(student.student_id || 'N/A');
            const email = escapeHtml(student.email || 'N/A');
            const course = escapeHtml(student.course || 'N/A');

            return `
                <article class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 space-y-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold tracking-wide text-gray-400 uppercase">#STU-${id}</p>
                            <h4 class="text-lg font-semibold text-gray-800 mt-1">${name}</h4>
                            <p class="text-sm text-gray-500">${studentId}</p>
                        </div>
                        ${statusBadge(student)}
                    </div>
                    <dl class="grid grid-cols-1 gap-3 text-sm">
                        <div><dt class="text-gray-400">Email</dt><dd class="text-gray-700 font-medium break-all">${email}</dd></div>
                        <div><dt class="text-gray-400">Course</dt><dd class="text-gray-700 font-medium">${course}</dd></div>
                        <div><dt class="text-gray-400">Date Registered</dt><dd class="text-gray-700 font-medium">${formatDate(student.created_at)}</dd></div>
                    </dl>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">${actions(student.id)}</div>
                </article>
            `;
        },
        onResults: function(data) {
            const items = data.items || [];
            if (!tableBody) {
                return;
            }
            if (!items.length) {
                tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-10 text-gray-500">No students found.</td></tr>';
                return;
            }
            tableBody.innerHTML = items.map(function(student) {
                const id = escapeHtml(student.id);
                return `
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 lg:px-8 py-5 font-semibold sticky left-0 bg-white">#STU-${id}</td>
                        <td class="px-6 lg:px-8 py-5">${escapeHtml(student.name || 'N/A')}</td>
                        <td class="px-6 lg:px-8 py-5">${escapeHtml(student.student_id || 'N/A')}</td>
                        <td class="px-6 lg:px-8 py-5">${escapeHtml(student.email || 'N/A')}</td>
                        <td class="px-6 lg:px-8 py-5">${escapeHtml(student.course || 'N/A')}</td>
                        <td class="px-6 lg:px-8 py-5">${formatDate(student.created_at)}</td>
                        <td class="px-6 lg:px-8 py-5">${actions(student.id)}</td>
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
