
<!-- This file should ONLY contain HTML/CSS - NO CLASS DECLARATIONS -->

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
    
    <!-- TOTAL REQUESTS -->
    <div class="stat-card bg-white rounded-2xl p-4 sm:p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs sm:text-sm font-medium mb-1">Total Requests</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-800"><?php echo $stats['total_requests']; ?></p>
                <p class="text-xs text-gray-400 mt-1 hidden sm:block">All time requests</p>
            </div>
            <div class="icon-container bg-blue-100 w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center">
                <i class="fas fa-file-alt text-blue-600 text-xl sm:text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- PENDING REQUESTS -->
    <div class="stat-card bg-white rounded-2xl p-4 sm:p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs sm:text-sm font-medium mb-1">Pending Requests</p>
                <p class="text-2xl sm:text-3xl font-bold text-yellow-600"><?php echo $stats['pending_requests']; ?></p>
                <p class="text-xs text-gray-400 mt-1 hidden sm:block">Awaiting approval</p>
            </div>
            <div class="icon-container bg-yellow-100 w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center">
                <i class="fas fa-clock text-yellow-600 text-xl sm:text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- APPROVED REQUESTS -->
    <div class="stat-card bg-white rounded-2xl p-4 sm:p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs sm:text-sm font-medium mb-1">Approved</p>
                <p class="text-2xl sm:text-3xl font-bold text-green-600"><?php echo $stats['approved_requests']; ?></p>
                <p class="text-xs text-gray-400 mt-1 hidden sm:block">Successfully processed</p>
            </div>
            <div class="icon-container bg-green-100 w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-xl sm:text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- REJECTED REQUESTS -->
    <div class="stat-card bg-white rounded-2xl p-4 sm:p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs sm:text-sm font-medium mb-1">Rejected</p>
                <p class="text-2xl sm:text-3xl font-bold text-red-600"><?php echo $stats['rejected_requests']; ?></p>
                <p class="text-xs text-gray-400 mt-1 hidden sm:block">Needs revision</p>
            </div>
            <div class="icon-container bg-red-100 w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center">
                <i class="fas fa-times-circle text-red-600 text-xl sm:text-2xl"></i>
            </div>
        </div>
    </div>

</div>