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
                            <form class="row g-3 needs-validation" novalidate>
                                <div class="col-md-8 position-relative">
                                    <label for="validationTooltip01" class="form-label tx-semibold">Tên sản phẩm</label>
                                    <input type="text" class="form-control" id="validationTooltip01" placeholder="Nhập tên sản phẩm" required>
                                    <div class="valid-tooltip">
                                    Looks good!
                                    </div>
                                </div>
                                <div class="col-md-4 position-relative">
                                    <label for="validationTooltip04" class="form-label tx-semibold">Danh mục</label>
                                    <select class="form-select" id="validationTooltip04" required>
                                    <option selected disabled value="">Lựa chọn...</option>
                                    <option>...</option>
                                    </select>
                                    <div class="invalid-tooltip">
                                    Please select a valid state.
                                    </div>
                                </div>
                                <div class="col-md-6 position-relative">
                                    <label for="validationTooltip02" class="form-label tx-semibold">Giá sản phẩm</label>
                                    <input type="text" class="form-control" id="validationTooltip02" placeholder="Nhập giá sản phẩm" required>
                                    <div class="valid-tooltip">
                                    Looks good!
                                    </div>
                                </div>
                                <div class="col-md-6 position-relative">
                                    <label for="validationTooltip03" class="form-label tx-semibold">Số lượng sản phẩm</label>
                                    <input type="text" class="form-control" id="validationTooltip03" placeholder="Nhập số lượng sản phẩm" required>
                                    <div class="invalid-tooltip">
                                    Please provide a valid city.
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


@endsection
