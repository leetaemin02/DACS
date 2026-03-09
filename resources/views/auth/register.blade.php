<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng Ký</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(120deg, #4e54c8, #8f94fb);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            width: 420px;
            border-radius: 15px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-link {
            font-size: 24px;
            font-weight: bold;
            color: #4e54c8;
            text-decoration: none;
            display: inline-block;
        }

        .logo-link:hover {
            color: #3c40a4;
            transition: 0.3s;
        }

        .logo-link svg {
            vertical-align: middle;
            margin-right: 8px;
        }

        .btn-login {
            background: #4e54c8;
            color: white;
            border: none;
        }

        .btn-login:hover {
            background: #3c40a4;
            color: white;
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 5px;
            font-size: 16px;
        }

        .password-toggle:hover {
            color: #495057;
        }

        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            background: none;
            border: none;
            font-size: 28px;
            color: #4e54c8;
            cursor: pointer;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            z-index: 1000;
        }

        .back-button:hover {
            color: #3c40a4;
            transform: scale(1.1);
            transition: 0.3s;
        }

        .card-container {
            position: relative;
        }
    </style>

</head>

<body>

    <div class="card-container">
        <a href="/" class="back-button">
            <i class="bi bi-arrow-left"></i>
        </a>

        <div class="card shadow p-4">

            <div class="logo-container">
                <a href="/" class="logo-link">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    BookStore</a>
            </div>

            <h3 class="text-center mb-4">Đăng Ký</h3>

            <form method="POST" action="/register">
                @csrf

                <div class="mb-3">
                    <label>Họ tên</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Mật khẩu</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" class="form-control" id="password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="bi bi-eye-slash" id="password-icon"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Xác nhận mật khẩu</label>
                    <div class="password-wrapper">
                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                            <i class="bi bi-eye-slash" id="password_confirmation-icon"></i>
                        </button>
                    </div>
                </div>

                <button class="btn btn-login w-100">Đăng ký</button>

                <p class="text-center mt-3">
                    Đã có tài khoản? <a href="/login">Đăng nhập</a>
                </p>

            </form>

        </div>

        <script>
            function togglePassword(inputId) {
                const input = document.getElementById(inputId);
                const icon = document.getElementById(inputId + '-icon');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.className = 'bi bi-eye';
                } else {
                    input.type = 'password';
                    icon.className = 'bi bi-eye-slash';
                }
            }
        </script>

    </div>

</body>

</html>