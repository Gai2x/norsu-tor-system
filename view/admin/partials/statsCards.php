<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4 sm:gap-6 mb-10 lg:mb-12">
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-3xl shadow-lg p-5 sm:p-6 flex justify-between items-center text-white min-w-0">
        <div>
            <p class="text-indigo-100 text-sm sm:text-lg font-medium">Total Students</p>
            <h3 class="text-2xl sm:text-4xl font-bold mt-3"><?php echo $totalStudents ?? 0; ?></h3>
        </div>
        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 rounded-2xl flex items-center justify-center text-xl sm:text-2xl backdrop-blur-sm shrink-0">
            <i class="fa-solid fa-users"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-3xl shadow-lg p-5 sm:p-6 flex justify-between items-center text-white min-w-0">
        <div>
            <p class="text-purple-100 text-sm sm:text-lg font-medium">Total Appointments</p>
            <h3 class="text-2xl sm:text-4xl font-bold mt-3"><?php echo $totalAppointments ?? 0; ?></h3>
        </div>
        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 rounded-2xl flex items-center justify-center text-xl sm:text-2xl backdrop-blur-sm shrink-0">
            <i class="fa-regular fa-calendar-check"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-3xl shadow-lg p-5 sm:p-6 flex justify-between items-center text-white min-w-0">
        <div>
            <p class="text-blue-100 text-sm sm:text-lg font-medium">Total Requests</p>
            <h3 class="text-2xl sm:text-4xl font-bold mt-3"><?php echo $totalRequests ?? 0; ?></h3>
        </div>
        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 rounded-2xl flex items-center justify-center text-xl sm:text-2xl backdrop-blur-sm shrink-0">
            <i class="fa-regular fa-file-lines"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-3xl shadow-lg p-5 sm:p-6 flex justify-between items-center text-white min-w-0">
        <div>
            <p class="text-yellow-100 text-sm sm:text-lg font-medium">Pending Requests</p>
            <h3 class="text-2xl sm:text-4xl font-bold mt-3"><?php echo $pendingRequests ?? 0; ?></h3>
        </div>
        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 rounded-2xl flex items-center justify-center text-xl sm:text-2xl backdrop-blur-sm shrink-0">
            <i class="fa-regular fa-clock"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-3xl shadow-lg p-5 sm:p-6 flex justify-between items-center text-white min-w-0">
        <div>
            <p class="text-green-100 text-sm sm:text-lg font-medium">Approved (Month)</p>
            <h3 class="text-2xl sm:text-4xl font-bold mt-3"><?php echo $approvedThisMonth ?? 0; ?></h3>
        </div>
        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 rounded-2xl flex items-center justify-center text-xl sm:text-2xl backdrop-blur-sm shrink-0">
            <i class="fa-solid fa-check-double"></i>
        </div>
    </div>
</div>
