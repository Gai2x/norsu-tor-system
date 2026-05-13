<?php
session_start();
include __DIR__ . '/../../public/includes/landing/Header.php';

/* preserve old values */
$old = $_SESSION['old'] ?? [];
$errors = $_SESSION['errors'] ?? [];

/* clear after reading */
unset($_SESSION['old']);
unset($_SESSION['errors']);
?>

<div class="min-h-screen flex flex-col">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .input-error {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }
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

        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden mt-20">

            <div class="bg-gradient-to-r from-blue-700 to-indigo-700 px-8 py-6">
                <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    One-Time Request
                </h2>

                <p class="text-blue-100 text-sm mt-1">
                    Submit a request without creating an account
                </p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="mx-8 mt-6 bg-red-50 border border-red-300 text-red-700 px-5 py-4 rounded-xl">
                    <h3 class="font-semibold mb-2">Please fix the following:</h3>

                    <ul class="list-disc pl-5 text-sm space-y-1">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form id="requestForm"
                action="/Norsu_Tor/controller/user/OneTimeRequestController.php"
                method="POST"
                class="p-8 space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Student ID -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Student ID Number <span class="text-red-500">*</span>
                        </label>

                        <input type="text"
                            id="student_id"
                            name="student_id"
                            value="<?= htmlspecialchars($old['student_id'] ?? '') ?>"
                            required
                            pattern="[0-9]{4}-[0-9]{5}"
                            maxlength="10"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 bg-gray-50"
                            placeholder="2021-12345">

                        <p class="text-xs text-gray-400 mt-1">
                            Format: 2021-12345
                        </p>
                    </div>

                    <!-- Full Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Full Name <span class="text-red-500">*</span>
                        </label>

                        <input type="text"
                            id="fullname"
                            name="fullname"
                            value="<?= htmlspecialchars($old['fullname'] ?? '') ?>"
                            required
                            minlength="5"
                            maxlength="100"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 bg-gray-50"
                            placeholder="Juan Dela Cruz">
                    </div>

                    <!-- Contact -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Contact Number <span class="text-red-500">*</span>
                        </label>

                        <input type="tel"
                            id="contact"
                            name="contact"
                            value="<?= htmlspecialchars($old['contact'] ?? '') ?>"
                            required
                            maxlength="11"
                            pattern="09[0-9]{9}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 bg-gray-50"
                            placeholder="09123456789">

                        <p class="text-xs text-gray-400 mt-1">
                            Must start with 09 and 11 digits total
                        </p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Email Address <span class="text-red-500">*</span>
                        </label>

                        <input type="email"
                            id="email"
                            name="email"
                            value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 bg-gray-50"
                            placeholder="email@example.com">
                    </div>

                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Category <span class="text-red-500">*</span>
                    </label>

                    <select id="category"
                        name="category"
                        required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 bg-gray-50">
                        <option value="">Select Category</option>
                        <option value="Document Request" <?= (($old['category'] ?? '') === 'Document Request') ? 'selected' : ''; ?>>Document Request</option>
                        <option value="Appointment" <?= (($old['category'] ?? '') === 'Appointment') ? 'selected' : ''; ?>>Appointment</option>
                    </select>
                </div>

                <div id="appointmentFields" class="grid grid-cols-1 md:grid-cols-2 gap-6 hidden">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Appointment Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                            id="appointment_date"
                            name="appointment_date"
                            value="<?= htmlspecialchars($old['appointment_date'] ?? '') ?>"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 bg-gray-50">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Appointment Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time"
                            id="appointment_time"
                            name="appointment_time"
                            value="<?= htmlspecialchars($old['appointment_time'] ?? '') ?>"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 bg-gray-50">
                    </div>
                </div>

                <!-- Service -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Service Type <span class="text-red-500">*</span>
                    </label>

                    <select id="service_type"
                        name="service_type"
                        required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 bg-gray-50">

                        <option value="">Select Service</option>

                        <?php
                        $services = [
                            "TOR" => "📄 TOR (Transcript of Records)",
                            "Certificate_of_Enrollment" => "📑 Certificate of Enrollment",
                            "Certificate_of_Grades" => "📊 Certificate of Grades",
                            "Good_Moral" => "⭐ Good Moral Certificate",
                            "Units_Earned" => "🎓 Certification of Units Earned",
                            "Diploma" => "🏅 Diploma Request",
                            "Honorable_Dismissal" => "📜 Honorable Dismissal",
                            "Grade_Consultation" => "📖 Grade Consultation",
                            "Subject_Crediting" => "🔄 Subject Crediting",
                            "Name_Correction" => "✏️ Correction of Name / Data"
                        ];

                        foreach ($services as $value => $label):
                        ?>
                            <option value="<?= $value; ?>"
                                <?= (($old['service_type'] ?? '') === $value) ? 'selected' : ''; ?>>
                                <?= $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Purpose -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Notes / Purpose
                    </label>

                    <select
                        id="notes"
                        name="notes"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 bg-gray-50">
                        <option value="">Select Purpose</option>
                        <?php
                        $purposeOptions = [
                            'For enrollment requirements',
                            'For scholarship application',
                            'For employment application',
                            'For board exam or licensure requirement',
                            'For personal academic record',
                            'For academic consultation',
                            'Other...'
                        ];

                        foreach ($purposeOptions as $purpose):
                        ?>
                            <option value="<?= htmlspecialchars($purpose); ?>" <?= (($old['notes'] ?? '') === $purpose) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($purpose); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <input type="text"
                        id="notes_other"
                        name="notes_other"
                        value="<?= htmlspecialchars($old['notes_other'] ?? '') ?>"
                        class="mt-3 hidden w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 bg-gray-50"
                        placeholder="Enter your purpose">

                    <p class="text-xs text-gray-400 mt-1">
                        Optional
                    </p>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 px-6 rounded-xl shadow-md">

                    Submit Request
                </button>

                <!-- Links -->
                <div class="text-center pt-4 border-t border-gray-100 mt-4">
                    <p class="text-sm text-gray-600">
                        Have an account?
                        <a href="login.php"
                            class="text-blue-600 font-semibold hover:underline">
                            Login here
                        </a>
                    </p>

                    <a href="/Norsu_Tor/src/Home.php"
                        class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-700 transition-colors group">
                        <i class="fas fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
                         Back to Home
                    </a>
                </div>

            </form>

            <div class="px-8 pb-8 text-center text-xs text-gray-500">
                <p>📋 No account registration required.</p>
                <p class="mt-1">🔒 Your data is handled securely.</p>
            </div>

        </div>
    </div>
