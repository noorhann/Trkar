@extends('layouts.vertical', ['title' => $pageTitle])

@section('css')
    <!-- Plugins css -->
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/summernote/summernote.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <!-- Start Content-->
    {{-- <div class="container-fluid"> --}}
    <div class="container-fluid">

        <!-- start page title -->

        @include('layouts.shared/breadcrumb')

        <!-- end page title -->


        {!! Form::open([
            'route' => isset($result) ? ['admin.product.update', $result->id] : 'admin.product.store',
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
                        <label>{{ __('Name (EN)') }}</label>
                        {!! Form::text('name_en', isset($result) ? $result->name_en : null, [
                            'class' => 'form-control',
                            'id' => 'name_en-form-input',
                            'autocomplete' => 'off',
                        ]) !!}
                        <div class="invalid-feedback" id="name_en-form-error"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label>{{ __('Details (EN)') }}</label>
                        {!! Form::text('details_en', isset($result) ? $result->details_en : null, [
                            'class' => 'form-control',
                            'id' => 'details_en-form-input',
                            'autocomplete' => 'off',
                        ]) !!}
                        <div class="invalid-feedback" id="details_en-form-error"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label>{{ __('Details (AR)') }}</label>
                        {!! Form::text('details_ar', isset($result) ? $result->details_ar : null, [
                            'class' => 'form-control',
                            'id' => 'details_ar-form-input',
                            'autocomplete' => 'off',
                        ]) !!}
                        <div class="invalid-feedback" id="details_ar-form-error"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label>{{ __('Product Type') }}</label>
                        {!! Form::select('product_type_id', ['1' => __('Normal'), '2' => __('Wholesale'), '3' => __('Both')], null, [
                            'class' => 'form-control',
                            'id' => 'product_type_id-form-input',
                        ]) !!}
                        <div class="invalid-feedback" id="product_type_id-form-error"></div>

                    </div>
                 
                    <div class="form-group mb-3">
                        <label>{{ __('Category') }}</label>
                        {!! Form::select(
                            'category_id',
                            App\Helpers\Helper::parentCategoriesForSelect(),
                            isset($result) ? $result->category_id : null,
                            ['class' => 'select2 form-control ', 'id' => 'category_id-form-input', 'autocomplete' => 'off'],
                        ) !!}

                        <div class="invalid-feedback" id="category_id-form-error"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Sub Category') }}</label>
                        {!! Form::select(
                            'subcategory_id',
                            App\Helpers\Helper::categoriesForSelect(),
                            isset($result) ? $result->subcategory_id : null,
                            ['class' => 'select2 form-control', 'id' => 'subcategory_id-form-input', 'autocomplete' => 'off'],
                        ) !!}

                        <div class="invalid-feedback" id="subcategory_id-form-error"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Made') }}</label>
                        {!! Form::select(
                            'car_made_id',
                            App\Helpers\Helper::carMadesForSelect(),
                            isset($result) ? $result->car_made_id : null,
                            ['class' => 'select2 form-control', 'id' => 'car_made_id-form-input', 'autocomplete' => 'off'],
                        ) !!}

                        <div class="invalid-feedback" id="car_made_id-form-error"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Model') }}</label>
                        {!! Form::select(
                            'car_model_id',
                            App\Helpers\Helper::carModelForSelect(),
                            isset($result) ? $result->car_model_id : null,
                            ['class' => 'select2 form-control', 'id' => 'car_model_id-form-input', 'autocomplete' => 'off'],
                        ) !!}

                        <div class="invalid-feedback" id="car_model_id-form-error"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Engine') }}</label>
                        {!! Form::select(
                            'car_engine_id',
                            App\Helpers\Helper::carEnginesForSelect(),
                            isset($result) ? $result->car_engine_id : null,
                            ['class' => 'select2 form-control', 'id' => 'car_engine_id-form-input', 'autocomplete' => 'off'],
                        ) !!}

                        <div class="invalid-feedback" id="car_engine_id-form-error"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Serial Number') }}</label><br>
                        {!! Form::text('serial_number', isset($result) ? $result->serial_number : null, [
                            'class' => 'form-control',
                            'id' => 'serial_number-form-input',
                            'autocomplete' => 'off',
                        ]) !!}
                        <div class="invalid-feedback" id="serial_number-form-error"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Price') }}</label>
                        {!! Form::number('price', isset($result) ? $result->price : null, [
                            'class' => 'form-control',
                            'id' => 'price-form-input',
                            'autocomplete' => 'off',
                        ]) !!}
                        <div class="invalid-feedback" id="price-form-error"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Actual Price') }}</label>
                        {!! Form::number('Actual Price', isset($result) ? $result->actual_price : null, [
                            'class' => 'form-control',
                            'id' => 'actual_price-form-input',
                            'autocomplete' => 'off',
                        ]) !!}
                        <div class="invalid-feedback" id="actual_price-form-error"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Discount') }}</label>
                        {!! Form::number('discount', isset($result) ? $result->discount : null, [
                            'class' => 'form-control',
                            'id' => 'discount-form-input',
                            'autocomplete' => 'off',
                        ]) !!}
                        <div class="invalid-feedback" id="discount-form-error"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Year') }}</label>
                        {!! Form::select('year_id', App\Helpers\Helper::yearsForSelect(), isset($result) ? $result->year_id : null, [
                            'class' => 'select2 form-control',
                            'id' => 'year_id-form-input',
                            'autocomplete' => 'off',
                        ]) !!}

                        <div class="invalid-feedback" id="year_id-form-error"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Manufacturer') }}</label>
                        {!! Form::select(
                            'manufacturer_id',
                            App\Helpers\Helper::manufacturersForSelect(),
                            isset($result) ? $result->manufacturer_id : null,
                            ['class' => 'select2 form-control', 'id' => 'manufacturer_id-form-input', 'autocomplete' => 'off'],
                        ) !!}

                        <div class="invalid-feedback" id="manufacturer_id-form-error"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Original Country') }}</label>
                        {!! Form::select(
                            'original_country_id',
                            App\Helpers\Helper::originalCountriesForSelect(),
                            isset($result) ? $result->original_country_id : null,
                            ['class' => 'select2 form-control', 'id' => 'original_country_id-form-input', 'autocomplete' => 'off'],
                        ) !!}

                        <div class="invalid-feedback" id="original_country_id-form-error"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Store') }}</label>
                        {!! Form::select('store_id', App\Helpers\Helper::storesForSelect(), isset($result) ? $result->store_id : null, [
                            'class' => 'select2 form-control',
                            'id' => 'store_id-form-input',
                            'autocomplete' => 'off',
                        ]) !!}

                        <div class="invalid-feedback" id="store_id-form-error"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Status') }}</label>
                        {!! Form::select('status', [null => __('Select Status'),'1' => __('Active'), '0' => __('In-Active')], isset($result) ? $result->status : null, [
                            'class' => 'form-control',
                            'id' => 'status-form-input',
                        ]) !!}
                        <div class="invalid-feedback" id="status-form-error"></div>

                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Approved') }}</label>
                        {!! Form::select('approved', ['1' => __('Yes'), '0' => __('No')], null, [
                            'class' => 'form-control',
                            'id' => 'approved-form-input',
                        ]) !!}
                        <div class="invalid-feedback" id="approved-form-error"></div>

                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Tags') }}</label><br>
                        {!! Form::text('tags', isset($result) ? $result->tags : null, [
                            'class' => 'form-control',
                            'id' => 'tags-form-input',
                            'autocomplete' => 'off',
                        ]) !!}
                        <div class="invalid-feedback" id="tags-form-error"></div>
                    </div>
                </div> <!-- end card-box -->
            </div> <!-- end col -->

            <div class="col-lg-6">
                <div class="card-box">
                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">{{ __('Category Images') }}</h5>

                    <div class="form-group mb-3">
                        <input type="file" name="image" placeholder="{{ __('Choose image') }}" id="image">
                        <div class="invalid-feedback" id="image-form-error"></div>

                    </div>
                   
                    <div class="form-group mb-3">
                        <img id="preview-image" class="img-fluid"
                            src="{{ isset($result) ? App\Helpers\Helper::path() . '/' . $result->image : asset('assets/images/avatar.jpg') }}"
                            alt="preview image">
                    </div>
                    <div class="form-group mb-3">
                        <label>{{__('Images')}}</label>
                            {!! Form::file('images[]',['class'=>'form-control','multiple'=>'multiple','id'=>'images-form-input']) !!}
                            <div class="invalid-feedback" id="images-form-error"></div>
                    </div>
                    <hr style="margin-top: 40px;">

                    <div style="background: lightgrey;border-radius: 10px;padding: 10px;">

                        <div class="form-group row" style="padding-top: 40px;">
                            <div class="col-sm-12">
                                <h6>
                                    {{ __('Attributes Tiers') }}
                                    <a class="btn-sm btn-primary btn-icon-text" href="javascript:void(0);"
                                        onclick="addMultipleAttributes('#multiple-attributes-div');">
                                        <i class="mdi mdi-plus-circle-outline"></i>
                                        {{ __('Add') }}
                                    </a>
                                </h6>

                            </div>
                        </div>
                        <div id="multiple-attributes-div">
                            @if (isset($result->attributes_tiers))
                            @foreach($result->attributes_tiers as $key => $value)
                            <div class="multiple-attributes-form-div">
                               
                                <div class="col-sm-5">
                                    <label>{{ __('key') }}</label>
                                    {!! Form::text('attributes_tiers[key][]', $value['key'], ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                                </div>
                                <div class="col-sm-6">
                                    <label>{{ __('value') }}</label>
                                    {!! Form::text('attributes_tiers[value][]', $value['value'], ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                                </div>
                                <div class="col-sm-1" style="margin-top: 35px;">
                                    <a style="color: red;" href="javascript:void(0);" class="remove-multiple-attributes"><i
                                            data-feather="delete"></i></a>
                                </div>
                            </div>
                        @endforeach
                            @else
                                <div class="multiple-attributes-form-div">
                                    <div class="form-group row">

                                        <div class="col-sm-5">
                                            <label>{{ __('key') }}</label>
                                            {!! Form::text('attributes_tiers[key][]', null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                                        </div>
                                        <div class="col-sm-6">
                                            <label>{{ __('value') }}</label>
                                            {!! Form::text('attributes_tiers[value][]', null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                                        </div>
                                        <div class="col-sm-1" style="margin-top: 35px;">
                                            <a style="color: red;" href="javascript:void(0);" class="remove-multiple-attributes"><i
                                                    data-feather="delete"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div id="hidden-multiple-attributes" style="display: none;">
                            <div class="multiple-attributes-form-div">
                                <div class="form-group row">

                                    <div class="col-sm-5">
                                        <label>{{ __('key') }}</label>
                                        {!! Form::text('attributes_tiers[key][]', null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        <label>{{ __('value') }}</label>
                                        {!! Form::text('attributes_tiers[value][]', null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                                    </div>
                                    <div class="col-sm-1" style="margin-top: 35px;">
                                        <a style="color: red;" href="javascript:void(0);" class="remove-multiple-attributes"><i
                                                data-feather="delete"></i></a>
                                    </div>
                                </div>
                              
                            </div>
                        </div>
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

    </div>


    {{-- </div> <!-- container --> --}}
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

       
        
       
        function addMultipleAttributes($selector) {
            $($selector).append($('#hidden-multiple-attributes').html());
        }
        $('body').on('click', '.remove-multiple-attributes', function() {
            $(this).parents('.multiple-attributes-form-div').remove();
        });

        function submitMainForm() {

            formSubmit(
                '{{ isset($result) ? route('admin.product.update', $result->id) : route('admin.product.store') }}',
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
                            // window.location = $data.data.url;
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
    </script>
@endsection
