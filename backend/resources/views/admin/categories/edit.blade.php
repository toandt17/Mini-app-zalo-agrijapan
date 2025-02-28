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
                        <li class="breadcrumb-item active" aria-current="page">Thêm loại sản phẩm</li>
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
                            <form class="row g-3 needs-validation" novalidate>
                                <div class="col-md-12 position-relative">
                                    <label for="validationTooltip01" class="form-label tx-semibold">Tên loại sản phẩm</label>
                                    <input type="text" class="form-control" id="validationTooltip01" placeholder="Nhập tên loại sản phẩm" required>
                                    <div class="valid-tooltip">
                                    Looks good!
                                    </div>
                                </div>
                                
                                <div class="col-md-12 position-relative">
                                    <label for="validationTooltip03" class="form-label tx-semibold">Môt tả</label>
                                    <input type="text" class="form-control" id="validationTooltip03" placeholder="Nhập mô tả sản phẩm" required>
                                    <div class="invalid-tooltip">
                                    Please provide a valid city.
                                    </div>
                                </div>
                                <div class="col-md-12 position-relative">
									<div>
										<h6 class="main-content-label mb-1">Tải hình ảnh</h6>
									</div>
									<div>
										<input id="demo" type="file" name="files" accept="image/jpg, image/jpeg, image/png, text/html, application/zip, text/css, text/js" multiple>
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


@endsection
