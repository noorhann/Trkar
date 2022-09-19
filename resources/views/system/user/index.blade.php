@extends('layouts.vertical', ['title' => $pageTitle])

@section('css')
    <!-- Plugins css -->
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/summernote/summernote.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
@endsection



@section('content')
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
                                    <div class="col-sm-6 convert-text">
                                        <label>{{ __('Filter') }}</label>
                                        {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name-form-input']) !!}
                                        <div class="invalid-feedback" id="country-form-error"></div>
                                    </div>
                                    <div class="col-sm-6 convert-text">
                                        <label>{{ __('Status') }}</label>
                                        {!! Form::select('status', [null => __('Select Status'),'1' => __('Active'), '2' => __('In-Active')], null, [
                                            'class' => 'form-control',
                                            'id' => 'status-form-input',
                                        ]) !!}
                                        <div class="invalid-feedback" id="status-form-error"></div>
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
                            {{-- @if (App\Helpers\Helper::adminCan('admin.user.create'))
                                <a href="{{ route('admin.user.create') }}" class="btn btn-danger mb-2"><i
                                        class="mdi mdi-plus-circle mr-2"></i> {{ __('Add Customer') }}</a>
                            @endif --}}
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
    </script>
@endsection
