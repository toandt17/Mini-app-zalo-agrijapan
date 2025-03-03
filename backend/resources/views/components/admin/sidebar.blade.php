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
                    <li class="nav-item {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                        <a class="nav-link with-sub" href="{{ route('admin.index') }}">
                            <svg class="sidemenu-icon menu-icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M10.5,13h-7C3.2,13,3,13.2,3,13.5v7C3,20.8,3.2,21,3.5,21h7c0.3,0,0.5-0.2,0.5-0.5v-7C11,13.2,10.8,13,10.5,13z M10,20H4v-6h6V20z M10.5,3h-7C3.2,3,3,3.2,3,3.5v7C3,10.8,3.2,11,3.5,11h7c0.3,0,0.5-0.2,0.5-0.5v-7C11,3.2,10.8,3,10.5,3z M10,10H4V4h6V10z M20.5,3h-7C13.2,3,13,3.2,13,3.5v7c0,0.3,0.2,0.5,0.5,0.5h7c0.3,0,0.5-0.2,0.5-0.5v-7C21,3.2,20.8,3,20.5,3z M20,10h-6V4h6V10z M20.5,16.5h-3v-3c0-0.3-0.2-0.5-0.5-0.5s-0.5,0.2-0.5,0.5v3h-3c-0.3,0-0.5,0.2-0.5,0.5s0.2,0.5,0.5,0.5h3v3c0,0.3,0.2,0.5,0.5,0.5h0c0.3,0,0.5-0.2,0.5-0.5v-3h3c0.3,0,0.5-0.2,0.5-0.5S20.8,16.5,20.5,16.5z"/></svg>
                            <span class="sidemenu-label">Bảng điều khiển</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.admin.*') ? 'active' : '' }}">
                        <a class="nav-link with-sub" href="javascript:void(0);">
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
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <a class="nav-link with-sub" href="javascript:void(0);">
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
                        <a class="nav-link with-sub" href="javascript:void(0);">
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
                    <li class="nav-item {{ request()->routeIs('admin.order.*') ? 'active' : '' }}">
                        <a class="nav-link with-sub" href="javascript:void(0);">
                            <svg class="sidemenu-icon menu-icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M21.5,17h-15C5.6715698,17,5,16.3284302,5,15.5S5.6715698,14,6.5,14h10.9638672c1.2661133,0.0041504,2.3724365-0.8544312,2.6826172-2.0819702l1.8378906-7.2959595c0.0680542-0.2669678-0.0932617-0.5385742-0.3602905-0.6066284C21.5834961,4.005127,21.5418091,3.999939,21.5,4H6.3908691L6.3642578,3.8935547C6.0869751,2.7798462,5.0861816,1.9986572,3.9384766,2H2.5C2.223877,2,2,2.223877,2,2.5S2.223877,3,2.5,3h1.4384766C4.6269531,2.9990234,5.227356,3.4676514,5.3935547,4.1357422L7.609375,13H6.5C5.1192627,13,4,14.1192627,4,15.5S5.1192627,18,6.5,18h1.0124512C7.1818848,18.4303589,7.0018311,18.9573364,7,19.5C7,20.8807373,8.1192627,22,9.5,22s2.5-1.1192627,2.5-2.5c-0.0018311-0.5426636-0.1818848-1.0696411-0.5124512-1.5h4.0249023C15.1818848,18.4303589,15.0018311,18.9573364,15,19.5c0,1.3807373,1.1192627,2.5,2.5,2.5s2.5-1.1192627,2.5-2.5c-0.0018311-0.5426636-0.1818848-1.0696411-0.5124512-1.5H21.5c0.276123,0,0.5-0.223877,0.5-0.5S21.776123,17,21.5,17z M6.6416016,5h14.2167969l-1.6816406,6.6738281C18.9780884,12.4567871,18.2716675,13.0037842,17.4638672,13H8.65625L6.6416016,5z M9.5,21C8.6715698,21,8,20.3284302,8,19.5S8.6715698,18,9.5,18s1.5,0.6715698,1.5,1.5C10.9990845,20.328064,10.328064,20.9990845,9.5,21z M17.5,21c-0.8284302,0-1.5-0.6715698-1.5-1.5s0.6715698-1.5,1.5-1.5s1.5,0.6715698,1.5,1.5C18.9990845,20.328064,18.328064,20.9990845,17.5,21z"/></svg>
                            <span class="sidemenu-label">Đơn hàng</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item {{ request()->routeIs('admin.order.index') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{route('admin.order.index')}}">Danh sách</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.payment.*') ? 'active' : '' }}">
                        <a class="nav-link with-sub" href="javascript:void(0);">
                            <svg class="sidemenu-icon menu-icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M7 12C9.2 12 11 10.2 11 8S9.2 4 7 4 3 5.8 3 8 4.8 12 7 12M11 20V14.7C9.9 14.3 8.5 14 7 14C3.1 14 0 15.8 0 18V20H11M22 4H15C13.9 4 13 4.9 13 6V18C13 19.1 13.9 20 15 20H22C23.1 20 24 19.1 24 18V6C24 4.9 23.1 4 22 4M18 18H16V6H18V18Z"/></svg>
                            <span class="sidemenu-label">Thanh toán</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item {{ request()->routeIs('admin.payment.index') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{route('admin.payment.index')}}">Danh sách</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.preview.*') ? 'active' : '' }}">
                        <a class="nav-link with-sub" href="javascript:void(0);">
                            <svg class="sidemenu-icon menu-icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M18,14H10.5L12.5,12H18M6,14V11.5L12.88,4.64C13.07,4.45 13.39,4.45 13.59,4.64L15.35,6.41C15.55,6.61 15.55,6.92 15.35,7.12L8.47,14M20,2H4A2,2 0 0,0 2,4V22L6,18H20A2,2 0 0,0 22,16V4C22,2.89 21.1,2 20,2Z"/></svg>
                            <span class="sidemenu-label">Đánh giá</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item {{ request()->routeIs('admin.preview.index') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{route('admin.preview.index')}}">Danh sách</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link with-sub" href="javascript:void(0);">
                            <svg class="sidemenu-icon menu-icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M7.97,16L5,19C4.67,19.3 4.23,19.5 3.75,19.5A1.75,1.75 0 0,1 2,17.75V17.5L3,10.12C3.21,7.81 5.14,6 7.5,6H16.5C18.86,6 20.79,7.81 21,10.12L22,17.5V17.75A1.75,1.75 0 0,1 20.25,19.5C19.77,19.5 19.33,19.3 19,19L16.03,16H7.97M7,8V10H5V11H7V13H8V11H10V10H8V8H7M16.5,8A0.75,0.75 0 0,0 15.75,8.75A0.75,0.75 0 0,0 16.5,9.5A0.75,0.75 0 0,0 17.25,8.75A0.75,0.75 0 0,0 16.5,8M14.75,9.75A0.75,0.75 0 0,0 14,10.5A0.75,0.75 0 0,0 14.75,11.25A0.75,0.75 0 0,0 15.5,10.5A0.75,0.75 0 0,0 14.75,9.75M18.25,9.75A0.75,0.75 0 0,0 17.5,10.5A0.75,0.75 0 0,0 18.25,11.25A0.75,0.75 0 0,0 19,10.5A0.75,0.75 0 0,0 18.25,9.75M16.5,11.5A0.75,0.75 0 0,0 15.75,12.25A0.75,0.75 0 0,0 16.5,13A0.75,0.75 0 0,0 17.25,12.25A0.75,0.75 0 0,0 16.5,11.5Z"/></svg>
                            <span class="sidemenu-label">Sự kiện MINI GAME</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item">
                                <a class="nav-sub-link" href="ecommerce-dashboard.html">Danh sách</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.contact_sales.*') || request()->routeIs('admin.contact_tech.*') ? 'active' : '' }}">
                        <a class="nav-link with-sub" href="javascript:void(0);">
                            <svg class="sidemenu-icon menu-icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M7.97,16L5,19C4.67,19.3 4.23,19.5 3.75,19.5A1.75,1.75 0 0,1 2,17.75V17.5L3,10.12C3.21,7.81 5.14,6 7.5,6H16.5C18.86,6 20.79,7.81 21,10.12L22,17.5V17.75A1.75,1.75 0 0,1 20.25,19.5C19.77,19.5 19.33,19.3 19,19L16.03,16H7.97M7,8V10H5V11H7V13H8V11H10V10H8V8H7M16.5,8A0.75,0.75 0 0,0 15.75,8.75A0.75,0.75 0 0,0 16.5,9.5A0.75,0.75 0 0,0 17.25,8.75A0.75,0.75 0 0,0 16.5,8M14.75,9.75A0.75,0.75 0 0,0 14,10.5A0.75,0.75 0 0,0 14.75,11.25A0.75,0.75 0 0,0 15.5,10.5A0.75,0.75 0 0,0 14.75,9.75M18.25,9.75A0.75,0.75 0 0,0 17.5,10.5A0.75,0.75 0 0,0 18.25,11.25A0.75,0.75 0 0,0 19,10.5A0.75,0.75 0 0,0 18.25,9.75M16.5,11.5A0.75,0.75 0 0,0 15.75,12.25A0.75,0.75 0 0,0 16.5,13A0.75,0.75 0 0,0 17.25,12.25A0.75,0.75 0 0,0 16.5,11.5Z"/></svg>
                            <span class="sidemenu-label">Liên hệ</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item {{ request()->routeIs('admin.contact_sales.index_sales') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{route('admin.contact_sales.index_sales')}}">Yêu cầu đại lý</a>
                            </li>
                            <li class="nav-sub-item {{ request()->routeIs('admin.contact_tech.index_tech') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{route('admin.contact_tech.index_tech')}}">Tư vấn kỹ thuật</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.chart.*') ? 'active' : '' }}">
                        <a class="nav-link with-sub" href="{{route('admin.chart.index')}}">
                            <svg class="sidemenu-icon menu-icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M22,21H2V3H4V19H6V10H10V19H12V6H16V19H18V14H22V21Z"/></svg>
                            <span class="sidemenu-label">Thống kê</span>
                        </a>
                    </li>
                </ul>
                <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#c9bebe" width="24" height="24" viewBox="0 0 24 24"><path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/></svg></div>
            </div>
        </div>
    </div>
</div>
