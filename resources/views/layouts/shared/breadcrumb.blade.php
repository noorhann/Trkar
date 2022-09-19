<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin') }}">{{ __('Home') }}</a></li>
                    @foreach ($breadcrumb as $key => $value)
                        @if (isset($value['url']))
                            <li class="breadcrumb-item"><a href="{{ $value['url'] }}">{{ $value['text'] }}</a></li>
                        @else
                            <li class="breadcrumb-item active" aria-current="page">{{ $value['text'] }}</li>
                        @endif
                    @endforeach
                </ol>
            </div>
            <h4 class="page-title">{{ $pageTitle }}</h4>
        </div>
    </div>
</div>
