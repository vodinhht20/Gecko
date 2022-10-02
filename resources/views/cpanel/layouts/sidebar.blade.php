<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!-- User details -->
        <div class="user-profile text-center mt-3">
            <div class="">
                <img src="{{ Auth::user()->avatar ?? asset('/assets/images/users/avatar-1.jpg') }}" alt=""
                    class="avatar-md rounded-circle">
            </div>
            <div class="mt-3">
                <h4 class="font-size-16 mb-1">{{ @Auth::user()->name }}</h4>
                <span class="text-muted"><i class="ri-record-circle-line align-middle font-size-14 text-success"></i>
                    Admin</span>
            </div>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="waves-effect {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="ri-dashboard-line"></i>
                        <span>Thống kê</span>
                    </a>
                </li>

                @role('admin')
                    <li>
                        <a href="{{ route('admin.listPay') }}"
                            class="waves-effect {{ Request::routeIs('admin.listPay') ? 'active' : '' }}">
                            <i class="ri-bank-card-2-fill"></i><span class="badge rounded-pill bg-success float-end">1</span>
                            <span>Danh sách thanh toán</span>
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('admin.historyPay') }}"
                            class="waves-effect {{ Request::routeIs('admin.historyPay') ? 'active' : '' }}">
                            <i class="ri-history-line"></i><span class="badge rounded-pill bg-success float-end">1</span>
                            <span>Lịch sử giao dịch</span>
                        </a>
                    </li>
                @endrole

                <li class="menu-title">Khách hàng</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-account-circle-line"></i>
                        <span>Quản lý khách hàng</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('admin.users.listUsers') }}">Danh sách khách hàng</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
