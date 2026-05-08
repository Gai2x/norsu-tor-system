<?php include __DIR__ . '/../../public/admin/includes/AdminHeader.php'; ?>

<div class="p-12">
    <h2 class="text-4xl font-bold mb-6">Student Details</h2>

    <div class="bg-white p-8 rounded-2xl shadow space-y-4">
        <p><strong>Name:</strong> <?= $student['name'] ?></p>
        <p><strong>Student ID:</strong> <?= $student['student_id'] ?></p>
        <p><strong>Email:</strong> <?= $student['email'] ?></p>
        <p><strong>Course:</strong> <?= $student['course'] ?? 'N/A' ?></p>
        <p><strong>Date Registered:</strong> <?= !empty($student['created_at']) ? date("M d, Y", strtotime($student['created_at'])) : 'N/A' ?></p>
    </div>

    <a href="Students.php" class="inline-block mt-6 text-blue-600 hover:underline">
        â† Back to Students
    </a>
</div>

<?php include __DIR__ . '/../../public/admin/includes/AdminFooter.php'; ?>
