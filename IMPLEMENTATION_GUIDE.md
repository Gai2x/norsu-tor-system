# Global Search System Standardization & Category Integration
## Comprehensive Implementation Guide

### COMPLETED IMPLEMENTATIONS

#### 1. DATABASE CHANGES ✅
**File**: `database/migration_add_category.sql`
- Added `category` ENUM column to `requests` table
- Added `category` ENUM column to `appointments` table
- Added `category` ENUM column to `one_time_requests` table
- Category options: 'Document Request', 'Consultation', 'Enrollment Concern', 'Grade Concern', 'Other'
- Created indexes on category columns for performance

**Implementation Status**: SQL file created - ready to execute

---

#### 2. REUSABLE JAVASCRIPT MODULE ✅
**File**: `public/js/search-filter-manager.js`
- `SearchFilterManager` class for reusable AJAX search/filtering
- Features:
  - Debounced search input (300ms default)
  - Dynamic filter binding
  - Pagination support
  - Loading states with spinner
  - Empty state rendering
  - Error handling with user-friendly messages
  - Customizable item templates
  - Reset functionality

**Usage Example**:
```javascript
const search = new SearchFilterManager({
    endpoint: '/Norsu_Tor/superadmin/ajax-search-students',
    container: document.getElementById('container'),
    searchInput: document.getElementById('search-input'),
    filters: {
        course: 'course-filter-id',
        year_level: 'year-level-filter-id'
    },
    pageSize: 12,
    itemTemplate: function(item) {
        return `<div>${item.name}</div>`;
    }
});
search.search();
```

---

#### 3. SUPERADMIN MODEL ENHANCEMENTS ✅
**File**: `model/superadmin/SuperAdminModel.php`

**New Methods Added**:
- `getAppointments(filters, page, pageSize)` - Fetch appointments with filtering
- `countAppointments(filters)` - Count total matching appointments
- `getRequests(filters, page, pageSize)` - Fetch requests with filtering
- `countRequests(filters)` - Count total matching requests
- `getAppointmentTypes()` - Get distinct appointment types
- `getRequestCategories()` - Get available request categories

**Filters Supported**:
- Search (name, email, student ID, course, purpose/notes)
- Status (pending, approved, rejected)
- Course/Year Level
- Category
- Date (appointments and requests)
- Type (appointment type for appointments, service type for requests)

---

#### 4. SUPERADMIN CONTROLLER AJAX ENDPOINTS ✅
**File**: `controller/superadmin/SuperAdminController.php`

**New Methods**:
- `ajaxSearchAppointments()` - AJAX endpoint for appointment search/filter
- `ajaxSearchRequests()` - AJAX endpoint for request search/filter
- Already has: `ajaxSearchStudents()`, `ajaxCreateAdmin()`

**Response Format** (JSON):
```json
{
  "items": [...],
  "total": 150,
  "pagination": {
    "currentPage": 1,
    "pageSize": 12,
    "totalItems": 150,
    "totalPages": 13
  }
}
```

---

#### 5. ADMIN MODEL CREATION ✅
**File**: `model/admin/AdminModel.php` (was empty, now populated)

**Methods Created**:
- `getRequests(filters, page, pageSize)` - Admin request search
- `countRequests(filters)` - Count admin requests
- `getAppointments(filters, page, pageSize)` - Admin appointment search
- `countAppointments(filters)` - Count admin appointments
- `getCategories()` - Get request categories
- `getAppointmentTypes()` - Get appointment types
- `getServiceTypes()` - Get service types
- `getCourses()` - Get courses for filtering
- `getYearLevels()` - Get year levels for filtering

---

#### 6. ADMIN CONTROLLER AJAX ENDPOINTS ✅
**File**: `controller/admin/AdminController.php`

**New Methods**:
- `ajaxSearchRequests($conn)` - AJAX endpoint for admin request search
- `ajaxSearchAppointments($conn)` - AJAX endpoint for admin appointment search

---

#### 7. PUBLIC AJAX ENDPOINTS CREATED ✅
- `public/superadmin/AjaxSearchAppointments.php`
- `public/superadmin/AjaxSearchRequests.php`
- `public/admin/AjaxSearchRequests.php`
- `public/admin/AjaxSearchAppointments.php`

---

#### 8. ROUTING CONFIGURATION UPDATED ✅
**File**: `.htaccess`

