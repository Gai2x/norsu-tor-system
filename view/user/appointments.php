<?php include __DIR__ . '/../../public/includes/user/Header.php'; ?>

<div class="p-4 sm:p-6 space-y-6 animate-fade-in-up">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">My Appointments</h1>
            <p class="text-gray-500 mt-1">Schedule and manage your academic appointments</p>
        </div>
        <button onclick="openBookingModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl transition flex items-center gap-2 shadow-md hover:shadow-lg">
            <i class="fas fa-plus"></i>
            <span>Book New Appointment</span>
        </button>
    </div>

    <?php if ($message): ?>
    <div class="message-slide p-4 rounded-xl <?php echo $messageType === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800'; ?>">
        <div class="flex items-center gap-3">
            <i class="fas <?php echo $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> text-xl"></i>
            <p><?php echo htmlspecialchars($message); ?></p>
        </div>
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-calendar-week text-blue-600"></i>
                Upcoming Appointments
                <?php if (count($upcoming_appointments) > 0): ?>
                <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full"><?php echo count($upcoming_appointments); ?></span>
                <?php endif; ?>
            </h2>
        </div>

        <div class="p-6">
            <?php if (empty($upcoming_appointments)): ?>
            <div class="text-center py-12">
                <div class="text-gray-300 mb-4">
                    <i class="fas fa-calendar-alt text-6xl"></i>
                </div>
                <p class="text-gray-500 text-lg">No upcoming appointments</p>
                <p class="text-gray-400 mt-2">Book your first appointment using the button above</p>
            </div>
            <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach ($upcoming_appointments as $appointment): ?>
                <?php $appointmentStatus = strtolower($appointment['status'] ?? 'pending'); ?>
                <div class="appointment-card bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md transition">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-calendar-check text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($appointment['service_type'] ?? $appointment['appointment_type'] ?? 'N/A'); ?></p>
                                <p class="text-xs text-gray-500">
                                    <?php echo date('F j, Y', strtotime($appointment['appointment_date'])); ?> at
                                    <?php echo date('g:i A', strtotime($appointment['appointment_time'])); ?>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <?php echo htmlspecialchars($appointment['office'] ?? 'Office pending'); ?>
                                </p>
                            </div>
                        </div>
                        <span class="status-badge status-<?php echo $appointmentStatus; ?>">
                            <i class="fas <?php echo $appointmentStatus === 'pending' ? 'fa-clock' : 'fa-check-circle'; ?>"></i>
                            <?php echo ucfirst($appointmentStatus); ?>
                        </span>
                    </div>

                    <?php if ($appointment['advisor_name']): ?>
                    <p class="text-sm text-gray-600 mb-2">
                        <i class="fas fa-user-tie text-gray-400 mr-2"></i>
                        Advisor: <?php echo htmlspecialchars($appointment['advisor_name']); ?>
                    </p>
                    <?php endif; ?>

                    <p class="text-sm text-gray-600 mb-3">
                        <i class="fas fa-comment text-gray-400 mr-2"></i>
                        <?php echo htmlspecialchars(substr($appointment['purpose'], 0, 100)) . (strlen($appointment['purpose']) > 100 ? '...' : ''); ?>
                    </p>

                    <?php if ($appointmentStatus === 'pending'): ?>
                    <div class="flex justify-end">
                        <button onclick="cancelAppointment(<?php echo $appointment['id']; ?>)" class="text-red-600 hover:text-red-700 text-sm font-medium flex items-center gap-1">
                            <i class="fas fa-times-circle"></i>
                            Cancel Appointment
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($past_appointments)): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-history text-gray-600"></i>
                Past Appointments
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Office</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($past_appointments as $appointment): ?>
                    <?php $appointmentStatus = strtolower($appointment['status'] ?? 'cancelled'); ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                           <?php
                            $created_at = $appointment['created_at'] ?? null;

                            if (!empty($created_at) && $created_at !== '0000-00-00 00:00:00') {
                                $timestamp = strtotime($created_at);
                                echo date('M d, Y', $timestamp);
                                echo '<br><span class="text-xs text-gray-500">' . date('g:i A', $timestamp) . '</span>';
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($appointment['service_type'] ?? $appointment['appointment_type'] ?? 'N/A'); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($appointment['office'] ?? 'N/A'); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <?php echo htmlspecialchars(substr($appointment['purpose'], 0, 60)); ?>
                            <?php if (strlen($appointment['purpose']) > 60) echo '...'; ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="status-badge status-<?php echo $appointmentStatus; ?>">
                                <i class="fas <?php echo $appointmentStatus === 'completed' ? 'fa-check' : 'fa-ban'; ?>"></i>
                                <?php echo ucfirst($appointmentStatus); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">Book New Appointment</h2>
            <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form method="POST" action="" class="p-6 space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Service Type *</label>
                <select name="service_type" required class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <option value="">Select a service</option>
                    <?php foreach ($appointment_types as $type): ?>
                    <option value="<?php echo htmlspecialchars($type['name']); ?>">
                        <?php echo htmlspecialchars($type['name']); ?> (<?php echo $type['duration_minutes']; ?> min)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Advisor Name (Optional)</label>
                <input type="text" name="advisor_name" placeholder="e.g., Dr. Maria Santos" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Appointment Date *</label>
                <input type="date" name="appointment_date" required min="<?php echo date('Y-m-d'); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Appointment Time *</label>
                <select name="appointment_time" required class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <option value="">Select a time</option>
                    <option value="08:00:00">8:00 AM</option>
                    <option value="09:00:00">9:00 AM</option>
                    <option value="10:00:00">10:00 AM</option>
                    <option value="11:00:00">11:00 AM</option>
                    <option value="13:00:00">1:00 PM</option>
                    <option value="14:00:00">2:00 PM</option>
                    <option value="15:00:00">3:00 PM</option>
                    <option value="16:00:00">4:00 PM</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Purpose / Reason for Appointment *</label>
                <textarea name="purpose" required rows="4" placeholder="Please describe what you would like to discuss during this appointment..." class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"></textarea>
            </div>

            <div class="bg-blue-50 rounded-xl p-4">
                <p class="text-sm text-blue-800 flex items-center gap-2">
                    <i class="fas fa-info-circle"></i>
                    Your appointment request will be reviewed and confirmed by an admin. You will receive a notification once it's approved.
                </p>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeBookingModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition">Cancel</button>
                <button type="submit" name="book_appointment" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-md">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Book Appointment
                </button>
            </div>
        </form>
    </div>
</div>

<?php $additionalScripts = '
<script>
function openBookingModal() {
    document.getElementById("bookingModal").style.display = "flex";
    document.body.style.overflow = "hidden";
}

function closeBookingModal() {
    document.getElementById("bookingModal").style.display = "none";
    document.body.style.overflow = "";
}

function cancelAppointment(appointmentId) {
    if (confirm("Are you sure you want to cancel this appointment?")) {
        window.location.href = "?cancel=" + appointmentId;
    }
}

document.getElementById("bookingModal")?.addEventListener("click", function(e) {
    if (e.target === this) {
        closeBookingModal();
    }
});

document.addEventListener("keydown", function(e) {
    if (e.key === "Escape") {
        closeBookingModal();
    }
});

const dateInput = document.querySelector("input[name=\'appointment_date\']");
if (dateInput) {
    dateInput.min = new Date().toISOString().split("T")[0];
}
</script>
'; ?>

<?php include __DIR__ . '/../../public/includes/user/Footer.php'; ?>
