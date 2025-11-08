
const sidebar = document.getElementById('sidebar');
const menuIcon = document.getElementById('menuIcon');
const closeSidebar = document.getElementById('closeSidebar');
const notifBadge = document.getElementById('notifBadge');
const markAllReadBtn = document.getElementById('markAllReadBtn');

menuIcon.addEventListener('click', () => {
    if (window.innerWidth <= 992) {
        sidebar.classList.toggle('active');
    } else {
        sidebar.classList.toggle('collapsed');
    }
});

closeSidebar.addEventListener('click', () => {
    if (window.innerWidth <= 992) {
        sidebar.classList.remove('active');
    } else {
        sidebar.classList.toggle('collapsed');
    }
});

// Search: submit handler (example)
document.getElementById('adminSearchForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const q = document.getElementById('adminSearchInput').value.trim();
    if (!q) return;
    // Replace this with actual search route or AJAX call
    alert('Search for: ' + q);
});

// Notifications: Mark all read (client-side example)
markAllReadBtn.addEventListener('click', function (e) {
    e.preventDefault();
    // Example behavior: hide badge and disable button.
    notifBadge.style.display = 'none';
    markAllReadBtn.textContent = 'All read';
    markAllReadBtn.disabled = true;

    // TODO: Call server endpoint to mark notifications read
    // fetch('/admin/notifications/mark-read', { method: 'POST', headers: {...}, body: ... })
});

// Close mobile sidebar when clicking outside (optional)
document.addEventListener('click', (e) => {
    if (window.innerWidth <= 992) {
        if (!sidebar.contains(e.target) && !document.getElementById('topNav').contains(e.target)) {
            sidebar.classList.remove('active');
        }
    }
});


