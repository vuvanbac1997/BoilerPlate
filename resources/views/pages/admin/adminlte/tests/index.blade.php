@extends('pages.admin.' . config('view.admin') . '.layout.application', ['menu' => 'tests'] )

@section('metadata')
@stop

@section('styles')
@stop

@section('scripts')
    <script src="{!! \URLHelper::asset('js/delete_item.js', 'admin/adminlte') !!}"></script>
@stop

@section('title')
@stop

@section('header')
    Test | Admin | {{ config('site.name') }}
@stop

@section('breadcrumb')
    <li class="breadcrumb-item active">Test</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-md-7">
                    <form method="get" accept-charset="utf-8" action="{!! action('Admin\TestController@index') !!}">
                        {!! csrf_field() !!}
                        <div class="row search-input">
                            <div class="col-md-12" style="margin-bottom: 10px;">
                                <div class="search-input-text">
                                    <input type="text" name="keyword" class="form-control" placeholder="Search here" id="keyword" value="{{ $keyword }}">
                                    <button type="submit" class="btn">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <p style="display: inline-block;">@lang('admin.pages.common.label.search_results', ['count' => $count])</p>
                </div>

                <div class="col-md-5 wrap-top-pagination">
                    <div class="heading-page-pagination">
                        {!! \PaginationHelper::render($paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit'], $count, $paginate['baseUrl'], ['keyword' => $keyword], $count, 'shared.topPagination') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="box-body" style=" overflow-x: scroll; ">
            <table class="table table-bordered logs-system">
                <thead>
                    <tr>
                        <th style="width: 10px">{!! \PaginationHelper::sort('id', 'ID') !!}</th>
                                                                                <th>{!! \PaginationHelper::sort('title', trans('admin.pages.tests.columns.title')) !!}</th>
                                                                                <th>{!! \PaginationHelper::sort('content', trans('admin.pages.tests.columns.content')) !!}</th>
                        
                        <th style="width: 40px">@lang('admin.pages.common.label.actions')</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach( $tests as $test )
                        <tr>
                            <td>{{ $test->id }}</td>
                                                                                            <td>{{ $test->title }}</td>
                                                                                            <td>{{ $test->content }}</td>
                                                        <td>
                                <a href="{!! URL::action('Admin\TestController@show', $test->id) !!}" class="btn btn-block btn-primary btn-xs">@lang('admin.pages.common.buttons.edit')</a>
                                <a href="#" class="btn btn-block btn-danger btn-xs delete-button" data-delete-url="{!! action('Admin\TestController@destroy', $test->id) !!}">@lang('admin.pages.common.buttons.delete')</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="box-footer">
            {!! \PaginationHelper::render($paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit'], $count, $paginate['baseUrl'], ['keyword' => $keyword]) !!}
        </div>
    </div>
@stop