<?php include __DIR__ . '/../../public/admin/includes/AdminHeader.php'; ?>

<div class="p-4 sm:p-6 lg:p-10 space-y-8">
    <div>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-800 mb-2">Schedule Management</h2>
        <p class="text-gray-500 text-base sm:text-lg lg:text-2xl">Create schedule slots and monitor approved student appointments.</p>
    </div>

    <?php if (!empty($notice)): ?>
        <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700 font-semibold"><?php echo htmlspecialchars($notice); ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <section class="bg-white rounded-3xl shadow overflow-hidden">
        <div class="px-5 sm:px-8 py-6 border-b">
            <h3 class="text-2xl sm:text-3xl font-bold text-gray-800">Create Schedule</h3>
            <p class="text-sm text-gray-500 mt-1">Students are assigned to matching available schedules when appointments are approved.</p>
        </div>

        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-7 gap-4 p-5 sm:p-8">
            <input type="hidden" name="action" value="create_schedule">
            <select name="day_of_week" class="border border-gray-300 rounded-2xl px-4 py-3 min-h-[44px]" required>
                <option value="">Day</option>
                <?php foreach (['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $dayOption): ?>
                    <option value="<?php echo $dayOption; ?>"><?php echo $dayOption; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="time" name="start_time" class="border border-gray-300 rounded-2xl px-4 py-3 min-h-[44px]" required>
            <input type="time" name="end_time" class="border border-gray-300 rounded-2xl px-4 py-3 min-h-[44px]" required>
            <input type="text" name="office" placeholder="Office" class="border border-gray-300 rounded-2xl px-4 py-3 min-h-[44px]" required>
            <input type="text" name="service_type" placeholder="Service" class="border border-gray-300 rounded-2xl px-4 py-3 min-h-[44px]">
            <input type="number" name="max_slots" min="1" value="10" class="border border-gray-300 rounded-2xl px-4 py-3 min-h-[44px]" required>
            <button class="inline-flex min-h-[44px] items-center justify-center rounded-2xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-800">
                <i class="fas fa-plus mr-2"></i>Add
            </button>
            <input type="hidden" name="status" value="Available">
        </form>
    </section>

    <section class="bg-white rounded-3xl shadow overflow-hidden">
        <div class="px-5 sm:px-8 py-6 border-b flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
            <div>
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-800">Schedule Cards</h3>
                <p class="text-sm text-gray-500 mt-1">Each card shows assigned approved appointments and student details.</p>
            </div>
            <p class="text-sm text-gray-500"><span class="font-semibold">Summary:</span> <?php echo (int) $totalSlots; ?> schedules across <?php echo (int) $totalDays; ?> active weekdays</p>
        </div>

        <div class="p-5 sm:p-8 grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5">
            <?php foreach ($days as $day): ?>
                <div class="rounded-2xl border border-gray-200 bg-gray-50/70 p-4">
                    <h4 class="font-bold text-gray-800 text-lg mb-4 border-l-4 border-blue-500 pl-3"><?php echo htmlspecialchars($day); ?></h4>
                    <div class="space-y-4">
                        <?php if (!empty($weeklySchedules[$day])): ?>
                            <?php foreach ($weeklySchedules[$day] as $slot): ?>
                                <?php $assigned = $appointmentsBySchedule[(int) $slot['id']] ?? []; ?>
                                <article class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 space-y-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="font-mono text-sm font-semibold text-gray-800">
                                                <?php echo date("h:i A", strtotime($slot['start_time'])); ?> - <?php echo date("h:i A", strtotime($slot['end_time'])); ?>
                                            </p>
                                            <p class="text-sm text-gray-500 mt-1"><?php echo htmlspecialchars($slot['office']); ?></p>
                                            <p class="text-xs text-gray-400 mt-1"><?php echo htmlspecialchars(($slot['service_type'] ?? '') ?: 'All services'); ?></p>
                                        </div>
                                        <span class="<?php echo strtolower($slot['status']) === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?> rounded-full px-3 py-1 text-xs font-semibold">
                                            <?php echo htmlspecialchars($slot['status']); ?>
                                        </span>
                                    </div>

                                    <div class="text-xs text-gray-500">
                                        <?php echo (int) ($slot['booked_slots'] ?? count($assigned)); ?> / <?php echo (int) $slot['max_slots']; ?> approved appointments
                                    </div>

                                    <div class="rounded-xl bg-gray-50 border border-gray-100">
                                        <?php if (!empty($assigned)): ?>
                                            <?php foreach ($assigned as $appointment): ?>
                                                <div class="p-3 border-b last:border-b-0">
                                                    <p class="text-sm font-semibold text-gray-800"><?php echo htmlspecialchars($appointment['student_name'] ?? 'N/A'); ?></p>
                                                    <p class="text-xs text-gray-500"><?php echo htmlspecialchars($appointment['student_id'] ?? 'N/A'); ?></p>
                                                    <p class="text-xs text-gray-600 mt-1"><?php echo htmlspecialchars($appointment['service_type'] ?? 'N/A'); ?></p>
                                                    <span class="inline-flex mt-2 rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-700"><?php echo ucfirst($appointment['status']); ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p class="p-3 text-sm text-gray-400 italic">No approved appointments assigned.</p>
                                        <?php endif; ?>
                                    </div>

                                    <details class="rounded-xl border border-gray-100 bg-white">
                                        <summary class="cursor-pointer px-3 py-2 text-sm font-semibold text-blue-700">Edit Schedule</summary>
                                        <form method="POST" class="p-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <input type="hidden" name="action" value="update_schedule">
                                            <input type="hidden" name="schedule_id" value="<?php echo (int) $slot['id']; ?>">
                                            <select name="day_of_week" class="border rounded-xl px-3 py-2" required>
                                                <?php foreach (['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $dayOption): ?>
                                                    <option value="<?php echo $dayOption; ?>" <?php echo $slot['day_of_week'] === $dayOption ? 'selected' : ''; ?>><?php echo $dayOption; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <input type="text" name="office" value="<?php echo htmlspecialchars($slot['office']); ?>" class="border rounded-xl px-3 py-2" required>
                                            <input type="time" name="start_time" value="<?php echo htmlspecialchars($slot['start_time']); ?>" class="border rounded-xl px-3 py-2" required>
                                            <input type="time" name="end_time" value="<?php echo htmlspecialchars($slot['end_time']); ?>" class="border rounded-xl px-3 py-2" required>
                                            <input type="text" name="service_type" value="<?php echo htmlspecialchars($slot['service_type'] ?? ''); ?>" placeholder="Service" class="border rounded-xl px-3 py-2">
                                            <input type="number" name="max_slots" min="1" value="<?php echo (int) $slot['max_slots']; ?>" class="border rounded-xl px-3 py-2" required>
                                            <select name="status" class="border rounded-xl px-3 py-2">
                                                <option value="Available" <?php echo $slot['status'] === 'Available' ? 'selected' : ''; ?>>Available</option>
                                                <option value="Full" <?php echo $slot['status'] === 'Full' ? 'selected' : ''; ?>>Full</option>
                                            </select>
                                            <button class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Save</button>
                                        </form>
                                    </details>

                                    <form method="POST" onsubmit="return confirm('Delete this schedule? Approved appointments will remain, but will be unlinked from this schedule.');">
                                        <input type="hidden" name="action" value="delete_schedule">
                                        <input type="hidden" name="schedule_id" value="<?php echo (int) $slot['id']; ?>">
                                        <button class="w-full rounded-xl border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">Delete</button>
                                    </form>
                                </article>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-sm text-gray-400 italic">No schedules</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="bg-white rounded-3xl shadow overflow-hidden">
        <div class="px-5 sm:px-8 py-6 border-b">
            <h3 class="text-2xl sm:text-3xl font-bold text-gray-800">Approved Appointments</h3>
            <p class="text-sm text-gray-500 mt-1">Joined appointment, schedule, and student data.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px] text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                    <tr>
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Student ID</th>
                        <th class="px-6 py-4">Service</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Time</th>
                        <th class="px-6 py-4">Office</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($approvedAppointments)): ?>
                        <?php foreach ($approvedAppointments as $row): ?>
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-6 py-5 font-semibold text-gray-800"><?php echo htmlspecialchars($row['student_name'] ?? 'N/A'); ?></td>
                                <td class="px-6 py-5 text-gray-600"><?php echo htmlspecialchars($row['student_id'] ?? 'N/A'); ?></td>
                                <td class="px-6 py-5 text-gray-600"><?php echo htmlspecialchars($row['service_type'] ?? 'N/A'); ?></td>
                                <td class="px-6 py-5 text-gray-600"><?php echo !empty($row['appointment_date']) ? date("M d, Y", strtotime($row['appointment_date'])) : 'N/A'; ?></td>
                                <td class="px-6 py-5 text-gray-600"><?php echo !empty($row['appointment_time']) ? date("h:i A", strtotime($row['appointment_time'])) : 'N/A'; ?></td>
                                <td class="px-6 py-5 text-gray-600"><?php echo htmlspecialchars($row['office'] ?? 'Unassigned'); ?></td>
                                <td class="px-6 py-5"><span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Approved</span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-12 text-gray-400">No approved appointments yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php include __DIR__ . '/../../public/admin/includes/AdminFooter.php'; ?>
