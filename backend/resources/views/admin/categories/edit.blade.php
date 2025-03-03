@extends('admin.layouts.master')
@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Cập nhật loại sản phẩm</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Loại sản phẩm</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cập nhật loại sản phẩm</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">

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
                                <h6 class="main-content-label tx-15">Cập nhật loại sản phẩm</h6>
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="row g-3 needs-validation" action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" novalidate>
                                @csrf
                                @method('PUT')
                                <div class="col-md-12 position-relative">
                                    <label for="name" class="form-label tx-semibold">Tên loại sản phẩm</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $category->name) }}" placeholder="Nhập tên loại sản phẩm" required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 position-relative">
                                    <div>
                                        <h6 class="main-content-label mb-1">Tải hình ảnh</h6>
                                    </div>
                                    <div>
                                        <input type="file" class="form-control" id="image" name="image" accept="image/jpg, image/jpeg, image/png" onchange="previewImage(event)">
                                        <div id="imagePreviewContainer" style="position: relative; {{ $category->image ? '' : 'display: none;' }}">
                                            <img id="imagePreview" src="{{ $category->image ? asset('storage/' . $category->image) : '#' }}" alt="{{ $category->name }}" style="max-width: 100px; height: auto; margin-top: 10px;">
                                            <button type="button" onclick="removeImage()" style="position: absolute; top: 0; right: 0; background: red; color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer;">&times;</button>
                                        </div>
                                        @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary" type="submit">Lưu</button>
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
