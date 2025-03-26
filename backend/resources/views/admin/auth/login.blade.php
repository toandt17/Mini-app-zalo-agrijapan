<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - AgriJapan</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2E7D32;
            --secondary-color: #66BB6A;
            --accent-color: #FFA000;
            --dark-green: #1B5E20;
            --light-gray: #f7f7f7;
        }

        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: #9e9e9e;
            background-image: url('https://images.unsplash.com/photo-1473973266408-ed4e9cc18b23?q=80&w=2072&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background-color: white;
            border-radius: 16px;
            max-width: 450px;
            width: 100%;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .tech-line {
            height: 5px;
            flex: 1;
        }

        .tech-line:nth-child(odd) {
            background-color: var(--primary-color);
        }

        .tech-line:nth-child(even) {
            background-color: var(--accent-color);
        }

        .logo-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-header img {
            height: 60px;
        }

        .logo-text {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            font-size: 28px;
            color: var(--primary-color);
        }

        .logo-text i {
            margin-right: 10px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 5px;
        }

        .page-subtitle {
            font-size: 14px;
            color: #666;
            text-align: center;
            margin-bottom: 30px;
        }

        .divider {
            width: 70px;
            height: 3px;
            background-color: var(--accent-color);
            margin: 15px auto 25px;
        }

        .form-label {
            display: flex;
            align-items: center;
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-label i {
            margin-right: 10px;
            font-size: 18px;
        }

        .form-control {
            border: none;
            border-bottom: 1px solid #ddd;
            border-radius: 0;
            padding: 10px 5px;
            margin-bottom: 20px;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--primary-color);
        }

        .form-control::placeholder {
            color: #aaa;
        }

        .btn-login {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px;
            width: 100%;
            font-weight: 600;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-login i {
            margin-right: 10px;
        }

        .form-check-input {
            margin-right: 10px;
        }

        .error-box {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-card">

        <div class="logo-text">
            <i class="fas fa-leaf" style="color: var(--primary-color);"></i>
            <span style="margin-left: 10px;">AgriJapan</span>
        </div>

        <h1 class="page-title">Đăng nhập</h1>
        <p class="page-subtitle">Đăng nhập để tiếp tục quản lý hệ thống</p>

        <div class="divider"></div>

        @if ($errors->any())
            <div class="error-box">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label for="email" class="form-label">
                <i class="fas fa-envelope" style="color: var(--primary-color);"></i>
                Email
            </label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Nhập email của bạn" required>

            <label for="password" class="form-label">
                <i class="fas fa-lock" style="color: var(--primary-color);"></i>
                Mật khẩu
            </label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" required>

            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt"></i> ĐĂNG NHẬP
            </button>
        </form>
    </div>
</body>
</html>
