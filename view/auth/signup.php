<?php include __DIR__ . '/../../public/includes/landing/Header.php'; ?>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    body { font-family: 'Inter', sans-serif; }
</style>

<div class="bg-gradient-to-br from-blue-800 via-blue-600 to-blue-400 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden mt-20">
        <div class="text-center pt-8 pb-2 px-8">
            <div class="relative w-20 h-20 mx-auto mb-3">
                <img src="img/logo.png" alt="NORSU Logo" class="w-full h-full object-contain">
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-1">Register Account</h1>
            <p class="text-gray-500 text-sm">Create your NORSU student account</p>
        </div>

        <div class="px-8 pb-8 pt-4">
            <?php if (!empty($errors)): ?>
            <div class="mb-4 space-y-2">
                <?php foreach ($errors as $error): ?>
                <div class="p-3 text-sm text-red-600 bg-red-50 rounded-lg border border-red-200 flex items-center" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="student_id" class="block mb-2 text-sm font-medium text-gray-700">Student ID Number <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="far fa-user text-gray-400"></i>
                            </div>
                            <input type="text" id="student_id" name="student_id" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" pattern="[0-9]{4}-[0-9]{5}" placeholder="2021-12345" required>
                        </div>
                    </div>

                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="far fa-user text-gray-400"></i>
                            </div>
                            <input type="text" id="name" name="name" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="Juan Dela Cruz" pattern="[A-Za-z\s]+" title="Name should contain letters only" required>
                        </div>
                    </div>

                    <div>
                        <label for="course" class="block mb-2 text-sm font-medium text-gray-700">Course <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-book text-gray-400"></i>
                            </div>
                            <select id="course" name="course" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 appearance-none cursor-pointer" required>
                                <option value="" disabled selected>Select Course</option>
                                <option value="BSIT">BS Information Technology</option>
                                <option value="BSCS">BS Computer Science</option>
                                <option value="BSBA">BS Business Administration</option>
                                <option value="BSF">BS Fisheries</option>
                                <option value="BSC">BS Criminology</option>
                                <option value="BSOA">BS Office Administration</option>
                                <option value="BEED">BS Elementary Education</option>
                                <option value="BSED">BS Secondary Education</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email Address <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="far fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" id="email" name="email" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="student@norsu.edu.ph" maxlength="100" required>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" id="password" name="password" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="Enter password" minlength="6" pattern="(?=.*[A-Z])(?=.*[0-9]).{6,}" title="Password must be at least 6 characters and include one uppercase letter and one number" required>
                            <button type="button" onclick="togglePassword('password','toggleIcon1')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700">
                                <i id="toggleIcon1" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-700">Confirm Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" id="confirm_password" name="confirm_password" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="Confirm password" required>
                            <button type="button" onclick="togglePassword('confirm_password','toggleIcon2')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700">
                                <i id="toggleIcon2" class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            Password must be at least 6 characters, include one uppercase letter and one number.
                        </p>
                    </div>
                </div>

                <button type="submit" name="register" class="w-full text-white bg-blue-900 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors mt-6">
                    Register Account
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="Login.php" class="font-semibold text-blue-800 hover:underline">Login here</a>
                </p>
            </div>

            <div class="text-center mt-3">
                <a href="../src/Home.php" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-700 transition-colors group">
                        <i class="fas fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
                        Back to Home
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId, iconId) {
    const passwordField = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);

    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>

<?php include __DIR__ . '/../../public/includes/landing/Footer.php'; ?>
