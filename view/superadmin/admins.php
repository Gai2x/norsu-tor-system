<?php include __DIR__ . '/../../public/superadmin/includes/SuperAdminHeader.php'; ?>

<div class="p-4 sm:p-6 lg:p-10 space-y-8">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-blue-600 text-sm font-semibold uppercase tracking-[0.3em]">Admin Management</p>
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 mt-3">Administrator Accounts</h1>
            <p class="text-gray-500 mt-2 max-w-2xl">Browse admin profiles in a read-only card layout and create new admin accounts.</p>
        </div>
        <button
            type="button"
            data-modal-target="createAdminModal"
            data-modal-toggle="createAdminModal"
            class="inline-flex min-h-[48px] items-center justify-center rounded-2xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-blue-800"
        >
            <i class="fas fa-plus mr-2"></i>Create Admin
        </button>
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
            <h2 class="text-xl font-semibold text-slate-900">Admin directory</h2>
            <p class="text-sm text-slate-500 mt-1">Only administrator accounts are shown here. Role assignment is protected and cannot be changed in this editor.</p>
        </div>
        <div class="grid gap-5 p-6 md:grid-cols-2 xl:grid-cols-3">
            <?php if (!empty($admins)): ?>
                <?php foreach ($admins as $admin): ?>
                    <article class="group rounded-[2rem] border border-slate-200 bg-slate-50 p-6 shadow-sm transition hover:-translate-y-1 hover:border-slate-300 hover:bg-white">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.35em] text-slate-400">Admin ID</p>
                                <p class="text-xl font-semibold text-slate-900 mt-2"><?php echo htmlspecialchars($admin['student_id'] ?: 'N/A'); ?></p>
                            </div>
                            <button type="button" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700" data-admin-edit="<?php echo (int) $admin['id']; ?>" data-admin-id="<?php echo (int) $admin['id']; ?>" data-admin-name="<?php echo htmlspecialchars($admin['name'], ENT_QUOTES); ?>" data-admin-email="<?php echo htmlspecialchars($admin['email'], ENT_QUOTES); ?>" data-admin-student-id="<?php echo htmlspecialchars($admin['student_id'], ENT_QUOTES); ?>" data-admin-course="<?php echo htmlspecialchars($admin['course'], ENT_QUOTES); ?>">
                                <i class="fas fa-pen-to-square mr-2"></i>Edit
                            </button>
                        </div>

                        <div class="mt-6 space-y-4 text-sm text-slate-600">
                            <div class="grid gap-2">
                                <span class="text-xs uppercase tracking-[0.25em] text-slate-400">Name</span>
                                <p class="font-medium text-slate-900"><?php echo htmlspecialchars($admin['name']); ?></p>
                            </div>
                            <div class="grid gap-2">
                                <span class="text-xs uppercase tracking-[0.25em] text-slate-400">Email</span>
                                <p><?php echo htmlspecialchars($admin['email']); ?></p>
                            </div>
                            <div class="grid gap-2">
                                <span class="text-xs uppercase tracking-[0.25em] text-slate-400">Department</span>
                                <p><?php echo htmlspecialchars($admin['course'] ?: 'N/A'); ?></p>
                            </div>
                            <div class="grid gap-2">
                                <span class="text-xs uppercase tracking-[0.25em] text-slate-400">Created</span>
                                <p><?php echo !empty($admin['created_at']) ? date('M d, Y', strtotime($admin['created_at'])) : 'N/A'; ?></p>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full rounded-[2rem] border border-dashed border-slate-300 bg-slate-50 p-10 text-center text-slate-500">
                    No admin accounts matched your search.
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<div id="editAdminModal" class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto bg-black/40 px-4 py-6 sm:px-6">
    <div class="w-full max-w-2xl rounded-[2rem] bg-white shadow-2xl ring-1 ring-slate-200">
        <div class="flex items-start justify-between gap-4 rounded-[2rem] border-b border-slate-200 px-6 py-5">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Edit Admin Profile</h2>
                <p class="text-sm text-slate-500 mt-1">Update administrator details safely without inline edits.</p>
            </div>
            <button type="button" class="text-slate-500 transition hover:text-slate-900" data-modal-hide="editAdminModal">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <form id="edit-admin-form" action="" method="POST" class="space-y-5 px-6 py-6 sm:px-8 sm:py-8">
            <input type="hidden" name="action" value="update_admin">
            <input type="hidden" name="admin_id" id="edit-admin-id" value="">
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="edit-admin-name" class="mb-2 block text-sm font-medium text-slate-700">Full Name</label>
                    <input id="edit-admin-name" name="name" type="text" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-100" required>
                </div>
                <div>
                    <label for="edit-admin-email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                    <input id="edit-admin-email" name="email" type="email" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-100" required>
                </div>
                <div>
                    <label for="edit-admin-student-id" class="mb-2 block text-sm font-medium text-slate-700">Admin ID</label>
                    <input id="edit-admin-student-id" name="student_id" type="text" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-100" required>
                </div>
                <div class="sm:col-span-2">
                    <label for="edit-admin-course" class="mb-2 block text-sm font-medium text-slate-700">Department</label>
                    <input id="edit-admin-course" name="course" type="text" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-100" required>
                </div>
                <div class="sm:col-span-2">
                    <label for="edit-admin-password" class="mb-2 block text-sm font-medium text-slate-700">New Password <span class="text-slate-400">(optional)</span></label>
                    <input id="edit-admin-password" name="password" type="password" placeholder="Leave blank to keep existing password" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-100">
                </div>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button type="button" data-modal-hide="editAdminModal" class="inline-flex min-h-[46px] items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</button>
                <button type="submit" class="inline-flex min-h-[46px] items-center justify-center rounded-2xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-800">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<div id="createAdminModal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full overflow-y-auto overflow-x-hidden p-4 md:inset-0">
    <div class="relative max-h-full w-full max-w-2xl">
        <div class="relative rounded-3xl bg-white shadow-2xl">
            <div class="flex items-start justify-between rounded-t-3xl border-b px-6 py-5 sm:px-8">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Create Admin Account</h3>
                    <p class="mt-1 text-sm text-gray-500">Create a new administrator account with proper validation.</p>
                </div>
                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-gray-400 transition hover:bg-gray-100 hover:text-gray-900" data-modal-hide="createAdminModal">
                    <span class="sr-only">Close modal</span>
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="create-admin-form" class="space-y-5 px-6 py-6 sm:px-8 sm:py-8">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="create-admin-name" class="mb-2 block text-sm font-semibold text-gray-700">Full Name</label>
                        <input id="create-admin-name" name="name" type="text" class="block w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="create-admin-email" class="mb-2 block text-sm font-semibold text-gray-700">Email</label>
                        <input id="create-admin-email" name="email" type="email" class="block w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="create-admin-student-id" class="mb-2 block text-sm font-semibold text-gray-700">Admin ID</label>
                        <input id="create-admin-student-id" name="student_id" type="text" class="block w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="create-admin-course" class="mb-2 block text-sm font-semibold text-gray-700">Department</label>
                        <input id="create-admin-course" name="course" type="text" class="block w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="create-admin-password" class="mb-2 block text-sm font-semibold text-gray-700">Password</label>
                        <input id="create-admin-password" name="password" type="password" class="block w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                </div>
                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button type="button" data-modal-hide="createAdminModal" class="inline-flex min-h-[46px] items-center justify-center rounded-2xl border border-gray-300 px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Cancel</button>
                    <button type="submit" id="create-admin-submit" class="inline-flex min-h-[46px] items-center justify-center rounded-2xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-800">
                        <i class="fas fa-user-plus mr-2"></i>Create Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function () {
    const editModal = document.getElementById('editAdminModal');
    const createModal = document.getElementById('createAdminModal');
    const editButtons = document.querySelectorAll('[data-admin-edit]');
    const createForm = document.getElementById('create-admin-form');
    const createSubmit = document.getElementById('create-admin-submit');
    const fieldMap = {
        id: document.getElementById('edit-admin-id'),
        name: document.getElementById('edit-admin-name'),
        email: document.getElementById('edit-admin-email'),
        student_id: document.getElementById('edit-admin-student-id'),
        course: document.getElementById('edit-admin-course'),
    };

    function openEditModal() {
        editModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        editModal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    function openCreateModal() {
        createModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeCreateModal() {
        createModal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            fieldMap.id.value = this.dataset.adminId || '';
            fieldMap.name.value = this.dataset.adminName || '';
            fieldMap.email.value = this.dataset.adminEmail || '';
            fieldMap.student_id.value = this.dataset.adminStudentId || '';
            fieldMap.course.value = this.dataset.adminCourse || '';
            openEditModal();
        });
    });

    document.querySelectorAll('[data-modal-hide="editAdminModal"]').forEach(btn => btn.addEventListener('click', closeEditModal));
    editModal.addEventListener('click', function (event) {
        if (event.target === editModal) closeEditModal();
    });

    document.querySelectorAll('[data-modal-target="createAdminModal"]').forEach(btn => btn.addEventListener('click', openCreateModal));
    document.querySelectorAll('[data-modal-hide="createAdminModal"]').forEach(btn => btn.addEventListener('click', closeCreateModal));
    createModal.addEventListener('click', function (event) {
        if (event.target === createModal) closeCreateModal();
    });

    createForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        createSubmit.disabled = true;
        createSubmit.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';

        const formData = new FormData(this);

        try {
            const response = await fetch('/Norsu_Tor/superadmin/ajax-create-admin', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            const result = await response.json();

            if (result.success) {
                location.reload();
            } else {
                alert('Error: ' + (result.errors ? result.errors.join('\n') : 'Unknown error'));
                createSubmit.disabled = false;
                createSubmit.innerHTML = '<i class="fas fa-user-plus mr-2"></i>Create Admin';
            }
        } catch (error) {
            console.error('Create admin error:', error);
            alert('An error occurred while creating the admin account.');
            createSubmit.disabled = false;
            createSubmit.innerHTML = '<i class="fas fa-user-plus mr-2"></i>Create Admin';
        }
    });

    <?php if (!empty($filters['editAdminId']) && !empty($filters['editOld'])): ?>
        window.addEventListener('load', function () {
            const button = document.querySelector('[data-admin-edit="<?php echo (int) $filters['editAdminId']; ?>"]');
            if (button) {
                button.click();
                fieldMap.name.value = <?php echo json_encode($filters['editOld']['name'] ?? ''); ?>;
                fieldMap.email.value = <?php echo json_encode($filters['editOld']['email'] ?? ''); ?>;
                fieldMap.student_id.value = <?php echo json_encode($filters['editOld']['student_id'] ?? ''); ?>;
                fieldMap.course.value = <?php echo json_encode($filters['editOld']['course'] ?? ''); ?>;
            }
        });
    <?php endif; ?>
})();
</script>

<?php include __DIR__ . '/../../public/superadmin/includes/SuperAdminFooter.php'; ?>
