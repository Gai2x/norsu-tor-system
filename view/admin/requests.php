<?php include __DIR__ . '/../../public/admin/includes/AdminHeader.php'; ?>

<div class="p-4 sm:p-6 lg:p-10 space-y-8">
    <div>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-800 mb-2">All Requests</h2>
        <p class="text-gray-500 text-base sm:text-lg lg:text-2xl">Manage student requests efficiently</p>
    </div>

    <div class="bg-white rounded-3xl shadow overflow-hidden">
        <div class="px-4 sm:px-6 lg:px-8 py-5 sm:py-6 border-b flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
            <div>
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-800">Request List</h3>
                <p class="text-sm text-gray-500 mt-1">Regular and one-time requests in one queue.</p>
            </div>
            <form method="GET" class="flex w-full flex-col gap-3 sm:flex-row lg:w-auto" id="requestSearchForm">
                <div class="relative w-full lg:w-80">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input
                        id="requestSearchInput"
                        name="search"
                        type="search"
                        value="<?php echo htmlspecialchars($search ?? ''); ?>"
                        placeholder="Search student, type, or status"
                        class="w-full rounded-2xl border border-gray-300 py-3 pl-11 pr-4 text-sm min-h-[46px] focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="inline-flex min-h-[46px] items-center justify-center rounded-2xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-800">Search</button>
                    <?php if (!empty($search)): ?>
                        <a href="Requests.php" class="inline-flex min-h-[46px] items-center justify-center rounded-2xl border border-gray-300 px-5 py-3 text-sm font-semibold text-gray-700 no-underline transition hover:bg-gray-50">Clear</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="border-b bg-gray-50 px-4 py-3 text-sm text-gray-500 sm:px-6 lg:px-8">
            Showing <?php echo count($allRequests); ?> <?php echo count($allRequests) === 1 ? 'request' : 'requests'; ?><?php echo !empty($search) ? ' for "' . htmlspecialchars($search) . '"' : ''; ?>.
        </div>

        <div class="md:hidden p-4 space-y-4 bg-gray-50/70">
            <?php if (!empty($allRequests)): ?>
                <?php foreach ($allRequests as $row): ?>
                    <?php $isOneTime = ($row['type'] ?? 'regular') === 'one_time'; ?>
                    <?php $status = $row['status'] ?? 'pending'; ?>
                    <article class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold tracking-wide text-gray-400 uppercase">#REQ-<?php echo $row['id']; ?></p>
                                <h4 class="text-lg font-semibold text-gray-800 mt-1"><?php echo htmlspecialchars($row['name'] ?? $row['fullname'] ?? 'N/A'); ?></h4>
                                <p class="text-sm text-gray-500"><?php echo htmlspecialchars($row['student_id'] ?? 'N/A'); ?></p>
                            </div>
                            <span class="<?php echo $status == 'approved' ? 'bg-green-100 text-green-700' : ($status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700'); ?> px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                <?php echo ucfirst($status); ?>
                            </span>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <span class="<?php echo $isOneTime ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'; ?> px-3 py-1 rounded-full text-xs font-semibold">
                                <?php echo $isOneTime ? 'One-Time Request' : 'Regular Request'; ?>
                            </span>
                        </div>

                        <dl class="grid grid-cols-1 gap-3 text-sm">
                            <div>
                                <dt class="text-gray-400">Service</dt>
                                <dd class="text-gray-700 font-medium"><?php echo htmlspecialchars($row['service_type'] ?? 'N/A'); ?></dd>
                            </div>
                            <div>
                                <dt class="text-gray-400">Date</dt>
                                <dd class="text-gray-700 font-medium"><?php echo date("M d, Y", strtotime($row['created_at'] ?? 'now')); ?></dd>
                            </div>
                        </dl>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <?php if ($isOneTime): ?>
                                <a href="Requests.php?approve=<?php echo (int) $row['id']; ?>&type=one_time" onclick="return confirm('Approve this one-time request?')" class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-green-600 px-4 py-3 text-sm font-semibold text-white no-underline transition hover:bg-green-700">
                                    <i class="fas fa-check mr-2"></i>Approve
                                </a>
                                <a href="Requests.php?reject=<?php echo (int) $row['id']; ?>&type=one_time" onclick="return confirm('Reject this one-time request?')" class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-red-600 px-4 py-3 text-sm font-semibold text-white no-underline transition hover:bg-red-700">
                                    <i class="fas fa-times mr-2"></i>Reject
                                </a>
                            <?php else: ?>
                                <a href="RequestView.php?id=<?php echo (int) $row['id']; ?>" class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white no-underline transition hover:bg-blue-700 sm:col-span-2">
                                    <i class="fas fa-eye mr-2"></i>View Request
                                </a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="rounded-2xl border border-dashed border-gray-200 bg-white p-8 text-center text-gray-500">No requests found.</div>
            <?php endif; ?>
        </div>

        <div class="hidden md:block overflow-x-auto">
            <table class="w-full min-w-[1100px] text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                    <tr>
                        <th class="px-6 lg:px-8 py-4 sticky left-0 bg-gray-50">Reference No.</th>
                        <th class="px-6 lg:px-8 py-4">Type</th>
                        <th class="px-6 lg:px-8 py-4">Student</th>
                        <th class="px-6 lg:px-8 py-4">Student ID</th>
                        <th class="px-6 lg:px-8 py-4">Service</th>
                        <th class="px-6 lg:px-8 py-4">Date</th>
                        <th class="px-6 lg:px-8 py-4">Status</th>
                        <th class="px-6 lg:px-8 py-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($allRequests)): ?>
                    <?php foreach ($allRequests as $row): ?>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 lg:px-8 py-5 font-semibold sticky left-0 bg-white">#REQ-<?php echo $row['id']; ?></td>
                        <td class="px-6 lg:px-8 py-5">
                            <?php if (($row['type'] ?? 'regular') === 'one_time'): ?>
                                <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">One-Time Request</span>
                            <?php else: ?>
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">Regular Request</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 lg:px-8 py-5"><?php echo htmlspecialchars($row['name'] ?? $row['fullname'] ?? 'N/A'); ?></td>
                        <td class="px-6 lg:px-8 py-5"><?php echo htmlspecialchars($row['student_id'] ?? 'N/A'); ?></td>
                        <td class="px-6 lg:px-8 py-5"><?php echo htmlspecialchars($row['service_type'] ?? 'N/A'); ?></td>
                        <td class="px-6 lg:px-8 py-5"><?php echo date("M d, Y", strtotime($row['created_at'] ?? 'now')); ?></td>
                        <td class="px-6 lg:px-8 py-5">
                            <?php if (($row['status'] ?? '') == "pending"): ?>
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-semibold">Pending</span>
                            <?php elseif (($row['status'] ?? '') == "approved"): ?>
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">Approved</span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">Rejected</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 lg:px-8 py-5">
                            <?php if (($row['type'] ?? 'regular') === 'one_time'): ?>
                                <div class="flex flex-wrap items-center gap-2">
                                    <a href="Requests.php?approve=<?php echo (int) $row['id']; ?>&type=one_time" onclick="return confirm('Approve this one-time request?')" class="inline-flex min-h-[44px] items-center gap-1 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-xl text-xs font-semibold transition no-underline">
                                        <i class="fas fa-check"></i>Approve
                                    </a>
                                    <a href="Requests.php?reject=<?php echo (int) $row['id']; ?>&type=one_time" onclick="return confirm('Reject this one-time request?')" class="inline-flex min-h-[44px] items-center gap-1 bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-xl text-xs font-semibold transition no-underline">
                                        <i class="fas fa-times"></i>Reject
                                    </a>
                                </div>
                            <?php else: ?>
                                <a href="RequestView.php?id=<?php echo (int) $row['id']; ?>" class="inline-flex min-h-[44px] items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition no-underline">
                                    <i class="fas fa-eye"></i>View
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-10 text-gray-500">No requests found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $additionalScripts = ($additionalScripts ?? '') . "<script>
(function () {
    const form = document.getElementById('requestSearchForm');
    const input = document.getElementById('requestSearchInput');
    if (!form || !input) {
        return;
    }

    let debounceTimer;
    input.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = window.setTimeout(function () {
            form.requestSubmit();
        }, 300);
    });
})();
</script>"; ?>

<?php include __DIR__ . '/../../public/admin/includes/AdminFooter.php'; ?>
