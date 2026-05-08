<?php include __DIR__ . '/../../public/admin/includes/AdminHeader.php'; ?>

<div class="p-8">
    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b bg-gray-50">
            <h2 class="text-2xl font-bold text-gray-800">Add Schedule</h2>
            <p class="text-sm text-gray-500 mt-1">Create available appointment slots for students</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="mx-6 mt-4 bg-red-100 text-red-700 px-4 py-3 rounded-lg"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="p-6 space-y-6">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Day of Week</label>
                <select name="day_of_week" class="w-full border rounded-lg px-4 py-3" required>
                    <option value="">Select Day</option>
                    <option>Monday</option>
                    <option>Tuesday</option>
                    <option>Wednesday</option>
                    <option>Thursday</option>
                    <option>Friday</option>
                    <option>Saturday</option>
                    <option>Sunday</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Start Time</label>
                    <input type="time" name="start_time" class="w-full border rounded-lg px-4 py-3" required>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">End Time</label>
                    <input type="time" name="end_time" class="w-full border rounded-lg px-4 py-3" required>
                </div>
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Office</label>
                <input type="text" name="office" placeholder="Registrar / Guidance / Dean" class="w-full border rounded-lg px-4 py-3" required>
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Service Type</label>
                <input type="text" name="service_type" placeholder="Optional: Document Request / Advising / Clearance" class="w-full border rounded-lg px-4 py-3">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Max Slots</label>
                    <input type="number" name="max_slots" min="1" value="10" class="w-full border rounded-lg px-4 py-3" required>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="w-full border rounded-lg px-4 py-3" required>
                        <option value="Available">Available</option>
                        <option value="Full">Full</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="Schedule.php" class="px-5 py-3 rounded-lg border text-gray-700 hover:bg-gray-100">Cancel</a>
                <button type="submit" name="save_schedule" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">Save Schedule</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../public/admin/includes/AdminFooter.php'; ?>
