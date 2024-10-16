  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('home')}}" class="brand-link">
      <img src="{{ asset('images/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text">{{ Auth::user()->name }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{route('home')}}" class="nav-link {{ Request::is('/') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('logout') }}" class="nav-link">
              <i class="nav-icon fas fa-power-off"></i>
              <p>
                Đăng xuất
              </p>
            </a>
          </li>

          <li class="nav-item {{ Request::is('recruitments*') ? 'menu-open' : '' }}">
            <a href="{{route('recruitments.index')}}" class="nav-link {{ Request::is('recruitment*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-search-location"></i>
              <p>
                Tuyển dụng
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('recruitments.index')}}" class="nav-link {{ Request::is('recruitments*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                  <p>Yêu cầu</p>
                </a>
              </li>
            </ul>
          </li>


          <li class="nav-header">HỆ THỐNG</li>
          <!-- Người dùng -->
          <li class="nav-item">
            <a href="{{route('users.index')}}" class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Người dùng
              </p>
            </a>
          </li>

          <!-- Vai trò -->
          <li class="nav-item">
            <a href="{{route('roles.index')}}" class="nav-link {{ Request::is('roles*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-tag"></i>
              <p>
                Vai trò
              </p>
            </a>
          </li>

          <!-- Phòng/ban -->
          <li class="nav-item">
            <a href="{{route('departments.index')}}" class="nav-link {{ Request::is('departments*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-sitemap"></i>
              <p>
                Phòng/ban
              </p>
            </a>
          </li>

          <!-- Bộ phận -->
          <li class="nav-item">
            <a href="{{route('divisions.index')}}" class="nav-link {{ Request::is('divisions*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-code-branch"></i>
              <p>
                Bộ phận
              </p>
            </a>
          </li>

          <!-- Vị trí -->
          <li class="nav-item">
            <a href="{{route('positions.index')}}" class="nav-link {{ Request::is('positions*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>
                Vị trí
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
