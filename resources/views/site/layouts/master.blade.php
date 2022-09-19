<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Site Metas -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="{{ asset('assets/images/logo-light.png') }}" type="">
    <title>Trakar</title>
    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('site/css/bootstrap.css') }}" />

    <!-- font awesome style -->
    <link href="{{ asset('site/css/font-awesome.min.css') }}" rel="stylesheet" />

    <!-- responsive style -->
    <link href="{{ asset('site/css/responsive.css') }}" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="{{ asset('site/css/style.css') }}" rel="stylesheet" />
</head>

<body >
    <div id="app">



        @include('site.layouts.sidebar-menu')


        {{-- Content Wrapper. Contains page content --}}
   
            {{-- Main content --}}

            @yield('content')


            {{-- /.content --}}
        {{-- /.content-wrapper --}}

        {{-- Main Footer --}}

   
    </div>
    {{-- ./wrapper --}}

    @auth

    @endauth
    <!-- jQery -->
    <script src="{{ asset('site/js/jquery-3.4.1.min.js') }}"></script>
    <!-- popper js -->
    <script src="{{ asset('site/js/popper.min.js') }}"></script>
    <!-- bootstrap js -->
    <script src="{{ asset('site/js/bootstrap.js') }}"></script>
    <!-- custom js -->
    <script src="{{ asset('site/js/custom.js') }}"></script>
    <script src="{{mix('js/app.js')}}"></script> 

</body>

</html>

