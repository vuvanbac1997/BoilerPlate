@extends('pages.admin.' . config('view.admin') . '.layout.application', ['menu' => 'admin_user_notifications'] )

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
    AdminUserNotifications
@stop

@section('breadcrumb')
    <li class="breadcrumb-item active">AdminUserNotifications</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">

            <div class="row">
                <div class="col-sm-6">
                    <h3 class="box-title">
                        <p class="text-right">
                            <a href="{!! URL::action('Admin\AdminUserNotificationController@create') !!}"
                               class="btn btn-block btn-primary btn-sm"
                               style="width: 125px;">@lang('admin.pages.common.buttons.create')</a>
                        </p>
                    </h3>
                    <br>
                    <p style="display: inline-block;">@lang('admin.pages.common.label.search_results', ['count' => $count])</p>
                </div>
                <div class="col-sm-6 wrap-top-pagination">
                    <div class="heading-page-pagination">
                        {!! \PaginationHelper::render($paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit'], $count, $paginate['baseUrl'], [], $count, 'shared.topPagination') !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body" style=" overflow-x: scroll; ">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 10px">{!! \PaginationHelper::sort('id', 'ID') !!}</th>
                    <th style="width: 10px">{!! \PaginationHelper::sort('locale', trans('admin.pages.admin-user-notifications.columns.locale')) !!}</th>
                    <th style="width: 10px">{!! \PaginationHelper::sort('read', trans('admin.pages.admin-user-notifications.columns.read')) !!}</th>
                    <th>{!! \PaginationHelper::sort('category_type', trans('admin.pages.admin-user-notifications.columns.category_type')) !!}</th>
                    <th>{!! \PaginationHelper::sort('type', trans('admin.pages.admin-user-notifications.columns.type')) !!}</th>
                    <th>{!! \PaginationHelper::sort('sent_at', trans('admin.pages.admin-user-notifications.columns.sent_at')) !!}</th>

                    <th style="width: 40px">@lang('admin.pages.common.label.actions')</th>
                </tr>
                @foreach( $notifications as $notify )
                    <tr>
                        <td>{{ $notify->id }}</td>
                        <td>{{ $notify->locale }}</td>
                        <td>
                            @if( $notify->read )
                                <span class="badge bg-green">@lang('admin.pages.admin-user-notifications.columns.read_true')</span>
                            @else
                                <span class="badge bg-yellow">@lang('admin.pages.admin-user-notifications.columns.read_false')</span>
                            @endif
                        </td>
                        <td>{{ $notify->category_type }}</td>
                        <td>{{ $notify->type }}</td>
                        <td>{{ $notify->sent_at }}</td>
                        <td>
                            <a href="{!! URL::action('Admin\AdminUserNotificationController@edit', $notify->id) !!}"
                               class="btn btn-block btn-primary btn-xs">@lang('admin.pages.common.buttons.edit')</a>
                            <a href="#" class="btn btn-block btn-danger btn-xs delete-button"
                               data-delete-url="{!! action('Admin\AdminUserNotificationController@destroy', $notify->id) !!}">@lang('admin.pages.common.buttons.delete')</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="box-footer">
            {!! \PaginationHelper::render($paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit'], $count, $paginate['baseUrl'], []) !!}
        </div>
    </div>
@stop