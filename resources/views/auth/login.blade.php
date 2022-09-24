@extends('auth.layouts.main')
@section('title', ' Đăng nhập')
@section('content')
    <div class="container-fluid p-0">
        <div class="card">
            <div class="card-body">

                <div class="text-center mt-4">
                    <div class="mb-3">
                        <a href="{{ route('home') }}" class="auth-logo">
                            <img src="{{ asset('/') }}assets/images/logo-dark.png" height="30" class="logo-dark mx-auto"
                                alt="">
                            <img src="{{ asset('/') }}assets/images/logo-light.png" height="30"
                                class="logo-light mx-auto" alt="">
                        </a>
                    </div>
                </div>

                <h4 class="text-muted text-center font-size-18"><b>Đăng nhập</b></h4>

                <div class="p-3">
                    @if (Session::has('message.error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ Session::get('message.error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form class="form-horizontal mt-3" method="post">
                        @csrf
                        <div class="form-group mb-3 row">
                            <div class="col-12">
                                <input class="form-control" value="{{ old('email') }}" type="email" name="email" placeholder="Email">
                            </div>
                        </div>

                        <div class="form-group mb-3 row">
                            <div class="col-12">
                                <input class="form-control" value="{{ old('password') }}" type="password" name="password" placeholder="Password">
                            </div>
                        </div>

                        <div class="form-group mb-3 text-center row mt-3 pt-1">
                            <div class="col-12">
                                <button class="btn btn-info w-100 waves-effect waves-light" type="submit">Đăng
                                    nhập</button>
                            </div>
                        </div>

                        <div class="form-group mb-0 row mt-2">
                            <div class="col-sm-7 mt-3">
                                <a href="{{ route('resetPass') }}" class="text-muted"><i class="mdi mdi-lock"></i> Quên mật
                                    khẩu?</a>
                            </div>
                            <div class="col-sm-5 mt-3">
                                <a href="{{ route('signup') }}" class="text-muted"><i class="mdi mdi-account-circle"></i>
                                    Tạo tài khoản mới</a>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- end -->
            </div>
            <!-- end cardbody -->
        </div>
        <!-- end card -->
    </div>
@endsection
