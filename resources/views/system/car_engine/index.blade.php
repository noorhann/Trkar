@extends('layouts.vertical', ['title' => 'car-engin'])

@section('css')
    <!-- Plugins css -->
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/summernote/summernote.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
@endsection



@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            @include('system.car_engine/table')
        </div>
    </div>
 
@endsection

