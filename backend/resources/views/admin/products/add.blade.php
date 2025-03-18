@extends('admin.layouts.master')
@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Sản phẩm</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Sản phẩm</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Thêm sản phẩm</li>
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
                                <h6 class="main-content-label tx-15">Thêm sản phẩm</h6>
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="row g-3 needs-validation" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                                @csrf
                                <div class="col-md-8 position-relative">
                                    <label for="validationTooltip01" class="form-label tx-semibold">Tên sản phẩm</label>
                                    <input type="text" class="form-control" id="validationTooltip01" name="name" placeholder="Nhập tên sản phẩm" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="valid-tooltip">
                                    Looks good!
                                    </div>
                                </div>
                                <div class="col-md-4 position-relative">
                                    <label for="validationTooltip04" class="form-label tx-semibold">Danh mục</label>
                                    <select class="form-select" id="validationTooltip04" name="category_id" required>
                                    <option selected disabled value="">Lựa chọn...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-tooltip">
                                    Please select a valid state.
                                    </div>
                                </div>
                                <div class="col-md-12 position-relative">
                                    <label for="validationTooltip03" class="form-label tx-semibold">Mô tả</label>
                                    <input type="text" class="form-control" id="validationTooltip03" name="detail" placeholder="Nhập mô tả sản phẩm" value="{{ old('detail') }}" required>
                                    <div class="invalid-tooltip">
                                    Please provide a valid city.
                                    </div>
                                </div>
                                <div class="col-md-12 position-relative">
                                    <div>
                                        <h6 class="main-content-label mb-1">Tải hình ảnh</h6>
                                    </div>
                                    <div>
                                        <input id="image" type="file" class="form-control" name="image" accept="image/jpg, image/jpeg, image/png" onchange="previewImage(event)">
                                        @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
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
