<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Không tìm thấy đại lý</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2a7d2e;
            --danger-color: #dc3545;
            --text-color: #333;
            --border-radius: 12px;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f2f5;
            color: var(--text-color);
            line-height: 1.6;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background-color: var(--primary-color);
            color: white;
            padding: 15px 0;
            margin-bottom: 30px;
            text-align: center;
        }

        .error-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
            flex-grow: 1;
        }

        .error-icon {
            font-size: 5rem;
            color: var(--danger-color);
            margin-bottom: 20px;
        }

        .error-title {
            font-size: 1.8rem;
            color: var(--danger-color);
            margin-bottom: 15px;
            font-weight: 700;
        }

        .error-message {
            margin-bottom: 25px;
        }

        .btn-home {
            background-color: var(--primary-color);
            border: none;
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: 500;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            padding: 20px 0;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .error-details {
            margin-top: 30px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: var(--border-radius);
            text-align: left;
            font-family: monospace;
            overflow-x: auto;
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            body {
                background-color: #1a1a1a;
                color: #e0e0e0;
            }

            .error-container {
                background-color: #2a2a2a;
            }

            .error-details {
                background-color: #333;
            }

            :root {
                --text-color: #e0e0e0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h2 class="mb-0">Thông tin đại lý</h2>
        </div>
    </div>

    <div class="container mb-4">
        <div class="error-container">
            <div class="error-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>

            <h1 class="error-title">Không tìm thấy đại lý</h1>

            <div class="error-message">
                <p>Xin lỗi, chúng tôi không thể tìm thấy đại lý mà bạn đang tìm kiếm.</p>
                <p>Mã QR này có thể đã hết hạn hoặc đã bị vô hiệu hóa.</p>
            </div>

            <a href="{{ url('/') }}" class="btn btn-primary btn-home">
                <i class="fas fa-home me-2"></i> Về trang chủ
            </a>

            @if(isset($error) && app()->environment('local'))
            <div class="error-details">
                <p class="mb-1"><strong>Thông tin lỗi (chỉ hiển thị trong môi trường phát triển):</strong></p>
                <p class="mb-1">{{ $error }}</p>
                @if(isset($agent_id))
                <p class="mb-0">ID đại lý: {{ $agent_id }}</p>
                @endif
            </div>
            @endif
        </div>
    </div>

    <div class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} AgriJapan - Powered by Zalo Mini App</p>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
