<!DOCTYPE html>
<html lang="en" dir="ltr">

<!-- Mirrored from laravelui.spruko.com/dashplex/index by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 27 Feb 2025 01:24:10 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>

		<!-- META DATA -->
		<meta charset="UTF-8">
		<meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
		<meta name="description" content="Dashplex - Laravel Admin Panel Dashboard Template">
	    <meta name="author" content="Spruko Technologies Private Limited">
		<meta name="keywords" content="admin dashboard, dashboard ui, backend, admin panel, admin template, dashboard template, admin, bootstrap, laravel, laravel admin panel, php admin panel, php admin dashboard, laravel admin template, laravel dashboard, laravel admin panel"/>

        <!-- FAVICON -->
		<link rel="icon" href="{{ asset('assets/build/assets/img/brand/favicon.ico') }}" type="image/x-icon" />

		<!-- TITLE -->
		<title> Dashplex - Laravel Bootstrap5 Premium Dashboard Template</title>

        <!-- BOOTSTRAP CSS -->
	    <link id="style" href="{{ asset('assets/build/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />

        <!-- ICONS CSS -->
        <link href="{{ asset('assets/build/assets/web-fonts/icons.css') }}" rel="stylesheet"/>
        <link href="{{ asset('assets/build/assets/web-fonts/font-awesome/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/build/assets/web-fonts/plugin.css') }}" rel="stylesheet"/>

        <!-- APP CSS & APP SCSS -->
        <link rel="preload" as="style" href="{{ asset('assets/build/assets/app.67855e29.css') }}" /><link rel="preload" as="style" href="{{ asset('assets/build/assets/app.4b443544.css') }}" /><link rel="stylesheet" href="{{ asset('assets/build/assets/app.67855e29.css') }}" /><link rel="stylesheet" href="{{ asset('assets/build/assets/app.4b443544.css') }}" />



	</head>

	<body class="main-body leftmenu ltr light-theme dark-menu">

        <!-- SWITCHER -->
        <div class="switcher-wrapper">
				<div class="demo_changer">
					<div class="form_holder sidebar-right1">
						<div class="row">
							<div class="predefined_styles">
								<div class="swichermainleft text-center">
									<div class="p-3 d-grid gap-2">
										<a href="index-2.html" class="btn ripple btn-primary mt-0" target="_blank">View Demo</a>
										<a href="https://themeforest.net/item/dashplex-laravel-dashboard-template/39846037" class="btn ripple btn-secondary" target="_blank">Buy Now</a>
										<a href="https://themeforest.net/user/spruko/portfolio" class="btn ripple btn-info" target="_blank">Our Portfolio</a>
									</div>
								</div>
								<div class="swichermainleft">
									<h4>LTR and RTL Versions</h4>
									<div class="skin-body">
										<div class="switch_section">
											<div class="switch-toggle d-flex">
												<span class="me-auto">LTR</span>
												<p class="onoffswitch2"><input type="radio" name="onoffswitch7" id="myonoffswitch19" class="onoffswitch2-checkbox" checked>
													<label for="myonoffswitch19" class="onoffswitch2-label"></label>
												</p>
											</div>
											<div class="switch-toggle d-flex mt-2">
												<span class="me-auto">RTL</span>
												<p class="onoffswitch2"><input type="radio" name="onoffswitch7" id="myonoffswitch20" class="onoffswitch2-checkbox">
													<label for="myonoffswitch20" class="onoffswitch2-label"></label>
												</p>
											</div>
										</div>
									</div>
								</div>
								<div class="swichermainleft">
									<h4>Navigation Style</h4>
									<div class="skin-body">
										<div class="switch_section">
											<div class="switch-toggle d-flex">
												<span class="me-auto">Vertical Menu</span>
												<p class="onoffswitch2"><input type="radio" name="onoffswitch01" id="myonoffswitch01" class="onoffswitch2-checkbox" checked>
													<label for="myonoffswitch01" class="onoffswitch2-label"></label>
												</p>
											</div>
											<div class="switch-toggle d-flex mt-2">
												<span class="me-auto">Horizontal Click Menu</span>
												<p class="onoffswitch2"><input type="radio" name="onoffswitch01" id="myonoffswitch02" class="onoffswitch2-checkbox">
													<label for="myonoffswitch02" class="onoffswitch2-label"></label>
												</p>
											</div>
											<div class="switch-toggle d-flex mt-2">
												<span class="me-auto">Horizontal Hover Menu</span>
												<p class="onoffswitch2"><input type="radio" name="onoffswitch01" id="myonoffswitch03" class="onoffswitch2-checkbox">
													<label for="myonoffswitch03" class="onoffswitch2-label"></label>
												</p>
											</div>
										</div>
									</div>
								</div>
								<div class="swichermainleft">
									<h4>Light Theme Style</h4>
									<div class="skin-body">
										<div class="switch_section">
											<div class="switch-toggle d-flex">
												<span class="me-auto">Light Theme</span>
												<p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch1" id="myonoffswitch1" class="onoffswitch2-checkbox" checked>
													<label for="myonoffswitch1" class="onoffswitch2-label"></label>
												</p>
											</div>
											<div class="switch-toggle d-flex mt-2">
												<span class="me-auto">Dark Theme</span>
												<p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch1" id="myonoffswitch2" class="onoffswitch2-checkbox">
													<label for="myonoffswitch2" class="onoffswitch2-label"></label>
												</p>
											</div>
										</div>
									</div>
								</div>
								<div class="swichermainleft">
									<h4>Theme Primary Color</h4>
									<div class="skin-body">
										<div class="switch_section">
											<div class="switch-toggle d-flex">
												<span class="me-auto">Primary Color</span>
												<div class="">
													<input class=" input-color-picker color-primary-light"
														value="#4454c3" id="colorID" (change)="changePrimaryColor()" type="color"
														data-id="bg-color" data-id1="bg-hover" data-id2="bg-border" name="lightPrimary">
												</div>
											</div>
											<div class="switch-toggle d-flex mt-2">
												<span class="me-auto">Background Color</span>
												<div class="">
													<input class="w-30p h-30 input-bg-picker background-primary-light"
														value="#1c203c" id="bgID" (change)="changeBackgroundColor()"
														type="color" data-id3="body" data-id4="theme" name="BackgroundPrimary">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="swichermainleft">
									<h4>Menu Styles</h4>
									<div class="skin-body">
										<div class="switch_section">
											<div class="switch-toggle lightMenu d-flex">
												<span class="me-auto">Light Menu</span>
												<p class="onoffswitch2"><input type="radio" name="onoffswitch2" id="myonoffswitch3" class="onoffswitch2-checkbox">
													<label for="myonoffswitch3" class="onoffswitch2-label"></label>
												</p>
											</div>
											<div class="switch-toggle colorMenu d-flex mt-2">
												<span class="me-auto">Color Menu</span>
												<p class="onoffswitch2"><input type="radio" name="onoffswitch2" id="myonoffswitch4" class="onoffswitch2-checkbox">
													<label for="myonoffswitch4" class="onoffswitch2-label"></label>
												</p>
											</div>
											<div class="switch-toggle darkMenu d-flex mt-2">
												<span class="me-auto">Dark Menu</span>
												<p class="onoffswitch2"><input type="radio" name="onoffswitch2" id="myonoffswitch5" class="onoffswitch2-checkbox">
													<label for="myonoffswitch5" class="onoffswitch2-label"></label>
												</p>
											</div>
										</div>
									</div>
								</div>
								<div class="swichermainleft">
									<h4>Header Styles</h4>
									<div class="skin-body">
										<div class="switch_section">
											<div class="switch-toggle lightHeader d-flex">
												<span class="me-auto">Light Header</span>
												<p class="onoffswitch2"><input type="radio" name="onoffswitch3" id="myonoffswitch6" class="onoffswitch2-checkbox" checked>
													<label for="myonoffswitch6" class="onoffswitch2-label"></label>
												</p>
											</div>
											<div class="switch-toggle  colorHeader d-flex mt-2">
												<span class="me-auto">Color Header</span>
												<p class="onoffswitch2"><input type="radio" name="onoffswitch3" id="myonoffswitch7" class="onoffswitch2-checkbox">
													<label for="myonoffswitch7" class="onoffswitch2-label"></label>
												</p>
											</div>
											<div class="switch-toggle darkHeader d-flex mt-2">
												<span class="me-auto">Dark Header</span>
												<p class="onoffswitch2"><input type="radio" name="onoffswitch3" id="myonoffswitch8" class="onoffswitch2-checkbox">
													<label for="myonoffswitch8" class="onoffswitch2-label"></label>
												</p>
											</div>
										</div>
									</div>
								</div>
								<div class="swichermainleft layout-width-style">
									<h4>Layout Width Styles</h4>
									<div class="skin-body">
										<div class="switch_section">
											<div class="switch-toggle d-flex">
												<span class="me-auto">Full Width</span>
												<p class="onoffswitch2"><input type="radio" name="onoffswitch4" id="myonoffswitch9" class="onoffswitch2-checkbox" checked>
													<label for="myonoffswitch9" class="onoffswitch2-label"></label>
												</p>
											</div>
											<div class="switch-toggle d-flex mt-2">
												<span class="me-auto">Boxed</span>
												<p class="onoffswitch2"><input type="radio" name="onoffswitch4" id="myonoffswitch10" class="onoffswitch2-checkbox">
													<label for="myonoffswitch10" class="onoffswitch2-label"></label>
												</p>
											</div>
										</div>
									</div>
								</div>
								<div class="swichermainleft">
									<h4>Layout Positions</h4>
									<div class="skin-body">
										<div class="switch_section">
											<div class="switch-toggle d-flex">
												<span class="me-auto">Fixed</span>
												<p class="onoffswitch2"><input type="radio" name="onoffswitch5" id="myonoffswitch11" class="onoffswitch2-checkbox" checked>
													<label for="myonoffswitch11" class="onoffswitch2-label"></label>
												</p>
											</div>
											<div class="switch-toggle d-flex mt-2">
												<span class="me-auto">Scrollable</span>
												<p class="onoffswitch2"><input type="radio" name="onoffswitch5" id="myonoffswitch12" class="onoffswitch2-checkbox">
													<label for="myonoffswitch12" class="onoffswitch2-label"></label>
												</p>
											</div>
										</div>
									</div>
								</div>
								<div class="swichermainleft vertical-switcher">
									<h4>Sidemenu layout Styles</h4>
									<div class="skin-body">
										<div class="switch_section">
											<div class="switch-toggle d-flex">
												<span class="me-auto">Default Menu</span>
												<p class="onoffswitch2"><input type="radio" name="onoffswitch6" id="myonoffswitch13" class="onoffswitch2-checkbox default-menu" checked>
													<label for="myonoffswitch13" class="onoffswitch2-label"></label>
												</p>
											</div>
											<div class="switch-toggle d-flex mt-2">
												<span class="me-auto">Icon with Text</span>
												<p class="onoffswitch2"><input type="radio" name="onoffswitch6" id="myonoffswitch14" class="onoffswitch2-checkbox">
													<label for="myonoffswitch14" class="onoffswitch2-label"></label>
												</p>
											</div>
											<div class="switch-toggle d-flex mt-2">
												<span class="me-auto">Icon Overlay</span>
												<p class="onoffswitch2"><input type="radio" name="onoffswitch6" id="myonoffswitch15" class="onoffswitch2-checkbox">
													<label for="myonoffswitch15" class="onoffswitch2-label"></label>
												</p>
											</div>
											<div class="switch-toggle d-flex mt-2">
												<span class="me-auto">Closed Sidemenu</span>
												<p class="onoffswitch2"><input type="radio" name="onoffswitch6" id="myonoffswitch16" class="onoffswitch2-checkbox">
													<label for="myonoffswitch16" class="onoffswitch2-label"></label>
												</p>
											</div>
											<div class="switch-toggle d-flex mt-2">
												<span class="me-auto">Hover Submenu</span>
												<p class="onoffswitch2"><input type="radio" name="onoffswitch6" id="myonoffswitch17" class="onoffswitch2-checkbox">
													<label for="myonoffswitch17" class="onoffswitch2-label"></label>
												</p>
											</div>
											<div class="switch-toggle d-flex mt-2">
												<span class="me-auto">Hover Submenu Style 1</span>
												<p class="onoffswitch2"><input type="radio" name="onoffswitch6" id="myonoffswitch18" class="onoffswitch2-checkbox">
													<label for="myonoffswitch18" class="onoffswitch2-label"></label>
												</p>
											</div>
										</div>
									</div>
								</div>
								<div class="swichermainleft">
									<h4>Reset All Styles</h4>
									<div class="skin-body">
										<div class="switch_section my-4">
											<button id="resetAll" class="btn btn-danger btn-block" type="button">Reset All
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>        <!-- SWITCHER -->

		<!--- GLOBAL LOADER -->
		<div id="global-loader" >
			<img src="{{ asset('assets/build/assets/img/loader.svg') }}" class="loader-img" alt="loader">
		</div>
		<!--- END GLOBAL LOADER -->

        <!-- PAGE -->
		<div class="page">

            <!-- MAIN-HEADER -->
            @include('components.admin.header')
            <!-- END MAIN-HEADER -->

            <!-- MAIN-SIDEBAR -->
            @include('components.admin.sidebar')
            <!-- END MAIN-SIDEBAR -->

            <!-- MAIN-CONTENT -->
            @yield('content')
            <!-- END MAIN-CONTENT -->

            <!-- MAIN-FOOTER -->
            @include('components.admin.footer')
            <!-- END MAIN-FOOTER -->


            <!-- RIGHT-SIDEBAR -->
            <div class="sidebar sidebar-right sidebar-animate">
				<div class="sidebar-icon">
					<a href="javascript:void(0);" class="text-white fs-18 mt-1 d-block" data-bs-toggle="sidebar-right" data-bs-target=".sidebar-right"><i class="fe fe-x"></i></a>
				</div>
				<div class="sidebar-body">
					<h5 class="text-white">Settings</h5>
					<div class="d-flex p-2">
						<span class="custom-switch-description">Notifications</span>
						<label class="custom-switch ms-auto">
							<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input" checked>
							<span class="custom-switch-indicator"></span>
						</label>
					</div>
					<div class="d-flex p-2 border-top">
						<span class="custom-switch-description">Show your Emails</span>
						<label class="custom-switch ms-auto">
							<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input" checked>
							<span class="custom-switch-indicator"></span>
						</label>
					</div>
					<div class="d-flex p-2 border-top">
						<span class="custom-switch-description">System Logs</span>
						<label class="custom-switch ms-auto">
							<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input">
							<span class="custom-switch-indicator"></span>
						</label>
					</div>
					<div class="d-flex p-2 border-top">
						<span class="custom-switch-description">Error Reporting</span>
						<label class="custom-switch ms-auto">
							<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input" checked>
							<span class="custom-switch-indicator"></span>
						</label>
					</div>
					<div class="d-flex p-2 border-top">
						<span class="custom-switch-description">Show recent activity</span>
						<label class="custom-switch ms-auto">
							<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input">
							<span class="custom-switch-indicator"></span>
						</label>
					</div>
					<div class="d-flex p-2 mb-1 border-top">
						<span class="custom-switch-description">Allow Data Collection</span>
						<label class="custom-switch ms-auto">
							<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input" checked>
							<span class="custom-switch-indicator"></span>
						</label>
					</div>
					<h5 class="text-white">Overview</h5>
					<div class="p-3">
						<div class="main-traffic-detail-item">
							<div>
								<span>Profits</span> <span>76%</span>
							</div>
							<div class="progress ht-7">
								<div aria-valuemax="100" aria-valuemin="0" aria-valuenow="70" class="progress-bar ht-7 progress-bar-xs wd-75p" role="progressbar"></div>
							</div><!-- progress -->
						</div>
						<div class="main-traffic-detail-item">
							<div>
								<span>Balance</span> <span>65%</span>
							</div>
							<div class="progress ht-7">
								<div aria-valuemax="100" aria-valuemin="0" aria-valuenow="65" class="progress-bar ht-7 progress-bar-xs bg-secondary wd-65p" role="progressbar"></div>
							</div><!-- progress -->
						</div>
						<div class="main-traffic-detail-item">
							<div>
								<span>Earnings</span> <span>87%</span>
							</div>
							<div class="progress ht-7">
								<div aria-valuemax="100" aria-valuemin="0" aria-valuenow="70" class="progress-bar ht-7 progress-bar-xs bg-success wd-70p" role="progressbar"></div>
							</div><!-- progress -->
						</div>
						<div class="main-traffic-detail-item">
							<div>
								<span>Customers</span> <span>55%</span>
							</div>
							<div class="progress ht-7">
								<div aria-valuemax="100" aria-valuemin="0" aria-valuenow="55" class="progress-bar ht-7 progress-bar-xs bg-info wd-55p" role="progressbar"></div>
							</div><!-- progress -->
						</div>
						<div class="main-traffic-detail-item">
							<div>
								<span>Total Likes</span> <span>62%</span>
							</div>
							<div class="progress ht-7">
								<div aria-valuemax="100" aria-valuemin="0" aria-valuenow="62" class="progress-bar ht-7 progress-bar-xs bg-warning wd-65p" role="progressbar"></div>
							</div><!-- progress -->
						</div>
					</div>
				</div>
			</div>
			           <!-- END RIGHT-SIDEBAR -->


            <!-- COUNTRY SELECTOR MODAL  -->
			<div class="modal fade" id="country-selector">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
						<div class="modal-header border-bottom">
							<h6 class="modal-title">Choose Country</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
						</div>
						<div class="modal-body">
							<ul class="row p-3">
								<li class="col-lg-6 mb-2">
									<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block active">
										<span class="country-selector"><img alt="" src="{{ asset('assets/build/assets/img/flags/us_flag.jpg') }}" class="me-3 language"></span>Usa
									</a>
								</li>
								<li class="col-lg-6 mb-2">
									<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block">
										<span class="country-selector"><img alt="" src="{{ asset('assets/build/assets/img/flags/italy_flag.jpg') }}" class="me-3 language"></span>Italy
									</a>
								</li>
								<li class="col-lg-6 mb-2">
									<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block">
										<span class="country-selector"><img alt="" src="{{ asset('assets/build/assets/img/flags/spain_flag.jpg') }}" class="me-3 language"></span>Spain
									</a>
								</li>
								<li class="col-lg-6 mb-2">
									<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block">
										<span class="country-selector"><img alt="" src="{{ asset('assets/build/assets/img/flags/india_flag.jpg') }}" class="me-3 language"></span>India
									</a>
								</li>
								<li class="col-lg-6 mb-2">
									<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block">
										<span class="country-selector"><img alt="" src="{{ asset('assets/build/assets/img/flags/french_flag.jpg') }}" class="me-3 language"></span>France
									</a>
								</li>
								<li class="col-lg-6 mb-2">
									<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block">
										<span class="country-selector"><img alt="" src="{{ asset('assets/build/assets/img/flags/mexico_flag.jpg') }}" class="me-3 language"></span>Mexico
									</a>
								</li>
								<li class="col-lg-6 mb-2">
									<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block">
										<span class="country-selector"><img alt="" src="{{ asset('assets/build/assets/img/flags/poland_flag.jpg') }}" class="me-3 language"></span>Poland
									</a>
								</li>
								<li class="col-lg-6 mb-2">
									<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block">
										<span class="country-selector"><img alt="" src="{{ asset('assets/build/assets/img/flags/austria_flag.jpg') }}" class="me-3 language"></span>Austria
									</a>
								</li>
								<li class="col-lg-6 mb-2">
									<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block">
										<span class="country-selector"><img alt="" src="{{ asset('assets/build/assets/img/flags/russia_flag.jpg') }}" class="me-3 language"></span>Russia
									</a>
								</li>
								<li class="col-lg-6 mb-2">
									<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block">
										<span class="country-selector"><img alt="" src="{{ asset('assets/build/assets/img/flags/germany_flag.jpg') }}" class="me-3 language"></span>Germany
									</a>
								</li>
								<li class="col-lg-6 mb-2">
									<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block">
										<span class="country-selector"><img alt="" src="{{ asset('assets/build/assets/img/flags/argentina_flag.jpg') }}" class="me-3 language"></span>Argentina
									</a>
								</li>
								<li class="col-lg-6 mb-2">
									<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block">
										<span class="country-selector"><img alt="" src="{{ asset('assets/build/assets/img/flags/uae_flag.jpg') }}" class="me-3 language"></span>U.A.E
									</a>
								</li>
								<li class="col-lg-6 mb-2">
									<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block">
										<span class="country-selector"><img alt="" src="{{ asset('assets/build/assets/img/flags/malaysia_flag.jpg') }}" class="me-3 language"></span>Malaysia
									</a>
								</li>
								<li class="col-lg-6 mb-2">
									<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block">
										<span class="country-selector"><img alt="" src="{{ asset('assets/build/assets/img/flags/canada_flag.jpg') }}" class="me-3 language"></span>Canada
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			            <!-- END COUNTRY SELECTOR MODAL  -->


		</div>
        <!-- END PAGE-->

        <!-- SCRIPTS -->
        <!-- BACK TO TOP -->
		<a href="#top" id="back-to-top"><i class="fe fe-arrow-up"></i></a>

		<!-- JQUERY JS -->
		<script src="{{ asset('assets/build/assets/plugins/jquery/jquery.min.js') }}"></script>

		<!-- BOOTSTRAP JS -->
		<script src="{{ asset('assets/build/assets/plugins/bootstrap/js/popper.min.js') }}"></script>
		<script src="{{ asset('assets/build/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/build/assets/plugins/datatable/js/buttons.bootstrap5.min.js') }}"></script>

		<!-- SELECT2 JS -->
		<script src="{{ asset('assets/build/assets/plugins/select2/js/select2.min.js') }}"></script>

		<!-- PERFECT-SCROLLBAR JS  -->
		<script src="{{ asset('assets/build/assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
		<script src="{{ asset('assets/build/assets/plugins/perfect-scrollbar/pscroll1.js') }}"></script>

		<!-- SIDEMENU JS -->
		<script src="{{ asset('assets/build/assets/plugins/sidemenu/sidemenu.js') }}"></script>

		<!-- SIDEBAR JS -->
		<script src="{{ asset('assets/build/assets/plugins/sidebar/sidebar.js') }}"></script>


		<!-- INTERNAL DATA TABLES JS -->
		<script src="{{ asset('assets/build/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
		<script src="{{ asset('assets/build/assets/plugins/datatable/js/dataTables.bootstrap5.js') }}"></script>
		<script src="{{ asset('assets/build/assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>

		<!-- APEX CHARTS JS -->
		<script src="{{ asset('assets/build/assets/plugins/apexcharts/apexcharts.js') }}"></script>

		<!-- INTERNAL DASHBOARD JS -->
		<link rel="modulepreload" href="{{ asset('assets/build/assets/index.d45bf003.js') }}" /><script type="module" src="{{ asset('assets/build/assets/index.d45bf003.js') }}"></script>

		<!-- CHART-CIRCLE JS-->
		<script src="{{ asset('assets/build/assets/plugins/circle-progress/circle-progress.min.js') }}"></script>


        <!-- STICKY JS-->
        <script src="{{ asset('assets/build/assets/sticky.js') }}"></script>

        <!-- APP JS -->
		<link rel="modulepreload" href="{{ asset('assets/build/assets/app.7e916841.js') }}" /><script type="module" src="{{ asset('assets/build/assets/app.7e916841.js') }}"></script>

        <!-- SWITCHER JS -->
        <link rel="modulepreload" href="{{ asset('assets/build/assets/switcher.e3d4733f.js') }}" /><script type="module" src="{{ asset('assets/build/assets/switcher.e3d4733f.js') }}"></script>
        <!-- END SCRIPTS -->

         <!-- INTERNAL DATA TABLE JS -->
         <script src="{{ asset('assets/build/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
         <script src="{{ asset('assets/build/assets/plugins/datatable/js/dataTables.bootstrap5.js') }}"></script>
         <script src="{{ asset('assets/build/assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
         <script src="{{ asset('assets/build/assets/plugins/datatable/js/buttons.bootstrap5.min.js') }}"></script>
         <script src="{{ asset('assets/build/assets/plugins/datatable/js/jszip.min.js') }}"></script>
         <script src="{{ asset('assets/build/assets/plugins/datatable/pdfmake/pdfmake.min.js') }}"></script>
         <script src="{{ asset('assets/build/assets/plugins/datatable/pdfmake/vfs_fonts.js') }}"></script>
         <script src="{{ asset('assets/build/assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
         <script src="{{ asset('assets/build/assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
         <script src="{{ asset('assets/build/assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
         <script src="{{ asset('assets/build/assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
         <script src="{{ asset('assets/build/assets/plugins/datatable/responsive.bootstrap5.min.js') }}"></script>
         <link rel="modulepreload" href="{{ asset('assets/build/assets/table-data.a7b3fed9.js') }}" /><script type="module" src="{{ asset('assets/build/assets/table-data.a7b3fed9.js') }}"></script>

	</body>

<!-- Mirrored from laravelui.spruko.com/dashplex/index by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 27 Feb 2025 01:25:33 GMT -->
</html>
