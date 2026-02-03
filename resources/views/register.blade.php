<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Portal Register</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Register for Secure Portal</h3>
                    </div>
                    <div class="card-body">
                        <form id="register-form">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div id="error-message" class="alert alert-danger" style="display: none;"></div>
                            <div id="success-message" class="alert alert-success" style="display: none;"></div>
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                        <div class="mt-3 text-center">
                            <p>Already have an account? <a href="/login">Login here</a></p>
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
        $('#register-form').on('submit', function(e) {
            e.preventDefault();

            const name = $('#name').val();
            const email = $('#email').val();
            const password = $('#password').val();

            $.ajax({
                url: '/api/register',
                type: 'POST',
                data: { name: name, email: email, password: password },
                success: function(response) {
                    $('#success-message').text('Registration successful! Check your email to verify your account.').show();
                    $('#error-message').hide();
                    $('#register-form')[0].reset();
                    setTimeout(function() {
                        window.location.href = '/login';
                    }, 5000);
                },
                error: function(xhr) {
                    const error = xhr.responseJSON ? xhr.responseJSON.message || 'Registration failed' : 'Registration failed';
                    $('#error-message').text(error).show();
                    $('#success-message').hide();
                }
            });
        });
    </script>
</body>
</html>