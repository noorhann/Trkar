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
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->

        @include('layouts.shared/breadcrumb')

        <!-- end page title -->


        {!! Form::open([
            'route' => isset($result) ? ['admin.category.update', $result->id] : 'admin.category.store',
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
                            @if (isset($result))
                                @isset($result->parent)
                                    @foreach ($arrayParents as $key => $value)
                                    <label>{{ $value['name'] }}</label>
                                    @if (count($arrayParents) != $key + 1)
                                    =>
                                    @endif
                                
                                    @endforeach
                                <br>
                                <label>{{ __('The section you chose before (if you want change you can chose from Parent drop down)') }}</label>
                                @endisset
                            @endif
                            {{-- <label>{{ __('The section you chose before (if you want change you can chose from Parent drop down)') }}</label>
                            <select class="form-control" disabled>
                               
                            </select> --}}

                        </div>
                   
                    <div class="form-group mb-3" id="tree">
                        <label>{{ __('Parent') }}</label>

                        {!! Form::select(
                            'parent_id[0]',
                            App\Helpers\Helper::ParentCategoriesForSelect(),
                            isset($result) ? $result->parent_id : null,
                            ['class' => ' form-control parent_id-form-input', 'autocomplete' => 'off'],
                        ) !!}

                        <div class="invalid-feedback" id="parent_id-form-error"></div>
                    </div>

                    <div class="form-group mb-3" id="sub-tree">

                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Order') }}</label><br>
                        <span class="order-text"></span>
                        <span class="order-validation"></span>
                        {!! Form::number('order', isset($result) ? $result->order : null, [
                            'class' => 'form-control',
                            'id' => 'order-form-input',
                            'autocomplete' => 'off',
                        ]) !!}
                        <div class="invalid-feedback" id="order-form-error"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ __('Status') }}</label>
                        {!! Form::select(
                            'status',
                            [null => __('Select Status'), '1' => __('Active'), '0' => __('In-Active')],
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
        function selectRefresh() {
            $('.select2').select2({

                width: '100%'
            });
        }
        // $(document).ready(function() {
        //     @foreach ($arrayParents as $key => $arrayParent)
        //         var parentValue = '{{ $arrayParent['id'] }}';
        //         var countParent = {{ count($arrayParents) }};
        //         // console.log('{{ $key + 1 }}');
        //         @if ($key + 1 != count($arrayParents))
        //             var parentValuess = '{{ $arrayParents[$key + 1]['id'] }}';
        //             selectChange(parentValue, parentValuess);
        //         @endif

        //         // console.log(parentValuess)
        //     @endforeach
        // });

        function Node(old, current) {
            this.old = old;
            this.current = current;
        }

        function searchValue(valueID) {
            $('select').each(function() {
                if (this.value == valueID) {
                    return this.id;
                }
            });
        }
        let array = [];
        let currentValue;

        $(document).on('change', '.parent_id-form-input', function(e) {

            if ($(this).val() == 1 || $(this).val() == 2) {
                $("#sub-tree").empty();
                selectChange($(this).val());
                selectChangeOrder($(this).val());
            } else {
                let prevElemnt = $(`select [value=${$(this).val()}]`).parent().attr('id');
                array.push(new Node(prevElemnt, $(this).val()));
                console.log(array);
                for (let i = 0; i < array.length; ++i) {
                    if (array[i]['old'] === this.id) {
                        $('#data' + array[i]['current']).remove();
                        // $('#' + array[i]['current']).select2('destroy');
                        $current = array[i]['current']
                    }
                    if (array[+i]['old'] === $current) {
                        // $('#' + array[+i]['current']).select2('destroy');
                        $('#data' + array[+i]['current']).remove();

                    }

                }

                selectChange($(this).val());
                selectChangeOrder($(this).val());

            }

        });

        function selectChange(targetEl, parent = null) {
console.log(parent);
            var data = '_token={{ csrf_token() }}';
            // POST to server using $.post or $.ajax

            $.ajax({
                data: data,
                type: 'POST',
                url: '{{ url('admin/getChilds') }}/' + targetEl,
                dataType: 'json',
                success: function(response) {

                    console.log(response); // show [object, Object]
                    if (response.status == true) {
                        var $select = $('#sub-tree');
                        // $select.find('select').remove();
                        $select.append(
                            '<div class="form-group mb-3" id="data' + targetEl +
                            '"><select class="form-control select2 parent_id-form-input" name=parent_id["' +
                            targetEl + '"] id="' + targetEl + '"><option value =null >Select </option>');
                        var $subTreeSelect = $('#' + targetEl + '');

                        // $select.find('option').remove();
                        $.each(response.data, function(key, value) {
                            if (parent != null) {
                                if(parent === value.id){
                                    $selectOption = 'selected';
                                }else{
                                $selectOption = '';
                            }
                            }else{
                                $selectOption = '';
                            }
                            $subTreeSelect.append('<option ' + $selectOption + ' value=' + value.id +
                                '>' + value
                                .name_ar +
                                '</option>');
                        });
                        $subTreeSelect.after("</select></div>");
                        selectRefresh();

                    }

                }
            });
        };



        $('#image').change(function() {

            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview-image').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);

        });
        // $('.parent_id-form-input').on('change', function() {
        function selectChangeOrder(targetEl, array) {
            $('.order-text').html('');
            $('.order-validation').html('');
            var routeName = '{{ url('admin/getcategoryByid') }}' + '/' + targetEl;
            $.post(
                routeName, {
                    '_method': 'POST',
                    "_token": "{{ csrf_token() }}",
                },
                function(response) {

                    if (isJSON(response)) {
                        $data = response;
                        if ($data.status == true) {

                            $('.order-text').append('{{ __('you can not choose this number :') }}');
                            $('.order-validation').html($data.data);
                        } else {

                        }
                    }
                }

            )
        }
        // });




        function submitMainForm() {

            formSubmit(
                '{{ isset($result) ? route('admin.category.update', $result->id) : route('admin.category.store') }}',
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
