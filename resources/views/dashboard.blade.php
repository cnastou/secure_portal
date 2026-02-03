<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Portal Dashboard</title>
    <!-- You can use Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Secure Portal</a>
  </div>
</nav>

<div class="container mt-4">
    <div id="dashboard-main" class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Dashboard</h1>
            <!--<p id="user-status" class="text-muted">Loading...</p>-->
        </div>
        <button id="logout-btn" class="btn btn-danger" style="display: none;">Logout</button>
    </div>

    <div id="user-info" class="mt-4">
        <h3>User Information</h3>
        <table class="table table-striped">
            <tbody id="user-table-body">
                <tr><td>Loading user info...</td></tr>
            </tbody>
        </table>
    </div>

    <div id="admin-section" class="mt-4" style="display: none;">
        <div class="alert alert-info">
            <h3>Admin Panel</h3>
            <p>This section is only visible to administrators.</p>
        </div>
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5>Admin Statistics</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tbody id="admin-stats-body">
                        <tr><td>Loading admin stats...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="calendar-events" class="mt-4">
        <h3>Calendar Events</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Start Time</th>
                </tr>
            </thead>
            <tbody id="events-table-body"></tbody>
        </table>
    </div>
</div>

<!-- Splash shown when user is not authenticated -->
<div id="dashboard-splash" style="display: none;">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div class="card shadow-sm">
                    <div class="card-body py-5">
                        <h1 class="display-5">Welcome to Secure Portal</h1>
                        <p class="lead text-muted">A secure place to manage your account and access admin tools.</p>
                        <div class="mt-4">
                            <a href="/login" class="btn btn-primary btn-lg me-2">Log In</a>
                            <a href="/register" class="btn btn-outline-primary btn-lg">Register</a>
                        </div>
                        <p class="mt-3"><a href="/">Return to homepage</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Optional: jQuery for AJAX -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    const token = localStorage.getItem('jwt_token'); // store JWT from login

    // Fetch logged-in user info
    if (token) {
        $.ajax({
            url: '/api/me',
            type: 'GET',
            headers: { 'Authorization': `Bearer ${token}` },
            success: function(user) {
                let tableHtml = '';
                let statusHtml = `<strong>${user.name}</strong>`;

                // Show admin badge and admin section when user is admin
                if (user.is_admin) {
                    statusHtml += ' <span class="badge bg-danger ms-2">ADMIN</span>';
                    $('#admin-section').show();
                    loadAdminStats();
                } else {
                    statusHtml += ' <span class="badge bg-secondary ms-2">USER</span>';
                }

                $('#user-status').html(statusHtml);

                tableHtml += `<tr><th>Name</th><td>${user.name}</td></tr>`;
                tableHtml += `<tr><th>Email</th><td>${user.email}</td></tr>`;
                tableHtml += `<tr><th>User ID</th><td>${user.id}</td></tr>`;
                tableHtml += `<tr><th>Status</th><td>${user.is_admin ? '<span class="badge bg-danger">Administrator</span>' : '<span class="badge bg-secondary">Regular User</span>'}</td></tr>`;
                if (user.created_at) {
                    tableHtml += `<tr><th>Created At</th><td>${new Date(user.created_at).toLocaleString()}</td></tr>`;
                }
                if (user.email_verified_at) {
                    tableHtml += `<tr><th>Email Verified</th><td>${new Date(user.email_verified_at).toLocaleString()}</td></tr>`;
                } else {
                    tableHtml += `<tr><th>Email Verified</th><td><span class="badge bg-warning">Not Verified</span></td></tr>`;
                }

                $('#user-table-body').html(tableHtml);
                $('#logout-btn').show();
            },
            error: function() {
                $('#user-table-body').html('<tr><td class="text-danger">Failed to load user info. Please login again.</td></tr>');
            }
        });

        // Fetch calendar events (if Google Calendar API is integrated)
        // $.ajax({
        //     url: '/api/calendar',
        //     type: 'GET',
        //     headers: { 'Authorization': `Bearer ${token}` },
        //     success: function(events) {
        //         if(events.length === 0) {
        //             $('#events-table-body').html('<tr><td colspan="2">No events found</td></tr>');
        //         } else {
        //             let tableHtml = '';
        //             events.forEach(event => {
        //                 const start = event.start?.dateTime || event.start?.date || '';
        //                 tableHtml += `<tr><td>${event.summary}</td><td>${start}</td></tr>`;
        //             });
        //             $('#events-table-body').html(tableHtml);
        //         }
        //     },
        //     error: function() {
        //         $('#events-table-body').html('<tr><td colspan="2" class="text-danger">Failed to load events</td></tr>');
        //     }
        // });
    } else {
        // No token: show a splash page and hide dashboard content
        $('#dashboard-main').hide();
        $('#user-info').hide();
        $('#admin-section').hide();
        $('#calendar-events').hide();
        $('#dashboard-splash').show();
    }

    // Logout functionality
    $('#logout-btn').on('click', function() {
        $.ajax({
            url: '/api/logout',
            type: 'POST',
            headers: { 'Authorization': `Bearer ${token}` },
            success: function() {
                localStorage.removeItem('jwt_token');
                document.cookie = 'jwt_token=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;';
                window.location.href = '/login';
            },
            error: function() {
                alert('Logout failed. Please try again.');
            }
        });
    });
</script>

</body>
</html>
