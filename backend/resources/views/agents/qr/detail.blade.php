<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agent->name }} - Thông tin đại lý</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2a7d2e;
            --secondary-color: #f8f9fa;
            --accent-color: #4caf50;
            --text-color: #333;
            --light-text: #6c757d;
            --border-radius: 12px;
            --box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f2f5;
            color: var(--text-color);
            line-height: 1.6;
            padding-bottom: 30px;
        }

        .header {
            background-color: var(--primary-color);
            color: white;
            padding: 15px 0;
            margin-bottom: 20px;
            text-align: center;
        }

        .agent-container {
            margin: 0 auto 30px;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }

        .agent-header {
            position: relative;
            height: 200px;
            overflow: hidden;
            background-color: #e9ecef;
        }

        .agent-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .agent-image-placeholder {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            background-color: var(--primary-color);
            color: white;
            font-size: 50px;
        }

        .agent-body {
            padding: 25px;
        }

        .agent-name {
            color: var(--primary-color);
            margin-bottom: 15px;
            font-weight: 700;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .badge-agent-status {
            font-size: 0.7rem;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .agent-info-card {
            background-color: var(--secondary-color);
            padding: 15px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .contact-info {
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .info-icon {
            min-width: 25px;
            margin-right: 10px;
            color: var(--primary-color);
        }

        .info-content {
            flex-grow: 1;
        }

        .info-content a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .info-content a:hover {
            text-decoration: underline;
        }

        .qr-info {
            background-color: rgba(76, 175, 80, 0.1);
            padding: 15px;
            border-radius: var(--border-radius);
            margin-top: 20px;
            border-left: 4px solid var(--accent-color);
        }

        .qr-info p {
            margin-bottom: 8px;
        }

        .map-container {
            height: 250px;
            margin-top: 20px;
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        .btn-directions {
            width: 100%;
            margin-top: 10px;
            background-color: var(--primary-color);
            border: none;
            padding: 12px;
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-directions:hover {
            background-color: #1e5e21;
            transform: translateY(-2px);
        }

        .btn-directions i {
            margin-right: 8px;
        }

        .description-box {
            background-color: var(--secondary-color);
            padding: 15px;
            border-radius: var(--border-radius);
            margin-top: 20px;
        }

        .description-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        /* Mobile styles */
        @media (max-width: 767px) {
            .agent-header {
                height: 150px;
            }

            .agent-body {
                padding: 15px;
            }

            .agent-name {
                font-size: 1.5rem;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            body {
                background-color: #1a1a1a;
                color: #e0e0e0;
            }

            .agent-container, .agent-body {
                background-color: #2a2a2a;
            }

            .agent-info-card, .description-box {
                background-color: #333;
            }

            .qr-info {
                background-color: rgba(76, 175, 80, 0.05);
            }

            .info-content a, .agent-name {
                color: #6ecf73;
            }

            :root {
                --text-color: #e0e0e0;
                --light-text: #aaa;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h2 class="mb-0">Thông tin AgriJapan</h2>
        </div>
    </div>

    <div class="container">
        <div class="agent-container">
            <div class="agent-header">
                @if($agent->image)
                    <img src="{{ asset('storage/' . $agent->image) }}" alt="AgriJapan" class="agent-image">
                @else
                    <div class="agent-image-placeholder">
                        <i class="fas fa-building"></i>
                    </div>
                @endif
            </div>

            <div class="agent-body">
                <div class="d-flex justify-content-between align-items-start">
                    <h1 class="agent-name">
                        <i class="fas fa-building"></i>
                        AgriJapan
                    </h1>

                    @if($agent->status == 'active')
                        <span class="badge bg-success badge-agent-status">Đang hoạt động</span>
                    @else
                        <span class="badge bg-danger badge-agent-status">Không hoạt động</span>
                    @endif
                </div>

                <div class="agent-info-card">
                    <div class="contact-info">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="info-content">
                                <strong>Công ty:</strong><br>
                                AgriJapan
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info-content">
                                <strong>Địa chỉ:</strong><br>
                                A2-12, Đường Số 2, KDC Long Thịnh, Thạnh Lợi, Phú Thứ, Cái Răng, Cần Thơ
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <strong>Email:</strong><br>
                                <a href="mailto:agrijapan2016@gmail.com">agrijapan2016@gmail.com</a>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="info-content">
                                <strong>Điện thoại:</strong><br>
                                <a href="tel:0868683689">0868 683 689</a>
                            </div>
                        </div>

                        <!-- Hiển thị mã barcode nhỏ gọn -->
                        @if(isset($agent) && $agent->latest_barcode)
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-barcode"></i>
                            </div>
                            <div class="info-content">
                                <span style="font-family: monospace; font-weight: bold; letter-spacing: 1px; font-size: 14px;">
                                    {{ $agent->latest_barcode->barcode_value }}
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Thêm nút Zalo OA -->
                <div class="text-center mt-3">
                    <a href="https://oauth.zaloapp.com/v4/permission?app_id=1684715619970753240&redirect_uri=https://agrijapanvn.com.vn/zalo/callback" target="_blank" class="btn btn-primary" style="background-color: #0068ff; border-color: #0068ff;"></a>
                        <i class="fab fa-zalo"></i> Chat Zalo với AgriJapan
                    </a>
                </div>

                @if($agent->description)
                <div class="description-box"></div>
                    <div class="description-title">
                        <i class="fas fa-info-circle"></i> Thông tin
                    </div>
                    <p>{{ $agent->description }}</p>
                </div>
                @endif

                @if(isset($qrCreatedFormatted))
                <div class="qr-info">
                    <p><i class="fas fa-qrcode"></i> <strong>Thời gian tạo QR:</strong> {{ $qrCreatedFormatted }}</p>
                    <p class="text-muted"><small><i class="fas fa-clock"></i> Thời gian quét: {{ now()->format('d/m/Y H:i:s') }}</small></p>

                    @if(isset($qrCode) && $qrCode)
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div>
                                <span class="badge bg-primary">Lượt quét: {{ $scan_count }}</span>
                                <span class="badge bg-info ms-1">Số người quét: {{ $unique_scan_count }}</span>
                            </div>

                            @if(isset($qrSource) && $qrSource == 'database')
                                <div>
                                    @if(isset($qrIsActive) && !$qrIsActive)
                                        <span class="badge bg-warning text-dark">Mã QR đã bị vô hiệu hóa</span>
                                    @else
                                        <span class="badge bg-success">Mã QR hợp lệ</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @elseif(isset($qrSource) && $qrSource == 'legacy')
                        <div class="mt-2">
                            <span class="badge bg-secondary">Mã QR phiên bản cũ</span>
                        </div>
                    @endif
                </div>
                @endif

                @if($agent->latitude && $agent->longitude)
                <div class="map-container" id="map"></div>
                <a href="https://maps.app.goo.gl/SsAMH6NGj5uDtKMN8"
                   class="btn btn-primary btn-directions" target="_blank">
                    <i class="fas fa-directions"></i> Chỉ đường đến công ty
                </a>
                @endif
            </div>
        </div>

        <div class="text-center text-muted small">
            <p>&copy; {{ date('Y') }} AgriJapan - Thông tin QR</p>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    @if($agent->latitude && $agent->longitude)
    <!-- Google Maps -->
    <script>
        function initMap() {
            const location = { lat: {{ $agent->latitude }}, lng: {{ $agent->longitude }} };
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: location,
                styles: [
                    {
                        "featureType": "poi",
                        "elementType": "labels",
                        "stylers": [{ "visibility": "off" }]
                    }
                ]
            });

            const marker = new google.maps.Marker({
                position: location,
                map: map,
                title: "{{ $agent->name }}",
                animation: google.maps.Animation.DROP
            });

            // Add info window
            const contentString =
                '<div id="content" style="padding: 10px;">' +
                '<h6 style="margin-top: 0;">{{ $agent->name }}</h6>' +
                '<div>{{ $agent->address }}</div>' +
                '<div><a href="tel:{{ $agent->phone }}">{{ $agent->phone }}</a></div>' +
                '</div>';

            const infowindow = new google.maps.InfoWindow({
                content: contentString,
            });

            marker.addListener("click", () => {
                infowindow.open({
                    anchor: marker,
                    map,
                });
            });

            // Open info window by default
            infowindow.open({
                anchor: marker,
                map,
            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY', '') }}&callback=initMap" defer></script>
    @endif
</body>
</html>
