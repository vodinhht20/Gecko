@extends('cpanel.layouts.main')
@section('title', 'Chỉnh sửa khách hàng')
@section('content')
    <div class="container-fluid">
        @include('cpanel.layouts.breadcrumb')
        <div class="row">
            <div class="col-xl-3"></div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Đang chỉnh sửa: {{ @$user->name }}</h4>
                        <span class="badge badge-soft-info">*Bỏ trống các trường không cần update</span>
                        <form class="needs-validation" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="validationCustom01" class="form-label">Họ tên khách hàng</label>
                                        <input type="text" class="form-control" id="validationCustom01"
                                            placeholder="Họ tên khách hàng" name="name" value="{{ @$user->name }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="validationCustom02" class="form-label">Email</label>
                                        <input type="text" class="form-control" id="validationCustom02"
                                            placeholder="Email" readonly name="email" value="{{ @$user->email }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="validationCustom04" class="form-label">Thay đổi mật khẩu</label>
                                        <input type="password" class="form-control" id="validationCustom04"
                                            placeholder="Nhập mật khẩu mới" name="password">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="validationCustom05" class="form-label">Cash</label>
                                        <input type="text" class="form-control" name="cash" id="validationCustom05"
                                            placeholder="Số dư" value="{{ @$user->cash }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="validationCustom05" class="form-label">Quyền</label>
                                        <select class="select2 form-control select2-multiple" multiple="multiple"
                                            name="roles[]" data-placeholder="Chọn quyền...">
                                            <optgroup label="Danh sách quyền">
                                                <option value="">Thành viên</option>
                                                <option value="Mod">mod</option>
                                                <option value="admin">Admin</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-primary btn-full" type="submit">Cập nhật</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end card -->
            </div> <!-- end col -->
            <div class="col-xl-3"></div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
@endsection