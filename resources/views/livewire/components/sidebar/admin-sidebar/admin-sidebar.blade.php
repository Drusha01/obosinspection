<div id="wrapper">
    <ul class="navbar-nav bg-sidebar-primary sidebar sidebar-dark accordion d-print-none" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo SITEURL; ?>inspection/dashboard/">
            <div class="sidebar-brand-icon rotate-n-15" style="width: 50px; height: 50px">
                <img style="width: 100%; height: 100%" src="<?php echo SITEURL ?>assets/img/lgu_logo.png">
            </div>
            <div class="sidebar-brand-text mx-3">OBOS</div>
        </a>
        <li class="nav-item active">
            <a class="nav-link" href="<?php echo SITEURL; ?>inspection/dashboard/">
                <i class="fas fa-fw fa-chart-line"></i>
                <span>Dashboard</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#userSubMenu1" aria-expanded="false" aria-controls="userSubMenu1">
                <span class="d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-fw fa-circle-user"></i>
                        Establishments 
                    </span>
                    <i class="fas fa-chevron-down"></i> 
                </span>
            </a>
            <div id="userSubMenu1" class="collapse">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITEURL ?>inspection/owner/">
                            <i class="fas fa-fw fa-user"></i>
                            <span>Owner</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="inspection/business/">
                            <i class="fas fa-fw fa-business-time"></i>
                            <span>Business</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITEURL?>inspection/inspector/">
                            <i class="fas fa-fw fa-user"></i>
                            <span>Barangay location</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#userSubMenu2" aria-expanded="false" aria-controls="userSubMenu2">
                <span class="d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-fw fa-circle-user"></i>
                        Equipments
                    </span>
                    <i class="fas fa-chevron-down"></i> 
                </span>
            </a>
            <div id="userSubMenu2" class="collapse">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITEURL ?>inspection/category/">
                            <i class="fa-solid fa-layer-group"></i>
                            <span>Category</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITEURL ?>inspection/item/">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                            <span>Items</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITEURL ?>inspection/billing/">
                            <i class="fa fa-credit-card-alt"></i>
                            <span>Billing</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?php echo SITEURL ?>inspection/inspection/">
                <i class="fas fa-fw fa-check-square"></i>
                <span>Inspection</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?php echo SITEURL ?>inspection/certificate/">
                <i class="fas fa-fw fa-file-medical"></i>
                <span>Certificate</span></a>
        </li>


        <?php if ($role === 'Administrator') : ?>

            <!-- <li class="nav-item">
                <a class="nav-link" href="inspection/business/">
                    <i class="fas fa-fw fa-business-time"></i>
                    <span>Business</span></a>
            </li> -->

            <!-- <li class="nav-item">
                <a class="nav-link" href="inspection/owner/">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Owner</span></a>
            </li> -->

            <li class="nav-item">
                <a class="nav-link" href="<?php echo SITEURL ?>inspection/inspector/">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Inspector</span></a>
            </li>

            <!-- <li class="nav-item">
                <a class="nav-link" href="inspection/billing/">
                    <i class="fa fa-credit-card-alt"></i>
                    <span>Billing</span></a>
            </li> -->

            <li class="nav-item">
                <a class="nav-link" href="<?php echo SITEURL ?>inspection/schedule/">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Schedule</span></a>
            </li>

        <?php endif; ?>

        <li class="nav-item">
            <a class="nav-link" href="<?php echo SITEURL ?>inspection/violation/">
                <i class="fa-solid fa-fw fa-triangle-exclamation"></i>
                <span>Violation</span></a>
        </li>

        <?php if ($role === 'Administrator') : ?>

            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#userSubMenu" aria-expanded="false" aria-controls="userSubMenu">
                <span class="d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-fw fa-circle-user"></i>
                        User
                    </span>
                    <i class="fas fa-chevron-down"></i> 
                </span>
                </a>
                <div id="userSubMenu" class="collapse">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITEURL ?>inspection/user/administrator/">
                                <i class="fas fa-fw fa-user-tie"></i>
                                <span>Administrator</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITEURL ?>inspection/user/team-inspector/">
                                <i class="fas fa-fw fa-users"></i>
                                <span>Team Inspector</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITEURL ?>inspection/user/inspector/">
                                <i class="fas fa-fw fa-user"></i>
                                <span>Inspector</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

        <?php endif; ?>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>
</div>