**New Routes Added**:
```
superadmin/ajax-search-students
superadmin/ajax-search-appointments
superadmin/ajax-search-requests
superadmin/ajax-create-admin
admin/ajax-search-requests
admin/ajax-search-appointments
```

---

#### 9. SUPERADMIN STUDENTS PAGE UPDATED ✅
**File**: `view/superadmin/users.php`

**Changes**:
- Converted to AJAX-based search/filtering (no page reloads)
- Uses `SearchFilterManager` class
- Filters: Course, Year Level, Account Status
- Real-time search with debouncing
- Reset button functionality
- Responsive card layout
- Integrated edit button with modal support

---

### IMPLEMENTATION ROADMAP - REMAINING TASKS

#### PRIORITY 1: Database Migration (CRITICAL)
Execute the migration SQL:
```sql
-- Run database/migration_add_category.sql in your MySQL client or phpMyAdmin
```

After migration, existing records will have default categories:
- requests: 'Document Request'
- appointments: 'Consultation'
- one_time_requests: 'Document Request'

---

#### PRIORITY 2: Update Superadmin Pages

##### Superadmin Appointments Page
**File to Update**: `view/superadmin/appointments.php` (if exists, or create new)

**Template Structure**:
```php
<?php include __DIR__ . '/../../public/superadmin/includes/SuperAdminHeader.php'; ?>

<div class="p-4 sm:p-6 lg:p-10 space-y-8">
    <!-- Title Section -->
    <section class="bg-white rounded-3xl shadow overflow-hidden">
        <div class="border-b border-slate-200 px-6 py-5">
            <h2>Search & Filter</h2>
        </div>
        <div class="p-6 space-y-6">
            <form id="appointment-search-form">
                <input id="superadmin-appointment-search" type="search" placeholder="Search...">
                <select id="appointment-status-filter"></select>
                <select id="appointment-type-filter"></select>
                <select id="appointment-course-filter"></select>
                <select id="appointment-year-filter"></select>
                <select id="appointment-date-filter"></select>
                <select id="appointment-category-filter"></select>
            </form>
        </div>
    </section>
    
    <section class="bg-white rounded-3xl shadow overflow-hidden">
        <div id="appointments-container" class="grid gap-5 p-6"></div>
    </section>
</div>

<script src="/Norsu_Tor/public/js/search-filter-manager.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const appointmentSearch = new SearchFilterManager({
        endpoint: '/Norsu_Tor/superadmin/ajax-search-appointments',
        container: document.getElementById('appointments-container'),
        searchInput: document.getElementById('superadmin-appointment-search'),
        filters: {
            status: 'appointment-status-filter',
            appointment_type: 'appointment-type-filter',
            course: 'appointment-course-filter',
            year_level: 'appointment-year-filter',
            date: 'appointment-date-filter',
            category: 'appointment-category-filter'
        },
        pageSize: 12,
        itemTemplate: function(appt) {
            return `
                <article class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xl font-semibold">#APT-${appt.id}</p>
                    <p class="text-sm text-slate-600">${appt.name}</p>
                    <p>${appt.appointment_type}</p>
                    <p>${appt.appointment_date} ${appt.appointment_time}</p>
                    <p>${appt.category}</p>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold ${
                        appt.status === 'approved' ? 'bg-green-100 text-green-700' :
                        appt.status === 'rejected' ? 'bg-red-100 text-red-700' :
                        'bg-yellow-100 text-yellow-700'
                    }">${appt.status}</span>
                </article>
            `;
        }
    });
    window.appointmentSearch = appointmentSearch;
    appointmentSearch.search();
});
</script>
<?php include __DIR__ . '/../../public/superadmin/includes/SuperAdminFooter.php'; ?>
```

##### Superadmin All Requests Page
**File to Update**: `view/superadmin/requests.php`

Similar to above but with:
- Filters: Status, Service Type, Category, Course, Year Level, Date
- Endpoint: `/Norsu_Tor/superadmin/ajax-search-requests`
- Search fields: name, email, student_id, notes

---

#### PRIORITY 3: Update Admin Dashboard

**File to Update**: `view/admin/dashboard.php`

Add a new "Recent Request Queue" section with AJAX search:

