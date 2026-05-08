<?php
$page = "success";
$bodyClass = "bg-gray-100 min-h-screen";
include('includes/landing/Header.php');
?>

<div class="flex-grow flex items-center justify-center bg-gradient-to-br from-blue-800 via-blue-600 to-blue-500 min-h-screen p-4">

    <div class="bg-white max-w-xl w-full rounded-2xl shadow-xl p-10 text-center">

        <!-- Icon -->
        <div class="w-24 h-24 mx-auto bg-green-100 text-green-600 rounded-full flex items-center justify-center text-5xl mb-6">
            ✓
        </div>

        <!-- Title -->
        <h1 class="text-4xl font-bold text-gray-800 mb-4">
            Request Submitted Successfully!
        </h1>

        <!-- Message -->
        <p class="text-gray-600 text-lg mb-8 leading-relaxed">
            Your one-time academic service request has been received.
            Please wait while our admin reviews your request.
        </p>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 text-left mb-8">

            <h3 class="font-semibold text-blue-800 mb-3">
                What happens next?
            </h3>

            <ul class="text-sm text-gray-700 space-y-2">
                <li>• Your request will be reviewed by the admin.</li>
                <li>• Processing time depends on request type.</li>
                <li>• You may be contacted through your email or phone number.</li>
                <li>• Keep your phone available for updates.</li>
            </ul>

        </div>

        <!-- Buttons -->
        <div class="flex flex-col md:flex-row gap-4 justify-center">

            <a href="login.php"
               class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-3 rounded-lg font-semibold transition">
                Login Account
            </a>

            <a href="../src/Home.php" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-700 transition-colors group">
                <i class="fas fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
                Back to Home
            </a>

        </div>

    </div>

</div>

<?php include('includes/landing/Footer.php'); ?>
