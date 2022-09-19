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

        <!-- start page title -->

        @include('layouts.shared/breadcrumb')

        <!-- end page title -->


        {!! Form::open([
            'route' => isset($result) ? ['admin.attribute-tyre.update', $result->id] : 'admin.attribute-tyre.store',
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
                        <label>{{ __('Type') }}</label>
                        {!! Form::select('type_id', App\Helpers\Helper::typeForSelect(), isset($result) ? $result->type_id : null, [
                            'class' => 'select2 form-control',
                            'id' => 'type_id-form-input',
                            'autocomplete' => 'off',
                        ]) !!}

                        <div class="invalid-feedback" id="type_id-form-error"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Season') }}</label>
                        {{-- {!! Form::select(
                            'season_id',
                            App\Helpers\Helper::seasonForSelect(),
                            isset($result) ? $result->season_id : null,
                            ['class' => 'select2 form-control', 'id' => 'season_id-form-input', 'autocomplete' => 'off'],
                        ) !!} --}}
                        <select id="season_id-form-input" name="season_id" class="form-control">
                            <option
                                @if (isset($result)) @if ($result->season_id == 4)
                                selected @endif
                                @endif value="">Select Season</option>
                            @foreach ($seasons as $value)
                                <option
                                    @if (isset($result)) @if ($result->season_id == $value['id'])
                                        selected @endif
                                    @endif
                                    id="{{ $value['id'] }}" value="{{ $value['id'] }}">{{ $value['name'] }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="season_id-form-error"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Attribute') }}</label>
                        {!! Form::select(
                            'attribute_id',
                            App\Helpers\Helper::attributeForSelect(),
                            isset($result) ? $result->attribute_id : null,
                            ['class' => ' form-control', 'id' => 'attribute_id-form-input', 'autocomplete' => 'off'],
                        ) !!}

                        <div class="invalid-feedback" id="attribute_id-form-error"></div>
                    </div>
                    {{-- <div class="form-group mb-3" id="divParent" style="display: none">
                        <label>{{ __('Attribute Tyre') }}</label>
                        {{-- {!! Form::select(
                            'parent_id',
                            App\Helpers\Helper::attributeTyreForSelect(),
                            isset($result) ? $result->parent_id : null,
                            ['class' => ' form-control', 'id' => 'parent_id-form-input', 'autocomplete' => 'off'],
                        ) !!} --}}
                    {{-- <select name="width" class="form-control" id="width-form-input" style="display: none"></select>
                        <select name="hight" class="form-control" id="hight-form-input" style="display: none"></select>
                        <select name="diameter" class="form-control" id="diameter-form-input" style="display: none"></select>

                        <div class="invalid-feedback" id="parent_id-form-error"></div>
                    </div> --}}
                    <div class="form-group mb-3" id="width">
                        <label>{{ __('Width') }}</label>

                        <select name="width" class="form-control select2" id="width-form-input"></select>

                    </div>
                    <div class="form-group mb-3" id="hight">
                        <label>{{ __('Hight') }}</label>

                        <select name="hight" class="form-control select2" id="hight-form-input"></select>

                    </div>
                    <div class="form-group mb-3" id="diameter">
                        <label>{{ __('Diameter') }}</label>

                        <select name="diameter" class="form-control select2" id="diameter-form-input"></select>

                    </div>
                    <div class="form-group mb-3" id="manufacturer">
                        <label>{{ __('Manufacturer') }}</label>

                        <select name="manufacturer" class="form-control select2" id="manufacturer-form-input"></select>

                    </div>
                    <div class="form-group mb-3" id="speed" style="display: none">
                        <label>{{ __('Speed Rating') }}</label>

                        <select name="speed" class="form-control select2" id="speed-form-input"></select>

                    </div>
                    <div class="form-group mb-3" id="load" style="display: none">
                        <label>{{ __('Load Index') }}</label>

                        <select name="load" class="form-control select2" id="load-form-input"></select>

                    </div>
                    <div class="form-group mb-3" id="axle" style="display: none">
                        <label>{{ __('Axle') }}</label>

                        <select name="axle" class="form-control select2" id="axle-form-input"></select>

                    </div>


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
                '{{ isset($result) ? route('admin.attribute-tyre.update', $result->id) : route('admin.attribute-tyre.store') }}',
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
        $(document).ready(function() {
            $("#width-form-input").attr('disabled', true);
            $("#hight-form-input").attr('disabled', true);
            $("#diameter-form-input").attr('disabled', true);
            ajaxCallManufacturerAll();
            ajaxCallSpeedAll();
            ajaxCallLoadAll();
            ajaxCallAxleAll();
            @if (isset($result))
                @if ($result->type_id == 4)
                    $('#1').css("display", "none");
                    $('#2').css("display", "none");
                @endif
            @endif

        });
        $('#width-form-input').change(function() {
            $("#hight-form-input").attr('disabled', false);
            if ($("#hight-form-input option:selected").text() === 'Select Hight') {
                ajaxCallManufacturerAll();
                ajaxCallSpeedAll();
                ajaxCallLoadAll();
                ajaxCallAxleAll();
            } else {
                ajaxCallManufacturer($(this).val());
                ajaxCallSpeed($(this).val());
                ajaxCallLoad($(this).val());
                ajaxCallAxle($(this).val());
                ajaxCallHight($(this).val());
            }

        });
        $('#hight-form-input').change(function() {
            $("#diameter-form-input").attr('disabled', false);
            if ($("#diameter-form-input option:selected").text() === 'Select Diameter') {
                ajaxCallManufacturerAll();
                ajaxCallSpeedAll();
                ajaxCallLoadAll();
                ajaxCallAxleAll();
            } else {
                // alert($("#width-form-input option:selected").attr("value"));
                if ($("#hight-form-input option:selected").text() === 'Not Selected') {

                    $widthId = $("#width-form-input option:selected").attr("value");
                    ajaxCallManufacturer($("#width-form-input option:selected").attr("value"));
                    ajaxCallSpeed($("#width-form-input option:selected").attr("value"));
                    ajaxCallLoad($("#width-form-input option:selected").attr("value"));
                    ajaxCallAxle($("#width-form-input option:selected").attr("value"));
                    ajaxCallDiameter($("#width-form-input option:selected").attr("value"));
                }
                ajaxCallManufacturer($(this).val());
                ajaxCallSpeed($(this).val());
                ajaxCallLoad($(this).val());
                ajaxCallAxle($(this).val());
                ajaxCallDiameter($(this).val());
            }
        });
        $('#type_id-form-input').change(function() {
            if (this.value == 3) {
                $('#load').css("display", "block");
                $('#axle').css("display", "none");
                $('#speed').css("display", "none");
                $('#1').css("display", "block");
                $('#2').css("display", "block");
            } else if (this.value == 4) {
                $('#axle').css("display", "block");
                $('#speed').css("display", "block");
                $('#load').css("display", "none");
                $('#1').css("display", "none");
                $('#2').css("display", "none");
            } else if (this.value == 1) {
                $('#speed').css("display", "block");
                $('#axle').css("display", "none");
                $('#4').css("display", "none");
                $('#1').css("display", "block");
                $('#2').css("display", "block");
            } else if (this.value == 2) {
                $('#speed').css("display", "block");
                $('#axle').css("display", "none");
                $('#4').css("display", "none");
                $('#1').css("display", "block");
                $('#2').css("display", "block");
            }
        });
        $('#attribute_id-form-input').change(function() {
            if (this.value != 1) {
                $('#divParent').css("display", "block");
                if (this.value == 2 || this.value == 3 || this.value == 4 || this.value == 5 || this.value == 6 ||
                    this.value == 7) {
                    $("#width-form-input").attr('disabled', false);
                    ajaxCallManufacturer($(this).val());
                    ajaxCallSpeed($(this).val());
                    ajaxCallLoad($(this).val());
                    ajaxCallAxle($(this).val());
                    ajaxCallWidth($(this).val());
                }


            } else {
                $('#divParent').css("display", "none");
            }
        });

        function ajaxCallWidth($val) {
            $.ajax({
                data: {
                    '_token': '{{ csrf_token() }}',
                    'page': 'create',

                },
                type: 'POST',
                url: '{{ url('admin/searchAttributeWidth') }}',
                dataType: 'json',
                success: function(response) {

                    console.log(response.data); // show [object, Object]

                    var $select = $('#width-form-input');

                    $select.find('option').remove();
                    $select.append('<option>Select Width</option>');
                    $.each(response.data, function(key, value) {
                        $select.append('<option value=' + value.id + '>' +
                            value
                            .value +
                            '</option>');
                    });
                }
            });
        }

        function ajaxCallHight($val) {
            $.ajax({
                data: {
                    '_token': '{{ csrf_token() }}',
                    'parent_id': $val,
                    'page': 'create',
                },
                type: 'POST',
                url: '{{ url('admin/searchAttributeHight') }}',
                dataType: 'json',
                success: function(response) {

                    console.log(response.data); // show [object, Object]

                    var $select = $('#hight-form-input');
                    var $diameter = $('#diameter-form-input');

                    $diameter.find('option').remove();
                    $select.find('option').remove();
                    $select.append('<option>Select Hight</option><option value="">Not Selected</option>');

                    $.each(response.data, function(key, value) {
                        $select.append('<option value=' + value.id + '>' +
                            value
                            .value +
                            '</option>');
                    });
                }
            });
        }

        function ajaxCallDiameter($val) {
            $.ajax({
                data: {
                    '_token': '{{ csrf_token() }}',
                    'parent_id': $val,
                    'page': 'create',
                },
                type: 'POST',
                url: '{{ url('admin/searchAttributeDiameter') }}',
                dataType: 'json',
                success: function(response) {

                    console.log(response.data); // show [object, Object]

                    var $select = $('#diameter-form-input');

                    $select.find('option').remove();
                    $select.append('<option>Select Diameter</option>');

                    $.each(response.data, function(key, value) {
                        $select.append('<option value=' + value.id + '>' +
                            value
                            .value +
                            '</option>');
                    });
                }
            });
        }

        function ajaxCallManufacturerAll() {
            $.ajax({
                data: {
                    '_token': '{{ csrf_token() }}',
                    'page': 'create',
                },
                type: 'POST',
                url: '{{ url('admin/searchAttributeManufacturer') }}',
                dataType: 'json',
                success: function(response) {

                    console.log(response.data); // show [object, Object]

                    var $select = $('#manufacturer-form-input');

                    $select.find('option').remove();
                    $select.append('<option>Select Manufacturer</option>');

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
                url: '{{ url('admin/searchAttributeManufacturer') }}',
                dataType: 'json',
                success: function(response) {

                    console.log(response.data); // show [object, Object]

                    var $select = $('#manufacturer-form-input');

                    $select.find('option').remove();
                    $select.append('<option>Select Manufacturer</option>');

                    $.each(response.data, function(key, value) {
                        $select.append('<option value=' + value.id + '>' +
                            value
                            .value +
                            '</option>');
                    });
                }
            });
        }

        function ajaxCallSpeedAll() {
            $.ajax({
                data: {
                    '_token': '{{ csrf_token() }}',
                    'page': 'create',
                },
                type: 'POST',
                url: '{{ url('admin/searchAttributeSpeadRating') }}',
                dataType: 'json',
                success: function(response) {

                    console.log(response.data); // show [object, Object]

                    var $select = $('#speed-form-input');

                    $select.find('option').remove();
                    $select.append('<option>Select Speed Rating</option>');

                    $.each(response.data, function(key, value) {
                        $select.append('<option value=' + value.id + '>' +
                            value
                            .value +
                            '</option>');
                    });
                }
            });
        }

        function ajaxCallSpeed($val, seasonId) {
            $.ajax({
                data: {
                    '_token': '{{ csrf_token() }}',
                    'page': 'create',
                    'parent_id': $val,
                },
                type: 'POST',
                url: '{{ url('admin/searchAttributeSpeadRating') }}',
                dataType: 'json',
                success: function(response) {

                    console.log(response.data); // show [object, Object]

                    var $select = $('#speed-form-input');

                    $select.find('option').remove();
                    $select.append('<option>Select Speed Rating</option>');

                    $.each(response.data, function(key, value) {
                        $select.append('<option value=' + value.id + '>' +
                            value
                            .value +
                            '</option>');
                    });
                }
            });
        }

        function ajaxCallLoadAll() {
            $.ajax({
                data: {
                    '_token': '{{ csrf_token() }}',
                    'page': 'create',
                },
                type: 'POST',
                url: '{{ url('admin/searchAttributeLoad') }}',
                dataType: 'json',
                success: function(response) {

                    console.log(response.data); // show [object, Object]

                    var $select = $('#load-form-input');

                    $select.find('option').remove();
                    $select.append('<option>Select Load Rating</option>');

                    $.each(response.data, function(key, value) {
                        $select.append('<option value=' + value.id + '>' +
                            value
                            .value +
                            '</option>');
                    });
                }
            });
        }

        function ajaxCallLoad($val, seasonId) {
            $.ajax({
                data: {
                    '_token': '{{ csrf_token() }}',
                    'parent_id': $val,
                    'page': 'create',
                },
                type: 'POST',
                url: '{{ url('admin/searchAttributeLoad') }}',
                dataType: 'json',
                success: function(response) {

                    console.log(response.data); // show [object, Object]

                    var $select = $('#load-form-input');

                    $select.find('option').remove();
                    $select.append('<option>Select Speed Load</option>');

                    $.each(response.data, function(key, value) {
                        $select.append('<option value=' + value.id + '>' +
                            value
                            .value +
                            '</option>');
                    });
                }
            });
        }

        function ajaxCallAxleAll() {
            $.ajax({
                data: {
                    '_token': '{{ csrf_token() }}',
                    'page': 'create',
                },
                type: 'POST',
                url: '{{ url('admin/searchAttributeAlex') }}',
                dataType: 'json',
                success: function(response) {

                    console.log(response.data); // show [object, Object]

                    var $select = $('#axle-form-input');

                    $select.find('option').remove();
                    $select.append('<option>Select Axel</option>');

                    $.each(response.data, function(key, value) {
                        $select.append('<option value=' + value.id + '>' +
                            value
                            .value +
                            '</option>');
                    });
                }
            });
        }

        function ajaxCallAxle($val, seasonId) {
            $.ajax({
                data: {
                    '_token': '{{ csrf_token() }}',
                    'parent_id': $val,
                    'page': 'create',
                },
                type: 'POST',
                url: '{{ url('admin/searchAttributeAlex') }}',
                dataType: 'json',
                success: function(response) {

                    console.log(response.data); // show [object, Object]

                    var $select = $('#axle-form-input');

                    $select.find('option').remove();
                    $select.append('<option>Select Axel</option>');

                    $.each(response.data, function(key, value) {
                        $select.append('<option value=' + value.id + '>' +
                            value
                            .value +
                            '</option>');
                    });
                }
            });
        }
        $('#type_id-form-input').change(function() {
            if (this.val == 4) {

            }
        });
    </script>
@endsection
