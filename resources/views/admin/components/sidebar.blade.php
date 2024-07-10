<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="#" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{asset('assets/images/logo-dark-sm.png')}}" alt="" height="26" style="margin-top: 25px">
                        </span>
                        <span class="logo-lg">
                            <img src="{{asset('assets/images/logo-dark.png')}}" alt="" height="28">
                        </span>
                    </a>

                </div>

    <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn">
        <i class="bx bx-menu align-middle"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu" >
                <li class="menu-title" data-key="t-menu">Dashboard</li>

                <li>
                    <a href="{{url ('/home')}}">
                        <i class="bx bx-home-alt icon nav-icon"></i>
                        <span class="menu-item" data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                <li class="menu-title" data-key="t-applications">Product</li>

                <li>
                    <a href="#">
                        <i class="bx bx-anchor icon nav-icon"></i>
                        <span class="menu-item" data-key="t-email">Fast Boat</span>
                    </a>
                </li>

                <li class="menu-title" data-key="t-layouts">Booking Data</li>

                <li>
                    <a href="#">
                        <i class="bx bx-receipt icon nav-icon"></i>
                        <span class="menu-item" data-key="t-horizontal">Booking Data</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <i class="bx bx-trash icon nav-icon"></i>
                        <span class="menu-item" data-key="t-horizontal">Booking Trash</span>
                    </a>
                </li>

                <li class="menu-title" data-key="t-components">Partner</li>

                <li>
                    <a href="{{route ('company.view')}}">
                        <i class="bx bx-building icon nav-icon"></i>
                        <span class="menu-item" data-key="t-ui-elements">Company</span>
                    </a>
                </li>

                <li>
                    <a href="{{route ('fastboat.view')}}">
                        <i class="bx bx-up-arrow icon nav-icon"></i>
                        <span class="menu-item" data-key="t-forms">Fast Boat</span>
                    </a>
                </li>

                <li class="menu-title" data-key="t-components">Destiny</li>

                <li>
                    <a href="{{route ('island.view')}}">
                        <i class="bx bx-world icon nav-icon"></i>
                        <span class="menu-item" data-key="t-tables">Island</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{route ('port.view')}}">
                        <i class="bx bx-map-alt icon nav-icon"></i>
                        <span class="menu-item" data-key="t-tables">Port</span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
<header class="ishorizontal-topbar">

    <div class="topnav">
        
    </div>
</header>