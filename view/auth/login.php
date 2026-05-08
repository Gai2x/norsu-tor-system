<?php include __DIR__ . '/../../public/includes/landing/Header.php'; ?>

<div class="min-h-screen flex flex-col">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e40af',
                        secondary: '#fbbf24',
                    }
                }
            }
        };
    </script>

    <div class="flex-grow flex items-center justify-center bg-gradient-to-br from-blue-800 via-blue-600 to-blue-500 min-h-screen p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden mt-20">
            <div class="text-center pt-8 pb-4 px-8">
                <div class="relative w-24 h-24 mx-auto mb-4">
                    <img src="img/logo.png" class="w-full h-full object-contain drop-shadow-md" alt="NORSU Logo">
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-1">NORSU Bais</h1>
                <p class="text-gray-500 text-sm">Student Appointment System</p>
            </div>

            <div class="px-8 pb-8">
                <?php if (!empty($error)): ?>
                <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-200 flex items-center animate-fade-in" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg border border-green-200 flex items-center animate-fade-in" role="status">
                    <i class="fas fa-circle-check mr-2"></i>
                    <span><?php echo htmlspecialchars($success); ?></span>
                </div>
                <?php endif; ?>

                <form method="POST" class="space-y-5">
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Student ID / Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="text" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-3 transition-all duration-200 hover:border-blue-300" placeholder="Enter your student ID or email" required>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-3 transition-all duration-200 hover:border-blue-300" placeholder="Enter your password" required>
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 cursor-pointer">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" name="login" class="w-full text-white bg-blue-800 hover:bg-blue-900 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 text-center transition-all duration-200 transform hover:scale-[1.02] shadow-lg hover:shadow-xl">
                        Login
                    </button>
                </form>

                <div class="flex justify-between items-center mt-6 text-sm">
                    <a href="Signup.php" class="text-blue-700 hover:text-blue-800 font-medium hover:underline transition-colors">Register Account</a>
                    <a href="ForgotPassword.php" class="text-gray-600 hover:text-gray-800 font-medium hover:underline transition-colors">
                        Forgot Password?
                    </a>
                </div>

                <div class="relative flex py-5 items-center">
                                    <div class="flex-grow border-t border-gray-300"></div>
                                    <span class="flex-shrink-0 mx-4 text-gray-400 text-sm font-medium">OR</span>
                                    <div class="flex-grow border-t border-gray-300"></div>
                                </div>

                <a href="google-login.php"
                class="w-full flex items-center justify-center gap-3 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-lg px-5 py-3 transition">

                <svg class="w-5 h-5" viewBox="0 0 48 48">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.7 1.22 9.2 3.6l6.85-6.85C35.91 2.5 30.36 0 24 0 14.64 0 6.56 5.38 2.56 13.22l7.98 6.19C12.36 13.09 17.74 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.1 24.55c0-1.64-.15-3.21-.42-4.73H24v8.95h12.4c-.54 2.9-2.18 5.35-4.64 7v5.8h7.5c4.39-4.04 6.84-10 6.84-17.02z"/>
                    <path fill="#FBBC05" d="M10.54 28.41a14.5 14.5 0 0 1 0-8.82v-5.8H3.04a24 24 0 0 0 0 20.42l7.5-5.8z"/>
                    <path fill="#34A853" d="M24 48c6.48 0 11.92-2.14 15.9-5.82l-7.5-5.8c-2.08 1.4-4.74 2.22-8.4 2.22-6.26 0-11.64-4.23-13.55-9.91l-7.5 5.8C6.56 42.62 14.64 48 24 48z"/>
                </svg>

                Continue with Google
                </a>

                <div class="relative flex py-5 items-center">
                    <div class="flex-grow border-t border-gray-300"></div>
                    <span class="mx-4 text-gray-400 text-sm">OR</span>
                    <div class="flex-grow border-t border-gray-300"></div>
                </div>

                <a href="OneTimeRequest.php"
                class="block w-full text-blue-800 bg-white border-2 border-yellow-400 hover:bg-yellow-50 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-3 text-center transition-all duration-200 transform hover:scale-[1.02] shadow-md">
                    One-Time Request
                </a>

                <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-100">
                    <p class="text-center text-xs text-gray-600">
                        <span class="font-semibold text-blue-800">Demo:</span> Use "admin@norsu.edu" for admin or any other email for student
                    </p>
                </div>

                <div class="text-center mt-6">
                    <a href="../src/Home.php" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-700 transition-colors group">
                        <i class="fas fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const password = document.getElementById("password");
    const icon = document.getElementById("eyeIcon");

    if (password.type === "password") {
        password.type = "text";
        icon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        password.type = "password";
        icon.classList.replace("fa-eye-slash", "fa-eye");
    }
}
</script>

<?php include __DIR__ . '/../../public/includes/landing/Footer.php'; ?>
