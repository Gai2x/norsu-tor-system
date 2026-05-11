<?php include __DIR__ . '/../../public/superadmin/includes/SuperAdminHeader.php'; ?>

<div class="p-4 sm:p-6 lg:p-10 space-y-8">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-blue-600 text-sm font-semibold uppercase tracking-[0.3em]">Students</p>
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900">Student Accounts</h1>
            <p class="text-slate-500 mt-2 max-w-2xl">Search and filter student accounts with real-time results.</p>
        </div>
    </div>

    <?php if (!empty($notice)): ?>
        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-700 font-semibold shadow-sm"><?php echo htmlspecialchars($notice); ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="rounded-3xl border border-rose-200 bg-rose-50 px-5 py-4 text-rose-700 shadow-sm space-y-1">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <section class="bg-white rounded-3xl shadow-lg overflow-hidden">
        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-xl font-semibold text-slate-900">Search & Filter</h2>
            <p class="text-sm text-slate-500 mt-1">Use search and filters to find specific students.</p>
        </div>
        
        <div class="p-6 space-y-6">
            <form class="space-y-4" id="student-search-form">
                <div class="flex flex-col gap-3 lg:flex-row">
                    <div class="flex-1">
                        <label for="superadmin-student-search" class="sr-only">Search students</label>
                        <input 
                            id="superadmin-student-search" 
                            type="search" 
                            placeholder="Search name, email, student ID, or course" 
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-100" 
                        />
                    </div>
                    <button type="button" onclick="studentSearch.reset()" class="inline-flex min-h-[44px] items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </button>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <label class="block">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Course</span>
                        <select id="student-course-filter" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-100">
                            <option value="">All Courses</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo htmlspecialchars($course); ?>"><?php echo htmlspecialchars($course); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="block">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Year Level</span>
                        <select id="student-year-filter" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-100">
                            <option value="">All Years</option>
                            <?php foreach ($yearLevels as $year): ?>
                                <option value="<?php echo htmlspecialchars($year); ?>"><?php echo htmlspecialchars($year); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="block">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Status</span>
                        <select id="student-status-filter" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-100">
                            <option value="">All Statuses</option>
                            <?php foreach ($statuses as $value => $label): ?>
                                <option value="<?php echo htmlspecialchars($value); ?>"><?php echo htmlspecialchars($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>
            </form>
        </div>
    </section>

    <section class="bg-white rounded-3xl shadow-lg overflow-hidden">
        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-semibold text-slate-900">Results</h2>
            <p class="text-sm text-slate-500 mt-1">Student directory with filtering and real-time search.</p>
        </div>
        <div id="students-container" class="grid gap-5 p-6 md:grid-cols-2 xl:grid-cols-3">
            <?php if (!empty($students)): ?>
                <?php foreach ($students as $student): ?>
                    <article class="group rounded-[2rem] border border-slate-200 bg-slate-50 p-6 shadow-sm transition hover:-translate-y-1 hover:border-slate-300 hover:bg-white">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.35em] text-slate-400">Student ID</p>
                                <p class="text-xl font-semibold text-slate-900 mt-2"><?php echo htmlspecialchars($student['student_id'] ?: 'N/A'); ?></p>
                            </div>
                            <button type="button" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700" data-student-edit="<?php echo (int) $student['id']; ?>" data-student-id="<?php echo (int) $student['id']; ?>" data-student-name="<?php echo htmlspecialchars($student['name']); ?>" data-student-email="<?php echo htmlspecialchars($student['email']); ?>" data-student-student-id="<?php echo htmlspecialchars($student['student_id']); ?>" data-student-course="<?php echo htmlspecialchars($student['course']); ?>">
                                <i class="fas fa-pen-to-square mr-2"></i>Edit
                            </button>
                        </div>
                        <div class="mt-6 space-y-4 text-sm text-slate-600">
                            <div class="grid gap-2">
                                <span class="text-xs uppercase tracking-[0.25em] text-slate-400">Name</span>
                                <p class="font-medium text-slate-900"><?php echo htmlspecialchars($student['name']); ?></p>
                            </div>
                            <div class="grid gap-2">
                                <span class="text-xs uppercase tracking-[0.25em] text-slate-400">Email</span>
                                <p><?php echo htmlspecialchars($student['email']); ?></p>
                            </div>
                            <div class="grid gap-2">
                                <span class="text-xs uppercase tracking-[0.25em] text-slate-400">Course</span>
                                <p><?php echo htmlspecialchars($student['course'] ?: 'N/A'); ?></p>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-10 text-center text-slate-500">No students found.</div>
            <?php endif; ?>
        </div>
    </section>
</div>

<script src="/Norsu_Tor/public/js/search-filter-manager.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const escapeHtml = function(value) {
        const div = document.createElement('div');
        div.textContent = value == null ? '' : String(value);
        return div.innerHTML;
    };

    const studentSearch = new SearchFilterManager({
        endpoint: '/Norsu_Tor/superadmin/ajax-search-students',
        container: document.getElementById('students-container'),
        searchInput: document.getElementById('superadmin-student-search'),
        filters: {
            course: 'student-course-filter',
            year_level: 'student-year-filter',
            status: 'student-status-filter',
        },
        pageSize: 12,
        itemTemplate: function(student) {
            const id = escapeHtml(student.id);
            const studentId = escapeHtml(student.student_id || 'N/A');
            const name = escapeHtml(student.name || 'N/A');
            const email = escapeHtml(student.email || 'N/A');
            const course = escapeHtml(student.course || 'N/A');

            return `
                <article class="group rounded-[2rem] border border-slate-200 bg-slate-50 p-6 shadow-sm transition hover:-translate-y-1 hover:border-slate-300 hover:bg-white">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-slate-400">Student ID</p>
                            <p class="text-xl font-semibold text-slate-900 mt-2">${studentId}</p>
                        </div>
                        <button type="button" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700" data-student-edit="${id}" data-student-id="${id}" data-student-name="${name}" data-student-email="${email}" data-student-student-id="${studentId}" data-student-course="${course}">
                            <i class="fas fa-pen-to-square mr-2"></i>Edit
                        </button>
                    </div>
                    <div class="mt-6 space-y-4 text-sm text-slate-600">
                        <div class="grid gap-2">
                            <span class="text-xs uppercase tracking-[0.25em] text-slate-400">Name</span>
                            <p class="font-medium text-slate-900">${name}</p>
                        </div>
                        <div class="grid gap-2">
                            <span class="text-xs uppercase tracking-[0.25em] text-slate-400">Email</span>
                            <p>${email}</p>
                        </div>
                        <div class="grid gap-2">
                            <span class="text-xs uppercase tracking-[0.25em] text-slate-400">Course</span>
                            <p>${course}</p>
                        </div>
                    </div>
                </article>
            `;
        }
    });

    // Make search instance global for reset button
    window.studentSearch = studentSearch;

    // Initial load
    studentSearch.search();
});
</script>

<?php include __DIR__ . '/../../public/superadmin/includes/SuperAdminFooter.php'; ?>
