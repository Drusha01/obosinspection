    <ul class="navbar-nav bg-sidebar-primary sidebar sidebar-dark accordion d-print-none" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/inspection/dashboard/">
            <div class="sidebar-brand-icon rotate-n-15" style="width: 50px; height: 50px">
                <img style="width: 100%; height: 100%" src="/assets/img/lgu_logo.png" alt="Logo">
            </div>
            <div class="sidebar-brand-text mx-3">OBOS</div>
        </a>
        @if($user_details->role_name == 'Administrator')
            <li class="nav-item @if(Route::is('administrator-dashboard')) active @endif ">
                <a class="nav-link" href="{{ route('administrator-dashboard') }}">
                    <i class="fas fa-fw fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#Establishmentsdropdown" aria-expanded="@if(Request()->route()->getPrefix() == 'administrator/establishments') false @else true @endif" aria-controls="Establishmentsdropdown">
                    <span class="d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-fw fa-building"></i>
                            Establishments 
                        </span>
                        <i class="fas @if(Request()->route()->getPrefix() == 'administrator/establishments') fa-chevron-up @else fa-chevron-down @endif"></i> 
                    </span>
                </a>
                <div id="Establishmentsdropdown" class="collapse  @if(Request()->route()->getPrefix() == 'administrator/establishments') show @endif">
                    <ul class="nav flex-column sub-menu" style="background-color: #50C878; border-radius: 8px; z-index: 9999;">
                        <li class="nav-item  @if( Route::is('administrator-establishments-owners'))  active @endif">
                            <a class="nav-link" href="{{ route('administrator-establishments-owners') }}">
                                <i class="fas fa-fw fa-user"></i>
                                <span>Owners</span>
                            </a>
                        </li>
                        <li class="nav-item  @if( Route::is('administrator-establishments-businesses') ) active @endif">
                            <a class="nav-link " href="{{ route('administrator-establishments-businesses') }}">
                                <i class="fas fa-fw fa-briefcase"></i>
                                <span>Businesses</span>
                            </a>
                        </li>
                        <li class="nav-item @if( Route::is('administrator-establishments-business-category') ) active @endif">
                            <a class="nav-link" href="{{ route('administrator-establishments-business-category') }}">
                                <i class="bi bi-grid-1x2"></i>
                                <span>Business Category</span>
                            </a>
                        </li>
                        <li class="nav-item @if( Route::is('administrator-establishments-business-type') ) active @endif">
                            <a class="nav-link" href="{{ route('administrator-establishments-business-type') }}">
                                <i class="bi bi-list-nested"></i>
                                <span>Business types</span>
                            </a>
                        </li>
                        <li class="nav-item @if( Route::is('administrator-establishments-business-occupation-classification') ) active @endif">
                            <a class="nav-link" href="{{ route('administrator-establishments-business-occupation-classification') }}">
                                <i class="bi bi-layout-wtf"></i>
                                <span>Occupancy classifications</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#Equipmentsdropdown" aria-expanded="@if(Request()->route()->getPrefix() == 'administrator/equipments') false @else true @endif" aria-controls="Equipmentsdropdown">
                    <span class="d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-fw fa-tools"></i>
                            Equipments
                        </span>
                        <i class="fas @if(Request()->route()->getPrefix() == 'administrator/equipments') fa-chevron-up @else fa-chevron-down @endif"></i> 
                    </span>
                </a>
                <div id="Equipmentsdropdown" class="collapse  @if(Request()->route()->getPrefix() == 'administrator/equipments') show @endif">
                    <ul class="nav flex-column sub-menu" style="background-color: #50C878; border-radius: 8px; z-index: 9999;">
                        <li class="nav-item @if(Route::is('administrator-equipments-categories')) active @endif">
                            <a class="nav-link" href="{{ route('administrator-equipments-categories') }}">
                                <i class="fas fa-fw fa-th-list"></i>
                                <span>Category</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('administrator-equipments-items')) active @endif">
                            <a class="nav-link" href="{{ route('administrator-equipments-items') }}">
                                <i class="fas fa-fw fa-cogs"></i>
                                <span>Items</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li wire:ignore.self class="nav-item">
                <a class="nav-link active" href="#" data-bs-toggle="collapse" data-bs-target="#Billingsdropdown" aria-expanded="@if(Request()->route()->getPrefix() == 'administrator/billings') true @else false @endif" aria-controls="Billingsdropdown">
                    <span class="d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-fw fa-file-invoice-dollar"></i>
                            Billings
                        </span>
                        <i class="fas @if(Request()->route()->getPrefix() == 'administrator/billings')fa-chevron-up @else fa-chevron-down @endif"></i> 
                    </span>
                </a>
                <div id="Billingsdropdown" class="collapse @if(Request()->route()->getPrefix() == 'administrator/billings') show @endif">
                    <ul class="nav flex-column sub-menu" style="background-color: #50C878; border-radius: 8px; z-index: 9999;">
                        <li class="nav-item @if(Route::is('administrator-billings-equipments-billings')) active @endif">
                            <a class="nav-link" href="{{ route('administrator-billings-equipments-billings') }}">
                                <i class="fas fa-fw fa-toolbox"></i>
                                <span>Equipments billings</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('administrator-billings-equipments-billing-sections')) active @endif">
                            <a class="nav-link" href="{{ route('administrator-billings-equipments-billing-sections') }}">
                                <i class="fas fa-fw fa-folder"></i>
                                <span>Equipment billing sections</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('administrator-billings-building-billings')) active @endif">
                            <a class="nav-link" href="{{ route('administrator-billings-building-billings') }}">
                                <i class="fas fa-fw fa-building"></i>
                                <span>Building billings</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('administrator-billings-building-billing-sections')) active @endif">
                            <a class="nav-link" href="{{ route('administrator-billings-building-billing-sections') }}">
                                <i class="fas fa-fw fa-columns"></i>
                                <span>Building billing sections</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('administrator-billings-signage-billings')) active @endif">
                            <a class="nav-link" href="{{ route('administrator-billings-signage-billings') }}">
                                <i class="fas fa-fw fa-sign"></i>
                                <span>Signage billings</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('administrator-billings-sanitary-billings')) active @endif">
                            <a class="nav-link" href="{{ route('administrator-billings-sanitary-billings') }}">
                                <i class="fas fa-fw fa-bath"></i>
                                <span>Sanitary billings</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#Requestdropdown" aria-expanded="@if(Request()->route()->getPrefix() == 'administrator/request') true @else false @endif" aria-controls="Requestdropdown">
                    <span class="d-flex justify-content-between align-items-center">
                        <span>
                            <i class="bi bi-files"></i>
                            Requests
                        </span>
                        <i class="fas  @if(Request()->route()->getPrefix() == 'administrator/request') fa-chevron-up @else fa-chevron-down @endif"></i> 
                    </span>
                </a>
                <div id="Requestdropdown" class="collapse @if(Request()->route()->getPrefix() == 'administrator/request') show @endif">
                    <ul class="nav flex-column sub-menu" style="background-color: #50C878; border-radius: 8px; z-index: 9999;">
                        <li class="nav-item @if(Route::is('administrator-request-generate-request')) active @endif">
                            <a class="nav-link" href="{{ route('administrator-request-generate-request') }}">
                                <i class="bi bi-file-earmark-plus"></i>
                                <span>Generate Request</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('administrator-request-accepted-request')) active @endif">
                            <a class="nav-link" href="{{ route('administrator-request-accepted-request') }}">
                                <i class="bi bi-file-earmark-check"></i>
                                <span>Accepted Request</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('administrator-request-declined-request')) active @endif">
                            <a class="nav-link" href="{{ route('administrator-request-declined-request') }}">
                                <i class="bi bi-file-earmark-x"></i>
                                <span>Declined Request</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('administrator-request-no-response-request')) active @endif" >
                            <a class="nav-link" href="{{ route('administrator-request-no-response-request') }}">
                                <i class="bi bi-question-square"></i>
                                <span>No Response Request</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('administrator-request-deleted-request')) active @endif">
                            <a class="nav-link" href="{{ route('administrator-request-deleted-request') }}">
                                <i class="bi bi-folder-x"></i>
                                <span>Deleted Request</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#Inspectionsdropdown" aria-expanded="@if(Request()->route()->getPrefix() == 'administrator/inspections') true @else false @endif" aria-controls="Inspectionsdropdown">
                    <span class="d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-fw fa-clipboard-check"></i>
                            Inspections
                        </span>
                        <i class="fas  @if(Request()->route()->getPrefix() == 'administrator/inspections') fa-chevron-up @else fa-chevron-down @endif"></i> 
                    </span>
                </a>
                <div id="Inspectionsdropdown" class="collapse @if(Request()->route()->getPrefix() == 'administrator/inspections') show @endif">
                    <ul class="nav flex-column sub-menu" style="background-color: #50C878; border-radius: 8px; z-index: 9999;">
                        <li class="nav-item @if(Route::is('administrator-inspections-inspection-schedules')) active @endif">
                            <a class="nav-link" href="{{ route('administrator-inspections-inspection-schedules') }}">
                                <i class="fas fa-fw fa-calendar-check"></i>
                                <span>Inspection schedules</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('administrator-inspections-ongoing-inspections')) active @endif">
                            <a class="nav-link" href="{{ route('administrator-inspections-ongoing-inspections') }}">
                                <i class="fas fa-fw fa-check-circle"></i>
                                <span>Ongoing inspections</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('administrator-inspections-completed-inspections')) active @endif">
                            <a class="nav-link" href="{{ route('administrator-inspections-completed-inspections') }}">
                                <i class="fas fa-fw fa-check-circle"></i>
                                <span>Completed inspections</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('administrator-inspections-deleted-inspections')) active @endif" >
                            <a class="nav-link" href="{{ route('administrator-inspections-deleted-inspections') }}">
                                <i class="fas fa-fw fa-times-circle"></i>
                                <span>Deleted inspections</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('administrator-inspections-upcoming-inspections')) active @endif" >
                            <a class="nav-link" href="{{ route('administrator-inspections-upcoming-inspections') }}">
                            <i class="fas fa-fw fa-calendar-check"></i>
                                <span>Upcoming inspections</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item  @if(Route::is('administrator-certifications')) active @endif">
                <a class="nav-link" href="{{ route('administrator-certifications') }}">
                    <i class="fa-solid fa-fw fa-triangle-exclamation"></i>
                    <span>Certificates</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#Userdropdown" aria-expanded="@if(Request()->route()->getPrefix() == 'administrator/users') true @else false @endif" aria-controls="Userdropdown">
                    <span class="d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-fw fa-users"></i>
                            User
                        </span>
                        <i class="fas @if(Request()->route()->getPrefix() == 'administrator/users') fa-chevron-up @else fa-chevron-down @endif"></i> 
                    </span>
                </a>
                <div id="Userdropdown" class="collapse @if(Request()->route()->getPrefix() == 'administrator/users') show @endif">
                    <ul class="nav flex-column sub-menu" style="background-color: #50C878; border-radius: 8px; z-index: 9999;">
                        <li class="nav-item @if(Route::is('administrator-users-inspectors')) active @endif">
                            <a class="nav-link" href="{{ route('administrator-users-inspectors') }}">
                                <i class="fas fa-fw fa-user-tie"></i>
                                <span>Inspector</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('administrator-users-group-inspectors')) active @endif">
                            <a class="nav-link" href="{{ route('administrator-users-group-inspectors') }}">
                                <i class="fas fa-fw fa-users"></i>
                                <span>Inspector Groups</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('administrator-users-work-roles')) active @endif">
                            <a class="nav-link" href="{{ route('administrator-users-work-roles') }}">
                                <i class="bi bi-person-badge"></i>
                                <span>Work Roles</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item @if(Route::is('administrator-violations')) active @endif">
                <a class="nav-link" href="{{ route('administrator-violations') }}">
                    <i class="fa-solid fa-fw fa-triangle-exclamation"></i>
                    <span>Violation</span>
                </a>
            </li>
            <li class="nav-item @if(Route::is('administrator-barangay-locations')) active @endif">
                <a class="nav-link" href="{{ route('administrator-barangay-locations') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Barangay location</span>
                </a>
            </li>
            <li class="nav-item @if(Route::is('administrator-activity-logs')) active @endif">
                <a class="nav-link" href="{{ route('administrator-activity-logs') }}">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Activity Logs</span>
                </a>
            </li>
            <li class="nav-item @if(Route::is('administrator-profile')) active @endif">
                <a class="nav-link" href="{{ route('administrator-profile') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>
        @elseif($user_details->role_name == 'Inspector Team Leader')
            <li class="nav-item @if(Route::is('inspector-team-leader-dashboard')) active @endif">
                <a class="nav-link" href="{{ route('inspector-team-leader-dashboard') }}">
                    <i class="fas fa-fw fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#Establishmentsdropdown" aria-expanded="@if(Request()->route()->getPrefix() == 'inspector-team-leader/establishments') false @else true @endif" aria-controls="Establishmentsdropdown">
                    <span class="d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-fw fa-building"></i>
                            Establishments 
                        </span>
                        <i class="fas @if(Request()->route()->getPrefix() == 'inspector-team-leader/establishments') fa-chevron-up @else fa-chevron-down @endif"></i> 
                    </span>
                </a>
                <div id="Establishmentsdropdown" class="collapse  @if(Request()->route()->getPrefix() == 'inspector-team-leader/establishments') show @endif">
                    <ul class="nav flex-column sub-menu"  style="background-color: #50C878; border-radius: 8px; z-index: 9999;">
                        <li class="nav-item  @if( Route::is('inspector-team-leader-establishments-owners'))  active @endif">
                            <a class="nav-link " href="{{ route('inspector-team-leader-establishments-owners') }}">
                                <i class="fas fa-fw fa-user"></i>
                                <span>Owners</span>
                            </a>
                        </li>
                        <li class="nav-item  @if( Route::is('inspector-team-leader-establishments-businesses') ) active @endif">
                            <a class="nav-link " href="{{ route('inspector-team-leader-establishments-businesses') }}">
                                <i class="fas fa-fw fa-briefcase"></i>
                                <span>Businesses</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#Equipmentsdropdown" aria-expanded="@if(Request()->route()->getPrefix() == 'inspector-team-leader/equipments') false @else true @endif" aria-controls="Equipmentsdropdown">
                    <span class="d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-fw fa-tools"></i>
                            Equipments
                        </span>
                        <i class="fas @if(Request()->route()->getPrefix() == 'inspector-team-leader/equipments') fa-chevron-up @else fa-chevron-down @endif"></i> 
                    </span>
                </a>
                <div id="Equipmentsdropdown" class="collapse  @if(Request()->route()->getPrefix() == 'inspector-team-leader/equipments') show @endif">
                    <ul class="nav flex-column sub-menu" style="background-color: #50C878; border-radius: 8px; z-index: 9999;">
                        <li class="nav-item @if(Route::is('inspector-team-leader-equipments-categories')) active @endif">
                            <a class="nav-link" href="{{ route('inspector-team-leader-equipments-categories') }}">
                                <i class="fas fa-fw fa-th-list"></i>
                                <span>Category</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('inspector-team-leader-equipments-items')) active @endif">
                            <a class="nav-link" href="{{ route('inspector-team-leader-equipments-items') }}">
                                <i class="fas fa-fw fa-cogs"></i>
                                <span>Items</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
          
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#Requestdropdown" aria-expanded="@if(Request()->route()->getPrefix() == 'inspector-team-leader/request') true @else false @endif" aria-controls="Requestdropdown">
                    <span class="d-flex justify-content-between align-items-center">
                        <span>
                            <i class="bi bi-files"></i>
                            Requests
                        </span>
                        <i class="fas  @if(Request()->route()->getPrefix() == 'inspector-team-leader/request') fa-chevron-up @else fa-chevron-down @endif"></i> 
                    </span>
                </a>
                <div id="Requestdropdown" class="collapse @if(Request()->route()->getPrefix() == 'inspector-team-leader/request') show @endif">
                    <ul class="nav flex-column sub-menu" style="background-color: #50C878; border-radius: 8px; z-index: 9999;">
                        <li class="nav-item @if(Route::is('inspector-team-leader-request-generate-request')) active @endif">
                            <a class="nav-link" href="{{ route('inspector-team-leader-request-generate-request') }}">
                                <i class="bi bi-file-earmark-plus"></i>
                                <span>Generate Request</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('inspector-team-leader-request-accepted-request')) active @endif">
                            <a class="nav-link" href="{{ route('inspector-team-leader-request-accepted-request') }}">
                                <i class="bi bi-file-earmark-check"></i>
                                <span>Accepted Request</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('inspector-team-leader-request-declined-request')) active @endif">
                            <a class="nav-link" href="{{ route('inspector-team-leader-request-declined-request') }}">
                                <i class="bi bi-file-earmark-x"></i>
                                <span>Declined Request</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('inspector-team-leader-request-no-response-request')) active @endif" >
                            <a class="nav-link" href="{{ route('inspector-team-leader-request-no-response-request') }}">
                                <i class="bi bi-question-square"></i>
                                <span>No Response Request</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('inspector-team-leader-request-deleted-request')) active @endif">
                            <a class="nav-link" href="{{ route('inspector-team-leader-request-deleted-request') }}">
                                <i class="bi bi-folder-x"></i>
                                <span>Deleted Request</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#Inspectionsdropdown" aria-expanded="@if(Request()->route()->getPrefix() == 'inspector-team-leader/inspections') true @else false @endif" aria-controls="Inspectionsdropdown">
                    <span class="d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-fw fa-clipboard-check"></i>
                            Inspections
                        </span>
                        <i class="fas  @if(Request()->route()->getPrefix() == 'inspector-team-leader/inspections') fa-chevron-up @else fa-chevron-down @endif"></i> 
                    </span>
                </a>
                <div id="Inspectionsdropdown" class="collapse @if(Request()->route()->getPrefix() == 'inspector-team-leader/inspections') show @endif">
                    <ul class="nav flex-column sub-menu" style="background-color: #50C878; border-radius: 8px; z-index: 9999;">
                        <li class="nav-item @if(Route::is('inspector-team-leader-inspections-inspection-schedules')) active @endif">
                            <a class="nav-link" href="{{ route('inspector-team-leader-inspections-inspection-schedules') }}">
                                <i class="fas fa-fw fa-calendar-check"></i>
                                <span>Inspection schedules</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('inspector-team-leader-inspections-ongoing-inspections')) active @endif">
                            <a class="nav-link" href="{{ route('inspector-team-leader-inspections-ongoing-inspections') }}">
                                <i class="fas fa-fw fa-check-circle"></i>
                                <span>Ongoing inspections</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('inspector-team-leader-inspections-completed-inspections')) active @endif">
                            <a class="nav-link" href="{{ route('inspector-team-leader-inspections-completed-inspections') }}">
                                <i class="fas fa-fw fa-check-circle"></i>
                                <span>Completed inspections</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('inspector-team-leader-inspections-deleted-inspections')) active @endif" >
                            <a class="nav-link" href="{{ route('inspector-team-leader-inspections-deleted-inspections') }}">
                                <i class="fas fa-fw fa-times-circle"></i>
                                <span>Deleted inspections</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('inspector-team-leader-inspections-upcoming-inspections')) active @endif" >
                            <a class="nav-link" href="{{ route('inspector-team-leader-inspections-upcoming-inspections') }}">
                            <i class="fas fa-fw fa-calendar-check"></i>
                                <span>Upcoming inspections</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item @if(Route::is('inspector-team-leader-certifications')) active @endif">
                <a class="nav-link" href="{{ route('inspector-team-leader-certifications') }}">
                    <i class="fa-solid fa-fw fa-triangle-exclamation"></i>
                    <span>Certificates</span>
                </a>
            </li>
            <li class="nav-item @if(Route::is('inspector-team-leader-violations')) active @endif">
                <a class="nav-link" href="{{ route('inspector-team-leader-violations') }}">
                    <i class="fa-solid fa-fw fa-triangle-exclamation"></i>
                    <span>Violation</span>
                </a>
            </li>
            <li class="nav-item @if(Route::is('inspector-team-leader-profile')) active @endif">
                <a class="nav-link" href="{{ route('inspector-team-leader-profile') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>
            
        @elseif($user_details->role_name == 'Inspector')
        <li class="nav-item @if(Route::is('inspector-dashboard')) active @endif">
                <a class="nav-link" href="{{ route('inspector-dashboard') }}">
                    <i class="fas fa-fw fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#Equipmentsdropdown" aria-expanded="@if(Request()->route()->getPrefix() == 'inspector/equipments') false @else true @endif" aria-controls="Equipmentsdropdown">
                    <span class="d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-fw fa-tools"></i>
                            Equipments
                        </span>
                        <i class="fas @if(Request()->route()->getPrefix() == 'inspector/equipments') fa-chevron-up @else fa-chevron-down @endif"></i> 
                    </span>
                </a>
                <div id="Equipmentsdropdown" class="collapse  @if(Request()->route()->getPrefix() == 'inspector/equipments') show @endif">
                    <ul class="nav flex-column sub-menu" style="background-color: #50C878; border-radius: 8px; z-index: 9999;">
                        <li class="nav-item @if(Route::is('inspector-equipments-categories')) active @endif">
                            <a class="nav-link" href="{{ route('inspector-equipments-categories') }}">
                                <i class="fas fa-fw fa-th-list"></i>
                                <span>Category</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('inspector-equipments-items')) active @endif">
                            <a class="nav-link" href="{{ route('inspector-equipments-items') }}">
                                <i class="fas fa-fw fa-cogs"></i>
                                <span>Items</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#Inspectionsdropdown" aria-expanded="@if(Request()->route()->getPrefix() == 'inspector/inspections') true @else false @endif" aria-controls="Inspectionsdropdown">
                    <span class="d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-fw fa-clipboard-check"></i>
                            Inspections
                        </span>
                        <i class="fas  @if(Request()->route()->getPrefix() == 'inspector/inspections') fa-chevron-up @else fa-chevron-down @endif"></i> 
                    </span>
                </a>
                <div id="Inspectionsdropdown" class="collapse @if(Request()->route()->getPrefix() == 'inspector/inspections') show @endif">
                    <ul class="nav flex-column sub-menu" style="background-color: #50C878; border-radius: 8px; z-index: 9999;">
                        <li class="nav-item @if(Route::is('inspector-inspections-inspection-schedules')) active @endif">
                            <a class="nav-link" href="{{ route('inspector-inspections-inspection-schedules') }}">
                                <i class="fas fa-fw fa-calendar-check"></i>
                                <span>Inspection schedules</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('inspector-inspections-ongoing-inspections')) active @endif">
                            <a class="nav-link" href="{{ route('inspector-inspections-ongoing-inspections') }}">
                                <i class="fas fa-fw fa-check-circle"></i>
                                <span>Ongoing inspections</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('inspector-inspections-completed-inspections')) active @endif">
                            <a class="nav-link" href="{{ route('inspector-inspections-completed-inspections') }}">
                                <i class="fas fa-fw fa-check-circle"></i>
                                <span>Completed inspections</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Route::is('inspector-inspections-deleted-inspections')) active @endif" >
                            <a class="nav-link" href="{{ route('inspector-inspections-deleted-inspections') }}">
                                <i class="fas fa-fw fa-times-circle"></i>
                                <span>Deleted inspections</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item @if(Route::is('inspector-certifications')) active @endif">
                <a class="nav-link" href="{{ route('inspector-certifications') }}">
                    <i class="fa-solid fa-fw fa-triangle-exclamation"></i>
                    <span>Certificates</span>
                </a>
            </li>
            <li class="nav-item @if(Route::is('inspector-violations')) active @endif">
                <a class="nav-link" href="{{ route('inspector-violations') }}">
                    <i class="fa-solid fa-fw fa-triangle-exclamation"></i>
                    <span>Violation</span>
                </a>
            </li>
            <li class="nav-item @if(Route::is('inspector-profile')) active @endif">
                <a class="nav-link" href="{{ route('inspector-profile') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>
        @endif
        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>

