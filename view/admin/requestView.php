<?php include __DIR__ . '/../../public/admin/includes/AdminHeader.php'; ?>

<div class="p-12">
    <h2 class="text-5xl font-bold text-gray-800 mb-2">Request Details</h2>
    <p class="text-gray-500 text-2xl mb-10">View and manage request information</p>

    <div class="bg-white rounded-3xl shadow overflow-hidden">
        <div class="px-10 py-6 border-b bg-gray-50">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold text-gray-700">Request #<?php echo $request['id']; ?></h3>
                    <p class="text-gray-500 mt-1">Submitted on <?php echo date("M d, Y", strtotime($request['created_at'])); ?></p>
                </div>
                <div class="text-right">
                    <?php if ($request['status'] == 'pending'): ?>
                        <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm font-semibold"><i class="fas fa-clock mr-2"></i>Pending</span>
                    <?php elseif ($request['status'] == 'approved'): ?>
                        <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-semibold"><i class="fas fa-check mr-2"></i>Approved</span>
                    <?php else: ?>
                        <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-semibold"><i class="fas fa-times mr-2"></i>Rejected</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="p-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <h4 class="text-xl font-semibold text-gray-800 border-b pb-2">Student Information</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Student Name</label>
                            <p class="text-lg text-gray-800"><?php echo htmlspecialchars($request['name']); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Student ID</label>
                            <p class="text-lg text-gray-800"><?php echo htmlspecialchars($request['student_id']); ?></p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <h4 class="text-xl font-semibold text-gray-800 border-b pb-2">Request Details</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Service Type</label>
                            <p class="text-lg text-gray-800"><?php echo htmlspecialchars($request['service_type']); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Submission Date</label>
                            <p class="text-lg text-gray-800"><?php echo date("F d, Y \a\t g:i A", strtotime($request['created_at'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <h4 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Additional Notes</h4>
                <div class="bg-gray-50 rounded-xl p-6">
                    <p class="text-gray-700 leading-relaxed"><?php echo nl2br(htmlspecialchars($request['notes'])); ?></p>
                </div>
            </div>

            <div class="mt-8">
                <h4 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Attachment</h4>
                <?php if (!empty($request['document_file'])): ?>
                    <?php 
                        $document_path = trim(str_replace('\\', '/', $request['document_file']));
                        $document_path = ltrim($document_path, '/');
                        if (strpos($document_path, 'public/uploads/requests/') === 0) {
                            $document_path = 'uploads/requests/' . basename($document_path);
                        } elseif (strpos($document_path, 'uploads/requests/') !== 0) {
                            $document_path = 'uploads/requests/' . basename($document_path);
                        }
                        $document_url = '../../' . implode('/', array_map('rawurlencode', explode('/', $document_path)));
                        $document_name = basename($document_path);
                        $file_ext = strtolower(pathinfo($document_name, PATHINFO_EXTENSION));
                        $is_image = in_array($file_ext, ['jpg', 'jpeg', 'png']);
                    ?>
                    <div class="bg-gray-50 rounded-xl p-6">
                        <?php if ($is_image): ?>
                            <div class="mb-4">
                                <img src="<?php echo htmlspecialchars($document_url); ?>" 
                                     alt="Attachment" class="max-w-md rounded-lg shadow">
                            </div>
                        <?php endif; ?>
                        <a href="<?php echo htmlspecialchars($document_url); ?>" 
                           target="_blank"
                           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                            <i class="fas fa-download"></i>Download/View Attachment
                        </a>
                        <p class="text-sm text-gray-500 mt-2"><?php echo htmlspecialchars($document_name); ?></p>
                    </div>
                <?php else: ?>
                    <div class="bg-gray-50 rounded-xl p-6">
                        <p class="text-gray-500"><i class="fas fa-file-circle-xmark mr-2"></i>No attachment</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mt-10 pt-8 border-t">
                <h4 class="text-xl font-semibold text-gray-800 mb-6">Actions</h4>
                <div class="flex flex-wrap gap-4">
                    <?php if ($request['status'] == 'pending'): ?>
                        <a href="Requests.php?approve=<?php echo $request['id']; ?>" onclick="return confirm('Approve this request?')" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold transition flex items-center gap-2"><i class="fas fa-check"></i>Approve Request</a>
                        <a href="Requests.php?reject=<?php echo $request['id']; ?>" onclick="return confirm('Reject this request?')" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-semibold transition flex items-center gap-2"><i class="fas fa-times"></i>Reject Request</a>
                    <?php endif; ?>

                    <a href="DeleteRequest.php?id=<?php echo $request['id']; ?>" onclick="return confirm('Delete this request permanently?')" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl font-semibold transition flex items-center gap-2"><i class="fas fa-trash"></i>Delete Request</a>
                    <a href="Requests.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition flex items-center gap-2"><i class="fas fa-arrow-left"></i>Back to Requests</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../public/admin/includes/AdminFooter.php'; ?>
