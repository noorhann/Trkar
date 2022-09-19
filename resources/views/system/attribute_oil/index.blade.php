@extends('layouts.vertical', ['title' => $pageTitle])

@section('css')
    <!-- Plugins css -->
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/summernote/summernote.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection



@section('content')
    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <div class="card-box">

                <div class="tab-content">


                    <div class="tab-pane active" id="data">
                        <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle mr-1"></i>
                            {{ __('Select Engine Oil Requirements') }} :
                        </h5>
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <i onclick="refresh()" class="fa fa-refresh" style="font-size:24px"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('SAE viscosity grade') }}</label>

                                    <select name="sae" class="form-control" id="sae-form-input">

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('Manufacturer') }}</label>
                                    <select name="manufacturer" class="form-control" id="manufacturer-form-input">

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('OEM Approval') }}</label>
                                    <select name="oem" class="form-control" id="oem-form-input">

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('Specification') }}</label>
                                    <select name="specification" class="form-control"
                                        id="specification-form-input"></select>
                                </div>
                            </div>

                        </div> <!-- end row -->

                    </div>
                    {{-- @endforeach --}}

                    <!-- end settings content-->

                </div> <!-- end tab-content -->
            </div> <!-- end card-box-->

        </div> <!-- end col -->


    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">

            <!-- Filter -->
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="collapse" id="filter-collapse">
                            {!! Form::open([
                                'id' => 'filterForm',
                                'onsubmit' => 'tablePaginationFilter($(this));return false;',
                                'class' => 'forms-sample',
                            ]) !!}
                            <div class="card-body">
                                <h6 class="card-title">{{ __('Filter') }}</h6>
                                <div id="form-alert-message"></div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label>{{ __('Created At') }}</label>
                                        <div class=" input-group">
                                            <input class="form-control" autocomplete="off" id="created_at1-form-input"
                                                name="created_at1" type="date">

                                            <div class="input-group-append">
                                                <span class="input-group-text"><i
                                                        class="mdi mdi-format-horizontal-align-center"></i></span>
                                            </div>
                                            <input class="form-control" autocomplete="off" id="created_at2-form-input"
                                                name="created_at2" type="date">
                                            <div class="invalid-feedback" id="created_at1-form-error"></div>
                                            <div class="invalid-feedback" id="created_at2-form-error"></div>

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12 convert-text">
                                        <label>{{ __('Filter') }}</label>
                                        {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name-form-input']) !!}
                                        <div class="invalid-feedback" id="name-form-error"></div>
                                    </div>


                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary mr-2 btn-icon-text">
                                    <i class="btn-icon-prepend" data-feather="search"></i>
                                    {{ __('Filter') }}
                                </button>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            <!-- Filter -->
            <div class="card">

                <div class="card-body">

                    <div class="row mb-2">
                        <div class="col-sm-4">
                            @if (App\Helpers\Helper::adminCan('admin.attribute-oil.create'))
                                <a href="{{ route('admin.attribute-oil.create') }}" class="btn btn-danger mb-2"><i
                                        class="mdi mdi-plus-circle mr-2"></i> {{ __('Add Attribute Oil') }}</a>
                            @endif
                        </div>
                        <div class="col-sm-8">
                            <div class="text-sm-right">
                                <button type="button" class="btn btn-light mb-2" data-toggle="collapse"
                                    href="#filter-collapse" role="button" aria-expanded="false"
                                    aria-controls="collapseExample"> <i class="btn-icon-prepend"
                                        data-feather="search"></i>{{ __('Filter') }}</button>

                            </div>
                        </div><!-- end col-->
                    </div>


                    <div class="table-responsive" id="table-pagination-div">
                        <table id="table-pagination" style="text-align: center;margin-top: 25px;margin-bottom: 25px;"
                            class="table table-striped table-bordered">
                            <tbody>
                                <tr id="table-pagination-tr-empty">
                                    <td id="table-pagination-td-empty">{{ __('Loading...') }}</td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
        $(document).ready(function() {

            tablePagination('{!! url()->full() !!}');
        });

        function tablePagination($url, $onDone) {
            $.get($url, {
                'isTablePagination': true
            }, function($html) {
                $('#table-pagination-div').html($html);
                if ($onDone !== undefined) {
                    $onDone();
                }

            });
        }

        function deleteRecord($routeName, $reload) {

            $('#top-modal').modal('show');

            document.querySelector("#delete").addEventListener('click', function(e) {

                if ($reload == undefined) {
                    $reload = 3000;
                }
                $.post(
                    $routeName, {
                        '_method': 'DELETE',
                        "_token": "{{ csrf_token() }}",
                    },
                    function(response) {

                        if (isJSON(response)) {
                            $data = response;
                            if ($data.status == true) {
                                $('#top-modal').modal('hide');
                                $('#danger-alert-modal').modal('show');
                                document.querySelector("#cont-btn").addEventListener('click', function(e) {
                                    e.preventDefault();
                                    location.reload();
                                });
                            } else {
                                alert('data not deleted')
                            }
                        }
                    }

                )
            });
        }
        $(document).ready(function() {
            ajaxCallManufacturerAll();
            ajaxCallSaeAll();
            ajaxCallOemAll();
            ajaxCallSpecificationAll();
        });

        $('#sae-form-input').change(function() {
            ajaxCallManufacturer($(this).val());
            ajaxCallOem($(this).val());
            ajaxCallSpecification($(this).val());
        });
        $('#oem-form-input').change(function() {
            ajaxCallSpecification($(this).val());
        });

        $('#manufacturer-form-input').change(function() {
            ajaxCallOem($(this).val());
            ajaxCallSpecification($(this).val());
        });

        function refresh() {
            ajaxCallManufacturerAll();
            ajaxCallSaeAll();
            ajaxCallOemAll();
            ajaxCallSpecificationAll();
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
                    $select.append('<option value="null">Select OEM approval</option>');

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
                    $select.append('<option value="null">Select OEM approval</option>');

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

        function approvedStatus($routeName, $reload) {

            $('#top-modal-approved').modal('show');
            document.querySelector("#closeBtn").addEventListener('click', function(e) {
                e.preventDefault();
                location.reload();
            });
            document.querySelector("#approved").addEventListener('click', function(e) {

                if ($reload == undefined) {
                    $reload = 3000;
                }
                $.post(
                    $routeName, {
                        '_method': 'POST',
                        "_token": "{{ csrf_token() }}",
                    },
                    function(response) {

                        if (isJSON(response)) {
                            $data = response;
                            if ($data.status == true) {
                                $('#top-modal-approved').modal('hide');
                                $('#success-alert-modal').modal('show');
                                document.querySelector("#succ-btn").addEventListener('click', function(e) {
                                    e.preventDefault();
                                    location.reload();
                                });
                            } else {
                                alert('data not approved')
                            }
                        }
                    }

                )
            });
        }
    </script>
@endsection
