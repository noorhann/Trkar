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
            'route' => isset($result) ? ['admin.store-vendor-staff.update', $result->id] : 'admin.store-vendor-staff.store',
            'files' => true,
            'method' => isset($result) ? 'PATCH' : 'POST',
            'class' => 'forms-sample',
            'id' => 'main-form',
            'onsubmit' => 'submitMainForm();return false;',
        ]) !!}
        <div id="form-alert-message"></div>
        <div class="row">


            {{-- <div class="row"> --}}
            <div class="col-lg-6">
                <div class="card-box">


                    

                   
                    <div class="form-group mb-3">
                        <label>{{ __('Vendor Staff') }}</label>
                        {!! Form::select(
                            'vendor_staff_id',
                            App\Helpers\Helper::vendorStaffForSelect(),
                            isset($result) ? $result->vendor_staff_id : null,
                            [
                                'class' => 'select2 form-control',
                                'id' => 'vendor_staff_id-form-input',
                                'autocomplete' => 'off',
                            ],
                        ) !!}

                        <div class="invalid-feedback" id="vendor_staff_id-form-error"></div>
                    </div>
                
  

                </div> <!-- end card-box -->
            </div> <!-- end col -->
            <div class="col-lg-6">
                <div class="card-box">
                    

                    <div class="form-group mb-3">
                        <label>{{ __('Store') }}</label>
                        {!! Form::select(
                            'store_id',
                            App\Helpers\Helper::storesForSelect(),
                            isset($result) ? $result->store_id : null,
                            [
                                'class' => 'select2 form-control',
                                'id' => 'store_id-form-input',
                                'autocomplete' => 'off',
                            ],
                        ) !!}

                        <div class="invalid-feedback" id="store_id-form-error"></div>
                    </div>
                </div>
                </div>
        

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
        $('#image').change(function() {

            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview-image').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);

        });

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
        $(document).ready(function() {

            // $("#breeds").attr('disabled', true);

            // check if selected
            if ($("#country_id-form-input").find('option:selected').val() == 0) {
                $("#city_id-form-input").attr('disabled', true);
                $("#area_id-form-input").attr('disabled', true);
            }

            $('#country_id-form-input').change(function() {
                // get value of selected animal type
                var selected_country_id = $(this).find('option:selected').val();
                var data = '_token={{ csrf_token() }}';
                // POST to server using $.post or $.ajax

                $.ajax({
                    data: data,
                    type: 'POST',
                    url: '{{ url('admin/getCity') }}/' + selected_country_id,
                    dataType: 'json',
                    success: function(response) {
                        $("#city_id-form-input").attr('disabled', false);

                        console.log(response.data); // show [object, Object]

                        var $select = $('#city_id-form-input');

                        $select.find('option').remove();
                        $.each(response.data, function(key, value) {
                            $select.append('<option value=' + key + '>' + value
                                .name_ar +
                                '</option>'); // return empty
                        });
                    }
                });
            });
            $('#city_id-form-input').change(function() {
                // get value of selected animal type
                var selected_area_id = $(this).find('option:selected').val();
                var data = '_token={{ csrf_token() }}';
                // POST to server using $.post or $.ajax

                $.ajax({
                    data: data,
                    type: 'POST',
                    url: '{{ url('admin/getArea') }}/' + selected_area_id,
                    dataType: 'json',
                    success: function(response) {
                        $("#area_id-form-input").attr('disabled', false);

                        console.log(response.data); // show [object, Object]

                        var $select = $('#area_id-form-input');

                        $select.find('option').remove();
                        $.each(response.data, function(key, value) {
                            $select.append('<option value=' + key + '>' + value
                                .name_ar +
                                '</option>'); // return empty
                        });
                    }
                });
            });
        });
    </script>
@endsection
