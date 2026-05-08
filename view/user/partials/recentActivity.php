<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <i class="fas fa-history text-blue-500"></i>
        Recent Activity
    </h3>
    <?php if(empty($recent_requests)): ?>
    <div class="text-center py-8">
        <div class="text-gray-300 mb-2">
            <i class="fas fa-inbox text-4xl sm:text-5xl"></i>
        </div>
        <p class="text-gray-500">No recent requests yet</p>
        <p class="text-xs text-gray-400 mt-1">Create your first request to get started</p>
    </div>
    <?php else: ?>
    <div class="space-y-3">
        <?php foreach($recent_requests as $request): ?>
        <div class="request-row flex items-center justify-between p-3 rounded-xl">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas <?php 
                        echo $request['service_type'] == 'Document Request' ? 'fa-file-alt' : 
                            ($request['service_type'] == 'Grade Inquiry' ? 'fa-chart-line' : 
                            ($request['service_type'] == 'Academic Advising' ? 'fa-chalkboard-user' : 'fa-question-circle')); 
                    ?> text-blue-500 text-sm sm:text-base"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800"><?php echo htmlspecialchars($request['service_type']); ?></p>
                    <p class="text-xs text-gray-500"><?php echo date('M d, Y', strtotime($request['created_at'])); ?></p>
                </div>
            </div>
            <span class="status-badge status-<?php echo $request['status']; ?> text-xs whitespace-nowrap ml-2">
                <i class="fas <?php 
                    echo $request['status'] == 'pending' ? 'fa-clock' : 
                        ($request['status'] == 'approved' ? 'fa-check' : 
                        ($request['status'] == 'rejected' ? 'fa-times' : 'fa-ban')); 
                ?> text-xs"></i>
                <span class="hidden sm:inline"><?php echo ucfirst($request['status']); ?></span>
                <span class="sm:hidden"><?php echo substr(ucfirst($request['status']), 0, 1); ?></span>
            </span>
        </div>
        <?php endforeach; ?>
    </div>
    <?php if(count($recent_requests) >= 5): ?>
    <div class="mt-4 text-center">
        <a href="Request.php" class="text-sm text-blue-600 hover:text-blue-700 font-medium inline-flex items-center gap-1 no-underline">
            View all requests <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>
