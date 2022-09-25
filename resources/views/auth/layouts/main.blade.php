<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Gecko | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <link rel="shortcut icon" href="{{ asset('/') }}assets/images/favicon.ico">
    @include('auth.layouts.style')
</head>

<body class="auth-body-bg">
    @include('sweetalert::alert')
    {{-- <div class="bg-overlay"></div> --}}
    <div class="wrapper-page">
        @yield('content')
    </div>
    @include('auth.layouts.script')
</body>

</html>
