<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $title ? $title.' | ' : '' }}{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
    <meta content="Coderthemes" name="author">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('img/logo.png')}}">

    <!-- App css -->
    <link href="{{asset('css/icons.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/app-modern.min.css')}}" rel="stylesheet" type="text/css" id="light-style"
          disabled="disabled">
    <link href="{{asset('css/app-modern-dark.min.css')}}" rel="stylesheet" type="text/css" id="dark-style">
    @stack('css')
</head>
<body class="loading"
      data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
<div class="wrapper mm-active">
    @include('layout.admin.sidebar')
    <div class="content-page">
        <div class="content">
            @include('layout.admin.navbar')
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Hyper</a></li>
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                                    <li class="breadcrumb-item active">Basic Tables</li>
                                </ol>
                            </div>
                            <h4 class="page-title">{{$title}}</h4>
                        </div>
                    </div>
                </div>
                @yield('content')
            </div>
            <!-- container -->

        </div>
        @include('layout.admin.footer')
    </div>
</div>
<div class="right-bar">

    <div class="rightbar-title">
        <a href="javascript:void(0);" class="right-bar-toggle float-right">
            <i class="dripicons-cross noti-icon"></i>
        </a>
        <h5 class="m-0">Settings</h5>
    </div>

    <div class="rightbar-content h-100" data-simplebar="init">
        <div class="simplebar-wrapper" style="margin: 0px;">
            <div class="simplebar-height-auto-observer-wrapper">
                <div class="simplebar-height-auto-observer"></div>
            </div>
            <div class="simplebar-mask">
                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                    <div class="simplebar-content-wrapper" style="height: 100%; overflow: hidden scroll;">
                        <div class="simplebar-content" style="padding: 0px;">

                            <div class="p-3">
                                <div class="alert alert-warning" role="alert">
                                    <strong>Customize </strong> the overall color scheme, sidebar menu, etc.
                                </div>

                                <!-- Settings -->
                                <h5 class="mt-3">Color Scheme</h5>
                                <hr class="mt-1">

                                <div class="custom-control custom-switch mb-1">
                                    <input type="radio" class="custom-control-input" name="color-scheme-mode"
                                           value="light" id="light-mode-check" checked="">
                                    <label class="custom-control-label" for="light-mode-check">Light Mode</label>
                                </div>

                                <div class="custom-control custom-switch mb-1">
                                    <input type="radio" class="custom-control-input" name="color-scheme-mode"
                                           value="dark" id="dark-mode-check">
                                    <label class="custom-control-label" for="dark-mode-check">Dark Mode</label>
                                </div>

                                <!-- Width -->
                                <h5 class="mt-4">Width</h5>
                                <hr class="mt-1">
                                <div class="custom-control custom-switch mb-1">
                                    <input type="radio" class="custom-control-input" name="width" value="fluid"
                                           id="fluid-check" checked="">
                                    <label class="custom-control-label" for="fluid-check">Fluid</label>
                                </div>
                                <div class="custom-control custom-switch mb-1">
                                    <input type="radio" class="custom-control-input" name="width" value="boxed"
                                           id="boxed-check">
                                    <label class="custom-control-label" for="boxed-check">Boxed</label>
                                </div>

                                <!-- Left Sidebar-->
                                <h5 class="mt-4">Left Sidebar</h5>
                                <hr class="mt-1">
                                <div class="custom-control custom-switch mb-1">
                                    <input type="radio" class="custom-control-input" name="theme" value="default"
                                           id="default-check" checked="">
                                    <label class="custom-control-label" for="default-check">Default</label>
                                </div>

                                <div class="custom-control custom-switch mb-1">
                                    <input type="radio" class="custom-control-input" name="theme" value="light"
                                           id="light-check">
                                    <label class="custom-control-label" for="light-check">Light</label>
                                </div>

                                <div class="custom-control custom-switch mb-3">
                                    <input type="radio" class="custom-control-input" name="theme" value="dark"
                                           id="dark-check">
                                    <label class="custom-control-label" for="dark-check">Dark</label>
                                </div>

                                <div class="custom-control custom-switch mb-1">
                                    <input type="radio" class="custom-control-input" name="compact" value="fixed"
                                           id="fixed-check" checked="">
                                    <label class="custom-control-label" for="fixed-check">Fixed</label>
                                </div>

                                <div class="custom-control custom-switch mb-1">
                                    <input type="radio" class="custom-control-input" name="compact" value="condensed"
                                           id="condensed-check">
                                    <label class="custom-control-label" for="condensed-check">Condensed</label>
                                </div>

                                <div class="custom-control custom-switch mb-1">
                                    <input type="radio" class="custom-control-input" name="compact" value="scrollable"
                                           id="scrollable-check">
                                    <label class="custom-control-label" for="scrollable-check">Scrollable</label>
                                </div>

                                <button class="btn btn-primary btn-block mt-4" id="resetBtn">Reset to Default</button>

                                <a href="https://themes.getbootstrap.com/product/hyper-responsive-admin-dashboard-template/"
                                   class="btn btn-danger btn-block mt-3" target="_blank"><i
                                        class="mdi mdi-basket mr-1"></i> Purchase Now</a>
                            </div> <!-- end padding-->

                        </div>
                    </div>
                </div>
            </div>
            <div class="simplebar-placeholder" style="width: auto; height: 756px;"></div>
        </div>
        <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
            <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
        </div>
        <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
            <div class="simplebar-scrollbar"
                 style="height: 253px; display: block; transform: translate3d(0px, 0px, 0px);"></div>
        </div>
    </div>
</div>
<div class="rightbar-overlay"></div>
<!-- /Right-bar -->
<!-- bundle -->
<script src="{{asset('js/vendor.min.js')}}"></script>
<script src="{{asset('js/app.min.js')}}"></script>
<script src="{{asset('js/helper.js')}}"></script>
<script type="text/javascript">
    function submitForm(form, type) {
        form.on('submit', function (event) {
            event.preventDefault();
            $.ajax({
                url: form.attr('action'),
                type: type,
                dataType: 'JSON',
                headers: {Authorization: `${getJWT().token_type} ` + getJWT().access_token},
                success: function (response) {
                    notifySuccess(response.message);
                    let route_login = `{{route('admin.login')}}`
                    window.location.href = `${route_login}`;
                },
                error: function (response) {
                    console.log(response)
                },
            });
        });
    }

    $(document).ready(function () {
        submitForm($("#form-logout"), "POST");
    });
</script>

@stack('js')
</body>
</html>
