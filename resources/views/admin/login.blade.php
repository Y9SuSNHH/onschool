<!DOCTYPE html>
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

</head>

<body class="loading authentication-bg"
      data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
<div class="account-pages mt-5 mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card">

                    <!-- Logo -->
                    <div class="card-header pt-4 pb-4 text-center bg-primary">
                        <a href="#">
                            <span><img src="{{asset('img/logo.png')}}" alt="" height="30"></span>
                        </a>
                    </div>

                    <div class="card-body p-4">

                        <div class="text-center w-75 m-auto">
                            <h4 class="text-dark-50 text-center mt-0 font-weight-bold">Sign In</h4>
                            <p class="text-muted mb-4">Enter your username and password to access manage.</p>
                        </div>

                        <form action="{{route('api.login')}}" method="POST" id="form-login">
                            @csrf
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input class="form-control" type="text" id="username" name="username" required
                                       placeholder="Enter your username">
                            </div>

                            <div class="form-group">
                                <a href="pages-recoverpw.html" class="text-muted float-right"><small>Forgot your
                                        password?</small></a>
                                <label for="password">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" name="password" required class="form-control"
                                           placeholder="Enter your password">
                                    <div class="input-group-append" data-password="false">
                                        <div class="input-group-text">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="checkbox-signin" checked>
                                    <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                                </div>
                            </div>

                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-primary" type="submit"> Log In</button>
                            </div>

                        </form>
                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->

                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <p class="text-muted">Don't have an account? <a href="pages-register.html"
                                                                        class="text-muted ml-1"><b>Sign Up</b></a></p>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end page -->

<footer class="footer footer-alt">
    2022
    @if(date('Y')!= 2022)
        - {{date('Y')}}
    @endif
    © {{config('app.name')}}
</footer>

<!-- bundle -->
<script src="{{asset('js/vendor.min.js')}}"></script>
<script src="{{asset('js/app.min.js')}}"></script>
<script src="{{asset('js/helper.js')}}"></script>
<script type="text/javascript">
    function submitForm(form, type) {
        form.on('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(form[0]);
            $.ajax({
                url: form.attr('action'),
                type: type,
                dataType: 'JSON',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    setJWT(response.data);
                    let route = `{{route('admin.index')}}`;
                    window.location.href = `${route}`;
                },
                error: function (response) {
                    notifyError(response.responseJSON.message)
                },
            });
        });
    }

    $(document).ready(function () {
        submitForm($("#form-login"), "POST");
    });
</script>
</body>
</html>
