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
            <ul class="nav nav-secondary">
              <li class="nav-item active">
                <a
                  href="{{  route('dashboard') }}"
                  class="collapsed"
                >
                  <i class="fas fa-home"></i>
                  <p>Dashboard</p>
                  <span class="caret"></span>
                </a>

              </li>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Modules</h4>
              </li>
                <li class="nav-item">
                    <a  href="{{ route('admin.user.index') }}">
                        <i class="fas fa-layer-group"></i>
                        <p>User</p>
                        <span class="caret"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a  href="{{ route('admin.role.index') }}">
                        <i class="fas fa-layer-group"></i>
                        <p>Role</p>
                        <span class="caret"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a  href="{{ route('admin.permission.index') }}">
                        <i class="fas fa-layer-group"></i>
                        <p>Permission</p>
                        <span class="caret"></span>
                    </a>
                </li>

              <li class="nav-item">
                <a  href="{{ route('admin.brand.index') }}">
                  <i class="fas fa-layer-group"></i>
                  <p>Brand</p>
                  <span class="caret"></span>
                </a>
              </li>
              <li class="nav-item">
                <a  href="{{ route('admin.product.index') }}">
                  <i class="fas fa-layer-group"></i>
                  <p>Product</p>
                  <span class="caret"></span>
                </a>
              </li>
              <li class="nav-item">
                <a  href="{{ route('admin.dealer.index') }}">
                  <i class="fas fa-layer-group"></i>
                  <p>Dealer</p>
                  <span class="caret"></span>
                </a>
              </li>
              <li class="nav-item">
                <a  href="{{ route('admin.customer.index') }}">
                  <i class="fas fa-layer-group"></i>
                  <p>Customer</p>
                  <span class="caret"></span>
                </a>
              </li>
              <li class="nav-item">
                <a  href="{{ route('admin.purchase.index') }}">
                  <i class="fas fa-layer-group"></i>
                  <p>Puchase</p>
                  <span class="caret"></span>
                </a>
              </li>
              <li class="nav-item">
                <a  href="{{ route('admin.sale.index') }}">
                  <i class="fas fa-layer-group"></i>
                  <p>Sales</p>
                  <span class="caret"></span>
                </a>
              </li>
              <li class="nav-item">
                <a  href="{{ route('admin.transaction.index') }}">
                  <i class="fas fa-layer-group"></i>
                  <p>Transacation</p>
                  <span class="caret"></span>
                </a>
              </li>
                <li class="nav-item">
                    <a  href="{{ route('admin.financeMaster.index') }}">
                        <i class="fas fa-layer-group"></i>
                        <p>Finance Master</p>
                        <span class="caret"></span>
                    </a>
                </li>
              <li class="nav-item">
                <a  href="{{ route('admin.daily-notes.index') }}">
                  <i class="fas fa-layer-group"></i>
                  <p>Notes</p>
                  <span class="caret"></span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
