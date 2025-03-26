<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In mã QR - {{ $agent->name }}</title>
    <style>
        @page {
            size: 35cm 22cm;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
            width: 35cm;
            height: 22cm;
        }
        .container {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            padding: 0;
            margin: 0;
        }
        .qr-wrapper {
            display: flex;
            justify-content: space-around;
            align-items: center;
            width: 100%;
            height: 100%;
            flex-wrap: wrap;
        }
        .qr-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            width: 15cm;
            height: 18cm;
            margin: 0.5cm;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .qr-image {
            width: 12cm;
            height: 12cm;
            margin: 0 auto 15px;
        }
        .agent-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .agent-info {
            font-size: 14px;
            color: #555;
            margin-bottom: 3px;
        }
        .button-group {
            margin: 10px 0;
        }
        .btn {
            padding: 8px 15px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
        }
        .btn-back {
            background-color: #607d8b;
        }
        .loading-indicator {
            display: none;
            margin-top: 20px;
            color: #666;
        }
        .print-status {
            margin-top: 10px;
            padding: 5px;
            border-radius: 3px;
            display: none;
        }
        .print-error {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
        @media print {
            .no-print {
                display: none;
            }
            .qr-card {
                border: none;
                page-break-inside: avoid;
            }
            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="button-group no-print">
            <button class="btn" id="printButton">In mã QR</button>
            <a href="{{ route('admin.agents.index') }}" class="btn btn-back">Quay lại</a>
        </div>

        <div id="loadingIndicator" class="loading-indicator no-print">
            Đang chuẩn bị mã QR để in...
        </div>

        <div id="printError" class="print-status print-error no-print"></div>

        <div class="qr-wrapper">
            <!-- QR Code 1 -->
            <div class="qr-card">
                <img src="{{ asset($agent->qr_code) }}" alt="QR Code" class="qr-image qr-image-1">
                <div class="agent-name">{{ $agent->name }}</div>
            </div>

            <!-- QR Code 2 (duplicate) -->
            <div class="qr-card">
                <img src="{{ asset($agent->qr_code) }}" alt="QR Code" class="qr-image qr-image-2">
                <div class="agent-name">{{ $agent->name }}</div>
            </div>
        </div>
    </div>

    <script>
        // Hiển thị trạng thái khi tải trang
        document.addEventListener('DOMContentLoaded', function() {
            var qrImages = document.querySelectorAll('.qr-image');
            var loadingIndicator = document.getElementById('loadingIndicator');
            var printButton = document.getElementById('printButton');
            var printError = document.getElementById('printError');
            var imagesLoaded = 0;
            var totalImages = qrImages.length;

            // Hiển thị đang tải
            loadingIndicator.style.display = 'block';

            // Kiểm tra tất cả ảnh
            qrImages.forEach(function(img) {
                if (img.complete) {
                    imagesLoaded++;
                    if (imagesLoaded === totalImages) {
                        loadingIndicator.style.display = 'none';
                        initializePrint();
                    }
                } else {
                    img.onload = function() {
                        imagesLoaded++;
                        if (imagesLoaded === totalImages) {
                            loadingIndicator.style.display = 'none';
                            initializePrint();
                        }
                    };

                    img.onerror = function() {
                        loadingIndicator.style.display = 'none';
                        printError.textContent = 'Không thể tải hình ảnh QR. Vui lòng tải lại trang.';
                        printError.style.display = 'block';
                    };
                }
            });

            // Khởi tạo chức năng in
            function initializePrint() {
                // Tự động mở hộp thoại in sau 1 giây
                setTimeout(function() {
                    try {
                        window.print();
                    } catch (e) {
                        printError.textContent = 'Có lỗi khi in: ' + e.message;
                        printError.style.display = 'block';
                    }
                }, 1000);

                // Xử lý sự kiện nút in
                printButton.addEventListener('click', function() {
                    try {
                        window.print();
                    } catch (e) {
                        printError.textContent = 'Có lỗi khi in: ' + e.message;
                        printError.style.display = 'block';
                    }
                });
            }
        });
    </script>
</body>
</html>
