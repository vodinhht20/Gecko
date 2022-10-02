@extends('cpanel.layouts.main')
@section('title', 'Danh sách thanh toán')
@section('content')
    <div class="container-fluid app_vue">
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
                                        <th>Khách hàng</th>
                                        <th>#ID Khách hàng</th>
                                        <th>Mã giao dịch</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày khởi tạo</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                    <tr v-for="(item, index) in listpays.data">
                                        <td>@{{ ++index }}</td>
                                        <td v-html="item.recharge_pack.value_str"></td>
                                        <td>@{{ item.users.name }}</td>
                                        <td>#@{{ item.users.id }}</td>
                                        <td>@{{ item.code }}</td>
                                        <td v-html="item.status_str"></td>
                                        <td>@{{ item.created_at }}</td>
                                        <td>
                                            <div v-if="item.status == 1">
                                                <span class="badge bg-primary confirmed-pay-admin" :data-id="item.id"
                                                    id="comfirm-pay">Xác nhận</span>
                                                <span class="badge bg-danger confirmed-pay-admin" :data-id="item.id"
                                                    id="cancel-pay">Hủy bỏ</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody><!-- end tbody -->
                            </table> <!-- end table -->

                            <div class="text-center d-flex justify-content-center mt-3" v-if="listpays.total > 0">
                                <template>
                                    <paginate :page-count="listpays.last_page" v-model="listpays.current_page"
                                        :initial-page="listpays.current_page" :click-handler="changePage"
                                        :prev-text="'‹'" :next-text="'›'" :page-link-class="'page-link'"
                                        :container-class="'pagination'" :page-class="'page-item'"
                                        :prev-link-class="'page-link'" :next-link-class="'page-link'"
                                        :prev-class="'page-item'" :next-class="'page-item'">
                                    </paginate>
                                </template>
                            </div>
                            <div class="overlay-load" style="position: fixed; z-index: 99999;"><img
                                    src="https://raw.githubusercontent.com/Codelessly/FlutterLoadingGIFs/master/packages/circular_progress_indicator.gif"
                                    alt="" style="width: 8%; pointer-events: none">
                                <p style="color: #7f6308; margin-top: 10px ">Vui lòng chờ ...</p>
                            </div>

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
    <script src="{{ asset('assets/js/vue-paginate.js') }}"></script>
    <script>
        $('.copyvalue.key-order').click(function() {
            navigator.clipboard.writeText($(this).data('id'));
            alertCopy($(this).data('id'));
        });

        Vue.component('paginate', VuejsPaginate)
        var app = new Vue({
            el: '.app_vue',
            data: {
                listpays: {!! $listPays !!}
            },
            methods: {
                changePage: (page) => {
                    // change param url
                    var urlParam = new URL(window.location);
                    urlParam.searchParams.set('page', page);
                    window.history.pushState({}, '', urlParam);
                    app.getData();
                },
                getData: () => {
                    // call api
                    $('.overlay-load').css('display', 'flex');
                    let params = location.search;
                    axios.get(`{{ route('admin.ajax-get-pay') }}${params}`)
                        .then(({
                            data
                        }) => {
                            app.listpays = data;
                            $('.overlay-load').css('display', 'none');
                        })
                },
            },
            created: function() {
                console.log('xx', this.listpays);
            }
        });

        $('.confirmed-pay-admin').click(function() {
            Swal.fire({
                text: $(this).attr('id') == "comfirm-pay" ? "Xác nhận và cộng tiền vào tài khoản users này!" : "Hủy bỏ lệnh này!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                let id = $(this).data('id');
                let data = {
                    'id': id,
                    'status': $(this).attr('id') == "comfirm-pay" ? 2 : 3,
                    _token: "{{ csrf_token() }}"
                };
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        contentType: "application/json",
                        url: "{{ route('admin.confirmation') }}",
                        data: JSON.stringify(data),
                        dataType: 'json',
                        cache: false,
                        timeout: 600000,
                        success: function(data) {
                            // call api
                            $('.overlay-load').css('display', 'flex');
                            let params = location.search;
                            axios.get(`{{ route('admin.ajax-get-pay') }}${params}`)
                                .then(({
                                    data
                                }) => {
                                    app.listpays = data;
                                    $('.overlay-load').css('display', 'none');
                                })
                            Swal.fire({
                                text: `${data.message}`,
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Đóng'
                            })
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
