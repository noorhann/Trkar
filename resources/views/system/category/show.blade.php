@extends('layouts.vertical', ['title' => 'Show'])

@section('css')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" />
    <link href="{{ asset('css/treeview.css') }}" rel="stylesheet">
@endsection


@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->

        @include('layouts.shared/breadcrumb')
        {{-- <h3>{{ __('Category') }} {{ $cat_name }} {{ __('List') }}</h3> --}}
        <div class="row">
            {{-- <div class="row"> --}}
            <div class="col-lg-12">
                <div class="card-box">
                    <ul id="myUL">
                        @foreach ($categories as $category)
                            <li>
                                
                              
                                <span class="box check-box"> {{ $category->name_ar }}</span>
                                
                                @if (count($category->childs))
                                    ({{ count($category->childs) }})
                                @endif
                                    <img style="height: 18px;" src="{{ isset($category) ?  App\Helpers\Helper::path() . '/' .$category->image : asset('assets/images/avatar.jpg') }}" alt="user-image" class="rounded-circle">
                                    
                                <ul class="nested active">
                                    @foreach ($childs as $child)
                                        @if (count($child->childs))
                                            <li><span class="box">
                                                    @if (count($child->childs))
                                                        {{ $child->name_ar }} ( {{ count($child->childs) }} )
                                                        <img style="height: 18px;" src="{{ isset($child) ?  App\Helpers\Helper::path() . '/' .$child->image : asset('assets/images/avatar.jpg') }}" alt="user-image" class="rounded-circle">

                                                    @endif
                                                </span>
                                            @else
                                            <li>{{ $child->name_ar }}
                                                <img style="height: 18px;" src="{{ isset($child) ? App\Helpers\Helper::path() . '/' .$child->image : asset('assets/images/avatar.jpg') }}" alt="user-image" class="rounded-circle">

                                        @endif

                                        @php
                                            $subchilds = DB::table('categories')
                                                ->where('parent_id', $child->id)
                                                ->orderBy('order', 'DESC')
                                                ->get();
                                        @endphp
                                        <ul class="nested ">
                                            @foreach ($subchilds as $subchild)
                                                <li style="padding: 0 32px;">
                                                    - {{ $subchild->name_ar }}
                                                    <img style="height: 18px;" src="{{ isset($subchild) ?  App\Helpers\Helper::path() . '/' .$subchild->image : asset('assets/images/avatar.jpg') }}" alt="user-image" class="rounded-circle">

                                                </li>
                                            @endforeach
                                        </ul>
                            </li>
                        @endforeach
                    </ul>
                    </li>
                    @endforeach
                    </ul>


                </div>
            </div>


        </div>
    </div>
@endsection

@section('script')
    <script>
        var toggler = document.getElementsByClassName("box");
        var i;

        for (i = 0; i < toggler.length; i++) {
            toggler[i].addEventListener("click", function() {
                this.parentElement.querySelector(".nested").classList.toggle("active");
                this.classList.toggle("check-box");
            });
        }
    </script>
@endsection
