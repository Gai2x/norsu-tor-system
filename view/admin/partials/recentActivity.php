<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
    <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
        <div class="px-4 sm:px-6 py-4 sm:py-5 border-b bg-gradient-to-r from-blue-50 to-white flex justify-between items-center gap-2">
            <h3 class="text-lg sm:text-2xl font-bold text-gray-800">
                <i class="fas fa-calendar-week text-blue-600 mr-2"></i>Recent Appointments
            </h3>
            <a href="appointments.php" class="text-blue-600 hover:text-blue-800 text-sm font-semibold no-underline">View All &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[560px] text-left">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3">Student</th>
                        <th class="px-6 py-3">Date & Time</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($recentAppointments) && !empty($recentAppointments)): ?>
                        <?php foreach ($recentAppointments as $appointment): ?>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium"><?php echo htmlspecialchars($appointment['student_name'] ?? 'N/A'); ?></td>
                            <td class="px-6 py-4 text-sm">
                                <?php echo date("M d, Y g:i A", strtotime($appointment['appointment_date'] ?? $appointment['created_at'] ?? 'now')); ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php
                                $status = $appointment['status'] ?? 'pending';
                                $badgeClass = $status == 'approved' ? 'bg-green-100 text-green-700' : ($status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700');
                                $statusText = ucfirst($status);
                                ?>
                                <span class="<?php echo $badgeClass; ?> px-3 py-1 rounded-full text-xs font-semibold"><?php echo $statusText; ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center py-8 text-gray-500">No appointments found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
        <div class="px-4 sm:px-6 py-4 sm:py-5 border-b bg-gradient-to-r from-purple-50 to-white flex justify-between items-center gap-2">
            <h3 class="text-lg sm:text-2xl font-bold text-gray-800">
                <i class="fas fa-file-signature text-purple-600 mr-2"></i>Recent Requests
            </h3>
            <a href="requests.php" class="text-purple-600 hover:text-purple-800 text-sm font-semibold no-underline">View All &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] text-left">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3">Student</th>
                        <th class="px-6 py-3">Service</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($recentRequests) && !empty($recentRequests)): ?>
                        <?php foreach ($recentRequests as $req): ?>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium"><?php echo htmlspecialchars($req['student_name'] ?? $req['name'] ?? 'N/A'); ?></td>
                            <td class="px-6 py-4 text-sm"><?php echo htmlspecialchars($req['service_type'] ?? 'N/A'); ?></td>
                            <td class="px-6 py-4 text-sm"><?php echo date("M d, Y", strtotime($req['created_at'] ?? 'now')); ?></td>
                            <td class="px-6 py-4">
                                <?php
                                $status = $req['status'] ?? 'pending';
                                $badgeClass = $status == 'approved' ? 'bg-green-100 text-green-700' : ($status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700');
                                $statusText = ucfirst($status);
                                ?>
                                <span class="<?php echo $badgeClass; ?> px-3 py-1 rounded-full text-xs font-semibold"><?php echo $statusText; ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-8 text-gray-500">No requests found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="lg:col-span-2 bg-white rounded-3xl shadow-lg overflow-hidden">
        <div class="px-4 sm:px-6 py-4 sm:py-5 border-b bg-gradient-to-r from-indigo-50 to-white flex justify-between items-center gap-2">
            <h3 class="text-lg sm:text-2xl font-bold text-gray-800">
                <i class="fas fa-user-plus text-indigo-600 mr-2"></i>Recently Joined Students
            </h3>
            <a href="students.php" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold no-underline">View All &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3">Student Name</th>
                        <th class="px-6 py-3">Student ID</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Joined Date</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($recentStudents) && !empty($recentStudents)): ?>
                        <?php foreach ($recentStudents as $student): ?>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium"><?php echo htmlspecialchars($student['name'] ?? $student['fullname'] ?? 'N/A'); ?></td>
                            <td class="px-6 py-4 text-sm"><?php echo htmlspecialchars($student['student_id'] ?? 'N/A'); ?></td>
                            <td class="px-6 py-4 text-sm"><?php echo htmlspecialchars($student['email'] ?? 'N/A'); ?></td>
                            <td class="px-6 py-4 text-sm"><?php echo date("M d, Y", strtotime($student['created_at'] ?? 'now')); ?></td>
                            <td class="px-6 py-4">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Active</span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500">No students found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
