<?php
if (!function_exists('escapeValue')) {
    function escapeValue($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

include __DIR__ . '/../../public/admin/includes/AdminHeader.php';
?>

<div class="p-4 sm:p-6 lg:p-8 animate-fade-in-up">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between mb-6">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-800">Admin Profile</h1>
            <p class="text-slate-500 mt-2 text-sm sm:text-base">Manage your administrator account and security settings</p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row">
            <button onclick="openAdminProfileModal()" type="button" class="inline-flex items-center justify-center gap-2 bg-blue-800 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-xl shadow-md transition">
                <i class="fas fa-pen text-sm"></i>
                <span>Edit Profile</span>
            </button>
            <button onclick="openAdminPasswordModal()" type="button" class="inline-flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-900 text-white font-semibold px-5 py-3 rounded-xl shadow-md transition">
                <i class="fas fa-lock text-sm"></i>
                <span>Change Password</span>
            </button>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-rose-700 mb-6">
            <?php foreach ($errors as $error): ?>
                <p><?php echo escapeValue($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($updated)): ?>
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-700 font-semibold mb-6">Profile updated successfully.</div>
    <?php endif; ?>

    <?php if (!empty($passwordUpdated)): ?>
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-700 font-semibold mb-6">Password changed successfully.</div>
    <?php endif; ?>

    <section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 sm:p-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                    <div class="relative">
                        <?php if (!empty($profileImageUrl)): ?>
                            <img src="<?php echo escapeValue($profileImageUrl); ?>" alt="Profile Image" class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-lg">
                        <?php else: ?>
                            <div class="w-28 h-28 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-white flex items-center justify-center shadow-lg text-4xl font-bold">
                                <?php echo escapeValue($profileInitial); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-800"><?php echo escapeValue($fullName !== '' ? $fullName : 'Administrator'); ?></h2>
                        <p class="text-slate-500 mt-1"><?php echo escapeValue($displayEmail); ?></p>

                        <div class="flex flex-wrap gap-2 mt-3">
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-medium">
                                <i class="far fa-id-badge text-xs"></i>
                                <?php echo escapeValue($displayRole); ?>
                            </span>
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-sm font-medium">
                                <i class="fas fa-circle text-[8px]"></i>
                                Active
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-200 mt-8 pt-8">
                <h3 class="text-2xl font-bold text-slate-800 mb-6">Account Information</h3>
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-2">Name</label>
                        <div class="flex items-center min-h-[56px] rounded-xl border border-slate-200 bg-slate-50 px-4 text-slate-800"><?php echo escapeValue($fullName); ?></div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-2">Email Address</label>
                        <div class="flex items-center gap-3 min-h-[56px] rounded-xl border border-slate-200 bg-slate-50 px-4 text-slate-800 break-all">
                            <i class="far fa-envelope text-slate-400"></i>
                            <span><?php echo escapeValue($displayEmail); ?></span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-2">Role</label>
                        <div class="flex items-center gap-3 min-h-[56px] rounded-xl border border-slate-200 bg-slate-50 px-4 text-slate-800">
                            <i class="fas fa-user-shield text-slate-400"></i>
                            <span><?php echo escapeValue($displayRole); ?></span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-2">Created Date</label>
                        <div class="flex items-center gap-3 min-h-[56px] rounded-xl border border-slate-200 bg-slate-50 px-4 text-slate-800">
                            <i class="far fa-calendar text-slate-400"></i>
                            <span><?php echo escapeValue($displayCreatedAt); ?></span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-2">Admin ID</label>
                        <div class="flex items-center min-h-[56px] rounded-xl border border-slate-200 bg-slate-50 px-4 text-slate-800"><?php echo escapeValue($displayAdminId); ?></div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-2">Phone Number</label>
                        <div class="flex items-center gap-3 min-h-[56px] rounded-xl border border-slate-200 bg-slate-50 px-4 text-slate-800">
                            <i class="fas fa-phone-alt text-slate-400"></i>
                            <span><?php echo escapeValue($displayPhone); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="adminProfileModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6">
            <h2 class="text-2xl font-bold mb-4 text-slate-800">Edit Profile</h2>
            <form id="admin-profile-form" action="profile.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update_profile">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block mb-2 font-semibold">Full Name</label>
                        <input type="text" name="name" value="<?php echo escapeValue($admin['name'] ?? ''); ?>" class="w-full border rounded-lg px-4 py-3" required>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2 font-semibold">Email</label>
                        <input type="email" name="email" value="<?php echo escapeValue($admin['email'] ?? ''); ?>" class="w-full border rounded-lg px-4 py-3" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-semibold">Phone Number</label>
                    <input type="text" name="phone_number" value="<?php echo escapeValue($admin['phone_number'] ?? ''); ?>" placeholder="09XXXXXXXXX or +639XXXXXXXXX" class="w-full border rounded-lg px-4 py-3">
                    <p class="text-xs text-gray-500 mt-1">Format: 09XXXXXXXXX or +639XXXXXXXXX</p>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-semibold">Profile Picture</label>
                    <input type="file" name="profile_pic" accept="image/png, image/jpeg, image/gif" class="w-full border rounded-lg px-4 py-3">
                    <p class="text-sm text-gray-500 mt-1">Leave empty to keep current image. PNG, JPG, or GIF only, max 2MB.</p>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeAdminProfileModal()" class="px-4 py-2 bg-gray-300 rounded-lg">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-blue-800 text-white rounded-lg" data-loading-text="Saving...">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <div id="adminPasswordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6">
            <h2 class="text-2xl font-bold mb-4 text-slate-800">Change Password</h2>
            <form id="admin-password-form" action="profile.php" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="change_password">

                <div>
                    <label class="block mb-2 font-semibold">Current Password</label>
                    <input type="password" name="current_password" class="w-full border rounded-lg px-4 py-3" required>
                </div>
                <div>
                    <label class="block mb-2 font-semibold">New Password</label>
                    <input type="password" name="new_password" minlength="6" class="w-full border rounded-lg px-4 py-3" required>
                </div>
                <div>
                    <label class="block mb-2 font-semibold">Confirm New Password</label>
                    <input type="password" name="confirm_password" minlength="6" class="w-full border rounded-lg px-4 py-3" required>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeAdminPasswordModal()" class="px-4 py-2 bg-gray-300 rounded-lg">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-slate-800 text-white rounded-lg" data-loading-text="Updating...">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/Norsu_Tor/public/js/ajax-utils.js"></script>
<script>
function openAdminProfileModal() {
    document.getElementById('adminProfileModal').classList.remove('hidden');
    document.getElementById('adminProfileModal').classList.add('flex');
}

function closeAdminProfileModal() {
    document.getElementById('adminProfileModal').classList.add('hidden');
    document.getElementById('adminProfileModal').classList.remove('flex');
}

function openAdminPasswordModal() {
    document.getElementById('adminPasswordModal').classList.remove('hidden');
    document.getElementById('adminPasswordModal').classList.add('flex');
}

function closeAdminPasswordModal() {
    document.getElementById('adminPasswordModal').classList.add('hidden');
    document.getElementById('adminPasswordModal').classList.remove('flex');
}

(function () {
    const profileForm = document.getElementById('admin-profile-form');
    const passwordForm = document.getElementById('admin-password-form');
    const apiEndpoint = '/Norsu_Tor/admin/ajax-admin-profile';

    AjaxUtils.bindForm(profileForm, {
        url: apiEndpoint,
        onSuccess: function (data) {
            if (data.success) {
                AjaxUtils.createNotification('Profile updated successfully.', 'success');
                setTimeout(() => window.location.href = 'profile.php?updated=1', 800);
                return;
            }

            AjaxUtils.createNotification(data.errors ? data.errors.join(' ') : 'Unable to save profile.', 'error');
        },
        onError: function (error) {
            const body = error.body || {};
            AjaxUtils.createNotification(body.errors ? body.errors.join(' ') : 'A network error occurred.', 'error');
        }
    });

    AjaxUtils.bindForm(passwordForm, {
        url: apiEndpoint,
        onSuccess: function (data) {
            if (data.success) {
                AjaxUtils.createNotification('Password changed successfully.', 'success');
                passwordForm.reset();
                closeAdminPasswordModal();
                return;
            }

            AjaxUtils.createNotification(data.errors ? data.errors.join(' ') : 'Unable to change password.', 'error');
        },
        onError: function (error) {
            const body = error.body || {};
            AjaxUtils.createNotification(body.errors ? body.errors.join(' ') : 'A network error occurred.', 'error');
        }
    });
})();
</script>

<?php include __DIR__ . '/../../public/admin/includes/AdminFooter.php'; ?>
