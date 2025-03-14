    @extends('admin.layouts.master')
    @section('content')

    <div class="main-content side-content pt-0">
        <div class="main-container container-fluid">
            <div class="inner-body">
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
                <div class="row row-sm">
                    <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="row row-sm">
                            <div class="col-sm-12 col-md-12 col-lg-4 col-xl-3 col-xxl-2">
                                <div class="card custom-card">
                                    <div class="card-body">
                                        <div class="card-item">
                                            <div class="card-item-icon bg-success-transparent">
                                                <svg class="text-primary wd-20 ht-20" fill="#19b159" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M14.6650391,13.3672485C16.6381226,12.3842773,17.9974365,10.3535767,18,8c0-3.3137207-2.6862793-6-6-6S6,4.6862793,6,8c0,2.3545532,1.3595581,4.3865967,3.3334961,5.3690186c-3.6583862,1.0119019-6.5859375,4.0562134-7.2387695,8.0479736c-0.0002441,0.0013428-0.0004272,0.0026855-0.0006714,0.0040283c-0.0447388,0.272583,0.1399536,0.5297852,0.4125366,0.5745239c0.272522,0.0446777,0.5297241-0.1400146,0.5744629-0.4125366c0.624939-3.8344727,3.6308594-6.8403931,7.465332-7.465332c4.9257812-0.8027954,9.5697632,2.5395508,10.3725586,7.465332C20.9594727,21.8233643,21.1673584,21.9995117,21.4111328,22c0.0281372,0.0001831,0.0562134-0.0021362,0.0839844-0.0068359h0.0001831c0.2723389-0.0458984,0.4558716-0.303833,0.4099731-0.5761719C21.2677002,17.5184937,18.411377,14.3986206,14.6650391,13.3672485z M12,13c-2.7614136,0-5-2.2385864-5-5s2.2385864-5,5-5c2.7600708,0.0032349,4.9967651,2.2399292,5,5C17,10.7614136,14.7614136,13,12,13z"/></svg>
                                            </div>
                                            <div class="card-item-title mb-2">
                                                <label class="main-content-label tx-13 mb-1">Tổng số tài khoản</label>
                                            </div>
                                            <div class="card-item-body">
                                                <div class="card-item-stat">
                                                    {{-- <h4 class="font-weight-normal">{{ $totalUsers }}</h4> --}}
                                                    <small><b class="badge rounded-pill bg-success fs-11">65%</b><span class="px-1">Higher</span></small>
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
                                                <label class="main-content-label tx-13 mb-1">Tổng số danh mục sản phẩm</label>
                                            </div>
                                            <div class="card-item-body">
                                                <div class="card-item-stat">
                                                    {{-- <h4 class="font-weight-normal">{{$totalCategories}}</h4> --}}
                                                    <small><b class="badge rounded-pill bg-danger fs-11">15%</b> <span class="px-1">Decreased</span></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card custom-card">
                                    <div class="card-body">
                                        <div class="card-item">
                                            <div class="card-item-icon bg-info-transparent">
                                                <svg class="text-primary wd-20 ht-20" fill="#01b8ff" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M22.5,10h-4.0005493C18.2234497,10.0001831,17.9998169,10.223999,18,10.5v12.0005493C18.0001831,22.7765503,18.223999,23.0001831,18.5,23h4.0006104C22.7765503,22.9998169,23.0001831,22.776001,23,22.5V10.4993896C22.9998169,10.2234497,22.776001,9.9998169,22.5,10z M22,22h-3V11h3V22z M14.5,2h-4.0005493C10.2234497,2.0001831,9.9998169,2.223999,10,2.5v20.0005493C10.0001831,22.7765503,10.223999,23.0001831,10.5,23h4.0006104C14.7765503,22.9998169,15.0001831,22.776001,15,22.5V2.4993896C14.9998169,2.2234497,14.776001,1.9998169,14.5,2z M14,22h-3V3h3V22z M6.5,14H2.4993896C2.2234497,14.0001831,1.9998169,14.223999,2,14.5v8.0005493C2.0001831,22.7765503,2.223999,23.0001831,2.5,23h4.0006104C6.7765503,22.9998169,7.0001831,22.776001,7,22.5v-8.0006104C6.9998169,14.2234497,6.776001,13.9998169,6.5,14z M6,22H3v-7h3V22z"/></svg>
                                            </div>
                                            <div class="card-item-title mb-2">
                                                <label class="main-content-label tx-13 mb-1">Tổng số sản phẩm</label>
                                            </div>
                                            <div class="card-item-body">
                                                <div class="card-item-stat">
                                                    {{-- <h4 class="font-weight-normal">{{$totalProducts}}</h4> --}}
                                                    <small><b class="badge rounded-pill bg-info fs-11">25%</b><span class="px-1">Increased</span></small>
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
                                            <h3 class="card-title tx-18"><label class="main-content-label tx-15">Lượng người dùng truy cập theo tháng</label></h3>
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
                                        <canvas id="trafficChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{-- <script>
    var ctx = document.getElementById('trafficChart').getContext('2d');

    var usersByMonth = @json($usersByMonth);

    var labels = usersByMonth.map(item => 'Tháng ' + item.month);
    var data = usersByMonth.map(item => item.count);

    var trafficChart = new Chart(ctx, {
        type: 'line', // Biểu đồ đường
        data: {
            labels: labels,
            datasets: [{
                label: 'Lượng truy cập',
                data: data,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderWidth: 2,
                pointRadius: 5,
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                tension: 0.3 // Độ cong của đường
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1, // Hiển thị số nguyên
                        callback: function(value) {
                            return Number.isInteger(value) ? value : null; 
                        }
                    }
                }
            }
        }
    });
</script> --}}


@endsection
