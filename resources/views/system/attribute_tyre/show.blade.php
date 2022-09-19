@extends('layouts.vertical', ['title' => $pageTitle])

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-xl-12">
                <div class="card-box">
                    <ul class="nav nav-pills navtab-bg nav-justified">

                        @foreach ($tyreTypes as $tyreType)
                            @php $string = str_replace(' ', '', $tyreType->name_en); @endphp
                            <li class="nav-item">
                                <a href="#test"
                                    onclick="location.href = '{{ url('admin/attribute-tyre') }}/{{ $tyreType->id }}';"
                                    data-id-item="{{ $tyreType->id }}" data-toggle="tab" aria-expanded="false"
                                    class="nav-link @if ($tyreType->id == Request::segment(3)) active @endif">
                                    {{ $tyreType->{'name_' . App::getLocale()} }}
                                </a>
                            </li>
                        @endforeach

                    </ul>
                    <div class="tab-content">
                        {{-- @foreach ($tyreTypes as $tyreType)
                            @php $string = str_replace(' ', '', $tyreType->name_en); @endphp --}}

                        <div class="tab-pane active" id="data">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Season">{{ __('Season') }}</label>
                                        {{-- {!! Form::select('season_id', App\Helpers\Helper::seasonForSelect(), null, [
                                            'class' => 'form-control',
                                            'id' => 'season_id-form-input',
                                            'autocomplete' => 'off',
                                        ]) !!} --}}
                                        <select id="season_id-form-input" class="form-control">
                                            <option value="">Select Season</option>
                                            @if (Request::segment(3) == 4)
                                                <option id="4" value="4">--</option>
                                            @endif
                                            <option id="{{ $seasons[0]['id'] }}"
                                                @if (Request::segment(3) == 4) style="display: none" @endif
                                                value="{{ $seasons[0]['id'] }}">
                                                {{ $seasons[0]['name'] }}</option>

                                            <option id="{{ $seasons[1]['id'] }}"
                                                @if (Request::segment(3) == 4) style="display: none" @endif
                                                value="{{ $seasons[1]['id'] }}">
                                                {{ $seasons[1]['name'] }}</option>

                                            <option id="{{ $seasons[2]['id'] }}" value="{{ $seasons[2]['id'] }}">
                                                {{ $seasons[2]['name'] }}</option>





                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{-- <input type="hidden" class="form-control" name="id_get" id="tyreType"
                                            value="{{ $tyreType->id }}"> --}}
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="Width">{{ __('Width') }}</label>
                                        <select class="form-control" id="width-form-input"></select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="hight">{{ __('Height') }}</label>
                                        <select class="form-control" id="hight-form-input"></select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="hight">{{ __('Diameter') }}</label>
                                        <select class="form-control" id="diameter-form-input">
                                        </select>
                                    </div>
                                </div>
                                {{-- @if (Request::segment(3) == 4)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="hight">{{ __('Winter tyres') }}</label>
                                            <input type="checkbox" name="">
                                        </div>
                                    </div>

                                    <div class="col-md-6">

                                    </div>
                                @endif --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="hight">{{ __('Manufacturer') }}</label>
                                        <select class="form-control" id="manufacturer-form-input">
                                        </select>
                                    </div>
                                </div>
                                @if (Request::segment(3) != 3)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="hight">{{ __('Speed Rating') }}</label>
                                            <select class="form-control" id="speed-form-input">
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                @if (Request::segment(3) == 3)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="hight">{{ __('Load Index') }}</label>
                                            <select class="form-control" id="load-form-input">
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                @if (Request::segment(3) == 4)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="hight">{{ __('Axle') }}</label>
                                            <select class="form-control" id="axle-form-input">
                                            </select>
                                        </div>
                                    </div>
                                @endif

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
                                <a href="{{ route('admin.attribute-tyre.create') }}" class="btn btn-danger mb-2"><i
                                        class="mdi mdi-plus-circle mr-2"></i> {{ __('Add Attribute Tyre') }}</a>
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


        <!-- end row-->

    </div> <!-- container -->
@endsection
@section('script')
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
    </script>
    <script type="text/javascript">
        function Node(tab, select) {
            this.tab = tab;
            this.select = select;
        }
        $(document).ready(function() {
            $("#width-form-input").attr('disabled', true);
            $("#hight-form-input").attr('disabled', true);
            $("#diameter-form-input").attr('disabled', true);
            ajaxCallManufacturerAll();
            ajaxCallSpeedAll();
            ajaxCallLoadAll();
            ajaxCallAxleAll();
            $('#season_id-form-input').change(function() {
                ajaxCallWidth($(this).val());
                var $width = $('#width-form-input');
                $width.find('option').remove();
                var $hight = $('#hight-form-input');
                $hight.find('option').remove();
                var $diameter = $('#diameter-form-input');
                $diameter.find('option').remove();
                $("#width-form-input").attr('disabled', false);

                ajaxCallManufacturer($(this).val(), $(this).val());
                ajaxCallSpeed($(this).val(), $(this).val());
                ajaxCallLoad($(this).val(), $(this).val());
                ajaxCallAxle($(this).val(), $(this).val());
            });
            $('#width-form-input').change(function() {
                var seasonId = $('#season_id-form-input').find(":selected").val();
                ajaxCallHight($(this).val(), seasonId);
                ajaxCallManufacturer($(this).val(), seasonId);
                ajaxCallSpeed($(this).val(), seasonId);
                ajaxCallLoad($(this).val(), seasonId);
                ajaxCallAxle($(this).val(), seasonId);

                $("#hight-form-input").attr('disabled', false);

            });
            $('#hight-form-input').change(function() {
                var seasonId = $('#season_id-form-input').find(":selected").val();

                ajaxCallDiameter($(this).val(), seasonId);
                ajaxCallManufacturer($(this).val(), seasonId);

                $("#diameter-form-input").attr('disabled', false);

            });
            $('#diameter-form-input').change(function() {
                var seasonId = $('#season_id-form-input').find(":selected").val();
                ajaxCallManufacturer($(this).val(), seasonId);


            });

            function ajaxCallWidth($val) {
                $.ajax({
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'season_id': $val,
                        'type_id': {{ Request::segment(3) }},
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

            function ajaxCallHight($val, seasonId) {
                $.ajax({
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'season_id': seasonId,
                        'parent_id': $val,
                        'type_id': {{ Request::segment(3) }},
                    },
                    type: 'POST',
                    url: '{{ url('admin/searchAttributeHight') }}',
                    dataType: 'json',
                    success: function(response) {

                        console.log(response.data); // show [object, Object]

                        var $select = $('#hight-form-input');

                        $select.find('option').remove();
                        $select.append('<option>Select Hight</option>');

                        $.each(response.data, function(key, value) {
                            $select.append('<option value=' + value.id + '>' +
                                value
                                .value +
                                '</option>');
                        });
                    }
                });
            }

            function ajaxCallDiameter($val, seasonId) {
                $.ajax({
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'season_id': seasonId,
                        'parent_id': $val,
                        'type_id': {{ Request::segment(3) }},
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
                        'type_id': {{ Request::segment(3) }},
                    },
                    type: 'POST',
                    url: '{{ url('admin/searchAttributeManufacturer') }}',
                    dataType: 'json',
                    success: function(response) {

                        console.log('manufacturerAll'); // show [object, Object]
                        console.log(response.data); // show [object, Object]
                        console.log('manufacturerAll'); // show [object, Object]

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
                        'season_id': seasonId,
                        'parent_id': $val,
                        'type_id': {{ Request::segment(3) }},
                    },
                    type: 'POST',
                    url: '{{ url('admin/searchAttributeManufacturer') }}',
                    dataType: 'json',
                    success: function(response) {
                        console.log('manufacturer'); // show [object, Object]
                        console.log(response.data); // show [object, Object]
                        console.log('manufacturer'); // show [object, Object]

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
                        'type_id': {{ Request::segment(3) }},
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
                        'season_id': seasonId,
                        'parent_id': $val,
                        'type_id': {{ Request::segment(3) }},
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
                        'type_id': {{ Request::segment(3) }},
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
                        'season_id': seasonId,
                        'parent_id': $val,
                        'type_id': {{ Request::segment(3) }},
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
                        'type_id': {{ Request::segment(3) }},
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
                        'season_id': seasonId,
                        'parent_id': $val,
                        'type_id': {{ Request::segment(3) }},
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

        });

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
