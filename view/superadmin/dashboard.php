<?php include __DIR__ . '/../../public/superadmin/includes/SuperAdminHeader.php'; ?>

<div class="p-4 sm:p-6 lg:p-10 space-y-8">
    <div class="welcome-banner rounded-3xl p-6 sm:p-8 text-white shadow-lg">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-blue-100 text-sm font-semibold uppercase tracking-wide">Super Admin</p>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold mt-2">System Dashboard</h2>
                <p class="text-blue-100 text-base sm:text-lg mt-3 max-w-3xl">View system totals, monitor pending work, and audit admin actions.</p>
            </div>
            <button
                type="button"
                data-modal-target="createUserModal"
                data-modal-toggle="createUserModal"
                class="inline-flex min-h-[48px] items-center justify-center rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-blue-900 shadow-md transition hover:bg-blue-50"
            >
                <i class="fas fa-plus mr-2"></i>Create User
            </button>
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
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-800">System Statistics</h3>
                <p class="text-sm text-gray-500 mt-1">Current database totals across the portal.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-5 sm:p-8">
                <div class="rounded-2xl border border-gray-100 p-5">
                    <p class="text-gray-500 text-sm">Students</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo (int) ($systemStats['total_students'] ?? 0); ?></p>
                </div>
                <div class="rounded-2xl border border-gray-100 p-5">
                    <p class="text-gray-500 text-sm">All Requests</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo (int) ($systemStats['total_requests'] ?? 0); ?></p>
                </div>
                <div class="rounded-2xl border border-gray-100 p-5">
                    <p class="text-gray-500 text-sm">Pending Appointments</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo (int) ($systemStats['pending_appointments'] ?? 0); ?></p>
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

<div id="createUserModal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full overflow-y-auto overflow-x-hidden p-4 md:inset-0">
    <div class="relative max-h-full w-full max-w-2xl">
        <div class="relative rounded-3xl bg-white shadow-2xl">
            <div class="flex items-start justify-between rounded-t-3xl border-b px-6 py-5 sm:px-8">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Create User</h3>
                    <p class="mt-1 text-sm text-gray-500">Create student, admin, or super admin accounts without leaving the dashboard.</p>
                </div>
                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-gray-400 transition hover:bg-gray-100 hover:text-gray-900" data-modal-hide="createUserModal">
                    <span class="sr-only">Close modal</span>
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="/Norsu_Tor/superadmin/store-user" method="POST" class="space-y-5 px-6 py-6 sm:px-8 sm:py-8">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="create-user-name" class="mb-2 block text-sm font-semibold text-gray-700">Name</label>
                        <input id="create-user-name" name="name" type="text" value="<?php echo htmlspecialchars($createUserOld['name'] ?? ''); ?>" class="block w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="create-user-email" class="mb-2 block text-sm font-semibold text-gray-700">Email</label>
                        <input id="create-user-email" name="email" type="email" value="<?php echo htmlspecialchars($createUserOld['email'] ?? ''); ?>" class="block w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="create-user-password" class="mb-2 block text-sm font-semibold text-gray-700">Password</label>
                        <input id="create-user-password" name="password" type="password" class="block w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="create-user-role" class="mb-2 block text-sm font-semibold text-gray-700">Role</label>
                        <select id="create-user-role" name="role" class="block w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500" required>
                            <?php $selectedRole = $createUserOld['role'] ?? 'student'; ?>
                            <option value="student" <?php echo $selectedRole === 'student' ? 'selected' : ''; ?>>Student</option>
                            <option value="admin" <?php echo $selectedRole === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            <option value="superadmin" <?php echo $selectedRole === 'superadmin' ? 'selected' : ''; ?>>Super Admin</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button type="button" data-modal-hide="createUserModal" class="inline-flex min-h-[46px] items-center justify-center rounded-2xl border border-gray-300 px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="inline-flex min-h-[46px] items-center justify-center rounded-2xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-800">
                        <i class="fas fa-user-plus mr-2"></i>Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if (!empty($createUserErrors)): ?>
    <?php $additionalScripts = ($additionalScripts ?? '') . "<script>window.addEventListener('load', function () { const toggle = document.querySelector('[data-modal-target=\"createUserModal\"]'); if (toggle) { toggle.click(); } });</script>"; ?>
<?php endif; ?>

<?php include __DIR__ . '/../../public/superadmin/includes/SuperAdminFooter.php'; ?>
