<?php include __DIR__ . '/../../public/admin/includes/AdminHeader.php'; ?>

<div class="p-12">
    <h2 class="text-4xl font-bold mb-6">Edit Student</h2>

    <?php if (!empty($error)): ?>
        <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-200">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="bg-white p-8 rounded-2xl shadow space-y-5">
        <div>
            <label>Name</label>
            <input type="text" name="name" value="<?= $student['name'] ?>" class="w-full border p-3 rounded">
        </div>

        <div>
            <label>Email</label>
            <input type="email" name="email" value="<?= $student['email'] ?>" class="w-full border p-3 rounded">
        </div>

        <div>
            <label class="block mb-2 font-medium">Course</label>
            <select id="course" name="course" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-pointer" required>
                <option value="" disabled>Select Course</option>
                <option value="BSIT" <?= ($student['course'] == "BSIT") ? 'selected' : '' ?>>BS Information Technology</option>
                <option value="BSCS" <?= ($student['course'] == "BSCS") ? 'selected' : '' ?>>BS Computer Science</option>
                <option value="BSBA" <?= ($student['course'] == "BSBA") ? 'selected' : '' ?>>BS Business Administration</option>
                <option value="BSF" <?= ($student['course'] == "BSF") ? 'selected' : '' ?>>BS Fisheries</option>
                <option value="BSC" <?= ($student['course'] == "BSC") ? 'selected' : '' ?>>BS Criminology</option>
                <option value="BSOA" <?= ($student['course'] == "BSOA") ? 'selected' : '' ?>>BS Office Administration</option>
                <option value="BEED" <?= ($student['course'] == "BEED") ? 'selected' : '' ?>>BS Elementary Education</option>
                <option value="BSED" <?= ($student['course'] == "BSED") ? 'selected' : '' ?>>BS Secondary Education</option>
            </select>
        </div>

        <button type="submit" name="update" class="bg-blue-600 text-white px-6 py-3 rounded-xl">Update Student</button>
    </form>

    <a href="Students.php" class="inline-block mt-6 text-blue-600 hover:underline">
        â† Back to Students
    </a>
</div>

<?php include __DIR__ . '/../../public/admin/includes/AdminFooter.php'; ?>
