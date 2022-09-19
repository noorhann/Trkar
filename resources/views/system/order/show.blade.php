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
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Order Number') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>{{ $result->order_number }}</p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->


                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Shipping Address Name From') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if (isset($result->user))
                                                {{ $result->user->username }}
                                            @else
                                                --
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->
                            <div class="col-md-6">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Shipping Address Address From') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if (isset($result->user))
                                                {{ $result->user->address }}
                                            @else
                                                --
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->

                        </div> <!-- end row -->
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Shipping Address Name To') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if (isset($userAddresses))
                                                {{$userAddresses->recipent_name }}
                                            @else
                                                --
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->
                            <div class="col-md-6">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Shipping Address Address To') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if (isset($userAddresses))
                                                {{ $userAddresses->address }}
                                            @else
                                                --
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->

                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Shipping By') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if (isset($result->shippingAddress))
                                                {{ $result->shippingAddress->{'name_' . \App::getLocale()}  }}
                                            @else
                                                --
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->
                            <div class="col-md-6">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Payment By') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if (isset($result->paymentMethod))
                                                {{ $result->paymentMethod->{'name_' . \App::getLocale()}  }}
                                            @else
                                                --
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->
                            <div class="col-md-6">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Shipping Cost') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                                {{ $result->shipping_cost  }}
                                 
                                        </p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Grand Total') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            {{ $result->grand_total }}
                                        </p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->
                            <div class="col-md-6">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Discount') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            {{ $result->discount }}
                                        </p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                        <div class="row">

                            <div class="col-md-12">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Order Status') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if (isset($result->orderStatus))
                                                {{ $result->orderStatus->{'name_' . \App::getLocale()} }}
                                            @else
                                                --
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->

                        </div> <!-- end row -->

                    </div> <!-- end card-->

                </div>
            </div>
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">

                        <h5 class="card-title font-16 mb-3">{{ __('Details') }}</h5>

                        <div class="table-responsive mt-4">
                            <table class="table table-bordered table-centered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Branch') }}</th>
                                        <th>{{ __('Product') }}</th>
                                        <th>{{ __('Product Number') }}</th>
                                        <th>{{ __('Quantity') }}</th>
                                        <th>{{ __('Price') }}</th>
                                        <th>{{ __('Tax') }}</th>
                                        <th>{{ __('Vendor') }}</th>
                                        <th>{{ __('Store') }}</th>
                                      

                                    </tr>
                                </thead>
                                <tbody> 
                                    @if (count($result->orderDetails))
                                        @foreach ($result->orderDetails as $key => $value)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    @if (isset($value->branch))
                                                        {{ $value->branch->{'name_' . \App::getLocale()} }}
                                                    @else
                                                        --
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (isset($value->product))
                                                        {{ $value->product->{'name_' . \App::getLocale()} }}
                                                    @else
                                                        --
                                                    @endif
                                                </td>
                                                <td>{{ $value->product_number }}</td>

                                                <td>{{ $value->quantity }}</td>
                                                <td>{{ $value->price }}</td>
                                                <td>{{ $value->tax }}</td>
                                              
                                                <td>
                                                    @if (isset($value->vendor))
                                                        {{ $value->vendor->username }}
                                                    @else
                                                        --
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (isset($value->store))
                                                        {{ $value->store->{'name_' . \App::getLocale()} }}
                                                    @else
                                                        --
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
        </div>
        <!-- end row-->

    </div> <!-- container -->
@endsection
