@yield('css')

<!-- icons -->
<link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />

@if (Auth::check())
    @if (Auth::user()->language == 'ar')
        <link href="{{ asset('assets/css/app-rtl.min.css') }} " rel="stylesheet" type="text/css"
            id="app-default-stylesheet" />
        <link href="{{ asset('assets/css/app-rtl.css') }} " rel="stylesheet" type="text/css"
            id="app-default-stylesheet" />
        <link href="{{ asset('assets/css/bootstrap-dark.min.css') }} " rel="stylesheet" type="text/css"
            id="bs-dark-stylesheet" disabled />
        <link href="{{ asset('assets/css/app-dark-rtl.min.css') }} " rel="stylesheet" type="text/css"
            id="app-dark-stylesheet" disabled />
    @else
        <link href="{{ asset('assets/css/app.min.css') }} " rel="stylesheet" type="text/css"
            id="app-default-stylesheet" />
        <link href="{{ asset('assets/css/app.css') }} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
        <link href="{{ asset('assets/css/bootstrap-dark.min.css') }} " rel="stylesheet" type="text/css"
            id="bs-dark-stylesheet" disabled />
        <link href="{{ asset('assets/css/app-dark.min.css') }} " rel="stylesheet" type="text/css"
            id="app-dark-stylesheet" disabled />
    @endif
@else
    <link href="{{ asset('assets/css/app.min.css') }} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
    <link href="{{ asset('assets/css/app.css') }} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
    <link href="{{ asset('assets/css/bootstrap-dark.min.css') }} " rel="stylesheet" type="text/css"
        id="bs-dark-stylesheet" disabled />
    <link href="{{ asset('assets/css/app-dark.min.css') }} " rel="stylesheet" type="text/css" id="app-dark-stylesheet"
        disabled />
@endif
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
