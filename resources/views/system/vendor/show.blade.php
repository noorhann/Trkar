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
                                <img src="{{ asset('assets/images/avatar.jpg') }}"
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
                                                <span class="ms-2"> {{ $result->id }}</span>
                                            </p>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="text-muted mb-2 font-13">

                                                <strong style="color:#3e4449c9">{{ __('Address') }} :</strong>
                                                <span class="ms-2"> {{ $result->address }}</span>
                                            </p>
                                        </div>
                                    </div>

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
            <div class="col-xl-6 col-lg-6">

                <div class="card">
                    <div class="card-body">

                        <h5 class="card-title font-16 mb-3">{{ __('Staff') }}</h5>

                        <div class="table-responsive mt-4">
                            <table class="table table-bordered table-centered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('User Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Phone') }}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($result->vendorStaff))
                                        @foreach ($result->vendorStaff as $vendorStaff)
                                            <tr>

                                                <td>{{ $vendorStaff->id }}</td>
                                                <td>{{ $vendorStaff->username }}</td>
                                                <td>{{ $vendorStaff->email }}</td>
                                                <td>{{ $vendorStaff->phone }}</td>
                                            </tr>
                                        @endforeach
                                    @endif

                                </tbody>
                            </table>
                        </div>





                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-box">
                    <h4 class="header-title mb-3">{{ __('Store Details') }}</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Reported by -->
                            <label class="mt-2 mb-1">{{ __('Store Name') }} :</label>
                            <div class="d-flex align-items-start">
                                <div class="w-100">
                                    <p>
                                        @if (isset($store))
                                            {{ $store->{'name_' . \App::getLocale()} }}
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
                            <label class="mt-2 mb-1">{{ __('Store Phone') }} :</label>
                            <div class="d-flex align-items-start">
                                <div class="w-100">
                                    <p>
                                        @if (isset($store))
                                            {{ $store->phone }}
                                        @else
                                            --
                                        @endif
                                    </p>

                                </div>
                            </div>
                            <!-- end assignee -->
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div> <!-- end row -->
            </div> <!-- end row -->

            <div class="col-lg-6">
                <div class="card-box">
                    <h4 class="header-title mb-3">{{ __('Map') }}</h4>
                    <div class="iframe-container">
                        <div id="map" class=""></div>
                    </div>

                </div> <!-- end card-box-->
            </div> <!-- end col-->
            <div class="col-xl-12 col-lg-12">

                <div class="card">
                    <div class="card-body">

                        <h5 class="card-title font-16 mb-3">{{ __('Store Branches') }}</h5>

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
                                    @if (isset($store->branches))
                                        <tr>
                                            @foreach ($store->branches as $key => $branch)
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $branch->name }}</td>
                                                <td>{{ $branch->slug }}</td>
                                                <td>{{ $branch->phone }}</td>

                                                <td>{{ $branch->address }}</td>
                                                <td>
                                                    @if ($branch->status == 1)
                                                        <div class="custom-control custom-switch">
                                                            <input
                                                                onchange="approved('{{ route('admin.branch.approved', $branch->id) }}')"
                                                                type="checkbox" class="custom-control-input" checked
                                                                id="{{ $branch->id }}">
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
                                                </td>
                                                <td>
                                                    @if (App\Helpers\Helper::adminCan('admin.store-branch.show'))
                                                        <a href="{{ route('admin.store-branch.show', $branch->id) }}"
                                                            class="action-icon"> <i
                                                                class="mdi mdi-eye-circle-outline"></i></a>
                                                    @endif
                                                </td>
                                            @endforeach

                                        </tr>
                                    @endif
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
    </script>
@endsection
