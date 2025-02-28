@extends('admin.layouts.master')
@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Loại sản phẩm</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Loại sản phẩm</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Danh sách loại</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">
                        <a href="{{route('admin.categories.add')}}">
                            <button class="btn btn-primary" type="submit" >Thêm mới</button>
                        </a>
                        
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
                                <h6 class="main-content-label mb-1">Danh sách loại sản phẩm</h6>
                            </div>
                            
                            <div class="table-responsive mt-3">
                                <table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0">STT</th>
                                            <th class="border-bottom-0">Loại sản phẩm</th>
                                            <th class="border-bottom-0">Hình ảnh</th>
                                            <th class="border-bottom-0">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>61</td>
                                            <td>2011/04/25</td>
                                            <td></td>
                                            <td>
                                                 {{-- <a href="{{ route('admin.categories.edit') }}"> --}}
                                                    <button class="btn btn-success"><i class="fe fe-edit"></i></button>
                                                {{-- </a>  --}}
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
