@extends('admin.layouts.master')

@section('title', 'Chi tiết nhiệm vụ')

@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Chi tiết nhiệm vụ</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Game</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.missions.index') }}">Quản lý nhiệm vụ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chi tiết</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">
                        <a href="{{ route('admin.missions.edit', $mission->id) }}" class="btn btn-primary">
                            <i class="fe fe-edit"></i> Chỉnh sửa
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
                                <h6 class="main-content-label mb-1">Thông tin nhiệm vụ</h6>
                            </div>

                            <div class="table-responsive mt-4">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th style="width: 200px;">ID</th>
                                            <td>{{ $mission->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tên nhiệm vụ</th>
                                            <td>{{ $mission->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Mô tả</th>
                                            <td>{{ $mission->description }}</td>
                                        </tr>
                                        <tr>
                                            <th>Hành động yêu cầu</th>
                                            <td>
                                                @php
                                                    $actionLabels = [
                                                        'login_daily' => 'Đăng nhập hàng ngày',
                                                        'complete_profile' => 'Hoàn thành hồ sơ',
                                                        'follow_zalo_page' => 'Theo dõi fanpage Zalo',
                                                        'read_articles' => 'Đọc bài viết',
                                                        'watch_youtube' => 'Xem video YouTube',
                                                        'follow_tiktok' => 'Theo dõi TikTok',
                                                        'share_facebook' => 'Chia sẻ Facebook',
                                                        'share_app' => 'Chia sẻ ứng dụng',
                                                        'comment_posts' => 'Bình luận bài viết',
                                                        'quiz_completion' => 'Trả lời câu hỏi',
                                                        'login_streak' => 'Đăng nhập liên tiếp',
                                                        'complete_survey' => 'Hoàn thành khảo sát',
                                                        'join_livestream' => 'Tham gia livestream',
                                                        'tiktok_review' => 'Đăng review TikTok',
                                                        'submit_article' => 'Đóng góp bài viết',
                                                        'refer_users' => 'Giới thiệu người dùng',
                                                        'reach_points' => 'Đạt điểm tích lũy'
                                                    ];
                                                @endphp
                                                <span class="badge bg-primary">
                                                    {{ $actionLabels[$mission->action_required] ?? $mission->action_required }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Điểm thưởng</th>
                                            <td>{{ number_format($mission->points_reward) }} điểm</td>
                                        </tr>
                                        <tr>
                                            <th>Vé quay</th>
                                            <td>{{ $mission->spin_tickets }} vé</td>
                                        </tr>
                                        <tr>
                                            <th>Phần thưởng</th>
                                            <td>
                                                @if($mission->reward)
                                                    <span class="badge badge-success">{{ $mission->reward->name }}</span>
                                                @else
                                                    <span class="badge badge-secondary">Không có</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Số người hoàn thành</th>
                                            <td>
                                                {{ $mission->userMissions()->count() }} người
                                                (<span class="text-success">{{ $mission->completion_percentage ?? 0 }}%</span> người dùng)
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Ngày tạo</th>
                                            <td>{{ $mission->created_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Cập nhật lần cuối</th>
                                            <td>{{ $mission->updated_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END ROW -->

            <!-- ROW: Người dùng đã hoàn thành -->
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card custom-card overflow-hidden">
                        <div class="card-header bg-transparent">
                            <h6 class="card-title mb-0">Danh sách người đã hoàn thành nhiệm vụ</h6>
                        </div>
                        <div class="card-body">
                            @if($completions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered mg-b-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Người dùng</th>
                                            <th>Email</th>
                                            <th>Điểm số</th>
                                            <th>Vé quay nhận được</th>
                                            <th>Thời gian hoàn thành</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($completions as $completion)
                                        <tr>
                                            <td>{{ $completion->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($completion->user->avatar)
                                                        <img src="{{ asset('storage/' . $completion->user->avatar) }}" alt="{{ $completion->user->name }}" class="rounded-circle mr-2" style="width: 40px; height: 40px;">
                                                    @else
                                                        <div class="avatar bg-primary rounded-circle mr-2">
                                                            <span>{{ substr($completion->user->name, 0, 1) }}</span>
                                                        </div>
                                                    @endif
                                                    <div class="ml-2">
                                                        <strong>{{ $completion->user->name }}</strong>
                                                        @if($completion->user->phone)
                                                            <div>
                                                                <small class="text-muted">{{ $completion->user->phone }}</small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $completion->user->email ?? 'N/A' }}</td>
                                            <td>{{ number_format($completion->user->points) }}</td>
                                            <td>{{ $completion->spin_tickets_earned }}</td>
                                            <td>{{ $completion->completed_at ? $completion->completed_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $completions->links() }}
                            </div>
                            @else
                            <div class="alert alert-info">
                                Chưa có người dùng nào hoàn thành nhiệm vụ này.
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 mb-5">
                <a href="{{ route('admin.missions.index') }}" class="btn btn-secondary">
                    <i class="fe fe-arrow-left"></i> Quay lại danh sách
                </a>
            </div>

        </div>
    </div>
</div>

@endsection
