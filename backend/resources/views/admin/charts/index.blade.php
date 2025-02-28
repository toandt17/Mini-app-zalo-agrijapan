@extends('admin.layouts.master')
@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Đánh giá</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Đánh giá</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Danh sách đánh giá</li>
                    </ol>    
                </div>
                <div class="d-flex">
                </div>
            </div>
            <!-- END PAGE HEADER -->
            
            <!-- ROW -->
            <div class="row row-sm">
                <div class="col-lg-6 col-md-6">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div>
                                <h6 class="main-content-label mb-1">Bar Chart1</h6>
                                <p class="text-muted card-sub-title">Below is the basic bar chart example..</p>
                            </div>
                            <div class="ht-200 ht-sm-300" id="flotBar1"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div>
                                <h6 class="main-content-label mb-1">Bar Chart2</h6>
                                <p class="text-muted card-sub-title">Below is the basic bar chart example..</p>
                            </div>
                            <div class="ht-200 ht-sm-300" id="flotBar2"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END ROW -->
        </div>
    </div>
</div>

@endsection