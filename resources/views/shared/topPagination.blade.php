<div class="dropdown float-right">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        @lang('admin.pages.common.label.page') {{ $currentPage  . " / " . $lastPage }}
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        @foreach( $pages as $page)
            @if( $page['current'] )
                <a class="dropdown-item disabled">@lang('admin.pages.common.label.page') {{ $page['number'] }} <i style='font-size: 10px; margin-left: 10px; color: #337ab7;' class='fa fa-check'></i></a>
            @else
                <a class="dropdown-item" href="{!! $page['link'] !!}">Page {{ $page['number'] }}</a>
            @endif
        @endforeach
    </div>
</div>
