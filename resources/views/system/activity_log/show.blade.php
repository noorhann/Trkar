@extends('layouts.vertical', ['title' => $pageTitle])

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        @include('layouts.shared/breadcrumb')
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <!-- project card -->
                <div class="card d-block">
                    <div class="card-body">
                        <div class="clerfix"></div>
                        <h5 class="card-title font-16 mb-3">{{ __('Name Of Date') }}</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- assignee -->
                                <label class="mt-2 mb-1"> {{__('Name')}} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if (isset($ActivityLog->name_ar))
                                                {{ $ActivityLog->name_ar }}
                                            @elseif (isset($ActivityLog->username))
                                                {{ $ActivityLog->username }}
                                            @elseif (isset($ActivityLog->recipent_name))
                                                {{ $ActivityLog->recipent_name }}
                                            @elseif (isset($ActivityLog->name))
                                                {{ $ActivityLog->name }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <!-- end assignee -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                    </div> <!-- end card-body-->

                </div> <!-- end card-->

            </div>
            @if ($description == 'updated')
                <div class="col-xl-6 col-lg-6">
                    <!-- project card -->
                    <div class="card d-block">
                        <div class="card-body">
                            <div class="clerfix"></div>
                            <h5 class="card-title font-16 mb-3">{{ __('After Edit') }}</h5>
                            <div class="row">
                                @if (isset($result['attributes']))
                                    @foreach ($result['attributes'] as $key => $attributes)
                                        @if ($key != 'image')
                                            <div class="col-md-6">
                                                <!-- assignee -->
                                                <label class="mt-2 mb-1"> {{ $key }} :</label>
                                                <div class="d-flex align-items-start">
                                                    <div class="w-100">
                                                        <p>{{ $attributes }}</p>
                                                    </div>
                                                </div>
                                                <!-- end assignee -->
                                            </div> <!-- end col -->
                                        @elseif ($key == 'image')
                                            <div class="col-md-6">
                                                <label class="mt-2 mb-1"> {{ $key }} :</label>
                                                <img src="{{ App\Helpers\Helper::path() . '/' . $attributes }}"
                                                    alt="" style="width: 40px; height: 40px;">
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div> <!-- end row -->
                        </div> <!-- end card-body-->

                    </div> <!-- end card-->

                </div>
                <div class="col-xl-6 col-lg-6">

                    <div class="card">
                        <div class="card-body">

                            <h5 class="card-title font-16 mb-3">{{ __('Before Edit') }}</h5>

                            <div class="row">
                                @if (isset($result['old']))
                                    @foreach ($result['old'] as $key => $old)
                                        @if ($key != 'image')
                                            <div class="col-md-6">
                                                <!-- assignee -->
                                                <label class="mt-2 mb-1"> {{ $key }} :</label>
                                                <div class="d-flex align-items-start">
                                                    <div class="w-100">
                                                        <p>{{ $old }}</p>
                                                    </div>
                                                </div>
                                                <!-- end assignee -->
                                            </div> <!-- end col -->
                                        @elseif ($key == 'image')
                                            <div class="col-md-6">
                                                <label class="mt-2 mb-1"> {{ $key }} :</label>
                                                <img src="{{ App\Helpers\Helper::path() . '/' . $old }}" alt=""
                                                    style="width: 40px; height: 40px;">
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div> <!-- end row -->

                        </div>
                    </div>
                </div>
            @elseif ($description == 'created')
                <div class="col-xl-6 col-lg-12">
                    <!-- project card -->
                    <div class="card d-block">
                        <div class="card-body">
                            <div class="clerfix"></div>
                            <h5 class="card-title font-16 mb-3">{{ __('Added data') }}</h5>
                            <div class="row">
                                @foreach ($result['attributes'] as $key => $attributes)
                                    @if ($key != 'image')
                                        <div class="col-md-6">
                                            <!-- assignee -->
                                            <label class="mt-2 mb-1"> {{ $key }} :</label>
                                            <div class="d-flex align-items-start">
                                                <div class="w-100">
                                                    <p>{{ $attributes }}</p>
                                                </div>
                                            </div>
                                            <!-- end assignee -->
                                        </div> <!-- end col -->
                                    @elseif ($key == 'image')
                                        <div class="col-md-6">
                                            <label class="mt-2 mb-1"> {{ $key }} :</label>
                                            <img src="{{ App\Helpers\Helper::path() . '/' . $attributes }}" alt=""
                                                style="width: 40px; height: 40px;">
                                        </div>
                                    @endif
                                @endforeach
                            </div> <!-- end row -->
                        </div> <!-- end card-body-->

                    </div> <!-- end card-->

                </div>
            @elseif ($description == 'deleted')
                <div class="col-xl-6 col-lg-12">
                    <!-- project card -->
                    <div class="card d-block">
                        <div class="card-body">
                            <div class="clerfix"></div>
                            <h5 class="card-title font-16 mb-3">{{ __('Deleted data') }}</h5>
                            <div class="row">
                                @foreach ($result['old'] as $key => $attributes)
                                    @if ($key != 'image')
                                        <div class="col-md-6">
                                            <!-- assignee -->
                                            <label class="mt-2 mb-1"> {{ $key }} :</label>
                                            <div class="d-flex align-items-start">
                                                <div class="w-100">
                                                    <p>{{ $attributes }}</p>
                                                </div>
                                            </div>
                                            <!-- end assignee -->
                                        </div> <!-- end col -->
                                    @elseif ($key == 'image')
                                        <div class="col-md-6">
                                            <label class="mt-2 mb-1"> {{ $key }} :</label>
                                            <img src="{{ App\Helpers\Helper::path() . '/' . $attributes }}" alt=""
                                                style="width: 40px; height: 40px;">
                                        </div>
                                    @endif
                                @endforeach
                            </div> <!-- end row -->
                        </div> <!-- end card-body-->

                    </div> <!-- end card-->

                </div>
            @endif
        </div>
        <!-- end row-->

    </div> <!-- container -->
@endsection
