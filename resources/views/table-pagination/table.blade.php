<div id="table-pagination-main-div">

    @if(!empty($headerDescription))
        <div class="row"  style="margin-top: 10px;">
            <div class="col-sm-12">
                <div class="alert alert-warning">
                    {!! $headerDescription !!}
                </div>
            </div>
        </div>
    @endif

    <table id="table-pagination" style="text-align: center;margin-top: 10px;margin-bottom: 25px;" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>{{__('#')}}</th>
                @foreach($headColumns as $value)
                    <th>{{$value}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @if(!$data->total())
                <tr id="table-pagination-tr-empty">
                    <td id="table-pagination-td-empty" colspan="{{count($headColumns)}}">{{__('Empty Records')}}</td>
                </tr>
            @else
                @foreach($data as $dataKey => $dataValue)
                    <tr id="table-pagination-tr-{{$dataKey}}">
                        @if ($currentPage == 1)
                        <td>{{$dataKey + 1  }} </td>
                        @else
                        @php $num = ($currentPage - 1) * $items @endphp
                        <td>{{$num + 1 + $dataKey}} </td>
                        @endif
                       
                        @foreach($columns as $key => $value)
                            <td id="table-pagination-td-{{$key}}">
                                @if(is_null($value))
                                    {{$dataValue->$key}}
                                @else
                                    {!! $value($dataValue) !!}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>



    <div class="row">
        <div class="col-sm-4">{{__('Showing :count of :total entries',['count'=> $data->count(),'total'=> $data->total()])}}</div>
        <div class="col-sm-8">
            @if ($data->hasPages())
                <nav style="float: right;">
                    <ul class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($data->onFirstPage())
                            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                                <span class="page-link" aria-hidden="true">&lsaquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="javascript:tablePaginationLoadPage({{request('page')-1}});" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                            </li>
                        @endif
                        {{-- Pagination Elements --}}
                        @foreach ($data->links()->elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $data->currentPage())
                                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="javascript:tablePaginationLoadPage({{$page}});">{{ $page }}</a></li>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($data->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="javascript:tablePaginationLoadPage({{request('page') ? request('page')+1 : 2}});" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                            </li>
                        @else
                            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                                <span class="page-link" aria-hidden="true">&rsaquo;</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            @endif
        </div>
    </div>
    <script type="text/javascript">
        function tablePaginationLoadPage($page){
            // addLoading();
            $parameters = {};
            @foreach(request()->query->all() as $key => $value)
                $parameters.{{$key}} = '{{str_replace('\\','\\\\',$value)}}';
            @endforeach
            $parameters.page = $page;

            $.get('{{request()->url()}}',$parameters,function ($response) {
                // removeLoading();
                $('#table-pagination-main-div').html($response);
                feather.replace();
            });
        }
    </script>
</div>
