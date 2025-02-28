@extends('admin.layouts.master')
@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Chỉnh sửa đơn hàng</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Đơn hàng</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa đơn hàng</li>
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
                                <h6 class="main-content-label tx-15">Đơn hàng</h6>
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="row g-3 needs-validation" novalidate>
                                <div class="col-md-6 position-relative">
                                    <label for="validationTooltip01" class="form-label tx-semibold">Đơn hàng</label>
                                    <input type="text" class="form-control" id="validationTooltip01" required>
                                    <div class="valid-tooltip">
                                    Looks good!
                                    </div>
                                </div>
                                <div class="col-md-6 position-relative">
                                    <label for="validationTooltip04" class="form-label tx-semibold">Ngày đặt hàng</label>
                                    <input type="date" class="form-control" id="validationTooltip04" required>
                                    <div class="invalid-tooltip">
                                    Please select a valid state.
                                    </div>
                                </div>
                                <div class="col-md-6 position-relative">
                                    <label for="validationTooltip04" class="form-label tx-semibold">Sản phẩm</label>
                                    <input type="text" class="form-control" id="validationTooltip04" required>
                                    <div class="invalid-tooltip">
                                    Please select a valid state.
                                    </div>
                                </div>
                                <div class="col-md-6 position-relative">
                                    <label for="validationTooltip04" class="form-label tx-semibold">Số lượng hàng</label>
                                    <input type="text" class="form-control" id="validationTooltip04" required>
                                    <div class="invalid-tooltip">
                                    Please select a valid state.
                                    </div>
                                </div>
                                <div class="col-md-6 position-relative">
                                    <label for="validationTooltip04" class="form-label tx-semibold">Giá</label>
                                    <input type="text" class="form-control" id="validationTooltip04" required>
                                    <div class="invalid-tooltip">
                                    Please select a valid state.
                                    </div>
                                </div>
                                <div class="col-md-6 position-relative">
                                    <label for="validationTooltip04" class="form-label tx-semibold">Trạng thái</label>
                                    <input type="text" class="form-control" id="validationTooltip04" required>
                                    <div class="invalid-tooltip">
                                    Please select a valid state.
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
                                    <button class="btn btn-primary" type="submit">Cập nhật</button>
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
