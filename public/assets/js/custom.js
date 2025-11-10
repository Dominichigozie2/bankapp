
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


document.addEventListener('DOMContentLoaded', () => {

    // Handle main dropdowns
    const dropdownBtns = document.querySelectorAll('.dropbtn');
    dropdownBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();

            const dropdown = btn.querySelector('.dropdown');

            // close other dropdowns
            document.querySelectorAll('.dropdown.active').forEach(d => {
                if (d !== dropdown) d.classList.remove('active');
            });

            dropdown.classList.toggle('active');
        });
    });

    // Handle sub-dropdowns
    const subDropdownBtns = document.querySelectorAll('.subdropbtn');
    subDropdownBtns.forEach(subBtn => {
        subBtn.addEventListener('click', (e) => {
            e.stopPropagation();

            const subdropdown = subBtn.querySelector('.subdropdown');

            // close only other subdropdowns inside the same parent dropdown
            subBtn.closest('.dropdown')
                .querySelectorAll('.subdropdown.active')
                .forEach(sd => {
                    if (sd !== subdropdown) sd.classList.remove('active');
                });

            subdropdown.classList.toggle('active');
        });
    });

    // close all when clicking outside
    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown.active, .subdropdown.active')
            .forEach(d => d.classList.remove('active'));
    });

});


// list active logic

document.addEventListener('DOMContentLoaded', () => {
    const currentUrl = window.location.pathname; // e.g. "/account/kyc"
    const navLinks = document.querySelectorAll('.nav-link, .dropdown a, .subdropdown a');

    navLinks.forEach(link => {
        const linkPath = link.getAttribute('href');

        // skip empty or "#" links
        if (!linkPath || linkPath === '#') return;

        // check if current URL matches or starts with the link href
        if (currentUrl === linkPath || currentUrl.startsWith(linkPath)) {
            // remove active class from all links first
            document.querySelectorAll('.nav-link.active').forEach(active => {
                active.classList.remove('active');
            });

            // add to this one
            link.classList.add('active');

            // also open its parent dropdown if it’s inside one
            const dropdown = link.closest('.dropdown');
            if (dropdown) dropdown.classList.add('active');

            const subdropdown = link.closest('.subdropdown');
            if (subdropdown) subdropdown.classList.add('active');
        }
    });
});



function previewImage(input, previewId) {
    const file = input.files[0];
    const preview = document.getElementById(previewId);

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.src = '';
        preview.style.display = 'none';
    }
}

// Cheque proof preview
document.querySelector('.cheque-proof').addEventListener('change', function () {
    previewImage(this, 'chequePreview');
});

// Mobile proof preview
document.querySelector('.mobile-proof').addEventListener('change', function () {
    previewImage(this, 'mobilePreview');
});