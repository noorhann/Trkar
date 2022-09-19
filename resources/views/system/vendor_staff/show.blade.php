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
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Banner') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p> {{ $result->banner }} </p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->

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
                                <label class="mt-2 mb-1">{{ __('Longitude') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p> {{ $result->longitude }} </p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->

                            <div class="col-md-6">
                                <!-- assignee -->
                                <label class="mt-2 mb-1">{{ __('Latitude') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p> {{ $result->latitude }} </p>
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
                        <div class="row">
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
                        </div> <!-- end row -->
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

                        <h5 class="card-title font-16 mb-3">Attachments</h5>

                        <div class="card mb-1 shadow-none border">
                            <div class="p-2">
                                <div class="row align-items-center">
                                    <div class="tab-pane active show" id="product-1-item">
                                        <img src="{{ isset($result) ? App\Helpers\Helper::path() . '/' . $result->image : asset('assets/images/avatar.jpg') }}"
                                            alt="" class="img-fluid mx-auto d-block rounded">
                                    </div>

                                </div>
                            </div>
                        </div>





                    </div>
                </div>
            </div>
         
        </div>
        <!-- end row-->

    </div> <!-- container -->
@endsection
