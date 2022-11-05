<div class="left-side-menu mm-show">
    <!-- LOGO -->
    <a href="{{route("admin.index")}}" class="logo text-center logo-light">
        <span class="logo-lg">
            <img src="{{asset('img/logo.png')}}" alt="" height="50">
        </span>
        <span class="logo-sm">
            <img src="{{asset('img/logo.png')}}" alt="" height="20">
        </span>
    </a>
    <div class="h-100 mm-active" id="left-side-menu-container" data-simplebar="init">
        <div class="simplebar-wrapper" style="margin: 0px;">
            <div class="simplebar-height-auto-observer-wrapper">
                <div class="simplebar-height-auto-observer"></div>
            </div>
            <div class="simplebar-mask">
                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                    <div class="simplebar-content-wrapper" style="height: 100%; overflow: hidden;">
                        <div class="simplebar-content" style="padding: 0px;">
                            <ul class="metismenu side-nav mm-show">
                                <li class="side-nav-title side-nav-item">homepage</li>
                                <li class="side-nav-item">
                                    <a href="{{route("admin.index")}}" class="side-nav-link">
                                        <i class="uil-home-alt"></i>
                                        <span class="badge badge-info badge-pill float-right"></span>
                                        <span>Dashboards</span>
                                    </a>
                                </li>
                                <li class="side-nav-title side-nav-item">apps</li>
                                <li class="side-nav-item">
                                    <a href="{{ route("admin.users.index") }}" class="side-nav-link">
                                        <i class="uil uil-user"></i>
                                        <span class="badge badge-info badge-pill float-right"></span>
                                        <span>Users</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="simplebar-placeholder" style="width: auto; height: 100px;"></div>
        </div>
        <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
            <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
        </div>
        <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
            <div class="simplebar-scrollbar"
                 style="height: 0px; transform: translate3d(0px, 0px, 0px); display: none;"></div>
        </div>
    </div>
    <!-- Sidebar -left -->

</div>