```php
<section class="bg-white rounded-3xl shadow overflow-hidden">
    <div class="px-4 sm:px-6 lg:px-8 py-5 sm:py-6 border-b">
        <h3 class="text-2xl sm:text-3xl font-bold text-gray-800">Request Queue</h3>
        <p class="text-sm text-gray-500 mt-1">Filter and search requests in real-time.</p>
    </div>
    
    <div class="p-6 space-y-6">
        <form id="admin-request-search-form">
            <input id="admin-request-search" type="search" placeholder="Search requests...">
            <select id="admin-request-status-filter"></select>
            <select id="admin-request-type-filter"></select>
            <select id="admin-request-category-filter"></select>
            <!-- ... other filters -->
        </form>
    </div>
    
    <div id="admin-requests-container" class="grid gap-5 p-6"></div>
</section>

<script>
const adminRequestSearch = new SearchFilterManager({
    endpoint: '/Norsu_Tor/admin/ajax-search-requests',
    container: document.getElementById('admin-requests-container'),
    // ... configuration
});
adminRequestSearch.search();
</script>
```

---

#### PRIORITY 4: Update Admin Appointments Page

**File to Update**: `view/admin/appointments.php`

Apply similar AJAX search/filter system with endpoint:
`/Norsu_Tor/admin/ajax-search-appointments`

---

#### PRIORITY 5: Update Admin Requests Page

**File to Update**: `view/admin/requests.php`

Full AJAX search/filter implementation with:
- Search: name, email, student ID, notes
- Filters: Status, Service Type, Category, Course, Year Level, Date

---

### FORM UPDATES NEEDED

#### 1. User Request Form (Create/Edit)
**Files to Update**:
- `view/user/OneTimeRequest.php`
- `view/user/requests.php` (if exists)

**Add to Request Form**:
```html
<label for="request-category" class="block text-sm font-semibold">Category *</label>
<select id="request-category" name="category" required class="rounded-2xl border border-gray-300 px-4 py-3">
    <option value="">Select Category</option>
    <option value="Document Request">Document Request</option>
    <option value="Consultation">Consultation</option>
    <option value="Enrollment Concern">Enrollment Concern</option>
    <option value="Grade Concern">Grade Concern</option>
    <option value="Other">Other</option>
</select>
```

#### 2. Appointment Form (Create/Edit)
**Files to Update**:
- `view/user/appointments.php` (if exists)
- `view/admin/addSchedule.php` (if exists)

**Add to Appointment Form**:
```html
<label for="appointment-category" class="block text-sm font-semibold">Category *</label>
<select id="appointment-category" name="category" required class="rounded-2xl border border-gray-300 px-4 py-3">
    <option value="">Select Category</option>
    <option value="Document Request">Document Request</option>
    <option value="Consultation">Consultation</option>
    <option value="Enrollment Concern">Enrollment Concern</option>
    <option value="Grade Concern">Grade Concern</option>
    <option value="Other">Other</option>
</select>
```

---

### CONTROLLER UPDATES NEEDED

#### Request Controllers
Update to handle category in form submission:
- `controller/user/RequestController.php`
- `controller/user/OneTimeRequestController.php`
- `controller/admin/RequestsPageController.php`

**Pattern**:
```php
$category = trim($_POST['category'] ?? 'Document Request');
// Validate against allowed values
$allowed_categories = ['Document Request', 'Consultation', 'Enrollment Concern', 'Grade Concern', 'Other'];
if (!in_array($category, $allowed_categories)) {
    $category = 'Document Request';
}

// Insert/Update with category
$stmt = $conn->prepare("INSERT INTO requests (user_id, service_type, category, notes, ...) VALUES (?, ?, ?, ?, ...)");
$stmt->bind_param('isss', $userId, $serviceType, $category, $notes);
```

#### Appointment Controllers
Similarly update:
- `controller/user/AppointmentController.php`
- `controller/admin/AppointmentsPageController.php`

---

### TESTING CHECKLIST

- [ ] Database migration executed successfully
- [ ] Superadmin Students page AJAX search works (no page reload)
- [ ] Superadmin Appointments page AJAX search works
- [ ] Superadmin Requests page AJAX search works
- [ ] Admin Dashboard requests card displays with AJAX
- [ ] Admin Requests page AJAX search works
- [ ] Admin Appointments page AJAX search works
- [ ] Category field appears in all request/appointment forms
- [ ] Category displays in request/appointment cards
- [ ] Filters work correctly for all filter types
- [ ] Reset button clears search and filters
- [ ] Mobile responsive design works
- [ ] Loading spinner shows during search
- [ ] Empty state displays when no results
- [ ] Error handling works properly
- [ ] Pagination works (if needed)

