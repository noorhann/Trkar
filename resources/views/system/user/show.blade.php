@extends('layouts.vertical', ['title' => $pageTitle])

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        @include('layouts.shared/breadcrumb')
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12 col-xl-12">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-xl-3">
                                <img src="{{ isset($result) ? App\Helpers\Helper::path() . '/' . $result->image : asset('assets/images/avatar.jpg') }}"
                                    class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">

                                <h4 class="mb-0">{{ $result->username }}</h4>
                                <h6 class="text-muted">{{ $result->email }}</h6>
                                <h6 class="text-muted">{{ $result->phone }}</h6>

                            </div>
                            <div class="col-lg-7 col-xl-7">
                                <div class="text-start mt-3" style="text-align: right !important;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="text-muted mb-2 font-13">

                                                <strong style="color:#3e4449c9">{{ __('ID') }} :</strong>
                                                <span class="ms-2"> {{ $result->uuid }}</span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-md-4">
                                            <!-- assignee -->
                                            <p class="text-muted mb-2 font-13">

                                                <strong style="color:#3e4449c9">{{ __('Country') }} :</strong>
                                                <span class="ms-2">
                                                    @if (isset($result->country))
                                                        {{ $result->country->{'name_' . \App::getLocale()} }}
                                                    @else
                                                        --
                                                    @endif
                                                </span>
                                            </p>
                                            <!-- end assignee -->
                                        </div> <!-- end col -->
                                        <div class="col-md-4">
                                            <!-- assignee -->
                                            <p class="text-muted mb-2 font-13">

                                                <strong style="color:#3e4449c9">{{ __('City') }} :</strong>
                                                <span class="ms-2">
                                                    @if (isset($result->city))
                                                        {{ $result->city->{'name_' . \App::getLocale()} }}
                                                    @else
                                                        --
                                                    @endif
                                                </span>
                                            </p>
                                            <!-- end assignee -->
                                        </div> <!-- end col -->
                                        <div class="col-md-4">
                                            <!-- Reported by -->
                                            <p class="text-muted mb-2 font-13">

                                                <strong style="color:#3e4449c9">{{ __('Area') }} :</strong>
                                                <span class="ms-2">
                                                    @if (isset($result->area))
                                                        {{ $result->area->{'name_' . \App::getLocale()} }}
                                                    @else
                                                        --
                                                    @endif
                                                </span>
                                            </p>
                                            <!-- end Reported by -->
                                        </div> <!-- end col -->

                                    </div> <!-- end row -->
                               
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="text-muted mb-2 font-13">

                                                <strong style="color:#3e4449c9">{{ __('Last Login') }} :</strong>
                                                <span class="ms-2"> {{ $result->last_login }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="text-muted mb-2 font-13">

                                                <strong style="color:#3e4449c9">{{ __('Phone Verified At') }} :</strong>
                                                <span class="ms-2">

                                                    @if ($result->phone_verified_at != null)
                                                        {{ $result->phone_verified_at }}
                                                    @else
                                                        <span class="badge badge-soft-danger">{{ __('In-Active') }}</span>
                                                    @endif
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="text-muted mb-2 font-13">

                                                <strong style="color:#3e4449c9">{{ __('Email Verified At') }} :</strong>
                                                <span class="ms-2">
                                                    @if ($result->email_verified_at != null)
                                                        {{ $result->email_verified_at }}
                                                    @else
                                                        <span class="badge badge-soft-danger">{{ __('In-Active') }}</span>
                                                    @endif
                                                </span>
                                            </p>
                                        </div>
                                    </div>


                                </div>

                            </div>
                            <div class="col-lg-1 col-xl-1">
                                <h3 class="ms-2">
                                    @if ($result->status == 1)
                                        <span class="badge badge-soft-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge badge-soft-danger">{{ __('In-Active') }}</span>
                                    @endif
                                </h3>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card -->
            </div>
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <h5 class="card-title font-16 mb-3">{{ __('Orders') }}</h5>

                        <div class="table-responsive mt-4">
                            <table class="table table-bordered table-centered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Order Number') }}</th>
                                        <th>{{ __('Vendor') }}</th>
                                        <th>{{ __('Store') }}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @if (count($result->orders))
                                            @foreach ($result->orders as $order)
                                            @endforeach
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->order_number }}</td>
                                            <td>
                                                @if (isset($order->vendor))
                                                    {{ $order->vendor->username }}
                                                @else
                                                    --
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($order->store))
                                                    {{ $order->store->{'name_' . \App::getLocale()} }}
                                                @else
                                                    --
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <h5 class="card-title font-16 mb-3">{{ __('User Addresses') }}</h5>

                        <div class="table-responsive mt-4">
                            <table class="table table-bordered table-centered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Recipent Name') }}</th>
                                        <th>{{ __('Recipent Phone') }}</th>
                                        <th>{{ __('Recipent Email') }}</th>
                                        <th>{{ __('Address') }}</th>
                                        <th>{{ __('Country') }}</th>
                                        <th>{{ __('City') }}</th>
                                        <th>{{ __('Area') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @if (count($result->userAddresses))
                                            @foreach ($result->userAddresses as $user)
                                            @endforeach
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->recipent_name }}</td>
                                            <td>{{ $user->recipent_phone }}</td>
                                            <td>{{ $user->recipent_email }}</td>
                                            <td>{{ $user->address }}</td>
                                            <td>
                                                @if (isset($user->country))
                                                    {{ $user->country->{'name_' . \App::getLocale()} }}
                                                @else
                                                    --
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($user->city))
                                                    {{ $user->city->{'name_' . \App::getLocale()} }}
                                                @else
                                                    --
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($user->area))
                                                    {{ $user->area->{'name_' . \App::getLocale()} }}
                                                @else
                                                    --
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- end row-->

    </div> <!-- container -->
@endsection
