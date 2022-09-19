@extends('layouts.vertical', ['title' => $pageTitle])

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        
        <!-- start page title -->
        @include('layouts.shared/breadcrumb')    
        <!-- end page title --> 

        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <div class="row">
                    
                        <div class="col-lg-12">
                            <div class="pl-xl-3 mt-3 mt-xl-0">
                                <h4 class="mb-4">{{__('uuid')}} : {{$result->uuid}}<h4 class="mb-4">
                               
                                <h4 class="mb-4">{{__('User Name')}} : <span class="text-muted mr-2"></span> <b>{{$result->username}}</b></h4>
                                <h4 class="mb-4">{{__('Email')}} : <span class="text-muted mr-2"></span> <b>{{$result->email}}</b></h4>
                                <h4 class="mb-4">{{__('Permission Group')}} : <span class="text-muted mr-2"></span> <b>@if(isset($result->permissionGroup)){{$result->permissionGroup->name }}@else -- @endif</b></h4>
                               
                              

                            </div>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->


                  

                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
        <!-- end row-->
        
    </div> <!-- container -->
@endsection
