<?php include __DIR__ . '/../../public/superadmin/includes/SuperAdminHeader.php'; ?>

<div class="p-4 sm:p-6 lg:p-10 space-y-8">
    <div class="welcome-banner rounded-3xl p-6 sm:p-8 text-white shadow-lg">
        <p class="text-blue-100 text-sm font-semibold uppercase tracking-wide">System Monitoring</p>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold mt-2">Requests Monitor</h2>
        <p class="text-blue-100 text-base sm:text-lg mt-3">Read-only visibility into regular and one-time requests. Overrides are exceptional actions.</p>
    </div>

    <?php if (!empty($notice)): ?>
        <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700 font-semibold"><?php echo htmlspecialchars($notice); ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <section class="bg-white rounded-3xl shadow overflow-hidden">
        <div class="px-5 sm:px-8 py-6 border-b">
            <h3 class="text-2xl sm:text-3xl font-bold text-gray-800">Request Search</h3>
            <p class="text-sm text-gray-500 mt-1">Filter by student, reference, service, status, or request type.</p>
        </div>

        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 p-5 sm:p-8">
            <input name="search" value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>" placeholder="Search requests..." class="md:col-span-2 border border-gray-300 rounded-2xl px-4 py-3 min-h-[44px] focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="status" class="border border-gray-300 rounded-2xl px-4 py-3 min-h-[44px]">
                <option value="">All Statuses</option>
                <?php foreach (['pending', 'approved', 'rejected'] as $statusOption): ?>
                    <option value="<?php echo $statusOption; ?>" <?php echo ($filters['status'] ?? '') === $statusOption ? 'selected' : ''; ?>><?php echo ucfirst($statusOption); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="type" class="border border-gray-300 rounded-2xl px-4 py-3 min-h-[44px]">
                <option value="">All Types</option>
                <option value="regular" <?php echo ($filters['type'] ?? '') === 'regular' ? 'selected' : ''; ?>>Regular</option>
                <option value="one_time" <?php echo ($filters['type'] ?? '') === 'one_time' ? 'selected' : ''; ?>>One-Time</option>
            </select>
            <div class="md:col-span-4 flex flex-wrap gap-3">
                <button class="inline-flex min-h-[44px] items-center justify-center rounded-2xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-800">
                    <i class="fas fa-magnifying-glass mr-2"></i>Filter
                </button>
                <a href="Requests.php" class="inline-flex min-h-[44px] items-center justify-center rounded-2xl border border-gray-300 px-5 py-3 text-sm font-semibold text-gray-700 no-underline hover:bg-gray-50">Reset</a>
            </div>
        </form>
    </section>

    <section class="bg-white rounded-3xl shadow overflow-hidden">
        <div class="px-5 sm:px-8 py-6 border-b flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
            <div>
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-800">Monitoring Results</h3>
                <p class="text-sm text-gray-500 mt-1">Daily request processing remains in the admin workflow.</p>
            </div>
            <span class="rounded-full bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700"><?php echo count($requests); ?> records</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1160px] text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                    <tr>
                        <th class="px-6 py-4">Reference</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Student ID</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Service</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($requests)): ?>
                        <?php foreach ($requests as $request): ?>
                            <?php $status = strtolower($request['status'] ?? 'pending'); ?>
                            <?php $detailsId = 'request-details-' . htmlspecialchars($request['type']) . '-' . (int) $request['id']; ?>
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-6 py-5 font-semibold">REQ-<?php echo str_pad($request['id'], 5, '0', STR_PAD_LEFT); ?></td>
                                <td class="px-6 py-5">
                                    <span class="<?php echo $request['type'] === 'one_time' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'; ?> rounded-full px-3 py-1 text-xs font-semibold">
                                        <?php echo $request['type'] === 'one_time' ? 'One-Time' : 'Regular'; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-5"><?php echo htmlspecialchars($request['name']); ?></td>
                                <td class="px-6 py-5"><?php echo htmlspecialchars($request['student_id']); ?></td>
                                <td class="px-6 py-5"><?php echo htmlspecialchars($request['email'] ?? 'N/A'); ?></td>
                                <td class="px-6 py-5"><?php echo htmlspecialchars($request['service_type'] ?? 'N/A'); ?></td>
                                <td class="px-6 py-5"><?php echo !empty($request['created_at']) ? date('M d, Y', strtotime($request['created_at'])) : 'N/A'; ?></td>
                                <td class="px-6 py-5">
                                    <span class="<?php echo $status === 'approved' ? 'bg-green-100 text-green-700' : ($status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700'); ?> rounded-full px-3 py-1 text-sm font-semibold">
                                        <?php echo ucfirst($status); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <button type="button" onclick="document.getElementById('<?php echo $detailsId; ?>').classList.toggle('hidden')" class="rounded-xl border border-blue-200 px-4 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-50">
                                        View
                                    </button>
                                </td>
                            </tr>
                            <tr id="<?php echo $detailsId; ?>" class="hidden bg-gray-50 border-t">
                                <td colspan="10" class="px-6 py-5">
                                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                        <div class="rounded-2xl bg-white border border-gray-100 p-4">
                                            <p class="text-xs text-gray-400 uppercase font-semibold">Student Info</p>
                                            <p class="mt-2 font-semibold text-gray-800"><?php echo htmlspecialchars($request['name']); ?></p>
                                            <p class="text-sm text-gray-500"><?php echo htmlspecialchars($request['student_id']); ?></p>
                                            <p class="text-sm text-gray-500 break-all"><?php echo htmlspecialchars($request['email'] ?? 'N/A'); ?></p>
                                        </div>
                                        <div class="rounded-2xl bg-white border border-gray-100 p-4">
                                            <p class="text-xs text-gray-400 uppercase font-semibold">Request Details</p>
                                            <p class="mt-2 text-sm text-gray-700"><span class="font-semibold">Service:</span> <?php echo htmlspecialchars($request['service_type'] ?? 'N/A'); ?></p>
                                            <p class="text-sm text-gray-700"><span class="font-semibold">Submitted:</span> <?php echo !empty($request['created_at']) ? date('M d, Y h:i A', strtotime($request['created_at'])) : 'N/A'; ?></p>
                                            <p class="text-sm text-gray-700"><span class="font-semibold">Notes:</span> <?php echo htmlspecialchars($request['notes'] ?? 'None'); ?></p>
                                        </div>
                                        <div class="rounded-2xl bg-white border border-gray-100 p-4">
                                            <p class="text-xs text-gray-400 uppercase font-semibold">Exceptional Override</p>
                                            <p class="mt-2 text-sm text-gray-500">Use only when a super-admin correction is required.</p>
                                            <?php if (($_SESSION['role'] ?? '') === 'super_admin'): ?>
                                                <form method="POST" class="mt-4 flex flex-wrap gap-2" onsubmit="return confirm('This is an override action. Continue?');">
                                                    <input type="hidden" name="action" value="update_request_status">
                                                    <input type="hidden" name="request_id" value="<?php echo (int) $request['id']; ?>">
                                                    <input type="hidden" name="type" value="<?php echo htmlspecialchars($request['type']); ?>">
                                                    <select name="status" class="min-h-[40px] rounded-xl border border-gray-300 px-3 py-2 text-sm">
                                                        <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="approved" <?php echo $status === 'approved' ? 'selected' : ''; ?>>Approved</option>
                                                        <option value="rejected" <?php echo $status === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                                    </select>
                                                    <button class="min-h-[40px] rounded-xl bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-700">Override</button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="10" class="py-10 text-center text-gray-500">No requests found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php include __DIR__ . '/../../public/superadmin/includes/SuperAdminFooter.php'; ?>
