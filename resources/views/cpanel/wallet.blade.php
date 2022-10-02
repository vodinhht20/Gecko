@extends('cpanel.layouts.main')
@section('title', 'Ví của bạn')
@section('content')
    <div class="container-fluid">
        @include('cpanel.layouts.breadcrumb')
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-6">
                <div class="card border border-primary">
                    <div class="card-header bg-transparent border-primary">
                        <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Số dư</h5>
                    </div>
                    <div class="card-body">
                        <h2 class="card-title"><span class="badge rounded-pill badge-soft-primary wallet-price">{{ money(@$surplus->cash, currency('Gecko'))->toString() }}</span></h2>
                        <p class="card-text">Bạn có thể sử dụng tín dụng để trải nghiệm tất cả sản phẩm trên website.</p>
                        <a href="{{ route('admin.rechargePack') }}" class="btn btn-primary waves-effect waves-light btn-full">Nạp tiền</a>
                        <a href="{{ route('admin.historyPay') }}" class="btn btn-light waves-effect waves-light btn-full mt-2">Lịch sử nạp tiền</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3"></div>
        </div>
        <!-- end row -->
    </div>
@endsection
