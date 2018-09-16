@extends('pages.admin.' . config('view.admin') . '.layout.application', ['menu' => 'logs'] )

@section('metadata')
@stop

@section('styles')
    <style>
        .logs-system tr td:nth-child(1), .logs-system tr td:nth-child(4), .logs-system tr td:nth-child(6) {
            text-align: center;
        }

        .logs-system tr td:nth-child(2), .logs-system tr td:nth-child(3), .logs-system tr td:nth-child(5), .logs-system tr td:nth-child(7) {
            text-align: left;
        }
    </style>
@stop

@section('title')
@stop

@section('header')
    Logs
@stop

@section('breadcrumb')
    <li class="breadcrumb-item active">Logs</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-md-7">
                    <form method="get" accept-charset="utf-8" action="{!! action('Admin\LogController@index') !!}">
                        {!! csrf_field() !!}
                        <div class="row search-input">
                            <div class="col-md-12" style="margin-bottom: 10px;">
                                <div class="search-input-text input-group">
                                    <input type="text" name="l_search_keyword" class="form-control" placeholder="Search here" id="l-search-keyword" value="{{ $keyword }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-outline-secondary" type="button"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <p style="display: inline-block;">@lang('admin.pages.common.label.search_results', ['count' => $count])</p>
                </div>

                <div class="col-md-5 wrap-top-pagination">
                    <div class="heading-page-pagination">
                        {!! \PaginationHelper::render($paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit'], $count, $paginate['baseUrl'], [], $count, 'shared.topPagination') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="box-body" style=" overflow-x: scroll; ">
            <table class="table table-bordered logs-system">
                <tr>
                    <th style="width: 10px">{!! \PaginationHelper::sort('id', 'ID') !!}</th>
                    <th style="width: 10px">{!! \PaginationHelper::sort('user_name', trans('admin.pages.logs.columns.user_name')) !!}</th>
                    <th style="width: 10px">{!! \PaginationHelper::sort('email', trans('admin.pages.logs.columns.email')) !!}</th>
                    <th style="width: 10px">{!! \PaginationHelper::sort('action', trans('admin.pages.logs.columns.action')) !!}</th>
                    <th style="width: 10px">{!! \PaginationHelper::sort('table', trans('admin.pages.logs.columns.table')) !!}</th>
                    <th style="width: 10px">{!! \PaginationHelper::sort('record_id', trans('admin.pages.logs.columns.record_id')) !!}</th>
                    <th style="width: 10px">{!! \PaginationHelper::sort('query', trans('admin.pages.logs.columns.query')) !!}</th>

                </tr>
                @foreach( $logs as $log )
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->user_name }}</td>
                        <td>{{ $log->email }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->table }}</td>
                        <td>{{ $log->record_id }}</td>
                        <td>{{ $log->query }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="box-footer">
            {!! \PaginationHelper::render($paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit'], $count, $paginate['baseUrl'], []) !!}
        </div>
    </div>
@stop