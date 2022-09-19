@extends('layouts.vertical', ['title' => $pageTitle])

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        @include('layouts.shared/breadcrumb')
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-8 col-lg-8">
                <!-- project card -->
                <div class="card d-block">
                    <div class="card-body">
                        <div class="clerfix"></div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Ticket type -->
                                <label class="mt-2 mb-1">{{ __('uuid') }} :</label>
                                <p>
                                    <i class="mdi mdi-ticket font-18 text-success me-1 align-middle"></i>
                                    {{ $result->uuid }}
                                </p>
                                <!-- end Ticket Type -->
                            </div>
                            <div class="col-md-6">
                                <!-- Ticket type -->
                                <label class="mt-2 mb-1">{{ __('OEN') }} :</label>
                                <p>
                                    <i class="mdi mdi-ticket font-18 text-success me-1 align-middle"></i>
                                    {{ $result->OEN }}
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
                                <label class="mt-2 mb-1">{{ __('Details (EN)') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>{{ $result->details_en }}</p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->

                            <div class="col-md-6">
                                <!-- assignee -->
                                <label class="mt-2 mb-1">{{ __('Details (AR)') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p> {{ $result->details_ar }} </p>
                                    </div>
                                </div>
                                <!-- end assignee -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Serial Number') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p> {{ $result->serial_number }} </p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->
                            <div class="col-md-6">
                                <!-- assignee -->
                                <label class="mt-2 mb-1">{{ __('Slug') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p> {{ $result->slug }} </p>
                                    </div>
                                </div>
                                <!-- end assignee -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Product Type') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if (isset($result->productType))
                                                {{ $result->productType->{'name_' . \App::getLocale()} }}
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
                                <label class="mt-2 mb-1">{{ __('Year') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if (count($yearsArrays))
                                                @forelse ($yearsArrays as $year)
                                                    {{ $year }} ,
                                                @endforeach
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
                                <label class="mt-2 mb-1">{{ __('Store Vendor') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if (isset($result->store))
                                                {{ $result->store->vendor->username }}
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
                                <label class="mt-2 mb-1">{{ __('Category') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if (isset($result->category))
                                                {{ $result->category->{'name_' . \App::getLocale()} }}
                                            @else
                                                --
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <!-- end assignee -->
                            </div> <!-- end col -->
                            <div class="col-md-6">
                                <!-- assignee -->
                                <label class="mt-2 mb-1">{{ __('Sub Category') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if (isset($result->subCategory))
                                                {{ $result->subCategory->{'name_' . \App::getLocale()} }}
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
                            <div class="col-md-4">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Made') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if (count($madeArrays))
                                                @forelse ($madeArrays as $made)
                                                    {{ $made }} ,
                                                @endforeach
                                            @else
                                                --
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->

                            <div class="col-md-4">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Model') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if (count($modelArrays))
                                            @forelse ($modelArrays as $model)
                                                {{ $model }} ,
                                            @endforeach
                                        @else
                                            --
                                        @endif
                                        </p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->

                            <div class="col-md-4">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Engine') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if (count($engineArrays))
                                            @forelse ($engineArrays as $engine)
                                                {{ $engine }} ,
                                            @endforeach
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
                                <label class="mt-2 mb-1">{{ __('Price') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            {{ $result->price }}
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
                            <div class="col-md-4">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Manufacturer') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if (isset($result->manufacturer))
                                                {{ $result->manufacturer->{'name_' . \App::getLocale()} }}
                                            @else
                                                --
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->

                            <div class="col-md-4">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Original Country') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if (isset($result->originalCountry))
                                                {{ $result->originalCountry->{'name_' . \App::getLocale()} }}
                                            @else
                                                --
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->

                            <div class="col-md-4">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Store') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if (isset($result->stores))
                                                {{ $result->stores->{'name_' . \App::getLocale()} }}
                                            @else
                                                --
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <!-- end Reported by -->
                            </div> <!-- end col -->
                        </div>
                        <div class="row">
                            <div class="col-md-12">
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

                        <div class="row">
                            <div class="col-md-12">
                                <!-- Reported by -->
                                <label class="mt-2 mb-1">{{ __('Tags') }} :</label>
                                <div class="d-flex align-items-start">
                                    <div class="w-100">
                                        <p>
                                            @if (count($result->tags))
                                                @foreach ($result->tags as $tag)
                                                    {{ $tag->name }},
                                                @endforeach
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

            <div class="col-xl-4 col-lg-4">

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
                        </div>
                        @if (count($result->images))
                            @foreach ($result->images as $image)
                                <div class="col-xl-6 col-lg-6" style=" width: 50%; height: 50%; ">
                                    <div class="p-2">
                                        <div class="row align-items-center">
                                            <div class="tab-pane active show" id="product-1-item">
                                                <img src="{{ isset($image) ? App\Helpers\Helper::path() . '/' . $image->image : asset('assets/images/avatar.jpg') }}"
                                                    alt="" class="img-fluid mx-auto d-block rounded">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-lg-12">

                <div class="card">
                    <div class="card-body">

                        <h5 class="card-title font-16 mb-3">{{ __('Questions') }}</h5>
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered table-centered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('Question') }}</th>
                                        <th>{{ __('Answer') }}</th>
                                        <th>{{ __('User Name') }}</th>
                                        <th>{{ __('Vendor') }}</th>
                                        <th>{{ __('Vendor Staff') }}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($result->questions))
                                        @foreach ($result->questions as $question)
                                            <tr>

                                                <td>{{ $question->question }}</td>
                                                <td>{{ $question->answer }}</td>
                                                <td>
                                                    @if (isset($question->user))
                                                        {{ $question->user->username }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (isset($question->vendorStaff))
                                                        {{ $question->vendorStaff->username }}
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
            @if (count($result->attributes))
            <div class="col-ms-12 col-lg-6">
                <div class="card">
                    <div class="card-body">

                        <h5 class="card-title font-16 mb-3">{{ __('Attributes') }}</h5>

                        <div class="table-responsive mt-4">
                            <table class="table table-bordered table-centered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('Key') }}</th>
                                        <th>{{ __('Value') }}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                        @foreach ($result->attributes as $attribute)
                                            <tr>

                                                <td>{{ $attribute->key }}</td>
                                                <td>{{ $attribute->value }}</td>

                                            </tr>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-ms-12 col-lg-6">
                <div class="card">
                    <div class="card-body">

                        <h5 class="card-title font-16 mb-3">{{ __('Quantities') }}</h5>

                        <div class="table-responsive mt-4">
                            <table class="table table-bordered table-centered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('Quantity') }}</th>
                                        <th>{{ __('Quantity Reminder') }}</th>
                                        <th>{{ __('Branch') }}</th>
                                        <th>{{ __('Store') }}</th>
                                        <th>{{ __('Vendor') }}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($result->quantities))
                                        @foreach ($result->quantities as $quantity)
                                            <tr>

                                                <td>{{ $quantity->quantity }}</td>
                                                <td>{{ $quantity->quantity_reminder }}</td>
                                                <td>
                                                    @if (isset($quantity->branch))
                                                        {{ $quantity->branch->name }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (isset($quantity->branch->store))
                                                        {{ $quantity->branch->store->{'name_' . \App::getLocale()} }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (isset($quantity->branch->store->vendor))
                                                        {{ $quantity->branch->store->vendor->username }}
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
            <div class="col-ms-12 col-lg-6">
                <div class="card">
                    <div class="card-body">

                        <h5 class="card-title font-16 mb-3">{{ __('Reviews') }}</h5>

                        <div class="table-responsive mt-4">
                            <table class="table table-bordered table-centered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('User Name') }}</th>
                                        <th>{{ __('Count') }}</th>
                                        <th>{{ __('Details') }}</th>
                                        <th>{{ __('Evaluation') }}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($result->reviews))
                                        @foreach ($result->reviews as $reviews)
                                            <tr>

                                                <td>
                                                    @if (isset($reviews->user))
                                                        {{ $reviews->user->username }}
                                                    @endif
                                                </td>
                                                <td>{{ $reviews->count }}</td>
                                                <td>{{ $reviews->details }}</td>
                                                <td>{{ $reviews->evaluation }}</td>

                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-ms-12 col-lg-6">
                <div class="card">
                    <div class="card-body">

                        <h5 class="card-title font-16 mb-3">{{ __('Views') }}</h5>

                        <div class="table-responsive mt-4">
                            <table class="table table-bordered table-centered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('User Name') }}</th>
                                        <th>{{ __('Count') }}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($result->views))
                                        @foreach ($result->views as $view)
                                            <tr>

                                                <td>
                                                    @if (isset($view->user))
                                                        {{ $view->user->username }}
                                                    @endif
                                                </td>
                                                <td>{{ $view->count }}</td>

                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @if ($result->product_type_id == 2)
            <div class="col-ms-12 col-lg-6">
                <div class="card">
                    <div class="card-body">

                        <h5 class="card-title font-16 mb-3">{{ __('Wholesales') }}</h5>

                        <div class="table-responsive mt-4">
                            <table class="table table-bordered table-centered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('Price') }}</th>
                                        <th>{{ __('Minimum Quntity') }}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($result->wholesales))
                                        @foreach ($result->wholesales as $wholesale)
                                            <tr>

                                                <td>{{ $wholesale->price }}</td>
                                                <td>{{ $wholesale->minimum_quntity }}</td>

                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>  
            @endif
           
        </div>
        <!-- end row-->

    </div> <!-- container -->
@endsection
