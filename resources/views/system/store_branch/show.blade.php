@extends('layouts.vertical', ['title' => $pageTitle])

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        @include('layouts.shared/breadcrumb')
        <!-- end page title -->

        <div class="row">
                
                <div class="col-xl-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="table-responsive mt-4">
                                <table class="table table-bordered table-centered mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <td>#</td>
                                            <th>{{ __('Product') }}</th>
                                            <th>{{ __('Product Quantity') }}</th>
                                            <th>{{ __('Product Quantity Reminder') }}</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            @foreach ($products as $key => $value)
                                                <td>{{ $key + 1 }}</td>

                                                <td>
                                                    @if (isset($value->product))
                                                        {{ $value->product->{'name_' . \App::getLocale()} }}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $value->quantity }}
                                                </td>
                                                <td>
                                                    {{ $value->quantity_reminder }}
                                                </td>
                                                
                                            @endforeach
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
