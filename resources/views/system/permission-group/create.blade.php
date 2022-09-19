@extends('layouts.vertical', ['title' => $pageTitle])

@section('css')
    <!-- Plugins css -->
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/summernote/summernote.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        @include('layouts.shared/breadcrumb')

        {!! Form::open([
            'route' => isset($result) ? ['admin.permission-group.update', $result->id] : 'admin.permission-group.store',
            'files' => true,
            'method' => isset($result) ? 'PATCH' : 'POST',
            'class' => 'forms-sample',
            'id' => 'main-form',
            'onsubmit' => 'submitMainForm();return false;',
        ]) !!}
        <div class="card-body">
            <div id="form-alert-message"></div>

            <div class="row">


                {{-- <div class="row"> --}}
                <div class="col-lg-12">
                    <div class="card-box">
                        <label>{{ __('Name') }}</label>
                        {!! Form::text('name', isset($result) ? $result->name : null, [
                            'class' => 'form-control',
                            'id' => 'name-form-input',
                            'autocomplete' => 'off',
                        ]) !!}
                        <div class="invalid-feedback" id="name-form-error"></div>
                    </div>
                </div>
            </div>
            <div class="row">


                {{-- <div class="row"> --}}
                <div class="col-lg-12">
                    <div class="card-box">
                        <a href="javascript:void(0);" class="btn btn-primary text-center"
                            onclick="$('input[name=\'permission_ids[]\']').prop('checked',true)">
                            <i class="fa fa-star"></i> {{ __('Select All') }}
                        </a>
                        <a href="javascript:void(0);" class="btn btn-outline-warning text-center"
                            onclick="$('input[name=\'permission_ids[]\']').prop('checked',false)">
                            <i class="fa fa-star-o"></i> {{ __('Deselect All') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">


                {{-- <div class="row"> --}}
                <div class="col-lg-12">
                    <div class="card-box">
                @foreach ($permissions as $value)
                    <div style="margin-bottom: 20px;font-size: .875rem;letter-spacing: normal;padding: 10px;background-color: #fff;border: 2px solid #e8ebf1;position: relative;"
                        class="example col-sm-12 permissions">
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-sm-6">
                                <h5 class="primary">
                                    {{ $value['name'] }}
                                </h5>
                            </div>
                            <div class="col-sm-6" style="text-align: left;">
                                <input type="checkbox" onclick="CheckPerms(this);">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row" style="padding-top: 10px;">
                                    @foreach ($value['permissions'] as $key => $permissionsValue)
                                        <label class="col-sm-4">
                                            {!! Form::checkbox(
                                                'permission_ids[]',
                                                "$key",
                                                isset($result) ? !array_diff($permissionsValue, $result->permissions) : false,
                                            ) !!}
                                            {!! __(ucfirst(str_replace('-', ' ', $key))) !!}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach



            </div>
            </div>
            </div>

        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary mr-2">{{ isset($result) ? __('Edit') : __('Submit') }}</button>
        </div>
        {!! Form::close() !!}

        <!-- end row -->


        <!-- end row -->





    </div> <!-- container -->
@endsection

@section('script')
    <!-- Plugins js-->
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/summernote/summernote.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>

    <!-- Page js-->
    <script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>
    <script src="{{ asset('assets/js/pages/add-product.init.js') }}"></script>
    <script src="{{ asset('js/helper.js') }}"></script>
    <script type="text/javascript">
        function submitMainForm() {
            var route = $('#main-form').attr('action');
            formSubmit(
                route,
                new FormData($('#main-form')[0]),
                function($data) {
                    if ($data.status) {
                        if (typeof $data.data.url !== 'undefined') {
                            $('#main-form')[0].reset();
                            $("html, body").animate({
                                scrollTop: 0
                            }, "fast");
                            pageAlert('#form-alert-message', 'success', $data.message);
                            setTimeout(function() {
                                window.location = $data.data.url;
                            }, 2500);
                        } else {
                            $('#main-form')[0].reset();
                            $("html, body").animate({
                                scrollTop: 0
                            }, "fast");
                            pageAlert('#form-alert-message', 'success', $data.message);
                        }
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


        function CheckPerms(perm) {
            var permessions = $(perm).parents('.permissions').find('input[type=\'checkbox\']');
            //console.log(permessions);
            if ($(perm).is(':checked')) {
                $(permessions).prop('checked', true);
            } else {
                $(permessions).prop('checked', false);
            }
        }
    </script>
@endsection
