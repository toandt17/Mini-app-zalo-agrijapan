@extends('admin.layouts.master')
@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">In mã QR đại lý</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Quản lý đại lý</a></li>
                        <li class="breadcrumb-item active" aria-current="page">In mã QR đại lý</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">
                        <a href="{{ route('admin.agents.index') }}" class="btn btn-white btn-icon-text my-2 me-2">
                            <i class="fe fe-list"></i>
                            <span>Danh sách đại lý</span>
                        </a>
                        <button id="printButton" class="btn btn-primary btn-icon-text my-2 me-2">
                            <i class="fe fe-printer"></i>
                            <span>In tất cả mã QR</span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- END PAGE HEADER -->

            <!-- ROW -->
            <div class="row row-sm">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div>
                                <h6 class="main-content-label mb-1">Bộ lọc</h6>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.agents.print-qr-codes') }}" class="row g-3">
                                <div class="col-md-5">
                                    <label for="province_id" class="form-label">Tỉnh/Thành phố</label>
                                    <select class="form-control" id="province_id" name="province_id" onchange="loadDistricts()">
                                        <option value="">Tất cả Tỉnh/Thành phố</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province->id }}" {{ (isset($provinceId) && $provinceId == $province->id) ? 'selected' : '' }}>
                                                {{ $province->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label for="district_id" class="form-label">Quận/Huyện</label>
                                    <select class="form-control" id="district_id" name="district_id">
                                        <option value="">Tất cả Quận/Huyện</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">Lọc</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END ROW -->

            <!-- ROW -->
            <div class="row row-sm" id="printableArea">
                @if($agentsWithQR->count() > 0)
                    @foreach($agentsWithQR as $agent)
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-3">
                            <div class="card qr-card">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $agent->name }}</h5>
                                    <p class="card-text">{{ $agent->phone }}</p>
                                    <p class="card-text small">{{ $agent->address }}</p>
                                    <img src="{{ asset($agent->qr_code) }}" alt="QR Code" class="img-fluid mb-2" style="max-width: 150px;">
                                    <div class="mt-2 no-print">
                                        <a href="{{ route('admin.agents.print-single-qr-code', $agent->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="fe fe-printer"></i> In riêng
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="alert alert-info">
                            Không tìm thấy đại lý nào có mã QR. Vui lòng tạo mã QR trước khi in.
                        </div>
                    </div>
                @endif
            </div>
            <!-- END ROW -->
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print, .main-header, .app-sidebar, .side-header, .main-footer {
            display: none !important;
        }
        .qr-card {
            page-break-inside: avoid;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            height: 370px;
        }
        .main-content {
            margin: 0 !important;
            padding: 0 !important;
            left: 0 !important;
        }
        .inner-body {
            margin: 0 !important;
            padding: 0 !important;
        }
    }
</style>

@section('scripts')
<script>
    // Hàm để tải Quận/Huyện theo Tỉnh/Thành phố
    function loadDistricts() {
        var provinceId = document.getElementById('province_id').value;

        if (provinceId) {
            fetch(`/districts/${provinceId}`)
                .then(response => response.json())
                .then(data => {
                    var districtSelect = document.getElementById('district_id');
                    districtSelect.innerHTML = '<option value="">Tất cả Quận/Huyện</option>';

                    if (Array.isArray(data)) {
                        // Trường hợp dữ liệu là mảng
                        data.forEach(district => {
                            districtSelect.innerHTML += `<option value="${district.id}">${district.name}</option>`;
                        });
                    } else if (data.districts && Array.isArray(data.districts)) {
                        // Trường hợp dữ liệu là object có property districts là mảng
                        data.districts.forEach(district => {
                            districtSelect.innerHTML += `<option value="${district.id}">${district.name}</option>`;
                        });
                    }

                    // Nếu có sẵn district_id, chọn lại
                    var savedDistrictId = "{{ $districtId ?? '' }}";
                    if (savedDistrictId) {
                        document.getElementById('district_id').value = savedDistrictId;
                    }
                })
                .catch(error => console.error('Error loading districts:', error));
        } else {
            document.getElementById('district_id').innerHTML = '<option value="">Tất cả Quận/Huyện</option>';
        }
    }

    // Khởi tạo trang
    document.addEventListener('DOMContentLoaded', function() {
        // Tải districts khi trang được load
        var provinceSelect = document.getElementById('province_id');
        if (provinceSelect.value) {
            loadDistricts();
        }

        // Xử lý nút in
        document.getElementById('printButton').addEventListener('click', function() {
            // Kiểm tra trước khi in
            const qrCards = document.querySelectorAll('.qr-card');
            if (qrCards.length === 0) {
                alert('Không có mã QR nào để in. Vui lòng chọn đại lý khác hoặc tạo mã QR trước.');
                return;
            }

            // Hiển thị thông báo
            console.log('Đang chuẩn bị in ' + qrCards.length + ' mã QR...');

            // Đợi tài nguyên tải xong trước khi in
            setTimeout(function() {
                try {
                    window.print();
                } catch (e) {
                    console.error("Lỗi khi in:", e);
                    alert("Có lỗi khi in: " + e.message);
                }
            }, 500);
        });
    });
</script>
@endsection
