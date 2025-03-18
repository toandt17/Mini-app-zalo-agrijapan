@extends('admin.layouts.master')
@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Tra cứu mã barcode</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Quản lý đại lý</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tra cứu mã barcode</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">
                        <a href="{{ route('admin.agents.index') }}" class="btn btn-primary my-2 btn-icon-text me-2">
                            <i class="fe fe-list"></i> Danh sách đại lý
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
                                <h6 class="main-content-label mb-3">Nhập mã barcode để tra cứu</h6>
                            </div>

                            <form action="{{ route('admin.agents.process-barcode-search') }}" method="POST" class="mb-4">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="barcode" placeholder="Nhập mã barcode (13 chữ số)" value="{{ $barcode_query ?? '' }}" required>
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-search me-1"></i> Tìm kiếm
                                            </button>
                                        </div>
                                        <small class="text-muted">Nhập chính xác 13 chữ số của mã barcode EAN-13</small>
                                    </div>
                                </div>
                            </form>

                            @if(isset($error_message))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i> {{ $error_message }}
                            </div>
                            @endif

                            @if(isset($barcode) && isset($agent))
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Kết quả tìm kiếm</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="fw-bold">Thông tin mã barcode</h6>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th width="40%">Mã barcode</th>
                                                    <td>{{ $barcode->barcode_value }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Mã đơn hàng</th>
                                                    <td>{{ $barcode->order_code ?? 'Không có' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Mã đại lý</th>
                                                    <td>{{ $code_agent }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Ngày tạo</th>
                                                    <td>{{ $generated_time }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Trạng thái</th>
                                                    <td>
                                                        @if($barcode->status == 'active')
                                                            <span class="badge bg-success">Hoạt động</span>
                                                        @else
                                                            <span class="badge bg-danger">Không hoạt động</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="fw-bold">Thông tin đại lý</h6>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th width="40%">Tên đại lý</th>
                                                    <td>{{ $agent->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Địa chỉ</th>
                                                    <td>{{ $agent->full_address }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Số điện thoại</th>
                                                    <td>{{ $agent->phone }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Trạng thái</th>
                                                    <td>
                                                        @if($agent->status == 'active')
                                                            <span class="badge bg-success">Hoạt động</span>
                                                        @else
                                                            <span class="badge bg-danger">Không hoạt động</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <a href="{{ route('admin.agents.barcode-history', $agent->id) }}" class="btn btn-info">
                                            <i class="fas fa-history me-1"></i> Xem lịch sử barcode
                                        </a>
                                        <a href="{{ route('admin.agents.show', $agent->id) }}" class="btn btn-primary">
                                            <i class="fas fa-eye me-1"></i> Xem chi tiết đại lý
                                        </a>
                                        @if($barcode->status == 'active')
                                        <a href="{{ route('admin.agents.deactivate-barcode', [$agent->id, $barcode->id]) }}" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn vô hiệu hóa mã barcode này?')">
                                            <i class="fas fa-ban me-1"></i> Vô hiệu hóa barcode
                                        </a>
                                        @else
                                        <a href="{{ route('admin.agents.activate-barcode', [$agent->id, $barcode->id]) }}" class="btn btn-success" onclick="return confirm('Bạn có chắc chắn muốn kích hoạt mã barcode này?')">
                                            <i class="fas fa-check-circle me-1"></i> Kích hoạt barcode
                                        </a>
                                        @endif
                                        <a href="{{ route('admin.agents.print-barcode', [$agent->id, $barcode->id]) }}" class="btn btn-secondary" target="_blank">
                                            <i class="fas fa-print me-1"></i> In mã barcode
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            <!-- END ROW -->
        </div>
    </div>
</div>

@endsection
