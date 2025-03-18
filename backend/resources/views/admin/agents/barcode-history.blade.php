@extends('admin.layouts.master')

@section('title', 'Lịch sử mã Barcode đại lý ' . $agent->name)

@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Lịch sử mã Barcode - {{ $agent->name }}</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.agents.index') }}">Đại lý</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.agents.show', $agent->id) }}">{{ $agent->name }}</a></li>
                        <li class="breadcrumb-item active">Lịch sử mã Barcode</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">
                        <a href="{{ route('admin.agents.show', $agent->id) }}" class="btn btn-white btn-icon-text my-2 me-2">
                            <i class="fe fe-arrow-left"></i>
                            <span>Quay lại</span>
                        </a>
                        <a href="{{ route('admin.agents.qr-history', $agent->id) }}" class="btn btn-info btn-icon-text my-2 me-2">
                            <i class="fe fe-list"></i>
                            <span>Lịch sử QR</span>
                        </a>
                        <a href="{{ route('admin.agents.generate-barcode', $agent->id) }}" class="btn btn-primary btn-icon-text my-2 me-2">
                            <i class="fe fe-plus-circle"></i>
                            <span>Tạo mã Barcode mới</span>
                        </a>
                        <a href="{{ route('admin.agents.search-barcode') }}" class="btn btn-secondary btn-icon-text my-2 me-2">
                            <i class="fe fe-search"></i>
                            <span>Tra cứu mã Barcode</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- END PAGE HEADER -->

            <!-- ROW -->
            <div class="row row-sm">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card custom-card shadow-sm">
                        <div class="card-header bg-transparent">
                            <div class="d-flex justify-content-between">
                                <h6 class="main-content-label mb-0">Thông tin đại lý</h6>
                                <div>
                                    <span class="badge rounded-pill bg-{{ $agent->status == 'active' ? 'success' : 'danger' }}">
                                        {{ $agent->status == 'active' ? 'Đang hoạt động' : 'Ngừng hoạt động' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><i class="fe fe-user me-2"></i>Tên đại lý:</strong> {{ $agent->name }}</p>
                                    <p><strong><i class="fe fe-phone me-2"></i>Số điện thoại:</strong> {{ $agent->phone }}</p>
                                    <p><strong><i class="fe fe-map-pin me-2"></i>Địa chỉ:</strong> {{ $agent->full_address }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong><i class="fe fe-tag me-2"></i>Mã đại lý:</strong> {{ $agent->code_agent }}</p>
                                    <p><strong><i class="fe fe-calendar me-2"></i>Ngày tạo:</strong> {{ $agent->created_at ? $agent->created_at->format('d/m/Y H:i:s') : 'N/A' }}</p>
                                    <p><strong><i class="fe fe-refresh-cw me-2"></i>Cập nhật:</strong> {{ $agent->updated_at ? $agent->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END ROW -->

            <!-- ROW -->
            <div class="row row-sm mt-3">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card custom-card shadow-sm">
                        <div class="card-header bg-transparent">
                            <h6 class="main-content-label mb-0">Tạo mã Barcode với đơn hàng</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.agents.generate-barcode-with-order', $agent->id) }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="order_code" class="fw-bold mb-2">Mã đơn hàng</label>
                                            <div class="input-group">
                                                <input type="text" name="order_code" id="order_code" class="form-control" placeholder="Nhập mã đơn hàng" required>
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fe fe-barcode me-1"></i> Tạo mã Barcode
                                                </button>
                                            </div>
                                            <small class="form-text text-muted">Nhập mã đơn hàng để tạo mã barcode kết hợp.</small>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END ROW -->

            <!-- ROW -->
            <div class="row row-sm mt-3">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card custom-card shadow-sm">
                        <div class="card-header bg-transparent">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="main-content-label mb-0">Lịch sử mã Barcode</h6>
                                <span class="badge rounded-pill bg-primary">{{ count($barcodes) }} mã</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="file-datatable" class="table table-bordered text-nowrap">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-center" width="50">STT</th>
                                            <th class="text-center" width="100">Hình ảnh</th>
                                            <th>Mã quy ước</th>
                                            <th>Mã đơn hàng</th>
                                            <th>Mã EAN-13</th>
                                            <th>Ngày tạo</th>
                                            <th class="text-center no-sort" width="100">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($barcodes as $barcode)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">
                                                @if($barcode->barcode_image)
                                                <a href="{{ asset($barcode->barcode_image) }}" target="_blank" data-fancybox="barcode-gallery" data-caption="Barcode #{{ $barcode->barcode_value }}">
                                                    <img src="{{ asset($barcode->barcode_image) }}" alt="Barcode" style="max-height: 50px; cursor: pointer;" class="img-fluid">
                                                </a>
                                                @else
                                                <span class="badge bg-warning text-dark">Không có hình ảnh</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($barcode->metadata['code_agent']))
                                                    <span class="fw-bold">{{ $barcode->metadata['code_agent'] }}</span>
                                                @else
                                                    <span class="fw-bold">{{ $barcode->agent_code }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($barcode->order_code)
                                                    <span class="badge bg-info text-dark">{{ $barcode->order_code }}</span>
                                                @else
                                                    <span class="text-muted fst-italic">Không có</span>
                                                @endif
                                            </td>
                                            <td style="font-family: monospace; font-weight: bold; letter-spacing: 1px; font-size: 14px;">
                                                {{ $barcode->barcode_value }}
                                            </td>
                                            <td>{{ $barcode->generated_at ? $barcode->generated_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.agents.print-barcode', [$agent->id, $barcode->id]) }}" class="btn btn-sm btn-info" title="In mã Barcode" target="_blank">
                                                        <i class="fe fe-printer"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="empty-state">
                                                    <i class="fe fe-database display-4 text-muted mb-2"></i>
                                                    <p class="text-muted">Chưa có mã Barcode nào được tạo.</p>
                                                    <a href="{{ route('admin.agents.generate-barcode', $agent->id) }}" class="btn btn-sm btn-primary mt-2">
                                                        <i class="fe fe-plus-circle me-1"></i> Tạo mã Barcode mới
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
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

@endsection

@push('scripts')
<script>
    $(function () {
        // Initialize DataTables with column filtering
        $('#file-datatable').DataTable({
            language: {
                search: "<i class='fas fa-search'></i>",
                searchPlaceholder: "Tìm kiếm mã barcode, đơn hàng...",
                lengthMenu: "Hiển thị _MENU_ dòng",
                zeroRecords: "Không tìm thấy kết quả phù hợp",
                info: "Hiển thị _START_ đến _END_ của _TOTAL_ mã barcode",
                infoEmpty: "Hiển thị 0 đến 0 của 0 mã barcode",
                infoFiltered: "(lọc từ _MAX_ mã barcode)",
                paginate: {
                    first: "Đầu",
                    last: "Cuối",
                    previous: "<i class='fas fa-chevron-left'></i>",
                    next: "<i class='fas fa-chevron-right'></i>"
                }
            },
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            pageLength: 10,
            ordering: true,
            columnDefs: [
                { orderable: false, targets: 'no-sort' }
            ],
            dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center"l><"d-flex"f>>t<"d-flex justify-content-between mt-3"<"text-muted"i><"pagination-container"p>>',
            initComplete: function () {
                // Add custom styling to the search input
                $('.dataTables_filter input')
                    .removeClass('form-control-sm')
                    .addClass('rounded-pill border-primary');

                // Initialize fancybox for image previews if available
                if($.fn.fancybox) {
                    $('[data-fancybox]').fancybox({
                        buttons: ['zoom', 'download', 'close']
                    });
                }
            }
        });
    });
</script>
@endpush