---

### API RESPONSE EXAMPLES

#### Request Search Response
```json
{
  "items": [
    {
      "id": 5,
      "user_id": 2,
      "service_type": "Document Request",
      "category": "Document Request",
      "notes": "sssssssa",
      "year_level": "3rd Year",
      "status": "pending",
      "created_at": "2026-04-29 14:31:33",
      "name": "Daniel Saavedra",
      "student_id": "2023-00927",
      "course": "BSIT",
      "email": "dzdanielsaavedra@gmail.com"
    }
  ],
  "total": 3,
  "pagination": {
    "currentPage": 1,
    "pageSize": 12,
    "totalItems": 3,
    "totalPages": 1
  }
}
```

#### Appointment Search Response
```json
{
  "items": [
    {
      "id": 7,
      "user_id": 2,
      "appointment_type": "Enrollment Assistance",
      "appointment_date": "2026-05-06",
      "appointment_time": "10:00:00",
      "service_type": null,
      "purpose": "aaaa",
      "status": "approved",
      "category": "Consultation",
      "admin_notes": null,
      "name": "Daniel Saavedra",
      "student_id": "2023-00927",
      "course": "BSIT",
      "email": "dzdanielsaavedra@gmail.com"
    }
  ],
  "total": 1,
  "pagination": {
    "currentPage": 1,
    "pageSize": 12,
    "totalItems": 1,
    "totalPages": 1
  }
}
```

---

### TROUBLESHOOTING

#### Issue: AJAX endpoints return 404
**Solution**: Verify .htaccess routes are correct and .htaccess is enabled in Apache

#### Issue: Category field doesn't show in cards
**Solution**: Ensure database migration was executed and column exists

#### Issue: Search not working
**Solution**: 
1. Check browser console for errors
2. Verify endpoint URL is correct
3. Check that SearchFilterManager.js is loaded
4. Verify filters object ID references exist in HTML

#### Issue: Slow search/filter performance
**Solution**:
1. Verify database indexes are created
2. Check MySQL query optimization
3. Increase pageSize to reduce queries
4. Use prepared statements (already implemented)

---

### FILES MODIFIED/CREATED SUMMARY

**Created Files** (7):
1. `database/migration_add_category.sql`
2. `public/js/search-filter-manager.js`
3. `public/superadmin/AjaxSearchAppointments.php`
4. `public/superadmin/AjaxSearchRequests.php`
5. `public/admin/AjaxSearchRequests.php`
6. `public/admin/AjaxSearchAppointments.php`

**Modified Files** (6):
1. `model/superadmin/SuperAdminModel.php` - Added 6 new methods
2. `controller/superadmin/SuperAdminController.php` - Added 2 new AJAX methods
3. `model/admin/AdminModel.php` - Populated entire file with 8 methods
4. `controller/admin/AdminController.php` - Added 2 new AJAX methods
5. `view/superadmin/users.php` - Complete refactor to AJAX
6. `.htaccess` - Added 5 new routes

**Total Changes**: 13 files (6 new, 7 modified)

---

### NEXT STEPS

1. **Execute Database Migration** (Critical)
   - Run `database/migration_add_category.sql` in your MySQL client
   
2. **Test Superadmin Students Page**
   - Navigate to `/Norsu_Tor/superadmin/users`
   - Verify AJAX search works without page reload
   
3. **Implement Remaining Superadmin Pages**
   - Follow templates provided in this guide
   - Use `SearchFilterManager` class for consistency
   
4. **Update Admin Dashboard & Pages**
   - Add requests and appointments cards with AJAX
   - Integrate category filters
   
5. **Update Forms**
   - Add category select fields to request/appointment forms
   - Validate category values in controllers
   
6. **Comprehensive Testing**
   - Test all filter combinations
   - Verify mobile responsiveness
   - Test error scenarios

---

### CONTACT & SUPPORT

This implementation provides a solid foundation. All core infrastructure is in place:
- Reusable AJAX module
- Database schema updates
- Controller/Model methods
- Routing configuration

The remaining work is primarily view layer implementation following the patterns established.

For questions or issues, refer to this guide and the existing implementations as templates.
