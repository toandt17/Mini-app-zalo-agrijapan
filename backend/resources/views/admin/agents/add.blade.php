@extends('admin.layouts.master')
@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Thêm đại lý</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Quản lý đại lý</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Thêm đại lý</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">
                        <a href="{{ route('admin.agents.index') }}" class="btn btn-white btn-icon-text my-2 me-2">
                            <i class="fe fe-list"></i>
                            <span>Danh sách đại lý</span>
                        </a>
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
                                <h6 class="main-content-label tx-15">Thêm đại lý mới</h6>
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="row g-3 needs-validation" action="{{ route('admin.agents.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                                @csrf
                                <div class="col-md-6 position-relative">
                                    <label for="name" class="form-label tx-semibold">Tên đại lý <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Nhập tên đại lý" required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 position-relative">
                                    <label for="phone" class="form-label tx-semibold">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Nhập số điện thoại" required>
                                    @error('phone')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 position-relative">
                                    <label for="code_agent" class="form-label tx-semibold">Mã quy ước đại lý</label>
                                    <input type="text" class="form-control" id="code_agent" name="code_agent" value="{{ old('code_agent') }}" placeholder="Nhập mã quy ước đại lý (Ví dụ: AG00001)">
                                    <small class="form-text text-muted">Mã này sẽ được sử dụng để tạo mã barcode. Nếu không nhập, hệ thống sẽ tự tạo mã theo định dạng AG + ID.</small>
                                    @error('code_agent')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 position-relative">
                                    <label for="status" class="form-label tx-semibold">Trạng thái</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                    </select>
                                </div>

                                <div class="col-md-4 position-relative">
                                    <label for="province_id" class="form-label tx-semibold">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                    <select class="form-control" id="province_id" name="province_id" required onchange="loadDistricts()">
                                        <option value="">Chọn Tỉnh/Thành phố</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                                {{ $province->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('province_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 position-relative">
                                    <label for="district_id" class="form-label tx-semibold">Quận/Huyện <span class="text-danger">*</span></label>
                                    <select class="form-control" id="district_id" name="district_id" required onchange="loadWards()">
                                        <option value="">Chọn Quận/Huyện</option>
                                    </select>
                                    @error('district_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 position-relative">
                                    <label for="ward_id" class="form-label tx-semibold">Phường/Xã <span class="text-danger">*</span></label>
                                    <select class="form-control" id="ward_id" name="ward_id" required>
                                        <option value="">Chọn Phường/Xã</option>
                                    </select>
                                    @error('ward_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 position-relative">
                                    <label for="address" class="form-label tx-semibold">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" placeholder="Số nhà, tên đường..." required>
                                    @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 position-relative">
                                    <label for="latitude" class="form-label tx-semibold">Vĩ độ</label>
                                    <input type="text" class="form-control" id="latitude" name="latitude" value="{{ old('latitude') }}" placeholder="Nhập vĩ độ">
                                    @error('latitude')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 position-relative">
                                    <label for="longitude" class="form-label tx-semibold">Kinh độ</label>
                                    <input type="text" class="form-control" id="longitude" name="longitude" value="{{ old('longitude') }}" placeholder="Nhập kinh độ">
                                    @error('longitude')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 position-relative">
                                    <label for="open_hours" class="form-label tx-semibold">Giờ mở cửa</label>
                                    <input type="text" class="form-control" id="open_hours" name="open_hours" value="{{ old('open_hours') }}" placeholder="Ví dụ: 8:00 - 17:00, Thứ 2 - Thứ 6">
                                    @error('open_hours')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 position-relative">
                                    <label for="description" class="form-label tx-semibold">Mô tả</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Nhập mô tả về đại lý">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 position-relative">
                                    <label for="image" class="form-label tx-semibold">Hình ảnh đại lý</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/jpg, image/jpeg, image/png" onchange="previewImage(event)">
                                    @error('image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <div id="imagePreviewContainer" style="position: relative; display: none;">
                                        <img id="imagePreview" src="#" alt="Image Preview" style="max-width: 200px; height: auto; border: 1px solid #ddd; padding: 5px;" />
                                        <button type="button" onclick="removeImage()" style="position: absolute; top: 0; right: 0; background: red; color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer;">&times;</button>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary" type="submit">Thêm mới</button>
                                    <a href="{{ route('admin.agents.index') }}" class="btn btn-secondary">Hủy</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END ROW -->
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('imagePreview');
            var container = document.getElementById('imagePreviewContainer');
            output.src = reader.result;
            container.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function removeImage() {
        var input = document.getElementById('image');
        var output = document.getElementById('imagePreview');
        var container = document.getElementById('imagePreviewContainer');
        input.value = ''; // Clear the file input
        output.src = '#'; // Reset the image source
        container.style.display = 'none'; // Hide the preview container
    }

    // Hàm để tải Quận/Huyện theo Tỉnh/Thành phố
    function loadDistricts() {
        var provinceId = document.getElementById('province_id').value;
        console.log("Province ID selected:", provinceId);

        if (provinceId) {
            fetch(`/districts/${provinceId}`)
                .then(response => {
                    console.log("Response status:", response.status);
                    return response.json();
                })
                .then(data => {
                    console.log("Districts data:", data);
                    var districtSelect = document.getElementById('district_id');
                    districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';

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
                    } else {
                        console.error("Unexpected districts data format:", data);
                    }

                    // Reset phường/xã
                    document.getElementById('ward_id').innerHTML = '<option value="">Chọn Phường/Xã</option>';
                })
                .catch(error => console.error('Error loading districts:', error));
        } else {
            document.getElementById('district_id').innerHTML = '<option value="">Chọn Quận/Huyện</option>';
            document.getElementById('ward_id').innerHTML = '<option value="">Chọn Phường/Xã</option>';
        }
    }

    // Hàm để tải Phường/Xã theo Quận/Huyện
    function loadWards() {
        var districtId = document.getElementById('district_id').value;
        console.log("District ID selected:", districtId);

        if (districtId) {
            fetch(`/wards/${districtId}`)
                .then(response => {
                    console.log("Response status:", response.status);
                    return response.json();
                })
                .then(data => {
                    console.log("Wards data:", data);
                    var wardSelect = document.getElementById('ward_id');
                    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';

                    if (Array.isArray(data)) {
                        // Trường hợp dữ liệu là mảng
                        data.forEach(ward => {
                            wardSelect.innerHTML += `<option value="${ward.id}">${ward.name}</option>`;
                        });
                    } else if (data.wards && Array.isArray(data.wards)) {
                        // Trường hợp dữ liệu là object có property wards là mảng
                        data.wards.forEach(ward => {
                            wardSelect.innerHTML += `<option value="${ward.id}">${ward.name}</option>`;
                        });
                    } else {
                        console.error("Unexpected wards data format:", data);
                    }
                })
                .catch(error => console.error('Error loading wards:', error));
        } else {
            document.getElementById('ward_id').innerHTML = '<option value="">Chọn Phường/Xã</option>';
        }
    }

    // Load districts if province is already selected (for validation errors)
    window.onload = function() {
        var provinceSelect = document.getElementById('province_id');
        if (provinceSelect.value) {
            loadDistricts();

            // Also need to set the correct district if it was previously selected
            setTimeout(function() {
                var oldDistrictId = "{{ old('district_id') }}";
                if (oldDistrictId) {
                    document.getElementById('district_id').value = oldDistrictId;
                    loadWards();

                    // And set the correct ward if it was previously selected
                    setTimeout(function() {
                        var oldWardId = "{{ old('ward_id') }}";
                        if (oldWardId) {
                            document.getElementById('ward_id').value = oldWardId;
                        }
                    }, 500);
                }
            }, 500);
        }
    };
</script>

@endsection
