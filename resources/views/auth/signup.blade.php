@extends('auth.layouts.main')
@section('title', ' Đăng ký')
@section('content')
    <div class="container-fluid p-0">
        <div class="card">
            <div class="card-body">
                <div class="text-center mt-4">
                    <div class="mb-3">
                        <a href="{{ route('home') }}" class="auth-logo">
                            <img src="{{ asset('/') }}assets/images/logo-dark.png" height="30"
                                class="logo-dark mx-auto" alt="">
                            <img src="{{ asset('/') }}assets/images/logo-light.png" height="30"
                                class="logo-light mx-auto" alt="">
                        </a>
                    </div>
                </div>
                <h4 class="text-muted text-center font-size-18"><b>Đăng ký</b></h4>
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
                                <input class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="Họ và tên">
                            </div>
                        </div>
                        <div class="form-group mb-3 row">
                            <div class="col-12">
                                <input class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group mb-3 row">
                            <div class="col-12">
                                <input class="form-control" type="password" name="password" value="{{ old('password') }}" placeholder="Mật khẩu">
                            </div>
                        </div>
                        <div class="form-group mb-3 row">
                            <div class="col-12">
                                <input class="form-control" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="Nhập lại mật khẩu">
                            </div>
                        </div>
                        <div class="form-group mb-3 row">
                            <div class="col-12">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="term_of_use" class="custom-control-input" id="customCheck1">
                                    <label class="form-label ms-1 fw-normal" for="customCheck1">Tôi chấp nhận <a
                                            href="#" class="text-muted">các điều khoản và điều kiện</a></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center row mt-3 pt-1">
                            <div class="col-12">
                                <button class="btn btn-info w-100 waves-effect waves-light" type="submit">Đăng ký</button>
                            </div>
                        </div>
                        <div class="form-group mt-2 mb-0 row">
                            <div class="col-12 mt-3 text-center">
                                <a href="{{ route('login') }}" class="text-muted">Đã có tài khoản?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
