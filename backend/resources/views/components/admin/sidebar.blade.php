sidebar
@php
    $currentUrl = request()->path();
    $currentRouteName = Route::currentRouteName();
@endphp
<div style="background-color: #fff; padding: 5px; font-size: 12px; display: none;">
    URL: {{ $currentUrl }} <br>
    Route name: {{ $currentRouteName }}
</div>

<div class="sticky">
    <div class="main-menu main-sidebar main-sidebar-sticky side-menu">
        <div class="main-sidebar-header main-container-1 active">
            <div class="sidemenu-logo">
                <a class="main-logo" href="https://laravelui.spruko.com/dashplex">
                    <img src="{{ asset('assets/build/assets/img/logo/logo-agrijapan.png') }}" class="header-brand-img desktop-logo-dark" height="80px;" alt="logo">
                    <img src="build/assets/img/brand/icon-light.png" class="header-brand-img icon-logo-dark" alt="logo">
                    <img src="build/assets/img/brand/logo.png" class="header-brand-img desktop-logo" alt="logo">
                    <img src="build/assets/img/brand/icon.png" class="header-brand-img icon-logo" alt="logo">
                </a>
            </div>
            <div class="main-sidebar-body main-body-1">
                <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#c9bebe" width="24" height="24" viewBox="0 0 24 24"><path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"/></svg></div>
                <ul class="menu-nav mt-4 nav">
                    <li class="nav-item {{ request()->routeIs('admin.dashboards.index') }}">
                        <a class="nav-link with-sub" href="{{ route('admin.dashboards.index') }}">
                            <svg class="sidemenu-icon menu-icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M10.5,13h-7C3.2,13,3,13.2,3,13.5v7C3,20.8,3.2,21,3.5,21h7c0.3,0,0.5-0.2,0.5-0.5v-7C11,13.2,10.8,13,10.5,13z M10,20H4v-6h6V20z M10.5,3h-7C3.2,3,3,3.2,3,3.5v7C3,10.8,3.2,11,3.5,11h7c0.3,0,0.5-0.2,0.5-0.5v-7C11,3.2,10.8,3,10.5,3z M10,10H4V4h6V10z M20.5,3h-7C13.2,3,13,3.2,13,3.5v7c0,0.3,0.2,0.5,0.5,0.5h7c0.3,0,0.5-0.2,0.5-0.5v-7C21,3.2,20.8,3,20.5,3z M20,10h-6V4h6V10z M20.5,16.5h-3v-3c0-0.3-0.2-0.5-0.5-0.5s-0.5,0.2-0.5,0.5v3h-3c-0.3,0-0.5,0.2-0.5,0.5s0.2,0.5,0.5,0.5h3v3c0,0.3,0.2,0.5,0.5,0.5h0c0.3,0,0.5-0.2,0.5-0.5v-3h3c0.3,0,0.5-0.2,0.5-0.5S20.8,16.5,20.5,16.5z"/></svg>
                            <span class="sidemenu-label">Bảng điều khiển</span>
                        </a>
                    </li>
                    {{-- <li class="nav-item {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.admin.*') ? 'active' : '' }}">
                        <a class="nav-link with-sub">
                            <svg class="sidemenu-icon menu-icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/></svg>
                            <span class="sidemenu-label">Tài khoản</span>
                        </a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{route('admin.users.index')}}">Tài khoản nhân viên</a>
                            </li>
                            <li class="nav-sub-item {{ request()->routeIs('admin.admin.index') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{route('admin.admin.index')}}">Tài khoản khách hàng</a>
                            </li>
                        </ul>
                    </li> --}}
                    <li class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <a class="nav-link with-sub">
                            <svg class="sidemenu-icon menu-icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M7,5H21V7H7V5M7,13V11H21V13H7M4,4.5A1.5,1.5 0 0,1 5.5,6A1.5,1.5 0 0,1 4,7.5A1.5,1.5 0 0,1 2.5,6A1.5,1.5 0 0,1 4,4.5M4,10.5A1.5,1.5 0 0,1 5.5,12A1.5,1.5 0 0,1 4,13.5A1.5,1.5 0 0,1 2.5,12A1.5,1.5 0 0,1 4,10.5M7,19V17H21V19H7M4,16.5A1.5,1.5 0 0,1 5.5,18A1.5,1.5 0 0,1 4,19.5A1.5,1.5 0 0,1 2.5,18A1.5,1.5 0 0,1 4,16.5Z"/></svg>
                            <span class="sidemenu-label">Danh mục sản phẩm</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{route('admin.categories.index')}}">Danh sách</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <a class="nav-link with-sub">
                            <svg class="sidemenu-icon menu-icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M12,13A5,5 0 0,1 7,8H9A3,3 0 0,0 12,11A3,3 0 0,0 15,8H17A5,5 0 0,1 12,13M12,3A3,3 0 0,1 15,6H9A3,3 0 0,1 12,3M19,6H17A5,5 0 0,0 12,1A5,5 0 0,0 7,6H5C3.89,6 3,6.89 3,8V20A2,2 0 0,0 5,22H19A2,2 0 0,0 21,20V8C21,6.89 20.1,6 19,6Z"/></svg>
                            <span class="sidemenu-label">Sản phẩm</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{route('admin.products.index')}}">Danh sách</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.agents.*') ? 'active' : '' }}">
                        <a class="nav-link with-sub">
                            <svg class="sidemenu-icon menu-icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M12,5.5A3.5,3.5 0 0,1 15.5,9A3.5,3.5 0 0,1 12,12.5A3.5,3.5 0 0,1 8.5,9A3.5,3.5 0 0,1 12,5.5M5,8C5.56,8 6.08,8.15 6.53,8.42C6.38,9.85 6.8,11.27 7.66,12.38C7.16,13.34 6.16,14 5,14A3,3 0 0,1 2,11A3,3 0 0,1 5,8M19,8A3,3 0 0,1 22,11A3,3 0 0,1 19,14C17.84,14 16.84,13.34 16.34,12.38C17.2,11.27 17.62,9.85 17.47,8.42C17.92,8.15 18.44,8 19,8M5.5,18.25C5.5,16.18 8.41,14.5 12,14.5C15.59,14.5 18.5,16.18 18.5,18.25V20H5.5V18.25M0,20V18.5C0,17.11 1.89,15.94 4.45,15.6C3.86,16.28 3.5,17.22 3.5,18.25V20H0M24,20H20.5V18.25C20.5,17.22 20.14,16.28 19.55,15.6C22.11,15.94 24,17.11 24,18.5V20Z"/></svg>
                            <span class="sidemenu-label">Đại lý</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item {{ request()->routeIs('admin.agents.index') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{route('admin.agents.index')}}">Danh sách đại lý</a>
                            </li>
                            <li class="nav-sub-item {{ request()->routeIs('admin.agents.add') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{route('admin.agents.add')}}">Thêm mới đại lý</a>
                            </li>
                            <li class="nav-sub-item {{ request()->routeIs('admin.agents.print-qr-codes') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{route('admin.agents.print-qr-codes')}}">In mã QR</a>
                            </li>
                            <li class="nav-sub-item {{ request()->routeIs('admin.agents.print-qr-codes') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{route('admin.agents.search-barcode')}}">Tra cứu mã barcode</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.missions.*') || request()->routeIs('admin.questions.*') || request()->routeIs('admin.rewards.*') ? 'active' : '' }}">
                        <a class="nav-link with-sub">
                            <svg class="sidemenu-icon menu-icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M4,6H20V16H4M20,18A2,2 0 0,0 22,16V6C22,4.89 21.1,4 20,4H4C2.89,4 2,4.89 2,6V16A2,2 0 0,0 4,18H0V20H24V18H20Z"/></svg>
                            <span class="sidemenu-label">Hoạt động & Nhiệm vụ</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item {{ request()->routeIs('admin.missions.index') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{route('admin.missions.index')}}">Quản lý nhiệm vụ</a>
                            </li>
                            <li class="nav-sub-item {{ request()->routeIs('admin.questions.index') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{route('admin.questions.index')}}">Câu hỏi trắc nghiệm</a>
                            </li>
                            <li class="nav-sub-item {{ request()->routeIs('admin.rewards.index') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{route('admin.rewards.index')}}">Quản lý phần thưởng</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.lucky_wheel.*') ? 'active' : '' }}">
                        <a class="nav-link with-sub">
                            <svg class="sidemenu-icon menu-icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M12,10A2,2 0 0,1 14,12C14,13.1 13.1,14 12,14C10.9,14 10,13.1 10,12A2,2 0 0,1 12,10M12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"/></svg>
                            <span class="sidemenu-label">Vòng quay may mắn</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item {{ request()->routeIs('admin.lucky_wheel.index') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{route('admin.lucky_wheel.index')}}">Danh sách vòng quay</a>
                            </li>
                            <li class="nav-sub-item {{ request()->routeIs('admin.lucky_wheel.create') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{route('admin.lucky_wheel.create')}}">Tạo vòng quay mới</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.checkin.*') ? 'active' : '' }}">
                        <a class="nav-link with-sub">
                            <svg class="sidemenu-icon menu-icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M10,17L6,13L7.41,11.59L10,14.17L16.59,7.58L18,9M12,3A1,1 0 0,1 13,4A1,1 0 0,1 12,5A1,1 0 0,1 11,4A1,1 0 0,1 12,3M19,3H14.82C14.4,1.84 13.3,1 12,1C10.7,1 9.6,1.84 9.18,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3Z"/></svg>
                            <span class="sidemenu-label">Điểm danh</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item {{ request()->routeIs('admin.checkin.index') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{route('admin.checkin.index')}}">Quản lý điểm danh</a>
                            </li>
                            <li class="nav-sub-item {{ request()->routeIs('admin.checkin.reports') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{route('admin.checkin.reports')}}">Báo cáo điểm danh</a>
                            </li>
                            <li class="nav-sub-item {{ request()->routeIs('admin.checkin.settings') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{route('admin.checkin.settings')}}">Cài đặt điểm danh</a>
                            </li>
                        </ul>
                    </li>

                </ul>
                <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#c9bebe" width="24" height="24" viewBox="0 0 24 24"><path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/></svg></div>
            </div>
        </div>
    </div>
</div>
