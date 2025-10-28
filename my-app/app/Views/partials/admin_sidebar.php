<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="<?= site_url('/') ?>" class="text-nowrap logo-img">
                <img src="<?= base_url('assets/images/logos/logo.svg') ?>" alt="Gate Admin Logo" width="150">
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= site_url('/') ?>" aria-expanded="false">
                        <span><i class="ti ti-layout-dashboard"></i></span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <ul id="sidebarnav">

                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Reporting</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="<?= site_url('reports/daily') ?>" aria-expanded="false">
                            <span><i class="ti ti-chart-bar"></i></span>
                            <span class="hide-menu">Daily Reports</span>
                        </a>
                    </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Management</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= site_url('management/visitors') ?>" aria-expanded="false">
                        <span><i class="ti ti-id-badge"></i></span>
                        <span class="hide-menu">Visitor Passes</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= site_url('management/blacklist') ?>" aria-expanded="false">
                        <span><i class="ti ti-ban"></i></span>
                        <span class="hide-menu">Item Blacklist</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="#" aria-expanded="false">
                        <span><i class="ti ti-users"></i></span>
                        <span class="hide-menu">Users</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>