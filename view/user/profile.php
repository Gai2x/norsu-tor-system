<?php
if (!function_exists('escapeProfileValue')) {
    function escapeProfileValue($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

include __DIR__ . '/../../public/includes/user/Header.php';
?>

<div class="p-4 sm:p-6 lg:p-8 animate-fade-in-up">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between mb-6">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-800">My Profile</h1>
            <p class="text-slate-500 mt-2 text-sm sm:text-base">Manage your personal information and account settings</p>
        </div>

        <button onclick="openModal()" type="button" class="inline-flex items-center justify-center gap-2 bg-blue-800 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-xl shadow-md transition">
            <i class="fas fa-pen text-sm"></i>
            <span>Edit Profile</span>
        </button>
    </div>

    <section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 sm:p-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                    <div class="relative">
                        <?php if (!empty($user['profile_image'])): ?>
                            <img src="../../uploads/<?php echo $user['profile_image']; ?>" class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-lg">
                        <?php else: ?>
                            <div class="w-28 h-28 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-white flex items-center justify-center shadow-lg text-4xl font-bold">
                                <?php echo escapeProfileValue($profileInitial); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-800"><?php echo escapeProfileValue($fullName !== '' ? $fullName : 'Student User'); ?></h2>
                        <p class="text-slate-500 mt-1">Student ID: <?php echo escapeProfileValue($displayStudentId); ?></p>

                        <div class="flex flex-wrap gap-2 mt-3">
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-medium">
                                <i class="far fa-id-badge text-xs"></i>
                                <?php echo escapeProfileValue($displayCourse); ?>
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
                <h3 class="text-2xl font-bold text-slate-800 mb-6">Personal Information</h3>
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-2">First Name</label>
                        <div class="flex items-center min-h-[56px] rounded-xl border border-slate-200 bg-slate-50 px-4 text-slate-800"><?php echo escapeProfileValue($displayFirstName); ?></div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-2">Last Name</label>
                        <div class="flex items-center min-h-[56px] rounded-xl border border-slate-200 bg-slate-50 px-4 text-slate-800"><?php echo escapeProfileValue($displayLastName); ?></div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-2">Middle Name</label>
                        <div class="flex items-center min-h-[56px] rounded-xl border border-slate-200 bg-slate-50 px-4 text-slate-800"><?php echo escapeProfileValue($displayMiddleName); ?></div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-2">Date of Birth</label>
                        <div class="flex items-center gap-3 min-h-[56px] rounded-xl border border-slate-200 bg-slate-50 px-4 text-slate-800">
                            <i class="far fa-calendar text-slate-400"></i>
                            <span><?php echo escapeProfileValue($displayDateOfBirth); ?></span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-2">Email Address</label>
                        <div class="flex items-center gap-3 min-h-[56px] rounded-xl border border-slate-200 bg-slate-50 px-4 text-slate-800 break-all">
                            <i class="far fa-envelope text-slate-400"></i>
                            <span><?php echo escapeProfileValue($displayEmail); ?></span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-2">Phone Number</label>
                        <div class="flex items-center gap-3 min-h-[56px] rounded-xl border border-slate-200 bg-slate-50 px-4 text-slate-800">
                            <i class="fas fa-phone-alt text-slate-400"></i>
                            <span><?php echo escapeProfileValue($displayPhoneNumber); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6">
            <h2 class="text-2xl font-bold mb-4 text-slate-800">Edit Profile</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block mb-2 font-semibold">Full Name</label>
                    <input type="text" name="name" value="<?php echo escapeProfileValue($fullName); ?>" class="w-full border rounded-lg px-4 py-3">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 font-semibold">Middle Name</label>
                    <input type="text" name="middle_name" value="<?php echo escapeProfileValue($user['middle_name'] ?? ''); ?>" class="w-full border rounded-lg px-4 py-3">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 font-semibold">Date of Birth</label>
                    <input type="date" name="dob" value="<?php echo escapeProfileValue($user['dob'] ?? ''); ?>" class="w-full border rounded-lg px-4 py-3">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 font-semibold">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" placeholder="09XXXXXXXXX or +639XXXXXXXXX" value="<?php echo escapeProfileValue($user['phone_number'] ?? ''); ?>" class="w-full border rounded-lg px-4 py-3" oninput="validatePhone(this)">
                    <p class="text-xs text-gray-500 mt-1">Format: 09XXXXXXXXX or +639XXXXXXXXX</p>
                    <p id="phone_error" class="text-red-500 text-xs mt-1 hidden">Invalid phone number format</p>
                </div>
                <div class="mb-4">
                    <label class="block mb-2 font-semibold">Email</label>
                    <input type="email" name="email" value="<?php echo escapeProfileValue($displayEmail); ?>" class="w-full border rounded-lg px-4 py-3">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 font-semibold">Course</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-book text-gray-400"></i>
                        </div>
                        <select name="course" class="w-full border rounded-lg px-4 py-3 pl-10 pr-10 bg-white appearance-none">
                            <option value="" disabled>Select Course</option>
                            <option value="BSIT" <?php if($user['course'] == "BSIT") echo "selected"; ?>>BS Information Technology</option>
                            <option value="BSCS" <?php if($user['course'] == "BSCS") echo "selected"; ?>>BS Computer Science</option>
                            <option value="BSBA" <?php if($user['course'] == "BSBA") echo "selected"; ?>>BS Business Administration</option>
                            <option value="BSF" <?php if($user['course'] == "BSF") echo "selected"; ?>>BS Fisheries</option>
                            <option value="BSC" <?php if($user['course'] == "BSC") echo "selected"; ?>>BS Criminology</option>
                            <option value="BSOA" <?php if($user['course'] == "BSOA") echo "selected"; ?>>BS Office Administration</option>
                            <option value="BEED" <?php if($user['course'] == "BEED") echo "selected"; ?>>BS Elementary Education</option>
                            <option value="BSED" <?php if($user['course'] == "BSED") echo "selected"; ?>>BS Secondary Education</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block mb-2 font-semibold">Profile Picture</label>
                    <input type="file" name="profile_pic" accept="image/*" class="w-full border rounded-lg px-4 py-3">
                    <p class="text-sm text-gray-500 mt-1">Leave empty to keep current image</p>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded-lg">Cancel</button>
                    <button type="submit" name="update_profile" class="px-5 py-2 bg-blue-800 text-white rounded-lg">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
}
function validatePhone(input) {
    let value = input.value;
    const error = document.getElementById('phone_error');
    const regex = /^(09\d{9}|\+639\d{9})$/;

    if (value === '') {
        error.classList.add('hidden');
        input.style.borderColor = '';
        return;
    }

    if (!regex.test(value)) {
        error.classList.remove('hidden');
        input.style.borderColor = 'red';
    } else {
        error.classList.add('hidden');
        input.style.borderColor = 'green';
    }

    document.getElementById("phone_number").addEventListener("blur", function () {
        let blurValue = this.value;
        if (blurValue.startsWith("09") && blurValue.length === 11) {
            console.log("Valid local format");
        }
    });
}
</script>

<?php include __DIR__ . '/../../public/includes/user/Footer.php'; ?>