</div>

<script>
document.getElementById("requestForm").addEventListener("submit", function(e) {

    let valid = true;
    const student = document.getElementById("student_id");
    const fullname = document.getElementById("fullname");
    const contact = document.getElementById("contact");
    const category = document.getElementById("category");
    const appointmentDate = document.getElementById("appointment_date");
    const appointmentTime = document.getElementById("appointment_time");
    const notes = document.getElementById("notes");
    const notesOther = document.getElementById("notes_other");

    document.querySelectorAll("input, select").forEach(el => {
        el.classList.remove("input-error");
    });

    if (!/^\d{4}-\d{5}$/.test(student.value.trim())) {
        student.classList.add("input-error");
        valid = false;
    }

    if (!/^[a-zA-Z\s]+$/.test(fullname.value.trim())) {
        fullname.classList.add("input-error");
        valid = false;
    }

    if (!/^09\d{9}$/.test(contact.value.trim())) {
        contact.classList.add("input-error");
        valid = false;
    }

    if (!category.value) {
        category.classList.add("input-error");
        valid = false;
    }

    if (category.value === "Appointment") {
        if (!appointmentDate.value) {
            appointmentDate.classList.add("input-error");
            valid = false;
        }
        if (!appointmentTime.value) {
            appointmentTime.classList.add("input-error");
            valid = false;
        }
    }

    if (notes.value === "Other..." && !notesOther.value.trim()) {
        notesOther.classList.add("input-error");
        valid = false;
    }

    if (!valid) {
        e.preventDefault();
        alert("Please correct highlighted fields.");
    }
});

const categorySelect = document.getElementById("category");
const appointmentFields = document.getElementById("appointmentFields");
const appointmentDate = document.getElementById("appointment_date");
const appointmentTime = document.getElementById("appointment_time");
const purposeSelect = document.getElementById("notes");
const purposeOther = document.getElementById("notes_other");

function syncAppointmentFields() {
    const showAppointmentFields = categorySelect.value === "Appointment";
    appointmentFields.classList.toggle("hidden", !showAppointmentFields);
    appointmentDate.required = showAppointmentFields;
    appointmentTime.required = showAppointmentFields;
    if (!showAppointmentFields) {
        appointmentDate.value = "";
        appointmentTime.value = "";
    }
}

function syncPurposeOther() {
    const showOther = purposeSelect.value === "Other...";
    purposeOther.classList.toggle("hidden", !showOther);
    purposeOther.required = showOther;
    if (!showOther) {
        purposeOther.value = "";
    }
}

categorySelect.addEventListener("change", syncAppointmentFields);
purposeSelect.addEventListener("change", syncPurposeOther);
syncAppointmentFields();
syncPurposeOther();
</script>

<?php include __DIR__ . '/../../public/includes/landing/Footer.php'; ?>
