@extends('admin.layouts.master')
@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Sales Dashboard 01</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboards</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard-1</li>
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
                <div class="col-sm-12 col-lg-12 col-xl-12">

                    <!--Row-->
                    <div class="row row-sm">
                        <div class="col-sm-12 col-md-12 col-lg-4 col-xl-3 col-xxl-2">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="card-item">
                                        <div class="card-item-icon bg-success-transparent">
                                            <svg class="text-primary wd-20 ht-20" fill="#19b159" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M22.5,10h-4.0005493C18.2234497,10.0001831,17.9998169,10.223999,18,10.5v12.0005493C18.0001831,22.7765503,18.223999,23.0001831,18.5,23h4.0006104C22.7765503,22.9998169,23.0001831,22.776001,23,22.5V10.4993896C22.9998169,10.2234497,22.776001,9.9998169,22.5,10z M22,22h-3V11h3V22z M14.5,2h-4.0005493C10.2234497,2.0001831,9.9998169,2.223999,10,2.5v20.0005493C10.0001831,22.7765503,10.223999,23.0001831,10.5,23h4.0006104C14.7765503,22.9998169,15.0001831,22.776001,15,22.5V2.4993896C14.9998169,2.2234497,14.776001,1.9998169,14.5,2z M14,22h-3V3h3V22z M6.5,14H2.4993896C2.2234497,14.0001831,1.9998169,14.223999,2,14.5v8.0005493C2.0001831,22.7765503,2.223999,23.0001831,2.5,23h4.0006104C6.7765503,22.9998169,7.0001831,22.776001,7,22.5v-8.0006104C6.9998169,14.2234497,6.776001,13.9998169,6.5,14z M6,22H3v-7h3V22z"/></svg>
                                        </div>
                                        <div class="card-item-title mb-2">
                                            <label class="main-content-label tx-13 mb-1">Total Revenue</label>
                                        </div>
                                        <div class="card-item-body">
                                            <div class="card-item-stat">
                                                <h4 class="font-weight-normal">$6,800.00</h4>
                                                <small><b class="badge rounded-pill bg-success fs-11">65%</b><span class="px-1">Higher</span></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="card-item">
                                        <div class="card-item-icon bg-info-transparent">
                                            <svg class="text-primary wd-20 ht-20" fill="#01b8ff" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M14.6650391,13.3672485C16.6381226,12.3842773,17.9974365,10.3535767,18,8c0-3.3137207-2.6862793-6-6-6S6,4.6862793,6,8c0,2.3545532,1.3595581,4.3865967,3.3334961,5.3690186c-3.6583862,1.0119019-6.5859375,4.0562134-7.2387695,8.0479736c-0.0002441,0.0013428-0.0004272,0.0026855-0.0006714,0.0040283c-0.0447388,0.272583,0.1399536,0.5297852,0.4125366,0.5745239c0.272522,0.0446777,0.5297241-0.1400146,0.5744629-0.4125366c0.624939-3.8344727,3.6308594-6.8403931,7.465332-7.465332c4.9257812-0.8027954,9.5697632,2.5395508,10.3725586,7.465332C20.9594727,21.8233643,21.1673584,21.9995117,21.4111328,22c0.0281372,0.0001831,0.0562134-0.0021362,0.0839844-0.0068359h0.0001831c0.2723389-0.0458984,0.4558716-0.303833,0.4099731-0.5761719C21.2677002,17.5184937,18.411377,14.3986206,14.6650391,13.3672485z M12,13c-2.7614136,0-5-2.2385864-5-5s2.2385864-5,5-5c2.7600708,0.0032349,4.9967651,2.2399292,5,5C17,10.7614136,14.7614136,13,12,13z"/></svg>
                                        </div>
                                        <div class="card-item-title mb-2">
                                            <label class="main-content-label tx-13 mb-1">New Customers</label>
                                        </div>
                                        <div class="card-item-body">
                                            <div class="card-item-stat">
                                                <h4 class="font-weight-normal">5,031</h4>
                                                <small><b class="badge rounded-pill bg-info fs-11">25%</b><span class="px-1">Increased</span></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="card-item">
                                        <div class="card-item-icon bg-warning-transparent">
                                            <svg class="text-primary wd-20 ht-20" fill="#ff9b21" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M17.5,9.0009766c0.0001831,0,0.0003662,0,0.0005493,0c0.276001-0.0001221,0.4996338-0.223999,0.4994507-0.5V8.0020142c0.0003662-0.1011963-0.0302734-0.2000732-0.0878906-0.2832642c-0.3666992-1.5889282-1.7803955-2.715271-3.4111328-2.7177734L12.5,5.0001831V2.5C12.5,2.223877,12.276123,2,12,2s-0.5,0.223877-0.5,0.5V5h-2C7.5679932,5.0023193,6.0023193,6.5679932,6,8.5v0.5009766c0.0025635,1.9315796,1.5674438,3.4968872,3.4990234,3.5L12,12.5020142c0.0001221,0,0.0001831-0.000061,0.0003052-0.000061h2.5006714c1.3795776,0.0023804,2.4971924,1.1204224,2.4990234,2.5v0.5009766c-0.0012817,1.380188-1.119812,2.4987183-2.5,2.5h-2.4854736C12.0093994,18.0027466,12.005127,18,12,18c-0.005249,0-0.0096436,0.0028076-0.0148315,0.0029907h-2.486145c-1.3795776-0.0023804-2.4971924-1.1204224-2.4990234-2.5c0-0.276123-0.223877-0.5-0.5-0.5s-0.5,0.223877-0.5,0.5v0.4990234c-0.0002441,0.1014404,0.0303955,0.2005005,0.0878906,0.2841187c0.3677979,1.5880737,1.7810059,2.713623,3.4111328,2.7167969H11.5V21.5c0,0.0001831,0,0.0003662,0,0.0005493C11.5001831,21.7765503,11.723999,22.0001831,12,22c0.0001831,0,0.0003662,0,0.0006104,0c0.2759399-0.0001831,0.4995728-0.223999,0.4993896-0.5v-2.4970703h2c1.9320068-0.0023193,3.4976196-1.5679321,3.5-3.499939v-0.5009766c-0.0025024-1.9315796-1.5674438-3.4969482-3.4990234-3.500061H12c-0.0001221,0-0.0001831,0.000061-0.0003052,0.000061l-2.5006714-0.0010376C8.1194458,11.4985962,7.0018311,10.3805542,7,9.0009766V8.5C7.0012817,7.119812,8.119812,6.0012817,9.5,6H12l2.5009766,0.0009766c1.3798828,0.001709,2.4978638,1.1201782,2.4990234,2.5c0,0.0001831,0,0.0004272,0,0.0006104C17.0001831,8.7775269,17.223999,9.0011597,17.5,9.0009766z"/></svg>
                                        </div>
                                        <div class="card-item-title  mb-2">
                                            <label class="main-content-label tx-13 mb-1">Total Expenses</label>
                                        </div>
                                        <div class="card-item-body">
                                            <div class="card-item-stat">
                                                <h4 class="font-weight-normal">$2,500</h4>
                                                <small><b class="badge rounded-pill bg-danger fs-11">15%</b> <span class="px-1">Decreased</span></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-8 col-xl-9 col-xxl-7">
                            <div class="card custom-card overflow-hidden">
                                <div class="card-header border-bottom d-flex">
                                    <div>
                                        <h3 class="card-title tx-18"><label class="main-content-label tx-15">Sales summary</label></h3>
                                    </div>
                                    <div class="ms-auto float-end">
                                        <a href="javascript:void(0);" class="text-muted" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fe fe-more-vertical fs-16 "></i></a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="javascript:void(0);">Today</a>
                                            <a class="dropdown-item" href="javascript:void(0);">Last Week</a>
                                            <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                                            <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="salessummary"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-12 col-xl-12 col-xxl-3">
                            <div class="row row-sm">
                                <div class="col-sm-12 col-lg-12 col-xl-12">
                                    <div class="card custom-card overflow-hidden">
                                        <div class="card-body p-0">
                                            <div class="row row-sm">
                                                <div class="col-sm-6 col-md-6 border-end">
                                                    <div class="p-4">
                                                        <p class="revenuechart-container mb-3">
                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 0 30 30" version="1.1">
                                                                <g id="surface1">
                                                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(221 221 222);fill-opacity:1;" d="M 6.25 27.5 C 5.558594 27.5 5 26.941406 5 26.25 L 5 16.25 C 5 15.558594 5.558594 15 6.25 15 C 6.941406 15 7.5 15.558594 7.5 16.25 L 7.5 26.25 C 7.5 26.941406 6.941406 27.5 6.25 27.5 Z M 12.5 27.5 C 11.808594 27.5 11.25 26.941406 11.25 26.25 L 11.25 3.75 C 11.25 3.058594 11.808594 2.5 12.5 2.5 C 13.191406 2.5 13.75 3.058594 13.75 3.75 L 13.75 26.25 C 13.75 26.941406 13.191406 27.5 12.5 27.5 Z M 18.75 27.5 C 18.058594 27.5 17.5 26.941406 17.5 26.25 L 17.5 11.25 C 17.5 10.558594 18.058594 10 18.75 10 C 19.441406 10 20 10.558594 20 11.25 L 20 26.25 C 20 26.941406 19.441406 27.5 18.75 27.5 Z M 25 27.5 C 24.308594 27.5 23.75 26.941406 23.75 26.25 L 23.75 21.25 C 23.75 20.558594 24.308594 20 25 20 C 25.691406 20 26.25 20.558594 26.25 21.25 L 26.25 26.25 C 26.25 26.941406 25.691406 27.5 25 27.5 Z M 25 27.5 "/>
                                                                </g>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="chart2" width="30px" height="30px" viewBox="0 0 30 30" version="1.1">
                                                                <g id="surface2">
                                                                <path style=" stroke:none;fill-rule:nonzero;fill:#fd6074;fill-opacity:0.7;" d="M 6.25 27.5 C 5.558594 27.5 5 26.941406 5 26.25 L 5 16.25 C 5 15.558594 5.558594 15 6.25 15 C 6.941406 15 7.5 15.558594 7.5 16.25 L 7.5 26.25 C 7.5 26.941406 6.941406 27.5 6.25 27.5 Z M 12.5 27.5 C 11.808594 27.5 11.25 26.941406 11.25 26.25 L 11.25 3.75 C 11.25 3.058594 11.808594 2.5 12.5 2.5 C 13.191406 2.5 13.75 3.058594 13.75 3.75 L 13.75 26.25 C 13.75 26.941406 13.191406 27.5 12.5 27.5 Z M 18.75 27.5 C 18.058594 27.5 17.5 26.941406 17.5 26.25 L 17.5 11.25 C 17.5 10.558594 18.058594 10 18.75 10 C 19.441406 10 20 10.558594 20 11.25 L 20 26.25 C 20 26.941406 19.441406 27.5 18.75 27.5 Z M 25 27.5 C 24.308594 27.5 23.75 26.941406 23.75 26.25 L 23.75 21.25 C 23.75 20.558594 24.308594 20 25 20 C 25.691406 20 26.25 20.558594 26.25 21.25 L 26.25 26.25 C 26.25 26.941406 25.691406 27.5 25 27.5 Z M 25 27.5 "/>
                                                                </g>
                                                            </svg>
                                                            <span class="mb-0 fs-13 ms-2 result text-danger"><i class="fe fe-arrow-down"></i> 2.52%</span>
                                                        </p>
                                                        <h2 class="tx-normal"><span class="counter">$23,590</span></h2>
                                                        <p class="tx-12 text-muted">Last Week Revenue</p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-md-6">
                                                    <div class="p-4">
                                                        <p class="revenuechart-container mb-3">
                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 0 30 30" version="1.1">
                                                                <g id="surface3">
                                                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(221 221 222);fill-opacity:1;" d="M 6.25 27.5 C 5.558594 27.5 5 26.941406 5 26.25 L 5 16.25 C 5 15.558594 5.558594 15 6.25 15 C 6.941406 15 7.5 15.558594 7.5 16.25 L 7.5 26.25 C 7.5 26.941406 6.941406 27.5 6.25 27.5 Z M 12.5 27.5 C 11.808594 27.5 11.25 26.941406 11.25 26.25 L 11.25 3.75 C 11.25 3.058594 11.808594 2.5 12.5 2.5 C 13.191406 2.5 13.75 3.058594 13.75 3.75 L 13.75 26.25 C 13.75 26.941406 13.191406 27.5 12.5 27.5 Z M 18.75 27.5 C 18.058594 27.5 17.5 26.941406 17.5 26.25 L 17.5 11.25 C 17.5 10.558594 18.058594 10 18.75 10 C 19.441406 10 20 10.558594 20 11.25 L 20 26.25 C 20 26.941406 19.441406 27.5 18.75 27.5 Z M 25 27.5 C 24.308594 27.5 23.75 26.941406 23.75 26.25 L 23.75 21.25 C 23.75 20.558594 24.308594 20 25 20 C 25.691406 20 26.25 20.558594 26.25 21.25 L 26.25 26.25 C 26.25 26.941406 25.691406 27.5 25 27.5 Z M 25 27.5 "/>
                                                                </g>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="chart2" width="30px" height="30px" viewBox="0 0 30 30" version="1.1">
                                                                <g id="surface4">
                                                                <path style=" stroke:none;fill-rule:nonzero;fill:#19b159;fill-opacity:0.7;" d="M 6.25 27.5 C 5.558594 27.5 5 26.941406 5 26.25 L 5 16.25 C 5 15.558594 5.558594 15 6.25 15 C 6.941406 15 7.5 15.558594 7.5 16.25 L 7.5 26.25 C 7.5 26.941406 6.941406 27.5 6.25 27.5 Z M 12.5 27.5 C 11.808594 27.5 11.25 26.941406 11.25 26.25 L 11.25 3.75 C 11.25 3.058594 11.808594 2.5 12.5 2.5 C 13.191406 2.5 13.75 3.058594 13.75 3.75 L 13.75 26.25 C 13.75 26.941406 13.191406 27.5 12.5 27.5 Z M 18.75 27.5 C 18.058594 27.5 17.5 26.941406 17.5 26.25 L 17.5 11.25 C 17.5 10.558594 18.058594 10 18.75 10 C 19.441406 10 20 10.558594 20 11.25 L 20 26.25 C 20 26.941406 19.441406 27.5 18.75 27.5 Z M 25 27.5 C 24.308594 27.5 23.75 26.941406 23.75 26.25 L 23.75 21.25 C 23.75 20.558594 24.308594 20 25 20 C 25.691406 20 26.25 20.558594 26.25 21.25 L 26.25 26.25 C 26.25 26.941406 25.691406 27.5 25 27.5 Z M 25 27.5 "/>
                                                                </g>
                                                            </svg>
                                                            <span class="mb-0 fs-13 ms-2 result text-success"><i class="fe fe-arrow-up"></i> 2.68%</span>
                                                        </p>
                                                        <h2 class="tx-normal"><span class="counter">$32,900</span></h2>
                                                        <p class="tx-12 text-muted">This Week Revenue</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row row-sm">
                                <div class="col-sm-6 col-lg-6 col-xl-6">
                                    <div class="card custom-card">
                                        <div class="card-body text-center">
                                            <div class="icon-margin bg-secondary-transparent rounded-circle text-secondary">
                                                <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path fill="#f1388b" d="M19.5,7H18V6c-0.0018311-1.6561279-1.3438721-2.9981689-3-3H4.5C3.119812,3.0012817,2.0012817,4.119812,2,5.5V18c0.0018311,1.6561279,1.3438721,2.9981689,3,3h14.5c1.380188-0.0012817,2.4987183-1.119812,2.5-2.5v-9C21.9987183,8.119812,20.880188,7.0012817,19.5,7z M4.5,4H15c1.1040039,0.0014038,1.9985962,0.8959961,2,2v1H4.5C3.6715698,7,3,6.3284302,3,5.5S3.6715698,4,4.5,4z M21,16h-2c-1.1045532,0-2-0.8954468-2-2s0.8954468-2,2-2h2V16z M21,11h-2c-1.6568604,0-3,1.3431396-3,3s1.3431396,3,3,3h2v1.5c-0.0009155,0.828064-0.671936,1.4990845-1.5,1.5H5c-1.1040039-0.0014038-1.9985962-0.8959961-2-2V7.4990234C3.4321899,7.8247681,3.9588013,8.0006714,4.5,8h15c0.828064,0.0009155,1.4990845,0.671936,1.5,1.5V11z"/></svg>
                                            </div>
                                            <h6 class="mb-0">Gross Profit Margin</h6>
                                            <h2 class="mb-1 mt-2 tx-normal"><span class="counter">77</span>%</h2>
                                            <p class="text-muted"><span class="mb-0 text-danger fs-13 ms-1"><i class="fe fe-arrow-down"></i> 1.68%</span> <span class="fs-12">Last month</span></p>
                                            <button class="btn btn-sm btn-outline-secondary">Check</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-6 col-xl-6">
                                    <div class="card custom-card">
                                        <div class="card-body text-center">
                                            <div class="icon-margin bg-success-transparent rounded-circle text-success">
                                                <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path fill="#19b159" d="M21.5,6h-6C15.223877,6,15,6.223877,15,6.5S15.223877,7,15.5,7h4.7929688l-7.2930298,7.2930298l-3.6464844-3.6464844c-0.1895142-0.1831055-0.4863281-0.1843262-0.680542-0.0091553c-0.0080566,0.0045776-0.0195312,0.0024414-0.0264282,0.0090942l-6.5,6.5c-0.09375,0.09375-0.1464233,0.2208862-0.1464233,0.3534546C2,17.776062,2.223877,17.999939,2.5,18c0.1326294,0.0001221,0.2598267-0.0526123,0.3534546-0.1465454l6.1464844-6.1464844l3.6465454,3.6465454C12.7369385,15.4440308,12.8619385,15.499939,13,15.5c0.1326294,0.0001221,0.2598267-0.0526123,0.3534546-0.1465454L21,7.7069092v4.7936401C21.0001831,12.7765503,21.223999,13.0001831,21.5,13h0.0006104C21.7765503,12.9998169,22.0001831,12.776001,22,12.5V6.4993896C21.9998169,6.2234497,21.776001,5.9998169,21.5,6z"/></svg>
                                            </div>
                                            <h6 class="mb-0">Net Profit Margin</h6>
                                            <h2 class="mb-1 mt-2 tx-normal"><span class="counter">35</span>%</h2>
                                            <p class="text-muted"><span class="mb-0 text-success fs-13 ms-1"><i class="fe fe-arrow-up"></i> 22%</span> <span class="fs-12">Last month</span></p>
                                            <button class="btn btn-sm btn-outline-success">Check</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End row-->

                </div>
            </div>
            <!-- END ROW -->

            <!-- ROW -->
            <div class="row row-sm">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 col-xxl-3">
                    <div class="card custom-card overflow-hidden">
                        <div class="card-header border-bottom">
                                <div>
                                    <h3 class="card-title tx-18"><label class="main-content-label tx-15">TRANSACTIONS</label></h3>
                                </div>
                        </div>
                        <div class="card-body pb-2">
                            <div class="transaction">
                                <div class="transaction-blog">
                                    <div class="transaction-img rounded-50 border-primary bg-primary-transparent text-primary mt-1">
                                        <svg class="transcation-icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M19.9794922,7.9521484l-6-5.2666016c-1.1339111-0.9902344-2.8250732-0.9902344-3.9589844,0l-6,5.2666016C3.3717041,8.5219116,2.9998169,9.3435669,3,10.2069702V19c0.0018311,1.6561279,1.3438721,2.9981689,3,3h2.5h7c0.0001831,0,0.0003662,0,0.0006104,0H18c1.6561279-0.0018311,2.9981689-1.3438721,3-3v-8.7930298C21.0001831,9.3435669,20.6282959,8.5219116,19.9794922,7.9521484z M15,21H9v-6c0.0014038-1.1040039,0.8959961-1.9985962,2-2h2c1.1040039,0.0014038,1.9985962,0.8959961,2,2V21z M20,19c-0.0014038,1.1040039-0.8959961,1.9985962-2,2h-2v-6c-0.0018311-1.6561279-1.3438721-2.9981689-3-3h-2c-1.6561279,0.0018311-2.9981689,1.3438721-3,3v6H6c-1.1040039-0.0014038-1.9985962-0.8959961-2-2v-8.7930298C3.9997559,9.6313477,4.2478027,9.0836182,4.6806641,8.7041016l6-5.2666016C11.0455933,3.1174927,11.5146484,2.9414673,12,2.9423828c0.4853516-0.0009155,0.9544067,0.1751099,1.3193359,0.4951172l6,5.2665405C19.7521973,9.0835571,20.0002441,9.6313477,20,10.2069702V19z"/></svg>
                                    </div>
                                    <div class="transaction-details d-flex">
                                        <div><span class="text-dark tx-semibold"> Electricity Bill</span> <p class="text-muted tx-12"> 11 Mar 3:00PM</p></div>
                                        <div class="ms-auto fs-14 text-danger font-weight-normal">-$15.30</div>
                                    </div>
                                </div>
                                <div class="transaction-blog">
                                    <div class="transaction-img rounded-50 border-secondary bg-secondary-transparent text-secondary mt-1">
                                        <span class="avatar-1">AL</span>
                                    </div>
                                    <div class="transaction-details d-flex">
                                        <div><span class="text-dark tx-semibold"> Maxin Till </span>  <p class="text-muted tx-12"> 13 Mar 11:49AM</p></div>
                                        <div class="ms-auto fs-14 text-success font-weight-normal">+$100.00</div>
                                    </div>
                                </div>
                                <div class="transaction-blog">
                                    <div class="transaction-img rounded-50 border-success bg-success-transparent text-success mt-1">
                                        <span class="avatar-1">KL</span>
                                    </div>
                                    <div class="transaction-details d-flex">
                                        <div><span class="text-dark tx-semibold"> Keval Lia</span>   <p class="text-muted tx-12"> 16 Mar 1:06PM</p></div>
                                        <div class="ms-auto fs-14 text-success font-weight-normal">+$89.06</div>
                                    </div>
                                </div>
                                <div class="transaction-blog">
                                    <div class="transaction-img rounded-50 border-info bg-info-transparent text-info mt-1">
                                        <svg class="transcation-icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path fill="#01b8ff" d="M19.9794922,7.9521484l-6-5.2666016c-1.1339111-0.9902344-2.8250732-0.9902344-3.9589844,0l-6,5.2666016C3.3717041,8.5219116,2.9998169,9.3435669,3,10.2069702V19c0.0018311,1.6561279,1.3438721,2.9981689,3,3h2.5h7c0.0001831,0,0.0003662,0,0.0006104,0H18c1.6561279-0.0018311,2.9981689-1.3438721,3-3v-8.7930298C21.0001831,9.3435669,20.6282959,8.5219116,19.9794922,7.9521484z M15,21H9v-6c0.0014038-1.1040039,0.8959961-1.9985962,2-2h2c1.1040039,0.0014038,1.9985962,0.8959961,2,2V21z M20,19c-0.0014038,1.1040039-0.8959961,1.9985962-2,2h-2v-6c-0.0018311-1.6561279-1.3438721-2.9981689-3-3h-2c-1.6561279,0.0018311-2.9981689,1.3438721-3,3v6H6c-1.1040039-0.0014038-1.9985962-0.8959961-2-2v-8.7930298C3.9997559,9.6313477,4.2478027,9.0836182,4.6806641,8.7041016l6-5.2666016C11.0455933,3.1174927,11.5146484,2.9414673,12,2.9423828c0.4853516-0.0009155,0.9544067,0.1751099,1.3193359,0.4951172l6,5.2665405C19.7521973,9.0835571,20.0002441,9.6313477,20,10.2069702V19z"/></svg>
                                </div>
                                    <div class="transaction-details d-flex">
                                        <div><span class="text-dark tx-semibold"> Net Flix </span> <p class="text-muted tx-12"> 20 Mar 4:00PM</p></div>
                                        <div class="ms-auto fs-14 text-danger font-weight-normal">-$10.00</div>
                                    </div>
                                </div>
                                <div class="transaction-blog">
                                    <div class="transaction-img rounded-50 border-danger bg-danger-transparent text-danger mt-1">
                                        <span class="avatar-1">RT</span>
                                    </div>
                                    <div class="transaction-details d-flex">
                                        <div><span class="text-dark tx-semibold"> Rita Mark </span> <p class="text-muted tx-12"> 20 Mar 4:00PM</p></div>
                                        <div class="ms-auto fs-14 text-success font-weight-normal">+$125</div>
                                    </div>
                                </div>
                                <div class="transaction-blog">
                                    <div class="transaction-img rounded-50 border-warning bg-warning-transparent text-warning mt-1">
                                        <span class="avatar-1">PE</span>
                                    </div>
                                    <div class="transaction-details d-flex">
                                        <div><span class="text-dark tx-semibold"> Park Entri </span> <p class="text-muted tx-12"> 24 Mar 6:06PM</p></div>
                                        <div class="ms-auto fs-14 text-danger font-weight-normal">-$40.99</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 col-xxl-3">
                    <div class="row row-sm">
                        <div class="col-12">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="tx-15 tx-semibold text-dark"> Average Sales Per Day</div>
                                    <div class="d-flex">
                                        <div class="col-8 ps-0 my-auto">
                                            <h3 class="tx-normal mb-1 text-warning">36,580</h3>
                                            <span class="text-muted">
                                                <i class="fe fe-arrow-up me-1"></i> +5.3%
                                            </span>
                                        </div>
                                        <div class="col-4 text-end pe-0">
                                            <div id="circle1" class="circle-chart">
                                                <strong></strong>
                                            </div>

                                        </div>
                                    </div>
                                    <p class="mb-0 text-muted">Lorem ipsum dolor sit amet, consectetur.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="tx-15 tx-semibold text-dark"> Average Profits Per Day </div>
                                    <div class="d-flex">
                                        <div class="col-8 ps-0 my-auto">
                                            <h3 class="tx-normal mb-1 text-success">69.5%</h3>
                                            <span class="text-muted">
                                                <i class="fe fe-arrow-up me-1"></i> +3.9%
                                            </span>
                                        </div>
                                        <div class="col-4 text-end pe-0">
                                            <div id="circle2" class="circle-chart">
                                                <strong></strong>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-muted">Lorem ipsum dolor sit amet, consectetur.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="tx-15 tx-semibold text-dark"> Average Visits Per Day </div>
                                    <div class="d-flex">
                                        <div class="col-8 ps-0 my-auto">
                                            <h3 class="tx-normal mb-1 text-info">50k</h3>
                                            <span class="text-muted">
                                                <i class="fe fe-arrow-up me-1"></i> +6.25
                                            </span>
                                        </div>
                                        <div class="col-4 text-end pe-0">
                                            <div id="circle3" class="circle-chart">
                                                <strong></strong>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-muted">Lorem ipsum dolor sit amet, consectetur.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 col-xxl-3">
                    <div class="card custom-card overflow-hidden">
                        <div class="card-header bd-b-0">
                            <div>
                                <h3 class="card-title tx-18"><label class="main-content-label tx-15">Recent Orders Activity</label></h3>
                            </div>
                        </div>
                        <div class="">
                            <ul class="list-group rounded-0 orders-activity">
                                <li class="list-group-item d-flex align-items-center">
                                    <img alt="avatar" class="wd-30 rounded-circle mg-r-15" src="build/assets/img/users/5.jpg">
                                    <div>
                                        <h6 class="tx-13  mg-b-0"> Catrice Doshier Just Ordered a Deal. </h6>
                                        <i class="mdi mdi-clock text-muted me-1 tx-12"></i>
                                        <span class="mb-0 text-muted tx-11">Just now</span>
                                    </div>
                                    <p class="text-muted ms-auto tx-14 mb-0">$1,999.00</p>
                                </li>
                                <li class="list-group-item d-flex align-items-center">
                                    <img alt="avatar" class="wd-30 rounded-circle mg-r-15" src="build/assets/img/users/2.jpg">
                                    <div>
                                        <h6 class="tx-13  mg-b-0">  Mercy Ritia Delivered a Deal. </h6>
                                        <i class="mdi mdi-clock text-muted me-1 tx-12"></i>
                                        <span class="mb-0 text-muted tx-11">2 Hours ago</span>
                                    </div>
                                    <p class="text-muted ms-auto tx-14 mb-0">$79.00</p>
                                </li>
                                <li class="list-group-item d-flex align-items-center">
                                    <img alt="avatar" class="wd-30 rounded-circle mg-r-15" src="build/assets/img/users/3.jpg">
                                    <div>
                                        <h6 class="tx-13  mg-b-0">  Marry Cott Cancelled the order.  </h6>
                                        <i class="mdi mdi-clock text-muted me-1 tx-12"></i>
                                        <span class="mb-0 text-muted tx-11">3 Hours ago</span>
                                    </div>
                                    <p class="text-muted ms-auto tx-14 mb-0">$100.00</p>
                                </li>
                                <li class="list-group-item d-flex align-items-center">
                                    <img alt="avatar" class="wd-30 rounded-circle mg-r-15" src="build/assets/img/users/9.jpg">
                                    <div>
                                        <h6 class="tx-13  mg-b-0">  Alexandar Denied the payment.  </h6>
                                        <i class="mdi mdi-clock text-muted me-1 tx-12"></i>
                                        <span class="mb-0 text-muted tx-11">10 Hours ago</span>
                                    </div>
                                    <p class="text-muted ms-auto tx-14 mb-0">$99.00</p>
                                </li>
                                <li class="list-group-item d-flex align-items-center">
                                    <img alt="avatar" class="wd-30 rounded-circle mg-r-15" src="build/assets/img/users/7.jpg">
                                    <div>
                                        <h6 class="tx-13  mg-b-0">  Catrice Doshier Just Ordered a Deal. . </h6>
                                        <i class="mdi mdi-clock text-muted me-1 tx-12"></i>
                                        <span class="mb-0 text-muted tx-11">5 Hours ago</span>
                                    </div>
                                    <p class="text-muted ms-auto tx-14 mb-0">$199.00</p>
                                </li>
                                <li class="list-group-item d-flex align-items-center">
                                    <img alt="avatar" class="wd-30 rounded-circle mg-r-15" src="build/assets/img/users/4.jpg">
                                    <div>
                                        <h6 class="tx-13  mg-b-0">  Mark John Payment is Done.  </h6>
                                        <i class="mdi mdi-clock text-muted me-1 tx-12"></i>
                                        <span class="mb-0 text-muted tx-11">10-12-2021</span>
                                    </div>
                                    <p class="text-muted ms-auto tx-14 mb-0">$49.00</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 col-xxl-3">
                    <div class="card custom-card">
                        <div class="card-header border-bottom">
                            <div>
                                <h3 class="card-title tx-18"><label class="main-content-label tx-15">SALES ANALYTICS</label></h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-group sales-analytics">
                                <li class="list-group-item">
                                    <div class="clearfix">
                                        <span class="float-start text-dark">Women's Fashion</span>
                                        <span class="float-end text-dark">86%</span>
                                    </div>
                                    <div class="progress ht-7 mt-1">
                                        <div class="progress-bar ht-7 progress-bar-striped progress-bar-animated wd-90p" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="clearfix">
                                        <span class="float-start text-dark">Electronics</span>
                                        <span class="float-end text-dark">83%</span>
                                    </div>
                                    <div class="progress ht-7 mt-1">
                                        <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="83" class="progress-bar ht-7  progress-bar-striped progress-bar-animated wd-85p bg-success" role="progressbar"></div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="clearfix">
                                        <span class="float-start text-dark">Mobiles</span>
                                        <span class="float-end text-dark">76%</span>
                                    </div>
                                    <div class="progress ht-7 mt-1">
                                        <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="76" class="progress-bar ht-7  progress-bar-striped progress-bar-animated wd-80p bg-info" role="progressbar"></div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="clearfix">
                                        <span class="float-start text-dark">Footwear</span>
                                        <span class="float-end text-dark">71%</span>
                                    </div>
                                    <div class="progress ht-7 mt-2">
                                        <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="71" class="progress-bar ht-7  progress-bar-striped progress-bar-animated wd-75p bg-secondary" role="progressbar"></div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="clearfix">
                                        <span class="float-start text-dark">Refridgerators</span>
                                        <span class="float-end text-dark">66%</span>
                                    </div>
                                    <div class="progress ht-7 mt-1">
                                        <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="66" class="progress-bar ht-7  progress-bar-striped progress-bar-animated wd-70p bg-warning" role="progressbar"></div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="clearfix">
                                        <span class="float-start text-dark">Furniture</span>
                                        <span class="float-end text-dark">60%</span>
                                    </div>
                                    <div class="progress ht-7 mt-1">
                                        <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="60" class="progress-bar ht-7 progress-bar-striped progress-bar-animated wd-65p bg-danger" role="progressbar"></div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="clearfix">
                                        <span class="float-start text-dark">Grocery</span>
                                        <span class="float-end text-dark">55%</span>
                                    </div>
                                    <div class="progress ht-7 mt-1">
                                        <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="55" class="progress-bar ht-7  progress-bar-striped progress-bar-animated wd-60p bg-primary" role="progressbar"></div>
                                    </div>
                                </li>
                                <li class="list-group-item mb-3">
                                    <div class="clearfix">
                                        <span class="float-start text-dark">Home</span>
                                        <span class="float-end text-dark">52%</span>
                                    </div>
                                    <div class="progress ht-7 mt-2">
                                        <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="52" class="progress-bar ht-7  progress-bar-striped progress-bar-animated wd-55p bg-info" role="progressbar"></div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END ROW -->

            <!-- ROW -->
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card custom-card">
                        <div class="card-header border-bottom">
                            <h3 class="card-title tx-18"><label class="main-content-label tx-15">RECENT ORDERS</label></h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom mb-0  border" id="recentorders">
                                    <thead>
                                        <tr>
                                            <th class="wd-lg-10p">CUSTOMER</th>
                                            <th class="wd-lg-10p">ORDER ID</th>
                                            <th class="wd-lg-10p">DATE</th>
                                            <th class="wd-lg-10p">PRICE</th>
                                            <th class="wd-lg-10p">STATUS</th>
                                            <th class="wd-lg-10p">COUNTRY</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="font-weight-normal d-flex">
                                                <span class="main-img-user">
                                                    <img alt="avatar" class="rounded-circle" src="build/assets/img/users/1.jpg">
                                                </span>
                                                <span class="mt-2 ms-2">Andrea Sun </span>
                                            </td>
                                            <td class="font-weight-normal">#10001</td>
                                            <td class="text-muted tx-13">01 MAR 2021</td>
                                            <td class="font-weight-normal">$146</td>
                                            <td class=""><span class="badge rounded-pill text-success bg-success-transparent px-2">Delivered</span></td>
                                            <td class="text-dark tx-13">Australia</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-normal d-flex">
                                                <span class="main-img-user">
                                                    <img alt="avatar" class="rounded-circle" src="build/assets/img/users/2.jpg">
                                                </span>
                                                <span class="mt-2 ms-2"> Ben Shall </span>
                                            </td>
                                            <td class="font-weight-normal">#10002</td>
                                            <td class="text-muted tx-13">02 MAR 2021</td>
                                            <td class="font-weight-normal">$179</td>
                                            <td class=""><span class="badge rounded-pill text-success bg-success-transparent px-2">Delivered</span></td>
                                            <td class="text-dark tx-13">Australia</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-normal d-flex">
                                                <div class="avatar bg-info-transparent text-info">
                                                    CT
                                                </div>
                                                <span class="mt-2 ms-2"> Cinel Toa</span>
                                            </td>
                                            <td class="font-weight-normal">#10003</td>
                                            <td class=" text-muted tx-13">03 MAR 2021</td>
                                            <td class="font-weight-normal">$188</td>
                                            <td class=""><span class="badge rounded-pill text-success bg-success-transparent px-2">Delivered</span></td>
                                            <td class="text-dark tx-13">Canada</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-normal d-flex">
                                                <span class="main-img-user">
                                                    <img alt="avatar" class="rounded-circle" src="build/assets/img/users/4.jpg">
                                                </span>
                                                <span class="mt-2 ms-2">Dia Mark </span>
                                            </td>
                                            <td class="font-weight-normal">#10004</td>
                                            <td class="text-muted tx-13">04 MAR 2021</td>
                                            <td class="font-weight-normal">$193</td>
                                            <td class=""><span class="badge rounded-pill text-success bg-success-transparent px-2">Delivered</span></td>
                                            <td class="text-dark tx-13">Denmark</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-normal d-flex">
                                                <div class="avatar bg-success-transparent text-success">
                                                    ER
                                                </div>
                                                <span class="mt-2 ms-2"> Eutica Ria </span>
                                            </td>
                                            <td class="font-weight-normal">#10005</td>
                                            <td class="text-muted tx-13">05 MAR 2021</td>
                                            <td class="font-weight-normal">$209</td>
                                            <td class=""><span class="badge rounded-pill text-success bg-success-transparent px-2">Delivered</span></td>
                                            <td class="text-dark tx-13">France</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-normal d-flex">
                                                <span class="main-img-user">
                                                    <img alt="avatar" class="rounded-circle" src="build/assets/img/users/6.jpg">
                                                </span>
                                                <span class="mt-2 ms-2"> Frano Rit </span>
                                            </td>
                                            <td class="font-weight-normal">#10006</td>
                                            <td class="text-muted tx-13">06 MAR 2021</td>
                                            <td class="font-weight-normal">$218</td>
                                            <td class=""><span class="badge rounded-pill text-warning bg-warning-transparent px-2">Pending</span></td>
                                            <td class="text-dark tx-13">Iran</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-normal d-flex">
                                                <span class="main-img-user">
                                                    <img alt="avatar" class="rounded-circle" src="build/assets/img/users/7.jpg">
                                                </span>
                                                <span class="mt-2 ms-2">Goldie Mat </span>
                                            </td>
                                            <td class="font-weight-normal">#10007</td>
                                            <td class="text-muted tx-13">07 MAR 2021</td>
                                            <td class="font-weight-normal">$227</td>
                                            <td class=""><span class="badge rounded-pill text-success bg-success-transparent px-2">Delivered</span></td>
                                            <td class="text-dark tx-13">London</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-normal d-flex">
                                                <div class="avatar bg-danger-transparent text-danger">
                                                    HD
                                                </div>
                                                <span class="mt-2 ms-2"> Henry Dia </span>
                                            </td>
                                            <td class="font-weight-normal">#10008</td>
                                            <td class="text-muted tx-13">08 MAR 2021</td>
                                            <td class="font-weight-normal">$234</td>
                                            <td class=""><span class="badge rounded-pill text-success bg-success-transparent px-2">Delivered</span></td>
                                            <td class="text-dark tx-13">Malasia</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-normal d-flex">
                                                <span class="main-img-user">
                                                    <img alt="avatar" class="rounded-circle" src="build/assets/img/users/9.jpg">
                                                </span>
                                                <span class="mt-2 ms-2"> Hustro Mark </span>
                                            </td>
                                            <td class="font-weight-normal">#10009</td>
                                            <td class="text-muted tx-13">09 MAR 2021</td>
                                            <td class="font-weight-normal">$246</td>
                                            <td class=""><span class="badge rounded-pill text-danger bg-danger-transparent px-2">Cancelled</span></td>
                                            <td class="text-dark tx-13">Malasia</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-normal d-flex">
                                                <span class="main-img-user">
                                                    <img alt="avatar" class="rounded-circle" src="build/assets/img/users/10.jpg">
                                                </span>
                                                <span class="mt-2 ms-2">Jack Fince </span>
                                            </td>
                                            <td class="font-weight-normal">#10010</td>
                                            <td class="text-muted tx-13">10 MAR 2021</td>
                                            <td class="font-weight-normal">$253</td>
                                            <td class=""><span class="badge rounded-pill text-success bg-success-transparent px-2">Delivered</span></td>
                                            <td class="text-dark tx-13">Russia</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-normal d-flex">
                                                <span class="main-img-user">
                                                    <img alt="avatar" class="rounded-circle" src="build/assets/img/users/11.jpg">
                                                </span>
                                                <span class="mt-2 ms-2"> Kanae Tom</span>
                                            </td>
                                            <td class="font-weight-normal">#10011</td>
                                            <td class="text-muted tx-13">11 MAR 2021</td>
                                            <td class="font-weight-normal">$260</td>
                                            <td class=""><span class="badge rounded-pill text-success bg-success-transparent px-2">Delivered</span></td>
                                            <td class="text-dark tx-13">Sweden</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-normal d-flex">
                                                <div class="avatar bg-secondary-transparent text-secondary">
                                                    LD
                                                </div>
                                                <span class="mt-2 ms-2"> Luci Dia </span>
                                            </td>
                                            <td class="font-weight-normal">#10012</td>
                                            <td class="text-muted tx-13">12 MAR 2021</td>
                                            <td class="font-weight-normal">$268</td>
                                            <td class=""><span class="badge rounded-pill text-warning bg-warning-transparent px-2">Pending</span></td>
                                            <td class="text-dark tx-13">Sweden</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-normal d-flex">
                                                <span class="main-img-user">
                                                    <img alt="avatar" class="rounded-circle" src="build/assets/img/users/3.jpg">
                                                </span>
                                                <span class="mt-2 ms-2">Mercy Rico</span>
                                            </td>
                                            <td class="font-weight-normal">#10013</td>
                                            <td class="text-muted tx-13">13 MAR 2021</td>
                                            <td class="font-weight-normal">$275</td>
                                            <td class=""><span class="badge rounded-pill text-success bg-success-transparent px-2">Delivered</span></td>
                                            <td class="text-dark tx-13">Brazil</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-normal d-flex">
                                                <div class="avatar bg-warning-transparent text-warning">
                                                    NR
                                                </div>
                                                <span class="mt-2 ms-2"> Niccy Ricco </span>
                                            </td>
                                            <td class="font-weight-normal">#10014</td>
                                            <td class="text-muted tx-13">14 MAR 2021</td>
                                            <td class="font-weight-normal">$286</td>
                                            <td class=""><span class="badge rounded-pill text-success bg-success-transparent px-2">Delivered</span></td>
                                            <td class="text-dark tx-13">UK</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-normal d-flex">
                                                <span class="main-img-user">
                                                    <img alt="avatar" class="rounded-circle" src="build/assets/img/users/5.jpg">
                                                </span>
                                                <span class="mt-2 ms-2"> Vashti Faw  </span>
                                            </td>
                                            <td class="font-weight-normal">#10015</td>
                                            <td class="text-muted tx-13">15 MAR 2021</td>
                                            <td class="font-weight-normal">$289</td>
                                            <td class=""><span class="badge rounded-pill text-danger bg-danger-transparent px-2">Cancelled</span></td>
                                            <td class="text-dark tx-13">USA</td>
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
