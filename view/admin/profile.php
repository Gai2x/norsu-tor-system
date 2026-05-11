<?php
if (!function_exists('escapeValue')) {
    function escapeValue($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

include __DIR__ . '/../../public/admin/includes/AdminHeader.php';
?>

<div class="p-4 sm:p-6 lg:p-10 animate-fade-in-up">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between mb-6">
        <div>
            <p class="text-blue-600 text-sm font-semibold uppercase tracking-[0.3em]">Profile</p>
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900">Administrator Profile</h1>
            <p class="text-slate-500 mt-2 max-w-2xl">Review and update your account details without leaving the admin dashboard.</p>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="rounded-3xl border border-rose-200 bg-rose-50 px-6 py-4 text-rose-700 shadow-sm mb-6">
            <p class="font-semibold">Please fix the following:</p>
            <ul class="mt-3 list-disc list-inside text-sm space-y-1">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo escapeValue($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($updated)): ?>
        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-6 py-4 text-emerald-700 shadow-sm mb-6">
            Profile updated successfully.
        </div>
    <?php endif; ?>

    <?php if (!empty($passwordUpdated)): ?>
        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-6 py-4 text-emerald-700 shadow-sm mb-6">
            Password changed successfully.
        </div>
    <?php endif; ?>

    <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
        <section class="space-y-6">
            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center gap-5">
                        <div class="relative">
                            <?php if (!empty($profileImageUrl)): ?>
                                <img src="<?php echo escapeValue($profileImageUrl); ?>" alt="Profile Image" class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-lg">
                            <?php else: ?>
                                <div class="w-28 h-28 rounded-full bg-gradient-to-br from-blue-500 to-sky-700 text-white flex items-center justify-center shadow-lg text-4xl font-bold">
                                    <?php echo escapeValue($profileInitial); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900"><?php echo escapeValue($fullName); ?></h2>
                            <p class="text-slate-500 mt-2">Administrator profile</p>
                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="inline-flex items-center gap-2 rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700">
                                    <i class="far fa-id-badge"></i>
                                    <?php echo escapeValue($displayRole); ?>
                                </span>
                                <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700">
                                    <i class="fas fa-calendar-alt"></i>
                                    Joined <?php echo escapeValue($displayCreatedAt); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-2 text-sm text-slate-500">
                        <span class="uppercase tracking-[0.3em] text-xs text-slate-400">Admin ID</span>
                        <p class="text-xl font-semibold text-slate-900"><?php echo escapeValue($displayAdminId); ?></p>
                    </div>
                </div>
            </div>

            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-2xl font-semibold text-slate-900 mb-4">Profile Details</h2>
                <form id="admin-profile-form" action="profile.php" method="POST" enctype="multipart/form-data" class="space-y-5">
                    <input type="hidden" name="action" value="update_profile">

                    <div class="grid gap-5 sm:grid-cols-2">
                        <label class="block">
                            <span class="text-sm font-semibold text-slate-700">Full Name</span>
                            <input type="text" name="name" value="<?php echo escapeValue($admin['name'] ?? ''); ?>" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-100" required>
                        </label>
                        <label class="block">
                            <span class="text-sm font-semibold text-slate-700">Email Address</span>
                            <input type="email" name="email" value="<?php echo escapeValue($admin['email'] ?? ''); ?>" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-100" required>
                        </label>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <label class="block">
                            <span class="text-sm font-semibold text-slate-700">Phone Number</span>
                            <input id="admin-phone-number" type="text" name="phone_number" value="<?php echo escapeValue($admin['phone_number'] ?? ''); ?>" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-100" placeholder="09XXXXXXXXX or +639XXXXXXXXX">
                        </label>
                        <label class="block">
                            <span class="text-sm font-semibold text-slate-700">Profile Image</span>
                            <input type="file" name="profile_pic" accept="image/png, image/jpeg, image/gif" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900">
                            <p class="text-xs text-slate-500 mt-2">Upload PNG, JPG, or GIF (max 2MB).</p>
                        </label>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                        <button type="submit" class="inline-flex min-h-[46px] items-center justify-center rounded-2xl bg-blue-700 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-800" data-loading-text="Saving...">Save Changes</button>
                    </div>
                </form>
            </div>
        </section>

        <section class="space-y-6">
            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-2xl font-semibold text-slate-900 mb-4">Security</h2>
                <p class="text-sm text-slate-500 mb-6">Update your password securely. Current password verification is required.</p>

                <form id="admin-password-form" action="profile.php" method="POST" class="space-y-5">
                    <input type="hidden" name="action" value="change_password">

                    <div class="grid gap-5">
                        <label class="block">
                            <span class="text-sm font-semibold text-slate-700">Current Password</span>
                            <input type="password" name="current_password" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-100" required>
                        </label>
                        <label class="block">
                            <span class="text-sm font-semibold text-slate-700">New Password</span>
                            <input type="password" name="new_password" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-100" required>
                        </label>
                        <label class="block">
                            <span class="text-sm font-semibold text-slate-700">Confirm New Password</span>
                            <input type="password" name="confirm_password" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-100" required>
                        </label>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                        <button type="submit" class="inline-flex min-h-[46px] items-center justify-center rounded-2xl bg-slate-800 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-900" data-loading-text="Updating...">Change Password</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>

<div id="ajax-notification-container" class="fixed top-5 right-5 z-50 space-y-3"></div>

<script src="/Norsu_Tor/public/js/ajax-utils.js"></script>
<script>
(function () {
    const profileForm = document.getElementById('admin-profile-form');
    const passwordForm = document.getElementById('admin-password-form');

    const apiEndpoint = '/Norsu_Tor/admin/ajax-admin-profile';

    AjaxUtils.bindForm(profileForm, {
        url: apiEndpoint,
        onSuccess: function (data) {
            if (data.success) {
                AjaxUtils.createNotification('Profile updated successfully.', 'success');
                setTimeout(() => window.location.reload(), 900);
            } else {
                AjaxUtils.createNotification(data.errors ? data.errors.join(' ') : 'Unable to save profile.', 'error');
            }
        },
        onError: function (error) {
            AjaxUtils.createNotification(error.message || 'A network error occurred.', 'error');
        }
    });

    AjaxUtils.bindForm(passwordForm, {
        url: apiEndpoint,
        onSuccess: function (data) {
            if (data.success) {
                AjaxUtils.createNotification('Password changed successfully.', 'success');
                passwordForm.reset();
            } else {
                AjaxUtils.createNotification(data.errors ? data.errors.join(' ') : 'Unable to change password.', 'error');
            }
        },
        onError: function (error) {
            AjaxUtils.createNotification(error.message || 'A network error occurred.', 'error');
        }
    });
})();
</script>

<?php include __DIR__ . '/../../public/admin/includes/AdminFooter.php'; ?>
