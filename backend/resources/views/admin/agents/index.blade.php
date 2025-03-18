@extends('admin.layouts.master')
@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Danh sách đại lý</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Quản lý đại lý</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Danh sách đại lý</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">
                        <a href="{{ route('admin.agents.add') }}" class="btn btn-primary my-2 btn-icon-text me-2">
                            <i class="fe fe-plus"></i> Thêm mới đại lý
                        </a>
                        <a href="{{ route('admin.agents.print-qr-codes') }}" class="btn btn-info my-2 btn-icon-text">
                            <i class="fe fe-printer"></i> In mã QR
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
                                <h6 class="main-content-label mb-1">Danh sách đại lý</h6>
                            </div>
                            <div class="table-responsive">
                                <table id="file-datatable" class="table table-bordered text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tên đại lý</th>
                                            <th>Địa chỉ</th>
                                            <th>Số điện thoại</th>
                                            <th>Mã QR</th>
                                            <th>Trạng thái</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($agents as $agent)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $agent->name }}</td>
                                                <td>{{ $agent->full_address }}</td>
                                                <td>{{ $agent->phone }}</td>
                                                <td>
                                                    @if($agent->qr_code)
                                                        <div class="d-flex">
                                                            <a href="{{ asset($agent->qr_code) }}" target="_blank" class="me-2">
                                                                <img src="{{ asset($agent->qr_code) }}" alt="QR Code" style="max-width: 50px; max-height: 50px;" id="qrcode-{{ $agent->id }}">
                                                            </a>
                                                            <a href="{{ route('admin.agents.print-single-qr-code', $agent->id) }}" class="btn btn-sm btn-outline-secondary" title="In mã QR" target="_blank">
                                                                <i class="fe fe-printer"></i>
                                                            </a>

                                                        </div>
                                                    @else
                                                        <span class="text-danger">Chưa có mã QR</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($agent->status == 'active')
                                                        <span class="badge bg-success">Hoạt động</span>
                                                    @else
                                                        <span class="badge bg-danger">Không hoạt động</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('admin.agents.show', $agent->id) }}" class="btn btn-sm btn-info" title="Chi tiết">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.agents.edit', $agent->id) }}" class="btn btn-sm btn-primary" title="Chỉnh sửa">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="{{ route('admin.agents.regenerate-qr', $agent->id) }}" class="btn btn-sm btn-success" title="Tạo lại mã QR">
                                                            <i class="fas fa-qrcode"></i>
                                                        </a>
                                                        <a href="{{ route('admin.agents.qr-history', $agent->id) }}" class="btn btn-sm btn-secondary" title="Lịch sử mã QR">
                                                            <i class="fas fa-history"></i>
                                                        </a>
                                                        <a href="{{ route('admin.agents.barcode-history', $agent->id) }}" class="btn btn-sm btn-warning" title="Quản lý Barcode">
                                                            <i class="fas fa-barcode"></i>
                                                        </a>
                                                        <form action="{{ route('admin.agents.delete', $agent->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa đại lý này?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
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
    function confirmDelete() {
        return confirm('Bạn có chắc chắn muốn xóa đại lý này không?');
    }

    // Hàm in mã QR
    function printQRCode(agentId, agentName, agentPhone, agentAddress) {
        // Lấy URL của hình ảnh QR code
        var qrCodeSrc = document.getElementById('qrcode-' + agentId).src;

        // Kiểm tra xem đã lấy được URL hình ảnh chưa
        if (!qrCodeSrc) {
            alert('Không thể tìm thấy hình ảnh mã QR cho đại lý này');
            return;
        }

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
                    }
                    .qr-code {
                        max-width: 300px;
                        margin: 20px 0;
                    }
                    .agent-info {
                        margin-bottom: 20px;
                    }
                    @media print {
                        @page {
                            size: 80mm 80mm;
                            margin: 5mm;
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
                    <p>Quét mã QR này để xem thông tin đại lý</p>
                </div>
                <script>
                    // Đợi hình ảnh tải xong
                    document.getElementById('qr-image').onload = function() {
                        // Đặt timeout để đảm bảo tài nguyên được tải hoàn tất
                        setTimeout(function() {
                            try {
                                window.print();
                                // window.close(); // Đóng cửa sổ sau khi in xong
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
                </script>
            </body>
            </html>
        `;


    }
</script>

@endsection
