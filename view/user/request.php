<?php include __DIR__ . '/../../public/includes/user/Header.php'; ?>

<div class="p-4 sm:p-6 space-y-6 animate-fade-in-up">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">My Requests</h1>
            <p class="text-gray-500 mt-1">Manage and track your academic service requests</p>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
        <div class="stat-card bg-white rounded-2xl p-4 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs sm:text-sm font-medium">Total Requests</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-1"><?php echo count($requests); ?></p>
                </div>
                <div class="bg-blue-100 w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-blue-600 text-lg sm:text-xl"></i>
                </div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl p-4 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs sm:text-sm font-medium">Pending</p>
                    <p class="text-2xl sm:text-3xl font-bold text-yellow-600 mt-1"><?php echo $status_counts['pending']; ?></p>
                </div>
                <div class="bg-yellow-100 w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-lg sm:text-xl"></i>
                </div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl p-4 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs sm:text-sm font-medium">Approved</p>
                    <p class="text-2xl sm:text-3xl font-bold text-green-600 mt-1"><?php echo $status_counts['approved']; ?></p>
                </div>
                <div class="bg-green-100 w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-lg sm:text-xl"></i>
                </div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl p-4 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs sm:text-sm font-medium">Rejected</p>
                    <p class="text-2xl sm:text-3xl font-bold text-red-600 mt-1"><?php echo $status_counts['rejected']; ?></p>
                </div>
                <div class="bg-red-100 w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-lg sm:text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <?php if ($success_message): ?>
    <div class="message-slide bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
        <div class="flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500 text-xl"></i>
            <span><?php echo htmlspecialchars($success_message); ?></span>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
    <div class="message-slide bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
        <div class="flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
            <span><?php echo htmlspecialchars($error_message); ?></span>
        </div>
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
            <div class="flex items-center gap-3">
                <div class="bg-blue-600 w-8 h-8 rounded-lg flex items-center justify-center">
                    <i class="fas fa-plus text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">New Request</h2>
                    <p class="text-sm text-gray-500">Fill out the form below to submit a new service request</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <form method="POST" enctype="multipart/form-data" class="space-y-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-user-circle text-blue-600"></i>
                        Personal Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <div class="relative">
                                <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" value="<?php echo htmlspecialchars($user_data['name'] ?? ''); ?>" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600" readonly disabled>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Year Level</label>
                            <div class="relative">
                                <i class="fas fa-layer-group absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <select name="year_level" required class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select year level</option>
                                    <option value="1st Year">1st Year</option>
                                    <option value="2nd Year">2nd Year</option>
                                    <option value="3rd Year">3rd Year</option>
                                    <option value="4th Year">4th Year</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Number</label>
                            <div class="relative">
                                <i class="fas fa-phone absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="tel" name="contact_number" placeholder="09XXXXXXXXX"
                                value="<?php echo htmlspecialchars($user_data['phone_number'] ?? ''); ?>"
                                readonly disabled
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                                
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <div class="relative">
                                <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="email" value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600" readonly disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-cogs text-blue-600"></i>
                        Service Information
                    </h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Service Type</label>
                            <select name="service_type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select...</option>
                                <option value="Document Request">Document Request (Transcript, Certificate, etc.)</option>
                                <option value="Grade Inquiry">Grade Inquiry</option>
                                <option value="Academic Advising">Academic Advising</option>
                                <option value="Enrollment Concern">Enrollment Concern</option>
                                <option value="Scholarship Application">Scholarship Application</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes / Purpose</label>
                            <textarea name="notes" rows="4" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Please provide the purpose of your request..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Documents (If needed)</label>
                            <div class="dropzone border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition cursor-pointer" id="dropzone">
                                <input type="file" name="document" id="fileInput" class="hidden" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                <p class="text-gray-500">Click to upload or drag and drop</p>
                                <p class="text-xs text-gray-400 mt-1">JPG, PNG, PDF, DOC, DOCX up to 10MB</p>
                            </div>
                            <p id="fileName" class="text-sm text-gray-600 mt-2 hidden">
                                <i class="fas fa-file-alt mr-1"></i> <span></span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button type="reset" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">Cancel</button>
                    <button type="submit" name="submit_request" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">Submit Request</button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-history text-gray-600"></i>
                Recent Requests
            </h3>
        </div>

        <?php if (empty($requests)): ?>
        <div class="text-center py-12">
            <div class="text-gray-400 mb-3">
                <i class="fas fa-inbox text-6xl"></i>
            </div>
            <p class="text-gray-500 font-medium">No requests found</p>
            <p class="text-sm text-gray-400 mt-1">Submit a request using the form above</p>
        </div>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Submitted</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach (array_slice($requests, 0, 10) as $request): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-900">#<?php echo $request['id']; ?></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <i class="fas <?php echo $request['service_type'] === 'Document Request' ? 'fa-file-alt' : ($request['service_type'] === 'Grade Inquiry' ? 'fa-chart-line' : ($request['service_type'] === 'Academic Advising' ? 'fa-chalkboard-user' : 'fa-question-circle')); ?> text-blue-500"></i>
                                <span class="text-sm text-gray-700"><?php echo htmlspecialchars($request['service_type']); ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                            <?php echo htmlspecialchars(substr($request['notes'], 0, 50)) . (strlen($request['notes']) > 50 ? '...' : ''); ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="status-badge status-<?php echo $request['status']; ?>">
                                <i class="fas <?php echo $request['status'] === 'pending' ? 'fa-clock' : ($request['status'] === 'approved' ? 'fa-check' : ($request['status'] === 'rejected' ? 'fa-times' : 'fa-ban')); ?> text-xs"></i>
                                <?php echo ucfirst($request['status']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo date('M d, Y', strtotime($request['created_at'])); ?></td>
                        <td class="px-6 py-4">
                            <?php if ($request['status'] === 'pending'): ?>
                            <a href="?cancel=<?php echo $request['id']; ?>" onclick="return confirm('Are you sure you want to cancel this request?')" class="text-red-600 hover:text-red-800 text-sm font-medium transition inline-flex items-center gap-1">
                                <i class="fas fa-times-circle"></i> Cancel
                            </a>
                            <?php else: ?>
                            <span class="text-gray-400 text-sm">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
const dropzone = document.getElementById('dropzone');
const fileInput = document.getElementById('fileInput');
const fileNameSpan = document.getElementById('fileName');

if (dropzone) {
    dropzone.addEventListener('click', () => fileInput.click());
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('drag-over');
    });
    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('drag-over');
    });
    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('drag-over');
        const files = e.dataTransfer.files;
        if (files.length) {
            fileInput.files = files;
            updateFileName(files[0]);
        }
    });
}

if (fileInput) {
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length) {
            updateFileName(e.target.files[0]);
        }
    });
}

function updateFileName(file) {
    if (file.size > 10 * 1024 * 1024) {
        alert('File size must be less than 10MB');
        fileInput.value = '';
        fileNameSpan.classList.add('hidden');
        return;
    }

    const validExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];
    const ext = file.name.split('.').pop().toLowerCase();
    if (!validExtensions.includes(ext)) {
        alert('Only JPG, PNG, PDF, DOC, and DOCX files are allowed');
        fileInput.value = '';
        fileNameSpan.classList.add('hidden');
        return;
    }

    fileNameSpan.querySelector('span').textContent = file.name;
    fileNameSpan.classList.remove('hidden');
}

const style = document.createElement('style');
style.textContent = '.dropzone.drag-over { border-color: #3b82f6; background-color: #eff6ff; }';
document.head.appendChild(style);
</script>

<?php include __DIR__ . '/../../public/includes/user/Footer.php'; ?>
