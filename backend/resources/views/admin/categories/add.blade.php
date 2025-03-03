@extends('admin.layouts.master')
@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Thêm loại sản phẩm</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Loại sản phẩm</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Thêm loại sản phẩm</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">
                        <button type="button" class="btn btn-white btn-icon-text my-2 me-2">
                            <i class="fe fe-settings"></i>
                            <span>Settings</span>
                        </button>
                        <button type="button" class="btn btn-primary my-2 btn-icon-text">
                            <i class="fe fe-download-cloud bg-white-transparent text-white"></i>
                            <span>Reports</span>
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
                                <h6 class="main-content-label tx-15">Thêm loại sản phẩm</h6>
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="row g-3 needs-validation" action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                                @csrf
                                <div class="col-md-12 position-relative">
                                    <label for="name" class="form-label tx-semibold">Tên loại sản phẩm</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Nhập tên loại sản phẩm" required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 position-relative">
                                    <label for="image" class="form-label tx-semibold">Tải hình ảnh</label>
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
</script>

@endsection
