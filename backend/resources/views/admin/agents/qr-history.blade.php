@extends('admin.layouts.master')
@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Lịch sử mã QR - {{ $agent->name }}</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.agents.index') }}">Quản lý đại lý</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.agents.show', $agent->id) }}">Chi tiết đại lý</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Lịch sử mã QR</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">
                        <a href="{{ route('admin.agents.show', $agent->id) }}" class="btn btn-white btn-icon-text my-2 me-2">
                            <i class="fe fe-arrow-left"></i>
                            <span>Quay lại</span>
                        </a>
                        <a href="{{ route('admin.agents.regenerate-qr', $agent->id) }}" class="btn btn-primary btn-icon-text my-2 me-2" onclick="return confirm('Bạn có chắc chắn muốn tạo mã QR mới không?')">
                            <i class="fe fe-plus-circle"></i>
                            <span>Tạo mã QR mới</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- END PAGE HEADER -->

            <!-- ROW -->
            <div class="row row-sm">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div>
                                <h6 class="main-content-label mb-1">Lịch sử mã QR</h6>
                                <p class="text-muted card-sub-title">Danh sách các mã QR đã tạo cho đại lý {{ $agent->name }}</p>
                            </div>

                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px">#</th>
                                            <th style="width: 100px">Mã QR</th>
                                            <th>Token</th>
                                            <th>Ngày tạo</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($qrCodes->count() > 0)
                                            @foreach($qrCodes as $index => $qr)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        @if($qr->qr_code_path)
                                                            <img src="{{ asset($qr->qr_code_path) }}" alt="QR Code" style="max-width: 80px; max-height: 80px;">
                                                        @else
                                                            <span class="text-muted">Không có ảnh</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <small>{{ $qr->qr_token }}</small>
                                                    </td>
                                                    <td>
                                                        @if(is_string($qr->generated_at))
                                                            {{ $qr->generated_at }}
                                                        @else
                                                            {{ $qr->generated_at->format('d/m/Y H:i:s') }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            @if($qr->qr_code_path)
                                                                <a href="{{ asset($qr->qr_code_path) }}" download class="btn btn-sm btn-info">
                                                                    <i class="fe fe-download"></i> Tải xuống
                                                                </a>
                                                                <a href="javascript:void(0)" onclick="printQRCode('{{ $agent->name }}', '{{ $agent->phone }}', '{{ $agent->full_address }}', '{{ asset($qr->qr_code_path) }}')" class="btn btn-sm btn-outline-secondary">
                                                                    <i class="fe fe-printer"></i> In mã QR
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center">Chưa có mã QR nào được tạo</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END ROW -->
        </div>
    </div>
</div>

<script>
    // Hàm in mã QR
    function printQRCode(agentName, agentPhone, agentAddress, qrCodeSrc) {
        // Chuẩn bị nội dung để in
        var printContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>In mã QR - ${agentName}</title>
                <style>
                    body {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        height: 100vh;
                        font-family: Arial, sans-serif;
                    }
                    .container {
                        text-align: center;
                        max-width: 100%;
                        padding: 10px;
                    }
                    .qr-code {
                        width: 250px;
                        height: 250px;
                        margin: 20px auto;
                        object-fit: contain;
                    }
                    .agent-info {
                        margin-bottom: 20px;
                    }
                    @media print {
                        @page {
                            size: 80mm 80mm;
                            margin: 5mm;
                        }
                        body {
                            margin: 0;
                            padding: 0;
                        }
                        .qr-code {
                            width: 70%;
                            height: auto;
                        }
                        .agent-info h3 {
                            font-size: 14px;
                            margin-bottom: 5px;
                        }
                        .agent-info p {
                            font-size: 12px;
                            margin: 5px 0;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="agent-info">
                        <h3>${agentName}</h3>
                        <p>${agentPhone}</p>
                        <p>${agentAddress}</p>
                    </div>
                    <img src="${qrCodeSrc}" alt="QR Code" class="qr-code" id="qr-image">
                    <p style="font-size: 12px;">Quét mã QR này để xem thông tin đại lý</p>
                </div>
                <script>
                    // Đợi hình ảnh tải xong
                    document.getElementById('qr-image').onload = function() {
                        // Đặt timeout để đảm bảo tài nguyên được tải hoàn tất
                        setTimeout(function() {
                            try {
                                window.print();
                            } catch (e) {
                                console.error("Lỗi khi in:", e);
                                alert("Có lỗi khi in: " + e.message);
                            }
                        }, 500);
                    };

                    // Xử lý lỗi nếu hình ảnh không tải được
                    document.getElementById('qr-image').onerror = function() {
                        document.body.innerHTML = '<div style="color:red;text-align:center;margin:50px;">Không thể tải hình ảnh mã QR. Vui lòng thử lại!</div>';
                    };
                <\/script>
            </body>
            </html>
        `;
    }
</script>

@endsection
