<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | NORSU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="relative min-h-screen overflow-hidden bg-[radial-gradient(circle_at_top_right,_rgba(30,64,175,0.18),_transparent_30%),linear-gradient(135deg,_#eff6ff_0%,_#f8fafc_45%,_#dbeafe_100%)]">
        <div class="absolute inset-0 opacity-50" aria-hidden="true" style="background-image: linear-gradient(rgba(148,163,184,0.12) 1px, transparent 1px), linear-gradient(90deg, rgba(148,163,184,0.12) 1px, transparent 1px); background-size: 28px 28px;"></div>

        <div class="relative flex min-h-screen items-center justify-center p-4 sm:p-6">
            <div class="w-full max-w-md rounded-[28px] border border-white/70 bg-white/95 shadow-[0_24px_80px_rgba(15,23,42,0.14)] backdrop-blur">
                <div class="border-b border-slate-100 px-6 pt-8 pb-5 sm:px-8">
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-700 shadow-lg shadow-blue-200/60">
                            <img src="img/logo.png" class="h-9 w-9 object-contain" alt="NORSU Logo">
                        </div>
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-700">Account Security</p>
                            <h1 class="mt-1 text-2xl font-bold text-slate-900 sm:text-3xl">Create a new password</h1>
                        </div>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-slate-500">Choose a strong password for your student account. You will be returned to the login screen after a successful reset.</p>
                </div>

                <div class="px-6 py-6 sm:px-8 sm:py-8">
                    <?php if (!empty($message)): ?>
                    <div class="mb-5 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert" aria-live="polite">
                        <i class="fas fa-circle-exclamation mt-0.5"></i>
                        <span><?php echo htmlspecialchars($message); ?></span>
                    </div>
                    <?php endif; ?>

                    <form method="POST" class="space-y-5" novalidate onsubmit="handleLoadingState('resetButton', 'Resetting password...')">
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-semibold text-slate-700">New password</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input id="password" name="password" type="password" required aria-label="New password" class="block min-h-[52px] w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-12 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 hover:border-blue-300 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100" placeholder="Enter a new password">
                                <button type="button" onclick="togglePassword('password', 'passwordIcon')" class="absolute inset-y-0 right-0 flex min-h-[44px] items-center pr-4 text-slate-400 transition hover:text-slate-600 focus:outline-none focus:text-blue-600" aria-label="Show or hide new password">
                                    <i id="passwordIcon" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="confirm" class="block text-sm font-semibold text-slate-700">Confirm password</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="fas fa-shield-halved"></i>
                                </span>
                                <input id="confirm" name="confirm" type="password" required aria-label="Confirm password" class="block min-h-[52px] w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-12 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 hover:border-blue-300 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100" placeholder="Confirm your password">
                                <button type="button" onclick="togglePassword('confirm', 'confirmIcon')" class="absolute inset-y-0 right-0 flex min-h-[44px] items-center pr-4 text-slate-400 transition hover:text-slate-600 focus:outline-none focus:text-blue-600" aria-label="Show or hide confirm password">
                                    <i id="confirmIcon" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button id="resetButton" name="reset" type="submit" class="inline-flex min-h-[52px] w-full items-center justify-center gap-2 rounded-2xl bg-blue-800 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-900 focus:outline-none focus:ring-4 focus:ring-blue-200">
                            <i class="fas fa-key"></i>
                            Reset Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function togglePassword(fieldId, iconId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(iconId);
        if (!field || !icon) return;

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function handleLoadingState(buttonId, loadingText) {
        const button = document.getElementById(buttonId);
        if (!button) return true;
        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + loadingText;
        return true;
    }
    </script>
</body>
</html>
