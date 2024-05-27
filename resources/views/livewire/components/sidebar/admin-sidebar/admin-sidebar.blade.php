    <ul class="navbar-nav bg-sidebar-primary sidebar sidebar-dark accordion d-print-none" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/inspection/dashboard/">
            <div class="sidebar-brand-icon rotate-n-15" style="width: 50px; height: 50px">
                <img style="width: 100%; height: 100%" src="/assets/img/lgu_logo.png" alt="Logo">
            </div>
            <div class="sidebar-brand-text mx-3">OBOS</div>
        </a>
        <li class="nav-item active">
            <a class="nav-link" href="{{ route('administrator-dashboard') }}">
                <i class="fas fa-fw fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#Establishmentsdropdown" aria-expanded="false" aria-controls="Establishmentsdropdown">
                <span class="d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-fw fa-circle-user"></i>
                        Establishments 
                    </span>
                    <i class="fas fa-chevron-down"></i> 
                </span>
            </a>
            <div id="Establishmentsdropdown" class="collapse">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('administrator-establishments-businesses') }}">
                            <i class="fas fa-fw fa-business-time"></i>
                            <span>Businesses</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('administrator-establishments-owners') }}">
                            <i class="fas fa-fw fa-user"></i>
                            <span>Owners</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#Equipmentsdropdown" aria-expanded="false" aria-controls="Equipmentsdropdown">
                <span class="d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-fw fa-circle-user"></i>
                        Equipments
                    </span>
                    <i class="fas fa-chevron-down"></i> 
                </span>
            </a>
            <div id="Equipmentsdropdown" class="collapse">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('administrator-equipments-categories') }}">
                            <i class="fa-solid fa-layer-group"></i>
                            <span>Category</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('administrator-equipments-items') }}">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                            <span>Items</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#Billingsdropdown" aria-expanded="false" aria-controls="Billingsdropdown">
                <span class="d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-fw fa-circle-user"></i>
                        Billings
                    </span>
                    <i class="fas fa-chevron-down"></i> 
                </span>
            </a>
            <div id="Billingsdropdown" class="collapse">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('administrator-billings-equipments-billings') }}">
                            <i class="fa-solid fa-layer-group"></i>
                            <span>Equipments billings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('administrator-billings-building-billings') }}">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                            <span>Building billings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('administrator-billings-signage-billings') }}">
                            <i class="fa fa-credit-card-alt"></i>
                            <span>Signage billings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('administrator-billings-sanitary-billings') }}">
                            <i class="fa fa-credit-card-alt"></i>
                            <span>Sanitary billings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#Inspectionsdropdown" aria-expanded="false" aria-controls="Inspectionsdropdown">
                <span class="d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-fw fa-circle-user"></i>
                        Inspections
                    </span>
                    <i class="fas fa-chevron-down"></i> 
                </span>
            </a>
            <div id="Inspectionsdropdown" class="collapse">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('administrator-inspections-inspection-schedules') }}">
                            <i class="fa-solid fa-layer-group"></i>
                            <span>Inspection schedules</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('administrator-inspections-completed-inspections') }}">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                            <span>Completed inspections</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/inspection/billing/">
                            <i class="fa fa-credit-card-alt"></i>
                            <span>Certificates here???</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#Userdropdown" aria-expanded="false" aria-controls="Userdropdown">
                <span class="d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-fw fa-circle-user"></i>
                        User
                    </span>
                    <i class="fas fa-chevron-down"></i> 
                </span>
            </a>
            <div id="Userdropdown" class="collapse">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('administrator-users-administrators') }}">
                            <i class="fas fa-fw fa-user-tie"></i>
                            <span>Administrator</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('administrator-users-administrators') }}">
                            <i class="fas fa-fw fa-users"></i>
                            <span>Team Leader Inspector</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('administrator-users-inspectors') }}">
                            <i class="fas fa-fw fa-user"></i>
                            <span>Inspector</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('administrator-users-group-inspectors') }}">
                            <i class="fas fa-fw fa-user"></i>
                            <span>Inspector Groups</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('administrator-violations') }}">
                <i class="fa-solid fa-fw fa-triangle-exclamation"></i>
                <span>Violation</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('administrator-barangay-locations') }}">
                <i class="fas fa-fw fa-user"></i>
                <span>Barangay location</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('administrator-activity-logs') }}">
                <i class="fa-solid fa-calendar-days"></i>
                <span>Activity Logs</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('administrator-profile') }}">
                <i class="fas fa-fw fa-user"></i>
                <span>Profile</span>
            </a>
        </li>
        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>

