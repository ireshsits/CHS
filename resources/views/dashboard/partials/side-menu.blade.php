
            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <ul class="nav side-menu">
                  <li><a href="{{ route('dashboard.home') }}"><i class="fa fa-home"></i> Dashboard </a>
                  </li>
                </ul>
              </div>
              <div class="menu_section">
                <ul class="nav side-menu">
                  <li><a href="{{ route('dashboard.search.home') }}"><i class="fa fa-search"></i> Search </a>
                  </li>
                </ul>
              </div>
              <div class="menu_section">
              	<h3>Lodge Management</h3>
                <ul class="nav side-menu">
               	 <!-- Complaint Raised open for every one in CR2-->
               	 @hasanyrole(RoleHelper::getComplaintRaiseRoles())
                  <li><a href="{{ route('dashboard.complaint.setup') }}"><i class="fa fa-file-text" aria-hidden="true"></i> Raise </a>
                  </li>
                 @endhasanyrole 
                  <li><a href="{{ route('dashboard.complaint.manage',['type' => 'CMPLA']) }}"><i class="fa fa-comments" aria-hidden="true"></i> Complaints</a>
                  </li>
                  <li><a href="{{ route('dashboard.complaint.manage',['type' => 'CMPLI']) }}"><i class="fa fa-commenting" aria-hidden="true"></i> Compliments</a>
                  </li>
                </ul>
              </div>
             @hasanyrole(RoleHelper::getAdminViewRoles())
              <div class="menu_section">
                <h3>Reports And Analysis</h3>
                <ul class="nav side-menu">
                  <li><a href="{{ route('dashboard.report.manage') }}"><i class="fa fa-file"></i> Reports </a>
                  </li>
                  <li><a href="{{ route('dashboard.analysis.manage') }}"><i class="fa fa-pie-chart"></i> Analysis </a>
                  </li>
                  
                </ul>
              </div>
              @endhasanyrole
              @hasanyrole(RoleHelper::getAdminRoles())
              <div class="menu_section">
                <h3>Site Management</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-sitemap"></i>Hierarchy<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="{{ route('dashboard.zone.manage') }}">Zones</a></li>
                      <li><a href="{{ route('dashboard.region.manage') }}">Regions</a></li>
                    </ul>
                  </li>
                  <li><a href="{{ route('dashboard.mode.manage') }}"><i class="fa fa-user"></i>Modes</a>
                  </li>
                  <li><a href="{{ route('dashboard.category.manage') }}"><i class="fa fa-tag"></i> Categories</a>
                  </li>
<!--                   <li><a href="{{ route('dashboard.config.manage') }}"><i class="fa fa-cogs"></i> Configurations</a> -->
<!--                   </li> -->
                </ul>
              </div>
            @endhasanyrole
            </div>
            <!-- /sidebar menu -->
