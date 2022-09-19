@extends('layouts.vertical', ['title' => $pageTitle])

@section('css')
    <!-- Plugins css -->
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/summernote/summernote.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
       
    </style>
    @endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        @include('layouts.shared/breadcrumb')

        {!! Form::open([
            'route' => isset($result) ? ['admin.store.update', $result->id] : 'admin.store.store',
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
                        <label>{{ __('Name (AR)') }}</label>
                        {!! Form::text('name_ar', isset($result) ? $result->name_ar : null, [
                            'class' => 'form-control',
                            'id' => 'name_ar-form-input',
                            'autocomplete' => 'off',
                        ]) !!}
                        <div class="invalid-feedback" id="name_ar-form-error"></div>
                    </div>




                    <div class="form-group mb-3">
                        <label>{{ __('Email') }}</label><br>
                        {!! Form::text('email', isset($result) ? $result->email : null, [
                            'class' => 'form-control',
                            'id' => 'email-form-input',
                            'autocomplete' => 'off',
                            'disabled' => 'disabled'
                        ]) !!}
                        <div class="invalid-feedback" id="email-form-error"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Description (AR)') }}</label>
                        {!! Form::textarea('description_ar', null, [
                            'class' => 'form-control',
                            'id' => 'description_ar-form-input',
                            'autocomplete' => 'off',
                        ]) !!}

                        <div class="invalid-feedback" id="description_ar-form-error"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label>{{ __('address') }}</label><br>
                        {!! Form::text('address', isset($result) ? $result->address : null, [
                            'class' => 'form-control',
                            'id' => 'address-form-input',
                            'autocomplete' => 'off',

                        ]) !!}
                        <div class="invalid-feedback" id="address-form-error"></div>
                    </div>
                    <div class="form-group mb-3" style="display: none">
                        <label>{{ __('longitude') }}</label><br>
                        {!! Form::number('longitude', isset($result) ? $result->longitude : null, [
                            'class' => 'form-control',
                            'id' => 'longitude-form-input',
                            'autocomplete' => 'off',
                        ]) !!}
                        <div class="invalid-feedback" id="longitude-form-error"></div>
                    </div>
                    <div class="form-group mb-3" style="display: none">
                        <label>{{ __('latitude') }}</label><br>
                        {!! Form::number('latitude', isset($result) ? $result->latitude : null, [
                            'class' => 'form-control',
                            'id' => 'latitude-form-input',
                            'autocomplete' => 'off',
                        ]) !!}
                        <div class="invalid-feedback" id="latitude-form-error"></div>
                    </div>




                </div> <!-- end card-box -->
            </div> <!-- end col -->
            <div class="col-lg-6">
                <div class="card-box">
                    

                    <div class="form-group mb-3">
                        <label>{{ __('Name (EN)') }}</label>
                        {!! Form::text('name_en', isset($result) ? $result->name_en : null, [
                            'class' => 'form-control',
                            'id' => 'name_en-form-input',
                            'autocomplete' => 'off',
                        ]) !!}
                        <div class="invalid-feedback" id="name_en-form-error"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Phone') }}</label><br>
                        {!! Form::text('phone', isset($result) ? $result->phone : null, [
                            'class' => 'form-control',
                            'id' => 'phone-form-input',
                            'autocomplete' => 'off',
                            'disabled' => 'disabled'
                        ]) !!}
                        <div class="invalid-feedback" id="phone-form-error"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Description (EN)') }}</label>
                        {!! Form::textarea('description_en', isset($result) ? $result->description_en : null, [
                            'class' => 'form-control',
                            'id' => 'description_en-form-input',
                            'autocomplete' => 'off',
                        ]) !!}
                        <div class="invalid-feedback" id="description_en-form-error"></div>
                    </div>
                    @if (App\Helpers\Helper::adminCan('admin.store.type.permission'))
                    <div class="form-group mb-3">
                        <label>{{ __('Store Type') }}</label>
                        {!! Form::select(
                            'store_type_id',
                            App\Helpers\Helper::storeTypeForSelect(),
                            isset($result) ? $result->store_type_id : null,
                            [
                                'class' => 'select2 form-control',
                                'id' => 'store_type_id-form-input',
                                'autocomplete' => 'off',
                            ],
                        ) !!}

                        <div class="invalid-feedback" id="store_type_id-form-error"></div>
                    </div>
                    @endif
                    <div class="form-group mb-3">
                        <label>{{ __('Vendor') }}</label>
                        {!! Form::select(
                            'vendor_id',
                            App\Helpers\Helper::vendorForSelect(),
                            isset($result) ? $result->vendor_id : null,
                            [
                                'class' => 'select2 form-control',
                                'id' => 'vendor_id-form-input',
                                'autocomplete' => 'off',
                                'disabled' => 'disabled'
                            ],
                        ) !!}

                        <div class="invalid-feedback" id="vendor_id-form-error"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card-box">
                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">{{ __('Images') }}</h5>

                    <div class="form-group mb-3">
                        <input type="file" name="image" placeholder="{{ __('Choose image') }}" id="image">
                        <div class="invalid-feedback" id="image-form-error"></div>

                    </div>
                    <div class="form-group mb-3">
                        <img id="preview-image" class="img-fluid"
                            src="{{ isset($result) ? App\Helpers\Helper::path() . '/' . $result->image : asset('assets/images/avatar.jpg') }}"
                            alt="preview image">
                    </div>

                </div>

            </div>
           
            <div class="col-lg-6">
                <div class="card-box">
                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">{{ __('General') }}</h5>

                    <div class="form-group mb-3">
                        <label>{{ __('Status') }}</label>
                        {!! Form::select(
                            'status',
                            [null => __('Select Status'),'1' => __('Active'), '0' => __('In-Active')],
                            isset($result) ? $result->status : null,
                            [
                                'class' => 'form-control',
                                'id' => 'status-form-input',
                            ],
                        ) !!}
                        <div class="invalid-feedback" id="status-form-error"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-box">
                    <h4 class="header-title mb-3">{{__('Map')}}</h4>
                    <div class="iframe-container">
                        <div id="map" class=""></div>
                    </div>
                    
                </div> <!-- end card-box-->
            </div> <!-- end col-->


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
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDYqybeeGVjI8XA6jlgxuArOL13CUPThFU&callback=initMap&v=weekly"
        defer></script>
    <script>
        var longitude = document.getElementById("longitude-form-input").value;
        var latitude = document.getElementById("latitude-form-input").value;

        function initMap() {
            const myLatLng = {
                lat: parseFloat(latitude),
                lng: parseFloat(longitude)
            };
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: myLatLng,
            });

            new google.maps.Marker({
                position: myLatLng,
                map,
                title: "Point!",
            });
        }

        window.initMap = initMap;
    </script>
    <script type="text/javascript">
        $('#image').change(function() {

            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview-image').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);

        });
        $('#banner').change(function() {

            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview-banner').attr('src', e.target.result);
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
                            $select.append('<option value=' + value.id + '>' + value
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
                            $select.append('<option value=' + value.id + '>' + value
                                .name_ar +
                                '</option>'); // return empty
                        });
                    }
                });
            });
        });
    </script>
@endsection
