<?php include __DIR__ . '/../../public/superadmin/includes/SuperAdminHeader.php'; ?>

<div class="p-4 sm:p-6 lg:p-10 space-y-8">
    <div>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-800 mb-2">Users & Admins</h2>
        <p class="text-gray-500 text-base sm:text-lg lg:text-2xl">Review system accounts and manage administrator access.</p>
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
            <h3 class="text-2xl sm:text-3xl font-bold text-gray-800">Admin Accounts</h3>
            <p class="text-sm text-gray-500 mt-1">User creation now happens from the dashboard modal. This page stays focused on reviewing and updating existing accounts.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1180px] text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                    <tr>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Admin ID</th>
                        <th class="px-6 py-4">Department</th>
                        <th class="px-6 py-4">New Password</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($admins)): ?>
                        <?php foreach ($admins as $admin): ?>
                            <?php $updateFormId = 'update-admin-' . (int) $admin['id']; ?>
                            <tr class="border-t hover:bg-gray-50 align-top">
                                <td class="px-6 py-4"><input form="<?php echo $updateFormId; ?>" name="name" value="<?php echo htmlspecialchars($admin['name']); ?>" class="w-full border border-gray-300 rounded-xl px-3 py-2" required></td>
                                <td class="px-6 py-4"><input form="<?php echo $updateFormId; ?>" name="email" type="email" value="<?php echo htmlspecialchars($admin['email']); ?>" class="w-full border border-gray-300 rounded-xl px-3 py-2" required></td>
                                <td class="px-6 py-4"><input form="<?php echo $updateFormId; ?>" name="student_id" value="<?php echo htmlspecialchars($admin['student_id']); ?>" class="w-full border border-gray-300 rounded-xl px-3 py-2" required></td>
                                <td class="px-6 py-4"><input form="<?php echo $updateFormId; ?>" name="course" value="<?php echo htmlspecialchars($admin['course']); ?>" class="w-full border border-gray-300 rounded-xl px-3 py-2" required></td>
                                <td class="px-6 py-4"><input form="<?php echo $updateFormId; ?>" name="password" type="password" placeholder="Leave unchanged" class="w-full border border-gray-300 rounded-xl px-3 py-2"></td>
                                <td class="px-6 py-4">
                                    <form id="<?php echo $updateFormId; ?>" method="POST">
                                        <input type="hidden" name="action" value="update_admin">
                                        <input type="hidden" name="admin_id" value="<?php echo (int) $admin['id']; ?>">
                                    </form>
                                    <div class="flex flex-wrap gap-2">
                                        <button form="<?php echo $updateFormId; ?>" class="inline-flex min-h-[40px] items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Save</button>
                                        <form method="POST" onsubmit="return confirm('Delete this admin account?');">
                                            <input type="hidden" name="action" value="delete_admin">
                                            <input type="hidden" name="admin_id" value="<?php echo (int) $admin['id']; ?>">
                                            <button class="inline-flex min-h-[40px] items-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="py-10 text-center text-gray-500">No admin accounts found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="bg-white rounded-3xl shadow overflow-hidden">
        <div class="px-5 sm:px-8 py-6 border-b">
            <h3 class="text-2xl sm:text-3xl font-bold text-gray-800">All System Users</h3>
            <p class="text-sm text-gray-500 mt-1">Read-only overview of every account role.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-6 py-4 font-semibold">#<?php echo (int) $user['id']; ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($user['name']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td class="px-6 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold <?php echo $user['role'] === 'super_admin' ? 'bg-blue-100 text-blue-700' : ($user['role'] === 'admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-green-100 text-green-700'); ?>">
                                    <?php echo htmlspecialchars(str_replace('_', ' ', ucwords($user['role'], '_'))); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4"><?php echo !empty($user['created_at']) ? date('M d, Y', strtotime($user['created_at'])) : 'N/A'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php include __DIR__ . '/../../public/superadmin/includes/SuperAdminFooter.php'; ?>
