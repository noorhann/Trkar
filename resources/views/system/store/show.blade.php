@extends('layouts.vertical', ['title' => $pageTitle])

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        @include('layouts.shared/breadcrumb')
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <!-- project card -->
                <div class="card d-block">
                    <div class="card-body">



                        <div class="clerfix"></div>

                        <div class="row">
                            <div class="col-md-4">
                                <!-- Ticket type -->
                                <label class="mt-2 mb-1">{{ __('uuid') }} :</label>
                                <p>
                                    <i class="mdi mdi-ticket font-18 text-success me-1 align-middle"></i>
                                    {{ $result->uuid }}
                                </p>
                                <!-- end Ticket Type -->
                            </div>
                        </div> <!-- end row -->
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Name (EN)') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>{{ $result->name_en }}</p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->

                            <div class="col-md-6">
                                <!-- assignee -->
                                <label class="mt-2 mb-1">{{ __('Name (AR)') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p> {{ $result->name_ar }} </p>
                                    </div>
                                </div>
                                <!-- end assignee -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Phone') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>{{ $result->phone }}</p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->

                            <div class="col-md-6">
                                <!-- assignee -->
                                <label class="mt-2 mb-1">{{ __('Email') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p> {{ $result->email }} </p>
                                    </div>
                                </div>
                                <!-- end assignee -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                        <div class="row">


                            <div class="col-md-6">
                                <!-- assignee -->
                                <label class="mt-2 mb-1">{{ __('Address') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p> {{ $result->address }} </p>
                                    </div>
                                </div>
                                <!-- end assignee -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Vendor') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if (isset($result->vendor))
                                                {{ $result->vendor->username }}
                                            @else
                                                --
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->

                            <div class="col-md-6">
                                <!-- assignee -->
                                <label class="mt-2 mb-1">{{ __('Store Type') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if (isset($result->storeType))
                                                {{ $result->storeType->{'name_' . \App::getLocale()} }}
                                            @else
                                                --
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <!-- end assignee -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Description (AR)') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p> {{ $result->description_ar }} </p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->

                            <div class="col-md-6">
                                <!-- assignee -->
                                <label class="mt-2 mb-1">{{ __('Description (EN)') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p> {{ $result->description_en }} </p>
                                    </div>
                                </div>
                                <!-- end assignee -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                        {{-- <div class="row">
                            <div class="col-md-6">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Is the phone active?') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if ($result->phone_verified_at != null)
                                                <span class="badge badge-soft-success">{{ __(' Active') }}</span>
                                            @else
                                                <span class="badge badge-soft-danger">{{ __(' In-Active') }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->

                            <div class="col-md-6">
                                <!-- assignee -->
                                <label class="mt-2 mb-1">{{ __('Phone Verified At') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if ($result->phone_verified_at != null)
                                                {{ $result->phone_verified_at }}
                                            @else
                                                --
                                            @endif

                                        </p>

                                    </div>
                                </div>
                                <!-- end assignee -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Is the email active?') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if ($result->email_verified_at != null)
                                                <span class="badge badge-soft-success">{{ __(' Active') }}</span>
                                            @else
                                                <span class="badge badge-soft-danger">{{ __(' In-Active') }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->

                            <div class="col-md-6">
                                <!-- assignee -->
                                <label class="mt-2 mb-1">{{ __('Email Verified At') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if ($result->email_verified_at != null)
                                                {{ $result->email_verified_at }}
                                            @else
                                                --
                                            @endif
                                        </p>

                                    </div>
                                </div>
                                <!-- end assignee -->
                            </div> <!-- end col -->
                        </div> <!-- end row --> --}}
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Status') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if ($result->status == 1)
                                                <span class="badge badge-soft-success">{{ __(' Active') }}</span>
                                            @else
                                                <span class="badge badge-soft-danger">{{ __(' In-Active') }}</span>
                                        </p>
                                        @endif

                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->

                        </div> <!-- end row -->


                    </div> <!-- end card-body-->

                </div> <!-- end card-->

            </div>
            <div class="col-xl-4 col-lg-5">

                <div class="card">
                    <div class="card-body">

                        <h5 class="card-title font-16 mb-3">{{ __('Image') }}</h5>

                        <div class="card mb-1 shadow-none border">
                            <div class="p-2">
                                <div class="row align-items-center">
                                    <div class="tab-pane active show" id="product-1-item">
                                        <img src="{{ isset($result) ? App\Helpers\Helper::path() . '/' . $result->image : asset('assets/images/avatar.jpg') }}"
                                            alt="" class="img-fluid mx-auto d-block rounded">
                                    </div>

                                </div>
                            </div>
                        </div><br>
                        <h5 class="card-title font-16 mb-3">{{ __('Banner') }}</h5>

                        <div class="card mb-1 shadow-none border">
                            <div class="p-2">
                                <div class="row align-items-center">
                                    <div class="tab-pane active show" id="product-1-item">
                                        <img src="{{ isset($result) ? App\Helpers\Helper::path() . '/' . $result->banner : asset('assets/images/avatar.jpg') }}"
                                            alt="" class="img-fluid mx-auto d-block rounded">
                                    </div>

                                </div>
                            </div>
                        </div><br>
                        <h5 class="card-title font-16 mb-3">{{ __('Attachments') }}</h5>
                        @if (isset($result->vendor))
                            @foreach ($result->vendor->attachments as $key => $attachment)
                                <div class="card mb-1 shadow-none border">

                                    <div class="p-2">
                                        <div class="row align-items-center">

                                            <div class="col-auto">
                                                <div class="avatar-sm" style="height: 100%">
                                                    <span class="avatar-title badge-soft-primary text-primary rounded">
                                                        @if ($key == 0)
                                                            {{ __('Bank Account') }}
                                                        @elseif ($key == 1)
                                                            {{ __('Commercial Number') }}
                                                        @elseif ($key == 2)
                                                            {{ __('Tax Card Number') }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col pl-0">
                                                <a href="javascript:void(0);"
                                                    class="text-muted font-weight-bold">{{ __('size') }}</a>
                                                <p class="mb-0 font-12">{{ $attachment->size }}</p>
                                            </div>
                                            <div class="col-auto">
                                                @if (App\Helpers\Helper::adminCan('admin.store-reject.approved'))
                                                    @php
                                                        $store = App\Models\Store::where('vendor_id', $attachment->user_id)->first();
                                                        $storeRejected = App\Models\StoreRejectStatus::where('store_id', $store->id)->get();
                                                    @endphp
                                                    @if (count($storeRejected))
                                                        @if ($storeRejected[$key]->status == 1)
                                                            <div class="custom-control custom-switch">
                                                                <input
                                                                    onchange="approvedStoreRejected('{{ route('admin.store-reject.approved', $storeRejected[$key]->id) }}')"
                                                                    type="checkbox" class="custom-control-input" checked
                                                                    id="{{ $storeRejected[$key]->id }}">
                                                                <label class="custom-control-label"
                                                                    for="{{ $storeRejected[$key]->id }}"></label>
                                                            </div>
                                                        @else
                                                            <div class="custom-control custom-switch">
                                                                <input
                                                                    onchange="approvedStoreRejected('{{ route('admin.store-reject.approved', $storeRejected[$key]->id) }}')"
                                                                    type="checkbox" class="custom-control-input"
                                                                    id="{{ $storeRejected[$key]->id }}">
                                                                <label class="custom-control-label"
                                                                    for="{{ $storeRejected[$key]->id }}"></label>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @else
                                                    {{ __("you don't have a permission") }}
                                                @endif
                                            </div>

                                            <div class="col-auto">
                                                <!-- Button -->
                                                <a download="{{ $attachment->file }}"
                                                    href="{{ App\Helpers\Helper::path() . '/' . $attachment->file }}"
                                                    class="btn btn-link font-16 text-muted">
                                                    <i class="dripicons-download"></i>
                                                </a>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card-box">
                    <h4 class="header-title mb-3">{{ __('Map') }}</h4>
                    <div class="iframe-container">
                        <div id="map" class=""></div>
                    </div>

                </div> <!-- end card-box-->
                <div class="col-xl-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <h5 class="card-title font-16 mb-3">{{ __('Branches') }}</h5>

                            <div class="table-responsive mt-4">
                                <table class="table table-bordered table-centered mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>{{ __('ID') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Slug') }}</th>
                                            <th>{{ __('Phone') }}</th>
                                            <th>{{ __('Address') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Actions') }}</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($result->branches))
                                            @foreach ($result->branches as $key => $branch)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $branch->name }}</td>
                                                    <td>{{ $branch->slug }}</td>
                                                    <td>{{ $branch->phone }}</td>

                                                    <td>{{ $branch->address }}</td>
                                                    <td>
                                                        @if (App\Helpers\Helper::adminCan('admin.store-reject.approved'))
                                                            @if ($branch->status == 1)
                                                                <div class="custom-control custom-switch">
                                                                    <input
                                                                        onchange="approved('{{ route('admin.branch.approved', $branch->id) }}')"
                                                                        type="checkbox" class="custom-control-input"
                                                                        checked id="{{ $branch->id }}">
                                                                    <label class="custom-control-label"
                                                                        for="{{ $branch->id }}"></label>
                                                                @else
                                                                    <div class="custom-control custom-switch">
                                                                        <input
                                                                            onchange="approved('{{ route('admin.branch.approved', $branch->id) }}')"
                                                                            type="checkbox" class="custom-control-input"
                                                                            id="{{ $branch->id }}">
                                                                        <label class="custom-control-label"
                                                                            for="{{ $branch->id }}"></label>
                                                            @endif
                                                        @else
                                                            {{ __("you don't have a permission") }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (App\Helpers\Helper::adminCan('admin.store-branch.show'))
                                                            <a href="{{ route('admin.store-branch.show', $branch->id) }}"
                                                                class="action-icon"> <i
                                                                    class="mdi mdi-eye-circle-outline"></i></a>
                                                        @endif
                                                    </td>


                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">

                    <div id="form-alert-message-parent"></div>
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">

                            <div class="card-body">
                                <h5 class="modal-title">{{ __('Logs') }}</h5>

                                <div class="table-responsive" id="table-pagination-div">
                                    <table id="table-pagination"
                                        style="text-align: center;margin-top: 25px;margin-bottom: 25px;"
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
            </div>
            <!-- end row-->
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
        </div> <!-- container -->
    @endsection
    @section('script')
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
        <script>
            $(document).ready(function() {
                tablePagination('{!! route('admin.activity-log.index', ['subject_id' => $result->id, 'subject_type' => 'Store']) !!}');
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

            function approved($routeName, $reload) {

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

            function approvedStoreRejected($routeName, $reload) {

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
