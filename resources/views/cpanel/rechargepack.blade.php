@extends('cpanel.layouts.main')
@section('title', 'Chọn gói nạp')
@section('content')
    <div class="container-fluid">
        @include('cpanel.layouts.breadcrumb')
        <div class="row">
            @foreach ($reCharges as $reCharge)
                <div class="col-lg-3">
                    <div class="card border border-primary">
                        <div class="card-header bg-transparent border-primary">
                            <h6 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i></h6>
                        </div>
                        <div class="card-body">
                            <h2 class="card-title"><span class="badge rounded-pill badge-soft-primary recharge-title">{{ @$reCharge->title }}</span></h2>
                            <h3 class="card-title text-center"><span class="badge rounded-pill badge-soft-success">{{ money(@$reCharge->value, currency('VND2'))->toString() }}</h3>
                            <a href="{{ route('admin.pay', 'key='.@$reCharge->encodeRecharge()) }}" class="btn btn-primary waves-effect waves-light btn-full mt-3">Tạo giao dịch</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- end row -->
    </div>
@endsection
