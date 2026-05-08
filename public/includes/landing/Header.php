<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'NORSU Academic Services'; ?></title>

    <!-- Tailwind (compiled) -->
    <link rel="stylesheet" href="../public/assets/css/output.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="<?= $bodyClass ?? '' ?>">

<!-- ================= NAVBAR ================= -->
<nav class="bg-blue-700 fixed w-full top-0 left-0 z-50 shadow-md">

    <div class="max-w-screen-xl mx-auto flex items-center justify-between px-4 py-5">

        <!-- Logo -->
        <div class="flex items-center gap-3">
            <img src="../public/img/logo.png" class="h-7">
            <span class="text-white text-lg md:text-xl font-semibold">
                NORSU Bais
            </span>
        </div>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center gap-8">

            <ul class="flex items-center gap-8 font-medium text-white">

                <?php if(!isset($page) || ($page !== "auth" && $page !== "one_time_request")): ?>
                    <li><a href="#about" class="hover:text-blue-300 transition">About</a></li>
                    <li><a href="#services" class="hover:text-blue-300 transition">Services</a></li>
                    <li><a href="#contact" class="hover:text-blue-300 transition">Contact Us</a></li>
                <?php endif; ?>

            </ul>

            <div class="flex gap-3">

                <?php if(isset($page) && $page === "auth"): ?>

                    <a href="../src/Home.php"
                    class="inline-flex items-center text-sm font-medium text-white hover:text-yellow-500 transition-colors group">
                        <i class="fas fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
                        Back to Dashboard
                    </a>

                <?php elseif(isset($page) && $page === "one_time_request"): ?>

                    <a href="../src/Home.php"
                    class="inline-flex items-center text-sm font-medium text-white hover:text-yellow-500 transition-colors group">
                        <i class="fas fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
                        Back to Dashboard
                    </a>

                <?php else: ?>

                    <a href="../public/Login.php"
                    class="text-white border border-white px-4 py-2 rounded-lg text-sm hover:bg-white hover:text-blue-700 transition">
                        Login
                    </a>

                    <a href="../public/Signup.php"
                    class="bg-yellow-400 text-black px-4 py-2 rounded-lg text-sm hover:bg-yellow-500 transition">
                        Sign Up
                    </a>

                <?php endif; ?>
            </div>
        </div>

        <!-- Burger Button -->
        <button id="menuBtn" class="md:hidden text-white text-2xl">
            <i class="fas fa-bars"></i>
        </button>

    </div>

    <!-- MOBILE MENU -->
    <div id="mobileMenu"
        class="hidden md:hidden bg-blue-700 text-white px-6 pb-4 space-y-4">

        <?php if(!isset($page) || ($page !== "auth" && $page !== "one_time_request")): ?>
        <a href="#about" class="block py-2 border-b border-blue-600">About</a>
        <a href="#services" class="block py-2 border-b border-blue-600">Services</a>
        <a href="#contact" class="block py-2 border-b border-blue-600">Contact Us</a>
        <?php endif; ?>

        <?php if(isset($page) && $page === "auth"): ?>

            <a href="../src/Home.php"
            class="inline-flex items-center text-sm font-medium text-white hover:text-yellow-500 transition-colors group">
                <i class="fas fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
                Back to Home
            </a>

        <?php elseif(isset($page) && $page === "one_time_request"): ?>

            <a href="../src/Home.php"
            class="inline-flex items-center text-sm font-medium text-white hover:text-yellow-500 transition-colors group">
                <i class="fas fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
                Back to Dashboard
            </a>

        <?php else: ?>

            <a href="../public/Login.php"
            class="block text-center border border-white px-4 py-2 rounded-lg">
                Login
            </a>

            <a href="../public/Signup.php"
            class="block text-center bg-yellow-400 text-black px-4 py-2 rounded-lg">
                Sign Up
            </a>

        <?php endif; ?>
    </div>

</nav>
