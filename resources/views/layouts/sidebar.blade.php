
<body>

<div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
                <a href="{{  route('dashboard') }}" class="logo">
                    <img
                        src="{{ asset('logo/Kushal_mobile.png')}}"
                        alt="navbar brand"
                        class="navbar-brand"
                        height="50"
                    />
                </a>
                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar">
                        <i class="gg-menu-right"></i>
                    </button>
                    <button class="btn btn-toggle sidenav-toggler">
                        <i class="gg-menu-left"></i>
                    </button>
                </div>
                <button class="topbar-toggler more">
                    <i class="gg-more-vertical-alt"></i>
                </button>
            </div>
            <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
            <div class="sidebar-content">
                <ul class="nav nav-secondary" >
                    <li class="nav-item">
                        <a
                            href="{{  route('dashboard') }}"
                            class="collapsed"
                        >
                            <i class="fas fa-home"></i>
                            <p>Dashboard</p>

                        </a>

                    </li>
                    <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                        <h4 class="text-section">Modules</h4>
                    </li>

                    <li class="nav-item {{ request()->segment(2) == 'user' || request()->segment(2) == 'role' || request()->segment(2) == 'brand' || request()->segment(2) == 'product' || request()->segment(2) == 'dealer' || request()->segment(2) == 'customer' || request()->segment(1) == 'financeMaster' ? 'active' : '' }}">
                        <a data-bs-toggle="collapse" href="#masters">
                            <i class="fas fa-table"></i>
                            <p>Masters</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="masters">
                            <ul class="nav nav-collapse">

                                @can('sidebar-user')
                                    <li class="nav-item {{ request()->segment(2) == 'user' ? 'active' : '' }}">
                                        <a href="{{ route('admin.user.index') }}">
                                            <i class="fas fa-users"></i>
                                            <p>User</p>

                                        </a>
                                    </li>
                                @endcan
                                    @can('sidebar-role')
                                        <li class="nav-item {{ request()->segment(2) == 'role' ? 'active' : '' }}">
                                            <a href="{{ route('admin.role.index') }}">
                                                <i class="fas fa-user"></i>
                                                <p>Role</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('sidebar-brand')
                                        <li class="nav-item {{ request()->segment(2) == 'brand' ? 'active' : '' }}">
                                            <a href="{{ route('admin.brand.index') }}">
                                                <i class="fas fa-tags"></i>
                                                <p>Brand</p>

                                            </a>
                                        </li>
                                    @endcan
                                    @can('sidebar-product')
                                        <li class="nav-item {{ request()->segment(2) == 'product' ? 'active' : '' }}">
                                            <a href="{{ route('admin.product.index') }}">
                                                <i class="fas fa-box-open"></i>
                                                <p>Product</p>

                                            </a>
                                        </li>
                                    @endcan
                                    @can('sidebar-dealer')
                                        <li class="nav-item {{ request()->segment(2) == 'dealer' ? 'active' : '' }}">
                                            <a href="{{ route('admin.dealer.index') }}">
                                                <i class="fas fa-user-tie"></i>
                                                <p>Dealer</p>

                                            </a>
                                        </li>
                                    @endcan
                                    @can('sidebar-customer')
                                        <li class="nav-item {{ request()->segment(2) == 'customer' ? 'active' : '' }}">
                                            <a href="{{ route('admin.customer.index') }}">
                                                <i class="fas fa-user-friends"></i>
                                                <p>Customer</p>

                                            </a>
                                        </li>
                                    @endcan
                                    @can('sidebar-finance-master')
                                        <li class="nav-item {{ request()->segment(2) == 'financeMaster' ? 'active' : '' }}">
                                            <a href="{{ route('admin.financeMaster.index') }}">
                                                <i class="fas fa-wallet"></i>
                                                <p>Finance Master</p>

                                            </a>
                                        </li>
                                    @endcan
                            </ul>
                        </div>
                    </li>


                    @can('sidebar-permission')
                        <li class="nav-item {{ request()->segment(2) == 'permission' ? 'active' : '' }}">
                            <a href="{{ route('admin.permission.index') }}">
                                <i class="fas fa-user-check"></i>
                                <p>Permission</p>

                            </a>
                        </li>
                    @endcan



                    @can('sidebar-purchase')
                        <li class="nav-item {{ request()->segment(2) == 'purchase' ? 'active' : '' }}">
                            <a href="{{ route('admin.purchase.index') }}">
                                <i class="fas fa-shopping-bag"></i>
                                <p>Purchase</p>

                            </a>
                        </li>
                    @endcan
                    <li class="nav-item {{ request()->routeIs('admin.stock.index','admin.stock.create','admin.stock.edit','admin.stock.update') ? 'active' : '' }}">
                        <a href="{{ route('admin.stock.index') }}">
                            <i class="fas fa-shopping-bag"></i>
                            <p>Stock</p>
                        </a>
                    </li>
                @can('sidebar-sale')
                        <li class="nav-item {{ request()->segment(2) == 'sale' ? 'active' : '' }}">
                            <a href="{{ route('admin.sale.index') }}">
                                <i class="fas fa-rupee-sign"></i>
                                <p>Sales</p>

                            </a>
                        </li>
                    @endcan
                    {{--                @can('sidebar-deduction')--}}
                    <li class="nav-item {{ request()->segment(2) == 'deduction' ? 'active' : '' }}">
                        <a href="{{ route('admin.deduction.index') }}">
                            <i class="fas fa-percent"></i>
                            <p>Deduction</p>

                        </a>
                    </li>
                    {{--                @endcan--}}
                    @can('sidebar-transaction')
                        <li class="nav-item {{ request()->segment(2) == 'transaction' ? 'active' : '' }}">
                            <a href="{{ route('admin.transaction.index') }}">
                                <i class="fas fa-exchange-alt"></i>
                                <p>Transacation</p>

                            </a>
                        </li>
                    @endcan

                    <li class="nav-item {{ request()->segment(2) == 'finance' ? 'active' : '' }}">
                        <a href="{{ route('admin.finance.index') }}">
                            <i class="fas fa-wallet"></i>
                            <p>Finance</p>

                        </a>
                    </li>
                    @can('sidebar-notes')
                        <li class="nav-item {{ request()->segment(2) == 'daily-notes' ? 'active' : '' }}">
                            <a href="{{ route('admin.daily-notes.index') }}">
                                <i class="fas fa-sticky-note"></i>
                                <p>Notes</p>

                            </a>
                        </li>
                    @endcan
                    <li class="nav-item {{ request()->segment(2) == 'return' ? 'active' : '' }}">
                        <a href="{{ route('admin.return.index') }}">
                            <i class="fas fa-sticky-note"></i>
                            <p>Return</p>

                        </a>
                    </li>
                    <li class="nav-item {{ request()->segment(2) == 'report' ? 'active' : '' }}">
                        <a data-bs-toggle="collapse" href="#tables">
                            <i class="fas fa-table"></i>
                            <p>Reports</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="tables">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route('admin.report.stock') }}">
                                        <span class="sub-item">Stock Report</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.report.imei') }}">
                                        <span class="sub-item">Imei Report</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.report.sale') }}">
                                        <span class="sub-item">Sale Report</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.report.payment') }}">
                                        <span class="sub-item">Payment Report</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
