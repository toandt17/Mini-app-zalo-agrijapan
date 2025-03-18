<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đang chuyển hướng đến đại lý: {{ $agent->name }}</title>
    <meta name="description" content="Thông tin đại lý {{ $agent->name }} - Địa chỉ: {{ $agent->full_address }}">

    <!-- SEO tags -->
    <meta property="og:title" content="Đại lý {{ $agent->name }}">
    <meta property="og:description" content="Địa chỉ: {{ $agent->full_address }} - Điện thoại: {{ $agent->phone }}">
    @if($agent->image)
    <meta property="og:image" content="{{ url($agent->image) }}">
    @endif

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            max-width: 90%;
            width: 600px;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .logo {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
        }
        h1 {
            color: #2c74d3;
            font-size: 24px;
            margin-bottom: 10px;
        }
        p {
            margin: 10px 0;
            line-height: 1.5;
        }
        .loader {
            width: 48px;
            height: 48px;
            border: 5px solid #2c74d3;
            border-bottom-color: transparent;
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
            margin: 20px 0;
        }
        .info {
            background-color: #e6f3ff;
            border-left: 4px solid #2c74d3;
            padding: 10px 15px;
            text-align: left;
            margin: 15px 0;
            border-radius: 4px;
        }
        .btn {
            display: inline-block;
            background-color: #2c74d3;
            color: white;
            padding: 12px 24px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 15px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #1a5bb5;
        }
        .qr-info {
            background-color: #fff8e6;
            border-left: 4px solid #ffb300;
            padding: 10px 15px;
            text-align: left;
            margin: 15px 0;
            border-radius: 4px;
            font-size: 14px;
        }
        @keyframes rotation {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Đại lý {{ $agent->name }}</h1>

        <div class="info">
            <p><strong>Địa chỉ:</strong> {{ $agent->full_address }}</p>
            <p><strong>Điện thoại:</strong> {{ $agent->phone }}</p>
            @if($agent->open_hours)
            <p><strong>Giờ mở cửa:</strong> {{ $agent->open_hours }}</p>
            @endif
        </div>

        @if(isset($created_time))
        <div class="qr-info">
            <p><strong>Thông tin mã QR:</strong></p>
            <p>Thời gian tạo: {{ $created_time }}</p>
            <p>Thời gian quét: {{ $timestamp }}</p>
        </div>
        @endif

        <div id="loading">
            <span class="loader"></span>
            <p>Đang chuyển hướng đến ứng dụng Zalo...</p>
        </div>

        <div id="manual-redirect" style="display: none;">
            <p>Nếu trang không tự động chuyển hướng, vui lòng nhấn nút bên dưới:</p>
            <a href="" id="redirect-btn" class="btn">Mở trong Zalo Mini App</a>
            <p style="margin-top: 20px; font-size: 14px; color: #666;">
                Hoặc <a href="https://zalo.me/pc" target="_blank">tải ứng dụng Zalo</a> để xem thông tin đầy đủ.
            </p>
        </div>
    </div>

    <script>
        // URL để mở trong Zalo Mini App
        var zaloUrl = "{{ $zalo_url ?? '' }}";

        // Nếu không có zaloUrl trong biến, sử dụng URL format mặc định
        if (!zaloUrl) {
            zaloUrl = "https://zalo.me/s/45775019718875745/?page=/agents/{{ $agent->id }}";
            @if(isset($token))
            zaloUrl += "?created={{ $token }}";
            @endif
        }

        // URL web thông thường
        var webUrl = "{{ url('/agents/' . $agent->id) }}";
        @if(isset($token))
        webUrl += "?created={{ $token }}";
        @endif

        // Thiết lập timeout cho chuyển hướng tự động
        var redirectTimeout;

        // Kiểm tra xem có đang chạy trong Zalo app không
        var isZaloApp = {{ $is_zalo_app ? 'true' : 'false' }};

        // Cập nhật URL cho nút chuyển hướng thủ công
        document.getElementById('redirect-btn').href = zaloUrl;

        // Thực hiện chuyển hướng sau 2 giây
        function performRedirect() {
            try {
                console.log("Kiểm tra trong app Zalo: " + isZaloApp);

                if (isZaloApp) {
                    // Nếu đang trong app Zalo, sử dụng webUrl (sẽ được xử lý bởi Zalo)
                    console.log("Đang trong app Zalo, chuyển đến: " + webUrl);
                    window.location.href = webUrl;
                } else {
                    // Nếu không trong app Zalo, chuyển đến URL Zalo để mở Mini App
                    console.log("Không trong app Zalo, chuyển đến app Zalo: " + zaloUrl);
                    window.location.href = zaloUrl;

                    // Hiển thị nút chuyển hướng thủ công sau 1.5 giây
                    setTimeout(function() {
                        document.getElementById('loading').style.display = 'none';
                        document.getElementById('manual-redirect').style.display = 'block';
                    }, 1500);
                }
            } catch (e) {
                console.error("Redirect error:", e);
                // Hiển thị nút chuyển hướng thủ công trong trường hợp có lỗi
                document.getElementById('loading').style.display = 'none';
                document.getElementById('manual-redirect').style.display = 'block';
            }
        }

        // Bắt đầu chuyển hướng sau 2 giây
        redirectTimeout = setTimeout(performRedirect, 2000);
    </script>
</body>
</html>
