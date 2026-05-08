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
        <div class="px-4 sm:px-6 lg:px-8 py-5 sm:py-6 border-b flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
            <div>
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-800">Student List</h3>
                <p class="text-sm text-gray-500 mt-1">Browse student records and open profile actions.</p>
            </div>
            <input type="text" placeholder="Search students..." class="border border-gray-300 rounded-2xl px-4 py-3 w-full lg:w-80 min-h-[44px] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="md:hidden p-4 space-y-4 bg-gray-50/70">
            <?php if (!empty($studentRows)): ?>
                <?php foreach ($studentRows as $row): ?>
                    <article class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold tracking-wide text-gray-400 uppercase">#STU-<?php echo $row['id']; ?></p>
                                <h4 class="text-lg font-semibold text-gray-800 mt-1"><?php echo htmlspecialchars($row['name']); ?></h4>
                                <p class="text-sm text-gray-500"><?php echo htmlspecialchars($row['student_id']); ?></p>
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
                        <th class="px-6 lg:px-8 py-4 sticky left-0 bg-gray-50">ID</th>
                        <th class="px-6 lg:px-8 py-4">Student Name</th>
                        <th class="px-6 lg:px-8 py-4">Student ID</th>
                        <th class="px-6 lg:px-8 py-4">Email</th>
                        <th class="px-6 lg:px-8 py-4">Course</th>
                        <th class="px-6 lg:px-8 py-4">Date Registered</th>
                        <th class="px-6 lg:px-8 py-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($studentRows)): ?>
                    <?php foreach ($studentRows as $row): ?>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 lg:px-8 py-5 font-semibold sticky left-0 bg-white">#STU-<?php echo $row['id']; ?></td>
                        <td class="px-6 lg:px-8 py-5"><?php echo htmlspecialchars($row['name']); ?></td>
                        <td class="px-6 lg:px-8 py-5"><?php echo htmlspecialchars($row['student_id']); ?></td>
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

<?php include __DIR__ . '/../../public/admin/includes/AdminFooter.php'; ?>
