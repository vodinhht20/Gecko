@extends('cpanel.layouts.main')
@section('title', 'Chuyển khoản trực tiếp')
@section('content')
    <div class="container-fluid">
        @include('cpanel.layouts.breadcrumb')
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-5">
                <div class="card border border-primary">
                    <div class="card-header bg-transparent border-primary">
                        <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Thanh toán đơn hàng:
                            {{ @$reCharge->title }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text badge badge-soft-success">Ngân hàng:</p>
                        <h2 class="card-title"><span
                                class="badge rounded-pill badge-soft-primary wallet-price">VietComBank</span></h2>
                        <p class="card-text badge badge-soft-success">Người nhận:</p>
                        <h2 class="card-title"><span class="badge rounded-pill badge-soft-primary wallet-price">TRAN
                                TIEN</span></h2>
                        <p class="card-text badge badge-soft-success">Nội dung chuyển khoản:</p>
                        <h2 class="card-title"><span
                                class="badge rounded-pill badge-soft-primary wallet-price">{{ @$KeyOrder }} <i
                                    class="ri-file-copy-line copyvalue key-order"></i></span></h2>
                        <p class="card-text badge badge-soft-success">Số tiền:</p>
                        <h2 class="card-title"><span
                                class="badge rounded-pill badge-soft-primary wallet-price">{{ money(@$reCharge->value, currency('VND2'))->toString() }}
                                <i class="ri-file-copy-line copyvalue price"></i></span></h2>
                        <p class="card-text badge badge-soft-danger">*Lưu ý: Giao dịch thường được xử lý trong khoảng 5 phút
                            đến 12 giờ.</p>
                        <a href="#" class="btn btn-primary waves-effect waves-light btn-full confirm-pay">Xác nhận đã thanh
                            toán</a>
                        <a href="#" class="btn btn-dark waves-effect waves-light btn-full mt-2 cancel-pay">Hủy giao
                            dịch</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3"></div>
        </div>
        <!-- end row -->
    </div>
@endsection
@section('script')
    <script>
        $('.copyvalue.key-order').click(function() {
            navigator.clipboard.writeText("{{ @$KeyOrder }}");
            alertCopy("{{ @$KeyOrder }}");
        });
        $('.copyvalue.price').click(function() {
            navigator.clipboard.writeText("{{ @$reCharge->value }}");
            alertCopy("{{ money(@$reCharge->value, currency('VND2'))->toString() }}");
        });

        $('.cancel-pay').click(function() {
            Swal.fire({
                title: 'Thông báo?',
                html: "<p>Bạn có muốn hủy giao dịch {{ money(@$reCharge->value, currency('VND2'))->toString() }} không!</p><p>*Chú ý: Nếu bạn đã thanh toán trước đó tiền sẽ không được hoàn lại</p><p>- Mỗi ngày chỉ được hủy tối đa 5 giao dịch</p>",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Hủy giao dịch',
                cancelButtonText: 'Đóng'
            }).then((result) => {
                let data = {
                    'id': {{ $idcancel }},
                    _token: "{{ csrf_token() }}"
                };
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        contentType: "application/json",
                        url: "{{ route('admin.cancelPay') }}",
                        data: JSON.stringify(data),
                        dataType: 'json',
                        cache: false,
                        timeout: 600000,
                        success: function(data) {
                            Swal.fire({
                                text: `${data.message}`,
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Đóng'
                            }).then(() => {
                                window.location.href = '{{ route('admin.rechargePack') }}';
                            })
                        },
                        error: function(e) {
                            e = JSON.parse(e.responseText);
                            Swal.fire(
                                'Lỗi!',
                                `${e.message}`,
                                'error'
                            )
                        }
                    });
                }
            })
        })

        $('.confirm-pay').click(function() {
            Swal.fire({
                title: "Xác nhận thanh toán",
                html: "Số tiền: <span class='swal-value'>{{ money(@$reCharge->value, currency('VND2'))->toString() }}</span> <br> Mã giao dịch: <span class='swal-value'>{{ @$KeyOrder }}</span>",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xác nhận đã thanh toán',
                cancelButtonText: 'Đóng'
            }).then((result) => {
                let data = {
                    'id': {{ $idcancel }},
                    _token: "{{ csrf_token() }}"
                };
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        contentType: "application/json",
                        url: "{{ route('admin.sendTransaction') }}",
                        data: JSON.stringify(data),
                        dataType: 'json',
                        cache: false,
                        timeout: 600000,
                        success: function(data) {
                            Swal.fire({
                                title: 'Thanh toán đã được ghi nhận',
                                html: `${data.message}`,
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Đóng'
                            }).then(() => {
                                window.location.href = '{{ route('admin.wallet') }}';
                            })
                        },
                        error: function(e) {
                            e = JSON.parse(e.responseText);
                            Swal.fire(
                                'Lỗi!',
                                `${e.message}`,
                                'error'
                            )
                        }
                    });
                }
            })
        })
    </script>
@endsection
