<?php
// Footer.php - Shared footer with JavaScript for all user pages
?>
        <!-- End of dynamic content area - content from individual pages goes here -->
        
    </main>
</div>

<script>
// Sidebar state management
let isSidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
let isMobileSidebarOpen = false;

// DOM Elements
const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('mainContent');
const toggleSidebarBtn = document.getElementById('toggleSidebarBtn');
const toggleIcon = document.getElementById('toggleIcon');
const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const sidebarOverlay = document.getElementById('sidebarOverlay');

// Function to toggle sidebar collapse (desktop only)
function toggleSidebarCollapse() {
    if (window.innerWidth <= 1024) return;
    
    isSidebarCollapsed = !isSidebarCollapsed;
    
    if (isSidebarCollapsed) {
        sidebar.classList.add('collapsed');
        if (toggleIcon) {
            toggleIcon.classList.remove('fa-chevron-left');
            toggleIcon.classList.add('fa-chevron-right');
        }
        mainContent.style.marginLeft = '80px';
    } else {
        sidebar.classList.remove('collapsed');
        if (toggleIcon) {
            toggleIcon.classList.remove('fa-chevron-right');
            toggleIcon.classList.add('fa-chevron-left');
        }
        mainContent.style.marginLeft = '256px';
    }
    
    localStorage.setItem('sidebarCollapsed', isSidebarCollapsed);
}

// Function to open mobile sidebar
function openMobileSidebar() {
    if (window.innerWidth <= 1024) {
        isMobileSidebarOpen = true;
        sidebar.style.transform = 'translateX(0)';
        sidebarOverlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        if (sidebar.classList.contains('collapsed')) {
            sidebar.classList.remove('collapsed');
        }
    }
}

// Function to close mobile sidebar
function closeMobileSidebar() {
    if (window.innerWidth <= 1024) {
        isMobileSidebarOpen = false;
        sidebar.style.transform = 'translateX(-100%)';
        sidebarOverlay.style.display = 'none';
        document.body.style.overflow = '';
    }
}

// Function to toggle mobile sidebar
function toggleMobileSidebar() {
    if (isMobileSidebarOpen) {
        closeMobileSidebar();
    } else {
        openMobileSidebar();
    }
}

// Initialize sidebar based on screen size
function initSidebar() {
    if (window.innerWidth <= 1024) {
        // Mobile view
        sidebar.style.transform = 'translateX(-100%)';
        sidebar.style.position = 'fixed';
        sidebar.style.transition = 'transform 0.3s ease';
        sidebar.style.height = '100vh';
        sidebar.style.zIndex = '40';
        sidebar.style.width = '280px';
        mainContent.style.marginLeft = '0';
        closeMobileSidebar();
    } else {
        // Desktop view
        sidebar.style.transform = '';
        sidebar.style.position = 'fixed';
        sidebar.style.width = isSidebarCollapsed ? '80px' : '256px';
        mainContent.style.marginLeft = isSidebarCollapsed ? '80px' : '256px';
        
        if (isSidebarCollapsed) {
            sidebar.classList.add('collapsed');
            if (toggleIcon) {
                toggleIcon.classList.remove('fa-chevron-left');
                toggleIcon.classList.add('fa-chevron-right');
            }
        } else {
            sidebar.classList.remove('collapsed');
            if (toggleIcon) {
                toggleIcon.classList.remove('fa-chevron-right');
                toggleIcon.classList.add('fa-chevron-left');
            }
        }
        
        sidebarOverlay.style.display = 'none';
        document.body.style.overflow = '';
    }
}

// Handle window resize
let resizeTimeout;
window.addEventListener('resize', function() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(function() {
        initSidebar();
    }, 100);
});

// Event Listeners
if (toggleSidebarBtn) {
    toggleSidebarBtn.addEventListener('click', toggleSidebarCollapse);
}
if (mobileMenuBtn) {
    mobileMenuBtn.addEventListener('click', toggleMobileSidebar);
}
if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', closeMobileSidebar);
}

// Close mobile sidebar on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && isMobileSidebarOpen) {
        closeMobileSidebar();
    }
});

// Initialize on page load
initSidebar();

// Optional: Add smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if(href === "#" || href === "" || href === "#") return;
        const target = document.querySelector(href);
        if(target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>

<?php if(isset($additionalScripts)): ?>
    <?php echo $additionalScripts; ?>
<?php endif; ?>

</body>
</html>
