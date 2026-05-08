<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP | NORSU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="relative min-h-screen overflow-hidden bg-[radial-gradient(circle_at_top,_rgba(14,116,144,0.18),_transparent_30%),linear-gradient(135deg,_#f0f9ff_0%,_#f8fafc_50%,_#dbeafe_100%)]">
        <div class="absolute inset-0 opacity-50" aria-hidden="true" style="background-image: linear-gradient(rgba(148,163,184,0.12) 1px, transparent 1px), linear-gradient(90deg, rgba(148,163,184,0.12) 1px, transparent 1px); background-size: 28px 28px;"></div>

        <div class="relative flex min-h-screen items-center justify-center p-4 sm:p-6">
            <div class="w-full max-w-md rounded-[28px] border border-white/70 bg-white/95 shadow-[0_24px_80px_rgba(15,23,42,0.14)] backdrop-blur">
                <div class="border-b border-slate-100 px-6 pt-8 pb-5 sm:px-8">
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-cyan-700 shadow-lg shadow-cyan-200/60">
                            <i class="fas fa-shield-halved text-xl text-white"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-700">Verification</p>
                            <h1 class="mt-1 text-2xl font-bold text-slate-900 sm:text-3xl">Enter your OTP code</h1>
                        </div>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-slate-500">Check your email for the six-digit reset code, then verify it below to continue.</p>
                </div>

                <div class="px-6 py-6 sm:px-8 sm:py-8">
                    <?php if (!empty($message)): ?>
                    <div class="mb-5 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert" aria-live="polite">
                        <i class="fas fa-circle-exclamation mt-0.5"></i>
                        <span><?php echo htmlspecialchars($message); ?></span>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                    <div class="mb-5 flex items-start gap-3 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700" role="status" aria-live="polite">
                        <i class="fas fa-circle-check mt-0.5"></i>
                        <span><?php echo htmlspecialchars($success); ?></span>
                    </div>
                    <?php endif; ?>

                    <form method="POST" class="space-y-5" novalidate onsubmit="handleLoadingState('verifyButton', 'Verifying...')">
                        <div class="space-y-2">
                            <label for="otp" class="block text-sm font-semibold text-slate-700">One-time password</label>
                            <input id="otp" name="otp" type="text" inputmode="numeric" autocomplete="one-time-code" required aria-label="One-time password" class="block min-h-[56px] w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-center text-2xl font-semibold tracking-[0.45em] text-slate-900 outline-none transition placeholder:tracking-normal placeholder:text-slate-400 hover:border-cyan-300 focus:border-cyan-500 focus:bg-white focus:ring-4 focus:ring-cyan-100" placeholder="000000" maxlength="6">
                        </div>

                        <div class="rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-500">
                            Code expires in <?php echo max(0, (int) ceil(($remainingSeconds ?? 0) / 60)); ?> minute(s).
                        </div>

                        <button id="verifyButton" name="verify" type="submit" class="inline-flex min-h-[52px] w-full items-center justify-center gap-2 rounded-2xl bg-cyan-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-cyan-800 focus:outline-none focus:ring-4 focus:ring-cyan-200">
                            <i class="fas fa-check-double"></i>
                            Verify OTP
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
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
