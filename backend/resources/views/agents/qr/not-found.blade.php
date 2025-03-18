<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Không tìm thấy đại lý</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Feather Icons -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/feather-font/css/iconfont.css') }}">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .error-container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .error-icon {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 20px;
        }
        .error-message {
            margin-bottom: 30px;
            color: #333;
        }
        .back-btn {
            background-color: #28a745;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
            margin-top: 20px;
            text-decoration: none;
            display: inline-block;
        }
        .back-btn:hover {
            background-color: #218838;
            color: white;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="fe fe-alert-circle"></i>
        </div>
        <h2>Lỗi</h2>
        <div class="error-message">
            <p>{{ $message ?? 'Không tìm thấy thông tin đại lý.' }}</p>

            @if(isset($error))
            <div class="alert alert-danger">
                {{ $error }}
            </div>
            @endif

            @if(isset($agent))
            <div class="mt-3">
                <p><strong>Đại lý:</strong> {{ $agent->name }}</p>
                <p><strong>Địa chỉ:</strong> {{ $agent->full_address }}</p>
            </div>
            @endif
        </div>

        <a href="{{ url('/') }}" class="back-btn">
            <i class="fe fe-home"></i> Về trang chủ
        </a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
