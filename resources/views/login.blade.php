<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Portal Login</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Login to Secure Portal</h3>
                    </div>
                    <div class="card-body">
                        <form id="login-form">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div id="error-message" class="alert alert-danger" style="display: none;"></div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                        <div class="mt-3 text-center">
                            <p>Don't have an account? <a href="/register">Register here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        $('#login-form').on('submit', function(e) {
            e.preventDefault();

            const email = $('#email').val();
            const password = $('#password').val();

            $.ajax({
                url: '/api/login',
                type: 'POST',
                data: { email: email, password: password },
                success: function(response) {
                    // Store the token in localStorage
                    localStorage.setItem('jwt_token', response.access_token);
                    // Also set a cookie so server-side middleware can pick up the JWT
                    document.cookie = 'jwt_token=' + response.access_token + '; path=/';
                    // Redirect to dashboard
                    window.location.href = '/dashboard';
                },
                error: function(xhr, status, errorThrown) {
                    let message = 'Login failed';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        message = xhr.responseJSON.error;
                    } else if (xhr.responseText) {
                        message = xhr.responseText;
                    } else if (errorThrown) {
                        message = errorThrown;
                    }
                    $('#error-message').text(message).show();
                }
            });
        });
    </script>
</body>
</html>