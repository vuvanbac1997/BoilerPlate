<div class="d-flex justify-content-center">
    <ul class="pagination">
        @if( isset($firstPageLink) )
            <li class="page-item"><a class="page-link" href="{!! $firstPageLink!!}"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a></li>
        @else
            <li class="page-item disabled"><a class="page-link"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a></li>
        @endif
        @foreach( $pages as $page)
            @if( $page['current'] )
                <li class="page-item active"><a class="page-link">{{ $page['number'] }}</a></li>
            @else
                <li class="page-item"><a class="page-link" href="{!! $page['link'] !!}">{{ $page['number'] }}</a></li>
            @endif
        @endforeach
        @if( isset($lastPageLink) )
            <li class="page-item"><a class="page-link" href="{!! $lastPageLink!!}"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a></li>
        @else
            <li class="page-item"><a class="page-link"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a></li>
        @endif
    </ul>
</div>