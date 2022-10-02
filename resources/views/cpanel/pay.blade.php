@extends('cpanel.layouts.main')
@section('title', 'Phương thức thanh toán: '.@$reCharge->title)
@section('content')
    <div class="container-fluid">
        @include('cpanel.layouts.breadcrumb')
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-5">
                <div class="card border border-primary">
                    <div class="card-header bg-transparent border-primary">
                        <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Phương thức 1</h5>
                    </div>
                    <div class="card-body">
                        <h2 class="card-title"><span class="badge rounded-pill badge-soft-primary wallet-price">Chuyển khoản trực tiếp</span></h2>
                        <p class="card-text badge badge-soft-danger">*Lưu ý: Giao dịch thường được xử lý trong khoảng 5 phút đến 12 giờ.</p>
                        <a href="{{ route('admin.createTransaction', ['type='.config('typepay.directrecharge'), 'key='.@$reCharge->encodeRecharge()]) }}" class="btn btn-primary waves-effect waves-light btn-full">Thanh toán</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card border border-primary">
                    <div class="card-header bg-transparent border-primary">
                        <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Phương thức 2</h5>
                    </div>
                    <div class="card-body">
                        <h2 class="card-title"><span class="badge rounded-pill badge-soft-primary wallet-price">Thanh toán qua MOMO</span></h2>
                        <p class="card-text badge badge-soft-danger">*Lưu ý: Tiền sẽ được cộng trực tiếp vào tài khoản khi hoàn thành.</p>
                        <a href="#" class="btn btn-primary waves-effect waves-light btn-full">Thanh toán</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-1"></div>
        </div>
        <!-- end row -->
    </div>
@endsection
