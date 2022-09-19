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
            'route' => isset($result) ? ['admin.manufacturer.update', $result->id] : 'admin.manufacturer.store',
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
                        <label>{{ __('Company Name') }}</label>
                        {!! Form::text('company_name', isset($result) ? $result->company_name : null, [
                            'class' => 'form-control',
                            'id' => 'company_name-form-input',
                            'autocomplete' => 'off',
                        ]) !!}
                        <div class="invalid-feedback" id="company_name-form-error"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Address') }}</label>
                        {!! Form::text('address', isset($result) ? $result->address : null, [
                            'class' => 'form-control',
                            'id' => 'address-form-input',
                            'autocomplete' => 'off',
                        ]) !!}
                        <div class="invalid-feedback" id="address-form-error"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Website') }}</label>
                        {!! Form::text('website', isset($result) ? $result->website : null, [
                            'class' => 'form-control',
                            'id' => 'website-form-input',
                            'autocomplete' => 'off',
                        ]) !!}
                        <div class="invalid-feedback" id="website-form-error"></div>
                    </div>
                    {{-- <div class="form-group mb-3">
                            <label>{{ __('Category') }}</label>
                            {!! Form::select(
                                'category_id',
                                App\Helpers\Helper::categoriesForSelect(),
                                isset($result) ? $result->category_id : null,
                                ['class' => 'select2 form-control  ', 'id' => 'category_id -form-input', 'autocomplete' => 'off'],
                            ) !!}

                            <div class="invalid-feedback" id="category_id-form-error"></div>
                        </div> --}}
                    {{-- @if (isset($result))
                        <div class="form-group mb-3">
                            <label>{{ __('The section you chose before (if you want change you can chose from Parent drop down)') }}</label>
                            <select class="form-control" disabled>
                                @isset($result->category)
                                    <option value="{{ $result->category->id }}">
                                        {{ $result->category->name_ar }}
                                    </option>
                                @endisset
                            </select>

                        </div>
                    @endif --}}
                    @if (isset($result))
                        <div class="form-group mb-3" id="tree">
                            <label>{{ __('Category') }}</label>
                            {!! Form::select(
                            'category_id[]',
                            App\Helpers\Helper::categoriesForSelectManufacturer(),
                            $manufacturerCategories,
                            ['class' => ' form-control category_id-form-input select2','multiple'=>'multiple', 'autocomplete' => 'off'],
                        ) !!}

                            <div class="invalid-feedback" id="category_id-form-error"></div>
                        </div>
                    @else
                        <div class="form-group mb-3" id="tree">
                            <label>{{ __('Category') }}</label>
                            {!! Form::select('category_id[]', App\Helpers\Helper::categoriesForSelect(), null, [
                                'class' => ' form-control category_id-form-input select2',
                                'multiple'=>'multiple',
                                'autocomplete' => 'off',
                            ]) !!}

                            <div class="invalid-feedback" id="category_id-form-error"></div>
                        </div>
                    @endif


                    <div class="form-group mb-3" id="sub-tree">

                    </div>
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

                </div> <!-- end card-box -->
            </div> <!-- end col -->
            <div class="col-lg-6">
                <div class="card-box">
                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">{{ __('Logo') }}</h5>

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
        // function selectRefresh() {
        //     $('.select2').select2({

        //         width: '100%'
        //     });
        // }
        $(document).ready(function() {
            @if (isset($result))
                // $("#name_ar-form-input").attr('disabled', true);
                // $("#name_en-form-input").attr('disabled', true);
            @endif
            // selectRefresh();
        });

        // function Node(old, current) {
        //     this.old = old;
        //     this.current = current;
        // }

        // function searchValue(valueID) {
        //     $('select').each(function() {
        //         if (this.value == valueID) {
        //             return this.id;
        //         }
        //     });
        // }
        // let array = [];
        // let currentValue;

        // $(document).on('change', '.category_id-form-input', function(e) {

        //     if ($(this).val() == 1 || $(this).val() == 2) {
        //         $("#sub-tree").empty();
        //         selectChange($(this).val(), array);
        //         selectChangeOrder($(this).val(), array);
        //     } else {
        //         // console.log($(this).val());
        //         let prevElemnt = $(`select [value=${$(this).val()}]`).parent().attr('id');
        //         array.push(new Node(prevElemnt, $(this).val()));
        //         console.log(array);
        //         for (let i = 0; i < array.length; ++i) {
        //             if (array[i]['old'] === this.id) {
        //                 $('#' + array[i]['current']).remove();
        //                 $current = array[i]['current']
        //             }
        //             if (array[+i]['old'] === $current) {
        //                 $('#' + array[+i]['current']).remove();
        //             }

        //         }



        //         selectChange($(this).val(), array);
        //         selectChangeOrder($(this).val(), array);

        //     }

        // });

        // function selectChange(targetEl, array) {

        //     var data = '_token={{ csrf_token() }}';
        //     // POST to server using $.post or $.ajax

        //     $.ajax({
        //         data: data,
        //         type: 'POST',
        //         url: '{{ url('admin/getChilds') }}/' + targetEl,
        //         dataType: 'json',
        //         success: function(response) {

        //             console.log(response); // show [object, Object]
        //             if (response.status == true) {
        //                 var $select = $('#sub-tree');
        //                 // $select.find('select').remove();
        //                 $select.append(
        //                     '<div class="form-group mb-3"><select class="form-control select2 category_id-form-input" name=category_id["' +
        //                     targetEl + '"] id="' + targetEl + '"><option value =null >Select </option>');
        //                 var $subTreeSelect = $('#' + targetEl + '');

        //                 // $select.find('option').remove();
        //                 $.each(response.data, function(key, value) {

        //                     $subTreeSelect.append('<option value=' + value.id + '>' + value
        //                         .name_ar +
        //                         '</option>');
        //                 });
        //                 $subTreeSelect.after("</select></div>");
        //                 selectRefresh();

        //             }

        //         }
        //     });
        // };


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
    </script>
@endsection
