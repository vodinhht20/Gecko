<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Admin Gecko | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <link rel="shortcut icon" href="{{ asset('/') }}/assets/images/favicon.ico">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('cpanel.layouts.style')
    @yield('style')
</head>

<body data-topbar="dark">
    @include('sweetalert::alert')
    <div id="layout-wrapper">
        @include('cpanel.layouts.header')
        @include('cpanel.layouts.sidebar')
        <div class="main-content">
            <div class="page-content">
                @yield('content')
            </div>
            @include('cpanel.layouts.footer')
        </div>
    </div>

    <div class="right-bar">
        <div data-simplebar class="h-100">
            <div class="rightbar-title d-flex align-items-center px-3 py-4">

                <h5 class="m-0 me-2">Settings</h5>

                <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
                    <i class="mdi mdi-close noti-icon"></i>
                </a>
            </div>

            <!-- Settings -->
            <hr class="mt-0" />
            <h6 class="text-center mb-0">Choose Layouts</h6>

            <div class="p-4">
                <div class="mb-2">
                    <img src="{{ asset('/') }}/assets/images/layouts/layout-1.jpg" class="img-fluid img-thumbnail" alt="layout-1">
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input theme-choice" type="checkbox" id="light-mode-switch" checked>
                    <label class="form-check-label" for="light-mode-switch">Light Mode</label>
                </div>

                <div class="mb-2">
                    <img src="{{ asset('/') }}/assets/images/layouts/layout-2.jpg" class="img-fluid img-thumbnail" alt="layout-2">
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input theme-choice" type="checkbox" id="dark-mode-switch"
                        data-bsStyle="{{ asset('/') }}/assets/css/bootstrap-dark.min.css" data-appStyle="{{ asset('/') }}/assets/css/app-dark.min.css">
                    <label class="form-check-label" for="dark-mode-switch">Dark Mode</label>
                </div>

                <div class="mb-2">
                    <img src="{{ asset('/') }}/assets/images/layouts/layout-3.jpg" class="img-fluid img-thumbnail" alt="layout-3">
                </div>
                <div class="form-check form-switch mb-5">
                    <input class="form-check-input theme-choice" type="checkbox" id="rtl-mode-switch"
                        data-appStyle="{{ asset('/') }}/assets/css/app-rtl.min.css">
                    <label class="form-check-label" for="rtl-mode-switch">RTL Mode</label>
                </div>


            </div>

        </div> <!-- end slimscroll-menu-->
    </div>
    <div class="rightbar-overlay"></div>
    @include('cpanel.layouts.script')
    @yield('script')
</body>

</html>
