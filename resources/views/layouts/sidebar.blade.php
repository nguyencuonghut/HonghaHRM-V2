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

          <!-- Canidate -->
          <li class="nav-item">
            <a href="{{route('candidates.index')}}" class="nav-link {{ Request::is('candidates*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-plus"></i>
              <p>
                Ứng viên
              </p>
            </a>
          </li>

          <!-- Employee -->
          <li class="nav-item">
            <a href="{{route('employees.index')}}" class="nav-link {{ Request::is('employees*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Nhân sự
              </p>
            </a>
          </li>

          <!-- Contract -->
          <li class="nav-item">
            <a href="{{route('contracts.index')}}" class="nav-link {{ Request::is('contracts*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-signature"></i>
              <p>
                Hợp đồng
              </p>
            </a>
          </li>

          <!-- Appendix -->
          <li class="nav-item">
            <a href="{{route('appendixes.index')}}" class="nav-link {{ Request::is('appendixes*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-code-branch"></i>
              <p>
                Phụ lục
              </p>
            </a>
          </li>

          <!-- Work -->
          <li class="nav-item">
            <a href="{{route('works.index')}}" class="nav-link {{ Request::is('works*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-clock"></i>
              <p>
                Công tác
              </p>
            </a>
          </li>

          <!-- Salry -->
          <li class="nav-item">
            <a href="{{route('salaries.index')}}" class="nav-link {{ Request::is('salaries*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-hand-holding-usd"></i>
              <p>
                Lương
              </p>
            </a>
          </li>

          <!-- Probation -->
          <li class="nav-item">
            <a href="{{route('probations.index')}}" class="nav-link {{ Request::is('probations*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-hourglass-start"></i>
              <p>
                Thử việc
              </p>
            </a>
          </li>

          <!-- Insurance -->
          <li class="nav-item">
            <a href="{{route('insurances.index')}}" class="nav-link {{ Request::is('insurances*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-hand-holding-medical"></i>
              <p>
                Bảo hiểm
              </p>
            </a>
          </li>

          <!-- Regime -->
          <li class="nav-item">
            <a href="{{route('regimes.index')}}" class="nav-link {{ Request::is('regimes*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-hands"></i>
              <p>
                Chế độ
              </p>
            </a>
          </li>

          <!-- Welfare -->
          <li class="nav-item">
            <a href="{{route('welfares.index')}}" class="nav-link {{ Request::is('welfares*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-hand-holding-heart"></i>
              <p>
                Phúc lợi
              </p>
            </a>
          </li>

          <!-- Kpi -->
          <li class="nav-item">
            <a href="{{route('kpis.index')}}" class="nav-link {{ Request::is('kpis*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                KPI
              </p>
            </a>
          </li>

          <!-- YearReview -->
          <li class="nav-item">
            <a href="{{route('year_reviews.index')}}" class="nav-link {{ Request::is('year_reviews*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-calendar-check"></i>
              <p>
                Đánh giá năm
              </p>
            </a>
          </li>

          <!-- Reward -->
          <li class="nav-item">
            <a href="{{route('rewards.index')}}" class="nav-link {{ Request::is('rewards*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-medal"></i>
              <p>
                Khen thưởng
              </p>
            </a>
          </li>

          <!-- Discipline -->
          <li class="nav-item">
            <a href="{{route('disciplines.index')}}" class="nav-link {{ Request::is('disciplines*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-gavel"></i>
              <p>
                Kỷ luật
              </p>
            </a>
          </li>

          <!-- Calendar -->
          <li class="nav-item">
            <a href="{{route('calendars.index')}}" class="nav-link {{ Request::is('calendars*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                Lịch
              </p>
            </a>
          </li>

          <!-- Reports -->
          <li class="nav-item {{ Request::is('work_rotation_reports*') ? 'menu-open' : '' }}">
            <a href="{{route('work_rotation_reports.index')}}" class="nav-link {{ Request::is('work_rotation_reports*') ? 'active' : '' }}">
              <i class="nav-icon far fa-chart-bar"></i>
              <p>
                Báo cáo
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item {{ Request::is('work_rotation_reports*') ? 'menu-open' : '' }}">
                <a href="{{route('work_rotation_reports.index')}}" class="nav-link {{ Request::is('work_rotation_reports*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Luân chuyển
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{route('work_rotation_reports.index')}}" class="nav-link {{ Request::is('work_rotation_reports') ? 'active' : '' }}">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Tất cả</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('work_rotation_reports.show')}}" class="nav-link {{ Request::is('work_rotation_reports/show') || Request::is('work_rotation_reports/by_month') ? 'active' : '' }}">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Chi tiết</p>
                    </a>
                  </li>
                </ul>
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

          <!-- Lãnh đạo phòng/ban -->
          <li class="nav-item">
            <a href="{{route('department_managers.index')}}" class="nav-link {{ Request::is('department_managers*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-bookmark"></i>
              <p>
                QL phòng/ban
              </p>
            </a>
          </li>

          <!-- Phó phòng -->
          <li class="nav-item">
            <a href="{{route('department_vices.index')}}" class="nav-link {{ Request::is('department_vices*') ? 'active' : '' }}">
              <i class="nav-icon far fa-bookmark"></i>
              <p>
                Phó phòng
              </p>
            </a>
          </li>

          <!-- Lãnh đạo bộ phận -->
          <li class="nav-item">
            <a href="{{route('division_managers.index')}}" class="nav-link {{ Request::is('division_managers*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-thumbtack"></i>
              <p>
                QL bộ phận
              </p>
            </a>
          </li>

          <!-- Cách thức -->
          <li class="nav-item">
            <a href="{{route('methods.index')}}" class="nav-link {{ Request::is('methods*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-list-alt"></i>
              <p>
                Cách thức
              </p>
            </a>
          </li>

          <!-- Channel -->
          <li class="nav-item">
            <a href="{{route('channels.index')}}" class="nav-link {{ Request::is('channels*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-bullhorn"></i>
              <p>
                Phương tiện
              </p>
            </a>
          </li>

          <!-- Province -->
          <li class="nav-item">
            <a href="{{route('provinces.index')}}" class="nav-link {{ Request::is('provinces*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-map-marked-alt"></i>
              <p>
                Tỉnh
              </p>
            </a>
          </li>

          <!-- District -->
          <li class="nav-item">
            <a href="{{route('districts.index')}}" class="nav-link {{ Request::is('districts*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-map-marker-alt"></i>
              <p>
                Quận Huyện
              </p>
            </a>
          </li>

          <!-- Commune -->
          <li class="nav-item">
            <a href="{{route('communes.index')}}" class="nav-link {{ Request::is('communes*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-map-pin"></i>
              <p>
                Phường xã
              </p>
            </a>
          </li>

          <!-- School -->
          <li class="nav-item">
            <a href="{{route('schools.index')}}" class="nav-link {{ Request::is('schools*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-graduation-cap"></i>
              <p>
                Trường
              </p>
            </a>
          </li>

          <!-- Degree -->
          <li class="nav-item">
            <a href="{{route('degrees.index')}}" class="nav-link {{ Request::is('degrees*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-graduate"></i>
              <p>
                Trình độ
              </p>
            </a>
          </li>

          <!-- ContractType -->
          <li class="nav-item">
            <a href="{{route('contract_types.index')}}" class="nav-link {{ Request::is('contract_types*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th-list"></i>
              <p>
                Loại HĐ
              </p>
            </a>
          </li>

          <!-- DocType -->
          <li class="nav-item">
            <a href="{{route('doc_types.index')}}" class="nav-link {{ Request::is('doc_types*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Loại giấy tờ
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
