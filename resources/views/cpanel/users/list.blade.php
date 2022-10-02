@extends('cpanel.layouts.main')
@section('title', 'Danh sách khách hàng')
@section('content')
    <div class="container-fluid">
        @include('cpanel.layouts.breadcrumb')
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>#ID</th>
                                        <th>Họ và tên</th>
                                        <th>Avatar</th>
                                        <th>Email</th>
                                        <th>Trạng thái</th>
                                        <th>Số dư</th>
                                        <th>Quyền</th>
                                        <th colspan="1">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $i => $user)
                                        <tr>
                                            <th scope="row">{{ @++$i }}</th>
                                            <th scope="row"><span
                                                    class="badge badge-soft-info">#{{ @$user->id }}</span></th>
                                            <td>{{ @$user->name }}</td>
                                            <td>{{ @$user->avatar }}</td>
                                            <td>{{ @$user->email }}</td>
                                            <td><span data-name="{{ @$user->id }}">{!! @$user->status(@$user->id) !!}</span></td>
                                            <td><span
                                                    class="badge badge-soft-info">{{ money(@$user->cash, currency('Gecko'))->toString() }}</span>
                                            </td>
                                            <td>
                                                @if ($user->hasRole([config('roles.admin')]))
                                                <span class="badge badge-soft-danger">Admin</span>
                                                @endif
                                                @if ($user->hasRole([config('roles.mod')]))
                                                <span class="badge badge-soft-success">Mod</span>
                                                @endif
                                                <span class="badge badge-soft-dark">Thành viên</span>
                                            </td>
                                            <td><a href="{{ route('admin.users.editUser', @$user->id) }}" type="button"
                                                    class="btn btn-outline-primary waves-effect waves-light"><i
                                                        class="ri-edit-2-fill"></i></a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="text-center d-flex justify-content-center mt-3">{{ $users->links() }}</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('.active-user').click(function() {
            Swal.fire({
                title: 'Thông báo?',
                text: "Bạn có muốn thay đổi trạng thái tài khoản này không!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                let id = $(this).data('id');
                let data = {
                    'id': id,
                    _token: "{{ csrf_token() }}"
                };
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        contentType: "application/json",
                        url: "{{ route('admin.users.changeStatus') }}",
                        data: JSON.stringify(data),
                        dataType: 'json',
                        cache: false,
                        timeout: 600000,
                        success: function(data) {
                            if (data.status) {
                                $('.active-user[data-id=' + id + ']').addClass('bg-success');
                                $('.active-user[data-id=' + id + ']').removeClass('bg-danger');
                            }else{
                                $('.active-user[data-id=' + id + ']').addClass('bg-danger');
                            }
                            Swal.fire(
                                '',
                                `${data.message}`,
                                'success'
                            )
                        },
                        error: function(e) {
                            Swal.fire(
                                'Lỗi!',
                                `${e.responseText}`,
                                'error'
                            )
                        }
                    });
                }
            })
        })
    </script>
@endsection
