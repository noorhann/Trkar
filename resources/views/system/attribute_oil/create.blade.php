@extends('layouts.vertical', ['title' => $pageTitle])

@section('css')
    <!-- Plugins css -->
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/summernote/summernote.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    @endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->

        @include('layouts.shared/breadcrumb')

        <!-- end page title -->


        {!! Form::open([
            'route' => isset($result) ? ['admin.attribute-oil.update', $result->id] : 'admin.attribute-oil.store',
            'files' => true,
            'method' => isset($result) ? 'PATCH' : 'POST',
            'class' => 'forms-sample',
            'id' => 'main-form',
            'onsubmit' => 'submitMainForm();return false;',
        ]) !!}
        <div id="form-alert-message"></div>
        <div class="row">


            {{-- <div class="row"> --}}
            <div class="col-lg-12">
                <div class="card-box">



                    <div class="form-group mb-3">
                        <label>{{ __('Attribute') }}</label>
                        {!! Form::select(
                            'attribute_id',
                            App\Helpers\Helper::attributeOilForSelect(),
                            isset($result) ? $result->attribute_id : null,
                            ['class' => ' form-control', 'id' => 'attribute_id-form-input', 'autocomplete' => 'off'],
                        ) !!}

                        <div class="invalid-feedback" id="attribute_id-form-error"></div>
                    </div>
                    <i onclick="refresh()" class="fa fa-refresh" style="font-size:24px"></i>
                    <div class="form-group mb-3" id="sae">
                        <label>{{ __('SAE viscosity grade') }}</label>
                      
                        <select name="sae" class="form-control" id="sae-form-input">
                            @if (isset($result))
                            @foreach ($parent as $value)
                                @if ($value->attribute_id == 8)
                                    <option selected value="{{$value->id}}">{{$value->value}}</option>
                                @endif
                            @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group mb-3" id="manufacturer">
                        <label>{{ __('Manufacturer') }}</label>
                        <select name="manufacturer" class="form-control" id="manufacturer-form-input">
                            @if (isset($result))
                            @foreach ($parent as $value)
                                @if ($value->attribute_id == 7)
                                    <option selected value="{{$value->id}}">{{$value->value}}</option>
                                @endif
                            @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group mb-3" id="oem">
                        <label>{{ __('OEM Approval') }}</label>
                        <select name="oem" class="form-control" id="oem-form-input">
                            @if (isset($result))
                            @foreach ($parent as $value)
                                @if ($value->attribute_id == 9)
                                    <option selected value="{{$value->id}}">{{$value->value}}</option>
                                @endif
                            @endforeach
                            @endif
                        </select>
                    </div>
                    {{-- <div class="form-group mb-3" id="specification">
                        <label>{{ __('Specification') }}</label>
                        <select name="specification" class="form-control" id="specification-form-input"></select>
                    </div> --}}


                    <div class="form-group mb-3">
                        <label>{{ __('Value') }}</label>
                        {!! Form::text('value', isset($result) ? $result->value : null, [
                            'class' => 'form-control',
                            'id' => 'value-form-input',
                            'autocomplete' => 'off',
                        ]) !!}
                        <div class="invalid-feedback" id="value-form-error"></div>
                    </div>

                </div> <!-- end card-box -->
            </div> <!-- end col -->



        </div>
        <div class="row">
            <div class="col-12">
                <div class="text-center mb-3">

                    <button type="submit"
                        class="btn w-sm btn-success waves-effect waves-light">{{ isset($result) ? __('Edit') : __('Submit') }}</button>
                </div>
            </div> <!-- end col -->
        </div>
        {{-- </div> --}}
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

            formSubmit(
                '{{ isset($result) ? route('admin.attribute-oil.update', $result->id) : route('admin.attribute-oil.store') }}',
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
        // $(document).ready(function() {
        //     ajaxCallManufacturerAll();
        //     ajaxCallSaeAll();
        //     ajaxCallOemAll();
        // });
        $('#attribute_id-form-input').change(function() {
            if ($(this).val() == 8) {
                $("#sae-form-input").attr('disabled', true);
                $("#manufacturer-form-input").attr('disabled', true);
                $("#oem-form-input").attr('disabled', true);

                var $sae = $('#sae-form-input');
                $sae.find('option').remove();

                var $manufacturer = $('#manufacturer-form-input');
                $manufacturer.find('option').remove();

                var $oem = $('#oem-form-input');
                $oem.find('option').remove();
            } else {
                $("#sae-form-input").attr('disabled', false);
                $("#manufacturer-form-input").attr('disabled', false);
                $("#oem-form-input").attr('disabled', false);

                ajaxCallManufacturerAll();
                ajaxCallSaeAll();
                ajaxCallOemAll();
            }

        });
        $('#sae-form-input').change(function() {
            ajaxCallManufacturer($(this).val());
            ajaxCallOem($(this).val());
            ajaxCallSpecification($(this).val());
        });

        $('#manufacturer-form-input').change(function() {
            // ajaxCallSae($(this).val());
            ajaxCallOem($(this).val());
            ajaxCallSpecification($(this).val());
        });

        function refresh() {
            ajaxCallManufacturerAll();
            ajaxCallSaeAll();
            ajaxCallOemAll();
        }
        function ajaxCallManufacturerAll() {
            $.ajax({
                data: {
                    '_token': '{{ csrf_token() }}',
                    'page': 'create',
                },
                type: 'POST',
                url: '{{ url('admin/attribute-oil/searchAttributeManufacturer') }}',
                dataType: 'json',
                success: function(response) {

                    console.log(response.data); // show [object, Object]

                    var $select = $('#manufacturer-form-input');

                    $select.find('option').remove();
                    $select.append('<option value="null">Select Manufacturer</option>');

                    $.each(response.data, function(key, value) {
                        $select.append('<option value=' + value.id + '>' +
                            value
                            .value +
                            '</option>');
                    });
                }
            });
        }

        function ajaxCallManufacturer($val, seasonId) {
            $.ajax({
                data: {
                    '_token': '{{ csrf_token() }}',
                    'parent_id': $val,
                    'page': 'create',
                },
                type: 'POST',
                url: '{{ url('admin/attribute-oil/searchAttributeManufacturer') }}',
                dataType: 'json',
                success: function(response) {

                    console.log(response.data); // show [object, Object]

                    var $select = $('#manufacturer-form-input');

                    $select.find('option').remove();
                    $select.append('<option value="null">Select Manufacturer</option>');

                    $.each(response.data, function(key, value) {
                        $select.append('<option value=' + value.id + '>' +
                            value
                            .value +
                            '</option>');
                    });
                }
            });
        }

        function ajaxCallSaeAll() {
            $.ajax({
                data: {
                    '_token': '{{ csrf_token() }}',
                    'page': 'create',
                },
                type: 'POST',
                url: '{{ url('admin/attribute-oil/searchAttributeSae') }}',
                dataType: 'json',
                success: function(response) {

                    console.log(response.data); // show [object, Object]

                    var $select = $('#sae-form-input');

                    $select.find('option').remove();
                    $select.append('<option value="null">Select SAE viscosity grade</option>');

                    $.each(response.data, function(key, value) {
                        $select.append('<option value=' + value.id + '>' +
                            value
                            .value +
                            '</option>');
                    });
                }
            });
        }

        function ajaxCallSae($val, seasonId) {
            $.ajax({
                data: {
                    '_token': '{{ csrf_token() }}',
                    'page': 'create',
                    'parent_id': $val,
                },
                type: 'POST',
                url: '{{ url('admin/attribute-oil/searchAttributeSae') }}',
                dataType: 'json',
                success: function(response) {

                    console.log(response.data); // show [object, Object]

                    var $select = $('#sae-form-input');

                    $select.find('option').remove();
                    $select.append('<option value="null">Select SAE viscosity grade</option>');

                    $.each(response.data, function(key, value) {
                        $select.append('<option value=' + value.id + '>' +
                            value
                            .value +
                            '</option>');
                    });
                }
            });
        }

        function ajaxCallOemAll() {
            $.ajax({
                data: {
                    '_token': '{{ csrf_token() }}',
                    'page': 'create',
                },
                type: 'POST',
                url: '{{ url('admin/attribute-oil/searchAttributeOem') }}',
                dataType: 'json',
                success: function(response) {

                    console.log(response.data); // show [object, Object]

                    var $select = $('#oem-form-input');

                    $select.find('option').remove();
                    $select.append('<option value="null">{{__('Select OEM approval')}}</option>');

                    $.each(response.data, function(key, value) {
                        $select.append('<option value=' + value.id + '>' +
                            value
                            .value +
                            '</option>');
                    });
                }
            });
        }

        function ajaxCallOem($val, seasonId) {
            $.ajax({
                data: {
                    '_token': '{{ csrf_token() }}',
                    'parent_id': $val,
                    'page': 'create',
                },
                type: 'POST',
                url: '{{ url('admin/attribute-oil/searchAttributeOem') }}',
                dataType: 'json',
                success: function(response) {

                    console.log(response.data); // show [object, Object]

                    var $select = $('#oem-form-input');

                    $select.find('option').remove();
                    $select.append('<option value="null">{{__('Select OEM approval')}}</option>');

                    $.each(response.data, function(key, value) {
                        $select.append('<option value=' + value.id + '>' +
                            value
                            .value +
                            '</option>');
                    });
                }
            });
        }

        function ajaxCallSpecificationAll() {
            $.ajax({
                data: {
                    '_token': '{{ csrf_token() }}',
                    'page': 'create',
                },
                type: 'POST',
                url: '{{ url('admin/attribute-oil/searchAttributeSpecification') }}',
                dataType: 'json',
                success: function(response) {

                    console.log(response.data); // show [object, Object]

                    var $select = $('#specification-form-input');

                    $select.find('option').remove();
                    $select.append('<option value="null">Select Specification</option>');

                    $.each(response.data, function(key, value) {
                        $select.append('<option value=' + value.id + '>' +
                            value
                            .value +
                            '</option>');
                    });
                }
            });
        }

        function ajaxCallSpecification($val, seasonId) {
            $.ajax({
                data: {
                    '_token': '{{ csrf_token() }}',
                    'parent_id': $val,
                    'page': 'create',
                },
                type: 'POST',
                url: '{{ url('admin/attribute-oil/searchAttributeSpecification') }}',
                dataType: 'json',
                success: function(response) {

                    console.log(response.data); // show [object, Object]

                    var $select = $('#specification-form-input');

                    $select.find('option').remove();
                    $select.append('<option value="null">Select Specification</option>');

                    $.each(response.data, function(key, value) {
                        $select.append('<option value=' + value.id + '>' +
                            value
                            .value +
                            '</option>');
                    });
                }
            });
        }
    </script>
@endsection
