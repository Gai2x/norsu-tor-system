<?php
session_start();

if (!isset($_SESSION['google_email'])) {
    header("Location: Login.php");
    exit();
}

$email = $_SESSION['google_email'];
$name  = $_SESSION['google_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Complete Profile</title>

<!-- Tailwind -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
body {
    font-family: 'Inter', sans-serif;
}
</style>

</head>
<body class="bg-gradient-to-br from-blue-800 via-blue-600 to-blue-400 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden">

    <!-- HEADER -->
    <div class="text-center pt-8 pb-2 px-8">
        <img src="img/logo.png" class="w-24 mx-auto mb-4">

        <h1 class="text-3xl font-bold text-gray-800">
            Complete Profile
        </h1>

        <p class="text-gray-500 text-sm mt-1">
            Finish setting up your Google account
        </p>
    </div>

    <!-- FORM -->
    <div class="px-8 pb-8 pt-4">

        <form method="POST" action="SaveGoogleProfile.php" class="space-y-5">

            <div class="grid md:grid-cols-2 gap-5">

                <!-- NAME -->
                <div>
                    <label class="block text-sm font-medium mb-2">
                        Full Name
                    </label>

                    <input type="text"
                           name="name"
                           value="<?= htmlspecialchars($name) ?>"
                           class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <!-- EMAIL -->
                <div>
                    <label class="block text-sm font-medium mb-2">
                        Email Address
                    </label>

                    <input type="email"
                           value="<?= htmlspecialchars($email) ?>"
                           class="w-full border rounded-lg p-3 bg-gray-100 text-gray-500"
                           disabled>
                </div>

                <!-- STUDENT ID -->
                <div>
                    <label class="block text-sm font-medium mb-2">
                        Student ID
                    </label>

                    <input type="text"
                           name="student_id"
                           placeholder="2021-12345"
                           class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <!-- COURSE -->
                <div>
                    <label class="block text-sm font-medium mb-2">
                        Course
                    </label>

                    <select name="course"
                            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500"
                            required>

                        <option value="">Select Course</option>
                        <option value="BSIT">BS Information Technology</option>
                        <option value="BSCS">BS Computer Science</option>
                        <option value="BSBA">BS Business Administration</option>
                        <option value="BSF">BS Fisheries</option>
                        <option value="BSC">BS Criminology</option>
                        <option value="BSOA">BS Office Administration</option>
                        <option value="BEED">BS Elementary Education</option>
                        <option value="BSED">BS Secondary Education</option>
                    </select>
                </div>

            </div>

            <!-- BUTTON -->
            <button type="submit"
                    name="complete_google"
                    class="w-full bg-blue-900 hover:bg-blue-800 text-white font-semibold py-3 rounded-lg transition">

                Complete Registration
            </button>

        </form>

    </div>

</div>

</body>
</html>
