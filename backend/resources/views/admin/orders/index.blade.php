@extends('admin.layouts.master')
@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Đơn hàng</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Đơn hàng</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Danh sách đơn hàng</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">
                        <a href="{{ route('admin.products.add') }}">
                            {{-- <button aria-expanded="false" aria-haspopup="true" class="btn ripple btn-primary-transparent dropdown-toggle" data-bs-toggle="dropdown" type="button">Trạng thái đơn hàng<i class="fas fa-caret-down ms-1"></i></button>
                            <div  class="dropdown-menu tx-13">
                                <a class="dropdown-item" href="javascript:;">Đã thanh toán</a>
                                <a class="dropdown-item" href="javascript:;">Đang xử lý</a>
                                <a class="dropdown-item" href="javascript:;">Đã giao</a>
                                <a class="dropdown-item" href="javascript:;">Đã hủy</a>
                            </div> --}}
                        </a>
                            <button aria-expanded="false" aria-haspopup="true" class="btn ripple btn-primary-transparent dropdown-toggle" data-bs-toggle="dropdown" type="button">Trạng thái <i class="fas fa-caret-down ms-1"></i></button>
                                <div  class="dropdown-menu tx-13">
                                    <a class="dropdown-item" href="javascript:;">Đã thanh toán</a>
                                    <a class="dropdown-item" href="javascript:;">Đang xử lý</a>
                                    <a class="dropdown-item" href="javascript:;">Đã giao</a>
                                    <a class="dropdown-item" href="javascript:;">Đã hủy</a>
                                </div>
                    </div>
                </div>
            </div>
            <!-- END PAGE HEADER -->



            <!-- ROW -->
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card custom-card overflow-hidden">
                        <div class="card-body">
                            <div>
                                <h6 class="main-content-label mb-1">Danh sách đơn hàng</h6>
                            </div>
                            <div class="table-responsive">
                                <table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0">STT</th>
                                            <th class="border-bottom-0">Tên khách hàng</th>
                                            <th class="border-bottom-0">Ngày đặt hàng</th>
                                            <th class="border-bottom-0">Sản phẩm</th>
                                            <th class="border-bottom-0">Số lượng hàng</th>
                                            <th class="border-bottom-0">Giá</th>
                                            <th class="border-bottom-0">Hình ảnh</th>
                                            <th class="border-bottom-0">Trạng thái</th>
                                            <th class="border-bottom-0">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <button class="btn ripple btn-success"><i class="fe fe-edit"></i></button>
                                                <button class="btn ripple btn-danger"><i class="fe fe-trash"></i></button>
                                            </td>
                                        </tr>
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
