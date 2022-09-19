<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.shared.title-meta', ['title' => 'Log In'])

    @include('layouts.shared.head-css')
</head>

<body class="authentication-bg authentication-bg-pattern">

    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-pattern">

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <div class="auth-logo">
                                    <a href="#" class="logo logo-dark text-center">
                                        <span class="logo-lg">
                                            <img src="{{ asset('assets/images/TRKAT.png') }}" alt=""
                                                height="22">
                                        </span>
                                    </a>

                                    <a href="#}" class="logo logo-light text-center">
                                        <span class="logo-lg">
                                            <img src="{{ asset('assets/images/TRKAT.png') }}" alt=""
                                                height="22">
                                        </span>
                                    </a>
                                </div>
                                <p class="text-muted mb-4 mt-3">Enter your email address and password to access admin
                                    panel.</p>
                            </div>
                            {!! Form::open([
                                'route' => 'admin.login',
                                'method' => 'POST',
                                'class' => 'forms-sample',
                                'id' => 'main-form',
                                'onsubmit' => 'submitMainForm();return false;',
                            ]) !!}
                            <div id="form-alert-message"></div>
                            <div class="form-group mb-3">
                                <label for="emailaddress">Email address</label>
                                <input class="form-control  @if ($errors->has('email')) is-invalid @endif"
                                    name="email" type="email" id="email-form-error" id="emailaddress" required=""
                                    value="{{ old('email') }}" placeholder="Enter your email" />
                                    <div class="invalid-feedback" id="email-form-error"></div>

                                {{-- @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif --}}
                            </div>

                            <div class="form-group mb-3">
                                <a href="{{ route('admin.login') }}" class="text-muted float-right"><small>Forgot your
                                        password?</small></a>
                                <label for="password">Password</label>
                                <div
                                    class="input-group input-group-merge @if ($errors->has('password')) is-invalid @endif">
                                    <input class="form-control @if ($errors->has('password')) is-invalid @endif"
                                        name="password" type="password" required="" id="password"
                                        placeholder="Enter your password" />
                                    <div class="input-group-append" data-password="false">
                                        <div class="input-group-text">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="checkbox-signin" checked>
                                    <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                                </div>
                            </div>

                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-primary btn-block" type="submit"> Log In </button>
                            </div>

                            {!! Form::close() !!}

                          

                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->


                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->


    <footer class="footer footer-alt">
        <script>
            document.write(new Date().getFullYear())
        </script> &copy; Lacasa-code theme by <a href="" class="text-white-50">traker</a>
    </footer>

    @include('layouts.shared.footer-script')
    <script src="{{ asset('js/helper.js') }}"></script>

    <script type="text/javascript">
        function submitMainForm() {
            formSubmit(
                '{{ route('admin.login') }}',
                $('#main-form').serialize(),
                function($data) {
                    if ($data.status) {
                        window.location = $data.data.url;
                    } else {
                        $("html, body").animate({
                            scrollTop: 0
                        }, "fast");
                        pageAlert('#form-alert-message', 'error', $data.message);

                     
                    }
                },
                function($data) {
                    $("html, body").animate({
                        scrollTop: 0
                    }, "fast");
                    pageAlert('#form-alert-message', 'error', $data.message);
                }
            );
        }
    </script>
</body>

</html>
