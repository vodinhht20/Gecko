@extends('cpanel.layouts.main')
@section('title', 'Lịch sử giao dịch')
@section('content')
    <div class="container-fluid">
        @include('cpanel.layouts.breadcrumb')
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-centered mb-0 align-middle table-hover table-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>#STT</th>
                                        <th>Số tiền</th>
                                        <th>Mã giao dịch</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày khởi tạo</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    @foreach ($historyOrders as $i => $historyOrder)
                                        <tr>
                                            <td>
                                                <h6 class="mb-0">{{ ++$i }}</h6>
                                            </td>
                                            <td><span
                                                    class="badge badge-soft-info">{{ money(@$historyOrder->recharge_pack->value, currency('VND2'))->toString() }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-soft-dark copyvalue key-order"
                                                    data-id={{ @$historyOrder->code }}> {{ @$historyOrder->code }} <i
                                                        class="ri-file-copy-line copyvalue key-order"></i> </span>
                                            </td>
                                            <td>
                                                {!! @$historyOrder->status() !!}
                                            </td>
                                            <td>
                                                {{ @$historyOrder->created_at }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody><!-- end tbody -->
                            </table> <!-- end table -->
                            <div class="text-center d-flex justify-content-center mt-3">{{ $historyOrders->links() }}</div>
                        </div>
                    </div><!-- end card -->
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
@endsection
@section('script')
    <script>
        $('.copyvalue.key-order').click(function() {
            navigator.clipboard.writeText($(this).data('id'));
            alertCopy($(this).data('id'));
        });
    </script>
@endsection
