<?php include __DIR__ . '/../../public/admin/includes/AdminHeader.php'; ?>

<!-- CONTENT -->
<div class="p-4 sm:p-6 lg:p-10">
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
            <table class="w-full min-w-[980px] text-left">
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
                <tbody>
                <?php if (isset($requests) && mysqli_num_rows($requests) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($requests)): ?>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-8 py-5 font-semibold">#REQ-<?php echo $row['id']; ?></td>
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

</div>

<?php include __DIR__ . '/../../public/admin/includes/AdminFooter.php'; ?>
