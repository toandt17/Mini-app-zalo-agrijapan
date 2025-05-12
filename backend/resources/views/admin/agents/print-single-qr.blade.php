<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In mã QR - {{ $agent->name }}</title>
    <style>
        @page {
            size: 105mm 22mm;  /* 35mm x 3 = 105mm */
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
            width: 105mm;
            height: 22mm;
        }
        .container {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: row;
            padding: 0;
            margin: 0;
        }
        .qr-section {
            width: 35mm;
            height: 22mm;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 2mm;
            box-sizing: border-box;
        }
        .qr-pair {
            display: flex;
            justify-content: center;
            gap: 6mm;
            margin: 0 auto;
        }
        .qr-card {
            width: 13mm;
            height: 13mm;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 0;
        }
        .qr-image {
            width: 13mm;
            height: 13mm;
            margin: 0;
            padding: 0;
        }
        .qr-text {
            font-size: 9px;
            margin-top: 2mm;
            margin-left: 1mm;
            text-align: left;
            font-weight: bold;
            width: 32mm;
            display: inline-block;
            letter-spacing: 0.2px;
        }
        .agent-name {
            font-size: 5px;
            font-weight: bold;
            margin-top: 1mm;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 13mm;
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

        <!-- Section 1 -->
        <div class="qr-section">
            <div class="qr-pair">
                <div class="qr-card">
                    <img src="{{ asset($agent->qr_code) }}" alt="QR Code" class="qr-image">
                </div>
                <div class="qr-card">
                    <img src="{{ asset($agent->qr_code) }}" alt="QR Code" class="qr-image">
                </div>
            </div>
            <div class="qr-text">QR Bảo chứng chất lượng</div>
        </div>

        <!-- Section 2 -->
        <div class="qr-section">
            <div class="qr-pair">
                <div class="qr-card">
                    <img src="{{ asset($agent->qr_code) }}" alt="QR Code" class="qr-image">
                </div>
                <div class="qr-card">
                    <img src="{{ asset($agent->qr_code) }}" alt="QR Code" class="qr-image">
                </div>
            </div>
            <div class="qr-text">QR Bảo chứng chất lượng</div>
        </div>

        <!-- Section 3 -->
        <div class="qr-section">
            <div class="qr-pair">
                <div class="qr-card">
                    <img src="{{ asset($agent->qr_code) }}" alt="QR Code" class="qr-image">
                </div>
                <div class="qr-card">
                    <img src="{{ asset($agent->qr_code) }}" alt="QR Code" class="qr-image">
                </div>
            </div>
            <div class="qr-text">QR Bảo chứng chất lượng</div>
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
