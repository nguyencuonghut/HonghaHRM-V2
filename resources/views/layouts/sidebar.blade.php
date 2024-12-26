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

          <!-- Calendar -->
          <li class="nav-item">
            <a href="{{route('calendars.index')}}" class="nav-link {{ Request::is('calendars*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                Lịch
              </p>
            </a>
          </li>

          <!-- Logout -->
          <li class="nav-item">
            <a href="{{ route('logout') }}" class="nav-link">
              <i class="nav-icon fas fa-power-off"></i>
              <p>
                Đăng xuất
              </p>
            </a>
          </li>

          <li class="nav-item
                    {{
                        Request::is('recruitments*')
                        || Request::is('candidates*')
                        ?
                        'menu-open'
                        :
                        ''
                    }}">
            <a href="{{route('recruitments.index')}}"
                class="nav-link
                        {{
                            Request::is('recruitments*')
                            || Request::is('candidates*')
                            ?
                            'active'
                            :
                            ''
                        }}">
              <i class="nav-icon fas fa-search-location"></i>
              <p>
                Tuyển dụng
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <!-- Recruitment -->
              <li class="nav-item">
                <a href="{{route('recruitments.index')}}" class="nav-link {{ Request::is('recruitments*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                  <p>Yêu cầu</p>
                </a>
              </li>

              <!-- Canidate -->
              <li class="nav-item">
                <a href="{{route('candidates.index')}}"
                    class="nav-link {{ Request::is('candidates*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                  <p>Ứng viên</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Hồ sơ nhân sự -->
          <li class="nav-item
                    {{
                        Request::is('employees*')
                        || Request::is('join_dates*')
                        || Request::is('probations*')
                        || Request::is('salaries*')
                        || Request::is('contracts*')
                        || Request::is('appendixes*')
                        || Request::is('works*')
                        ?
                        'menu-open'
                        :
                        ''
                    }}">
            <a href="{{route('employees.index')}}"
                class="nav-link
                        {{
                            Request::is('employees*')
                            || Request::is('join_dates*')
                            || Request::is('probations*')
                            || Request::is('salaries*')
                            || Request::is('contracts*')
                            || Request::is('appendixes*')
                            || Request::is('works*')
                            ?
                            'active'
                            :
                            ''
                        }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Hồ sơ nhân sự
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
                <!-- Employee -->
                <li class="nav-item">
                  <a href="{{route('employees.index')}}" class="nav-link {{ Request::is('employees*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                    <p>
                      Nhân sự
                    </p>
                  </a>
                </li>

                <!-- Ngày vào -->
                <li class="nav-item">
                    <a href="{{route('join_dates.index')}}" class="nav-link {{ Request::is('join_dates*') ? 'active' : '' }}">
                        &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                    <p>
                        Ngày vào
                    </p>
                    </a>
                </li>

                <!-- Probation -->
                <li class="nav-item">
                  <a href="{{route('probations.index')}}" class="nav-link {{ Request::is('probations*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                    <p>
                      Thử việc
                    </p>
                  </a>
                </li>

                <!-- Salry -->
                <li class="nav-item">
                  <a href="{{route('salaries.index')}}" class="nav-link {{ Request::is('salaries*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                    <p>
                      Lương
                    </p>
                  </a>
                </li>

                <!-- Contract -->
                <li class="nav-item">
                  <a href="{{route('contracts.index')}}" class="nav-link {{ Request::is('contracts*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                    <p>
                      Hợp đồng
                    </p>
                  </a>
                </li>

                <!-- Appendix -->
                <li class="nav-item">
                  <a href="{{route('appendixes.index')}}" class="nav-link {{ Request::is('appendixes*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                    <p>
                      Phụ lục
                    </p>
                  </a>
                </li>

                <!-- Work -->
                <li class="nav-item">
                  <a href="{{route('works.index')}}" class="nav-link {{ Request::is('works*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                    <p>
                      Công tác
                    </p>
                  </a>
                </li>
            </ul>
          </li>

          <!-- Đánh giá nhân sự -->
          <li class="nav-item
                    {{
                        Request::is('kpis*')
                        || Request::is('year_reviews*')
                        || Request::is('rewards*')
                        || Request::is('disciplines*')
                        ?
                        'menu-open'
                        :
                        ''
                    }}">
            <a href="{{route('kpis.index')}}"
                class="nav-link
                        {{
                            Request::is('kpis*')
                            || Request::is('year_reviews*')
                            || Request::is('rewards*')
                            || Request::is('disciplines*')
                            ?
                            'active'
                            :
                            ''
                        }}">
                <i class="nav-icon fas fa-calendar-check"></i>
              <p>
                Đánh giá nhân sự
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <!-- Recruitment -->
              <li class="nav-item">
                <a href="{{route('kpis.index')}}" class="nav-link {{ Request::is('kpis*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                  <p>KPI</p>
                </a>
              </li>

              <!-- Canidate -->
              <li class="nav-item">
                <a href="{{route('year_reviews.index')}}" class="nav-link {{ Request::is('year_reviews*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                  <p>Đánh giá năm</p>
                </a>
              </li>

              <!-- Reward -->
              <li class="nav-item">
                <a href="{{route('rewards.index')}}" class="nav-link {{ Request::is('rewards*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                  <p>Khen thưởng</p>
                </a>
              </li>

              <!-- Discipline -->
              <li class="nav-item">
                <a href="{{route('disciplines.index')}}" class="nav-link {{ Request::is('disciplines*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                  <p>Kỷ luật</p>
                </a>
              </li>
            </ul>
          </li>


          <!-- Bảo hiểm -->
          <li class="nav-item
                    {{
                        Request::is('insurances*')
                        || Request::is('increase_insurances*')
                        || Request::is('decrease_insurances*')
                        ?
                        'menu-open'
                        :
                        ''
                    }}">
            <a href="{{route('insurances.index')}}"
                class="nav-link
                        {{
                            Request::is('insurances*')
                            || Request::is('increase_insurances*')
                            || Request::is('decrease_insurances*')
                            ?
                            'active'
                            :
                            ''
                        }}">
                <i class="nav-icon fas fa-hand-holding-medical"></i>
              <p>
                Bảo hiểm
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <!-- Recruitment -->
              <li class="nav-item">
                <a href="{{route('insurances.index')}}" class="nav-link {{ Request::is('insurances*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                  <p>Bảo hiểm</p>
                </a>
              </li>

              <!-- Insurance -->
              <li class="nav-item">
                <a href="{{route('increase_insurances.index')}}" class="nav-link {{ Request::is('increase_insurances*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                  <p>Tăng BHXH</p>
                </a>
              </li>

              <!-- Giảm BHXH -->
              <li class="nav-item">
                <a href="{{route('decrease_insurances.index')}}" class="nav-link {{ Request::is('decrease_insurances*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                  <p>Giảm BHXH</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Chế độ phúc lợi -->
          <li class="nav-item
                    {{
                        Request::is('regimes*')
                        || Request::is('welfares*')
                        ?
                        'menu-open'
                        :
                        ''
                    }}">
            <a href="{{route('regimes.index')}}"
                class="nav-link
                        {{
                            Request::is('regimes*')
                            || Request::is('welfares*')
                            ?
                            'active'
                            :
                            ''
                        }}">
                <i class="nav-icon fas fa-hand-holding-heart"></i>
              <p>
                Chế độ phúc lợi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <!-- Regime -->
              <li class="nav-item">
                <a href="{{route('regimes.index')}}" class="nav-link {{ Request::is('regimes*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                  <p>Chế độ</p>
                </a>
              </li>

              <!-- Welfares -->
              <li class="nav-item">
                <a href="{{route('welfares.index')}}" class="nav-link {{ Request::is('welfares*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                  <p>Phúc lợi</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Reports -->
          <li class="nav-item
           {{
           Request::is('work_rotation_reports*')
           || Request::is('off_work_reports*')
           || Request::is('reward_reports*')
           || Request::is('discipline_reports*')
           || Request::is('kpi_reports*')
           || Request::is('birthday_reports*')
           || Request::is('recruitment_reports*')
           || Request::is('situation_reports*')
           || Request::is('kid_policy_reports*')
           || Request::is('document_reports*')
           || Request::is('seniority_reports*')
           || Request::is('increase_decrease_insurance_reports*')
           ?
           'menu-open'
           :
           ''
           }}"
          >
            <a href="{{route('work_rotation_reports.index')}}"
                class="
                    nav-link {{
                    Request::is('work_rotation_reports*')
                    || Request::is('off_work_reports*')
                    || Request::is('reward_reports*')
                    || Request::is('discipline_reports*')
                    || Request::is('kpi_reports*')
                    || Request::is('birthday_reports*')
                    || Request::is('recruitment_reports*')
                    || Request::is('situation_reports*')
                    || Request::is('kid_policy_reports*')
                    || Request::is('document_reports*')
                    || Request::is('seniority_reports*')
                    || Request::is('increase_decrease_insurance_reports*')
                    ?
                    'active'
                    :
                    ''
                    }}
                "
            >
              <i class="nav-icon far fa-chart-bar"></i>
              <p>
                Báo cáo
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <!-- Luân chuyển -->
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
                  <li class="nav-item">
                    <a href="{{route('work_rotation_reports.by_range')}}" class="nav-link {{ Request::is('work_rotation_reports/by_range') ? 'active' : '' }}">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Theo khoảng</p>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>

            <!-- Nghỉ việc -->
            <ul class="nav nav-treeview">
                <li class="nav-item {{ Request::is('off_work_reports*') ? 'menu-open' : '' }}">
                  <a href="{{route('off_work_reports.index')}}" class="nav-link {{ Request::is('off_work_reports*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                      Nghỉ việc
                      <i class="fas fa-angle-left right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="{{route('off_work_reports.index')}}" class="nav-link {{ Request::is('off_work_reports') ? 'active' : '' }}">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Tất cả</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{route('off_work_reports.show')}}" class="nav-link {{ Request::is('off_work_reports/show') || Request::is('off_work_reports/by_month') ? 'active' : '' }}">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Chi tiết</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{route('off_work_reports.by_range')}}" class="nav-link {{ Request::is('off_work_reports/by_range') ? 'active' : '' }}">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Theo khoảng</p>
                      </a>
                    </li>
                  </ul>
                </li>
              </ul>

              <!-- Tuyển dụng -->
              <ul class="nav nav-treeview">
                  <li class="nav-item {{ Request::is('recruitment_reports*') ? 'menu-open' : '' }}">
                    <a href="{{route('recruitment_reports.index')}}" class="nav-link {{ Request::is('recruitment_reports*') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                        Tuyển dụng
                        <i class="fas fa-angle-left right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      <li class="nav-item">
                        <a href="{{route('recruitment_reports.index')}}" class="nav-link {{ Request::is('recruitment_reports') ? 'active' : '' }}">
                          <i class="far fa-dot-circle nav-icon"></i>
                          <p>Tất cả</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="{{route('recruitment_reports.show')}}" class="nav-link {{ Request::is('recruitment_reports/show') || Request::is('recruitment_reports/by_month') ? 'active' : '' }}">
                          <i class="far fa-dot-circle nav-icon"></i>
                          <p>Chi tiết</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="{{route('recruitment_reports.by_range')}}" class="nav-link {{ Request::is('recruitment_reports/by_range') ? 'active' : '' }}">
                          <i class="far fa-dot-circle nav-icon"></i>
                          <p>Theo khoảng</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                </ul>

              <!-- Thâm niên -->
                <ul class="nav nav-treeview">
                    <li class="nav-item {{ Request::is('seniority_reports*') ? 'menu-open' : '' }}">
                      <a href="{{route('seniority_reports.index')}}" class="nav-link {{ Request::is('seniority_reports*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                          Thâm niên
                          <i class="fas fa-angle-left right"></i>
                        </p>
                      </a>
                      <ul class="nav nav-treeview">
                        <li class="nav-item">
                          <a href="{{route('seniority_reports.index')}}" class="nav-link {{ Request::is('seniority_reports') ? 'active' : '' }}">
                            <i class="far fa-dot-circle nav-icon"></i>
                            <p>Tất cả</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{route('seniority_reports.show')}}" class="nav-link {{ Request::is('seniority_reports/show') || Request::is('reward_reports/by_year') ? 'active' : '' }}">
                            <i class="far fa-dot-circle nav-icon"></i>
                            <p>Chi tiết</p>
                          </a>
                        </li>
                      </ul>
                    </li>
                  </ul>

              <!-- Khen thưởng -->
              <ul class="nav nav-treeview">
                  <li class="nav-item {{ Request::is('reward_reports*') ? 'menu-open' : '' }}">
                    <a href="{{route('reward_reports.index')}}" class="nav-link {{ Request::is('reward_reports*') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                        Khen thưởng
                        <i class="fas fa-angle-left right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      <li class="nav-item">
                        <a href="{{route('reward_reports.index')}}" class="nav-link {{ Request::is('reward_reports') ? 'active' : '' }}">
                          <i class="far fa-dot-circle nav-icon"></i>
                          <p>Tất cả</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="{{route('reward_reports.show')}}" class="nav-link {{ Request::is('reward_reports/show') || Request::is('reward_reports/by_year') ? 'active' : '' }}">
                          <i class="far fa-dot-circle nav-icon"></i>
                          <p>Chi tiết</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                </ul>

                <!-- Kỷ luật -->
                <ul class="nav nav-treeview">
                    <li class="nav-item {{ Request::is('discipline_reports*') ? 'menu-open' : '' }}">
                      <a href="{{route('discipline_reports.index')}}" class="nav-link {{ Request::is('discipline_reports*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                          Kỷ luật
                          <i class="fas fa-angle-left right"></i>
                        </p>
                      </a>
                      <ul class="nav nav-treeview">
                        <li class="nav-item">
                          <a href="{{route('discipline_reports.index')}}" class="nav-link {{ Request::is('discipline_reports') ? 'active' : '' }}">
                            <i class="far fa-dot-circle nav-icon"></i>
                            <p>Tất cả</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{route('discipline_reports.show')}}" class="nav-link {{ Request::is('discipline_reports/show') || Request::is('discipline_reports/by_year') ? 'active' : '' }}">
                            <i class="far fa-dot-circle nav-icon"></i>
                            <p>Chi tiết</p>
                          </a>
                        </li>
                      </ul>
                    </li>

                    <!-- KPI -->
                    <li class="nav-item">
                        <a href="{{route('kpi_reports.show')}}" class="nav-link {{ Request::is('kpi_reports*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                        <p>KPI</p>
                      </a>
                    </li>

                    <!-- KPI -->
                    <li class="nav-item">
                        <a href="{{route('increase_decrease_insurance_reports.show')}}" class="nav-link {{ Request::is('increase_decrease_insurance_reports*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                        <p>Tăng giảm BHXH</p>
                      </a>
                    </li>

                    <!-- Birthday -->
                    <li class="nav-item">
                        <a href="{{route('birthday_reports.index')}}" class="nav-link {{ Request::is('birthday_reports*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                        <p>Sinh nhật</p>
                      </a>
                    </li>

                    <!-- Situation -->
                    <li class="nav-item">
                        <a href="{{route('situation_reports.index')}}" class="nav-link {{ Request::is('situation_reports*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                        <p>Hoàn cảnh</p>
                      </a>
                    </li>

                    <!-- Kid Policy -->
                    <li class="nav-item">
                        <a href="{{route('kid_policy_reports.index')}}" class="nav-link {{ Request::is('kid_policy_reports*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                        <p>Chế độ 1/6</p>
                      </a>
                    </li>

                    <!-- Document -->
                    <li class="nav-item">
                        <a href="{{route('document_reports.index')}}" class="nav-link {{ Request::is('document_reports*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                        <p>Hồ sơ</p>
                      </a>
                    </li>
                </ul>
          </li>

          @if ('Admin' == Auth::user()->role->name
            || 'Nhân sự' == Auth::user()->role->name)
          <li class="nav-header">HỆ THỐNG</li>

          @if ('Admin' == Auth::user()->role->name)
            <!-- Người dùng -->
            <li class="nav-item">
                <a href="{{route('users.index')}}" class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>
                    Người dùng
                </p>
                </a>
            </li>
          @endif

          <!-- Vai trò -->
          <li class="nav-item">
            <a href="{{route('roles.index')}}" class="nav-link {{ Request::is('roles*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-tag"></i>
              <p>
                Vai trò
              </p>
            </a>
          </li>

          <!-- Cơ cấu tổ chức -->
          <li class="nav-item
                    {{
                        Request::is('departments*')
                        || Request::is('divisions*')
                        || Request::is('positions*')
                        || Request::is('department_managers*')
                        || Request::is('department_vices*')
                        || Request::is('division_managers*')
                        ?
                        'menu-open'
                        :
                        ''
                    }}">
            <a href="{{route('departments.index')}}"
                class="nav-link
                        {{
                            Request::is('departments*')
                            || Request::is('divisions*')
                            || Request::is('positions*')
                            || Request::is('department_managers*')
                            || Request::is('department_vices*')
                            || Request::is('division_managers*')
                            ?
                            'active'
                            :
                            ''
                        }}">
              <i class="nav-icon fas fa-sitemap"></i>
              <p>
                Cơ cấu tổ chức
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <!-- Department -->
              <li class="nav-item">
                <a href="{{route('departments.index')}}" class="nav-link {{ Request::is('departments*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                  <p>Phòng ban</p>
                </a>
              </li>

              <!-- Division -->
              <li class="nav-item">
                <a href="{{route('divisions.index')}}" class="nav-link {{ Request::is('divisions*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                  <p>Bộ phận</p>
                </a>
              </li>

              <!-- Position -->
              <li class="nav-item">
                <a href="{{route('positions.index')}}" class="nav-link {{ Request::is('positions*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                  <p>Vị trí</p>
                </a>
              </li>

              <!-- Department Manager -->
              <li class="nav-item">
                <a href="{{route('department_managers.index')}}" class="nav-link {{ Request::is('department_managers*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                  <p>Trưởng phòng</p>
                </a>
              </li>

              <!-- Department Vice -->
              <li class="nav-item">
                <a href="{{route('department_vices.index')}}" class="nav-link {{ Request::is('department_vices*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                  <p>Phó phòng</p>
                </a>
              </li>

              <!-- Divisions Manager -->
              <li class="nav-item">
                <a href="{{route('division_managers.index')}}" class="nav-link {{ Request::is('division_managers*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                  <p>QL bộ phận</p>
                </a>
              </li>
            </ul>
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

          <!-- Hành chính -->
          <li class="nav-item
                    {{
                        Request::is('provinces*')
                        || Request::is('districts*')
                        || Request::is('communes*')
                        ?
                        'menu-open'
                        :
                        ''
                    }}">
            <a href="{{route('provinces.index')}}"
                class="nav-link
                        {{
                            Request::is('provinces*')
                            || Request::is('districts*')
                            || Request::is('communes*')
                            ?
                            'active'
                            :
                            ''
                        }}">
              <i class="nav-icon fas fa-map-marker-alt"></i>
              <p>
                Hành chính
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <!-- Province -->
              <li class="nav-item">
                <a href="{{route('provinces.index')}}" class="nav-link {{ Request::is('provinces*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                  <p>Thành phố/Tỉnh</p>
                </a>
              </li>

              <!-- District -->
              <li class="nav-item">
                <a href="{{route('districts.index')}}"
                    class="nav-link {{ Request::is('districts*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                  <p>Quận/Huyện</p>
                </a>
              </li>

              <!-- Commune -->
              <li class="nav-item">
                <a href="{{route('communes.index')}}"
                    class="nav-link {{ Request::is('communes*') ? 'active' : '' }}">
                    &nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
                  <p>Phường/Xã</p>
                </a>
              </li>
            </ul>
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
          @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
