<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In mã Barcode</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        .print-container {
            text-align: center;
            padding: 20px;
        }
        .barcode-wrapper {
            text-align: center;
            padding: 10px;
        }
        .barcode-image {
            max-width: 100%;
            height: auto;
        }
        .barcode-value {
            margin-top: 10px;
            font-size: 16px;
            word-break: break-all;
            font-weight: bold;
            letter-spacing: 2px;
            font-family: monospace;
        }
        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
                background-color: white;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" style="padding: 8px 16px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
            In mã Barcode
        </button>
        <button onclick="window.close()" style="padding: 8px 16px; background-color: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 5px;">
            Đóng
        </button>
    </div>

    <div class="print-container">
        <div class="barcode-wrapper">
            @if($barcode->barcode_image)
            <div>
                <img src="{{ asset($barcode->barcode_image) }}" alt="Barcode" class="barcode-image">
                <div class="barcode-value">{{ $barcode->barcode_value }}</div>
            </div>
            @else
            <div style="color: red; padding: 20px;">
                Không tìm thấy hình ảnh barcode
            </div>
            @endif
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            // Slight delay to ensure the page is fully rendered
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
