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
                'route' => isset($result) ? ['admin.store-reject-status.update', $result->id] : 'admin.store-reject-status.store',
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
                            <label>{{ __('Commercial Number') }}</label>
                            {!! Form::text('commercial_number', isset($result) ? $result->commercial_number : null, [
                                'class' => 'form-control',
                                'id' => 'commercial_number-form-input',
                                'autocomplete' => 'off',
                            ]) !!}
                            <div class="invalid-feedback" id="commercial_number-form-error"></div>
                        </div>
        
                        <div class="form-group mb-3">
                            <label>{{ __('Commercial Docs') }}</label>
                            {!! Form::text('commercial_docs', isset($result) ? $result->commercial_docs : null, [
                                'class' => 'form-control',
                                'id' => 'commercial_docs-form-input',
                                'autocomplete' => 'off',
                            ]) !!}
                            <div class="invalid-feedback" id="commercial_docs-form-error"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label>{{ __('Tax Card Number') }}</label><br>
                            {!! Form::text('tax_card_number', isset($result) ? $result->tax_card_number : null, [
                                'class' => 'form-control',
                                'id' => 'tax_card_number-form-input',
                                'autocomplete' => 'off',
                            ]) !!}
                            <div class="invalid-feedback" id="tax_card_number-form-error"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label>{{ __('Tax Card Docs') }}</label><br>
                            {!! Form::text('tax_card_docs', isset($result) ? $result->tax_card_docs : null, [
                                'class' => 'form-control',
                                'id' => 'tax_card_docs-form-input',
                                'autocomplete' => 'off',
                            ]) !!}
                            <div class="invalid-feedback" id="tax_card_docs-form-error"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label>{{ __('Bank Account') }}</label><br>
                            {!! Form::text('bank_account', isset($result) ? $result->bank_account : null, [
                                'class' => 'form-control',
                                'id' => 'bank_account-form-input',
                                'autocomplete' => 'off',
                            ]) !!}
                            <div class="invalid-feedback" id="bank_account-form-error"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label>{{ __('Bank Docs') }}</label><br>
                            {!! Form::text('bank_docs', isset($result) ? $result->bank_docs : null, [
                                'class' => 'form-control',
                                'id' => 'bank_docs-form-input',
                                'autocomplete' => 'off',
                            ]) !!}
                            <div class="invalid-feedback" id="bank_docs-form-error"></div>
                        </div>
                     
                        <div class="form-group mb-3">
                            <label>{{ __('Store Name') }}</label><br>
                            {!! Form::text('store_name', isset($result) ? $result->store_name : null, [
                                'class' => 'form-control',
                                'id' => 'store_name-form-input',
                                'autocomplete' => 'off',
                            ]) !!}
                            <div class="invalid-feedback" id="store_name-form-error"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label>{{ __('Wholesale Docs') }}</label><br>
                            {!! Form::text('wholesale_docs', isset($result) ? $result->wholesale_docs : null, [
                                'class' => 'form-control',
                                'id' => 'wholesale_docs-form-input',
                                'autocomplete' => 'off',
                            ]) !!}
                            <div class="invalid-feedback" id="wholesale_docs-form-error"></div>
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
