@extends('pages.admin.' . config('view.admin') . '.layout.application', ['menu' => 'oauth_clients'] )

@section('metadata')
@stop

@section('styles')
    <style>
        #oauth-clients-index tr td {
            text-align: center;
        }
    </style>
@stop

@section('scripts')
    <script src="{!! \URLHelper::asset('js/delete_item.js', 'admin/adminlte') !!}"></script>
@stop

@section('title')
@stop

@section('header')
    OauthClients
@stop

@section('breadcrumb')
    <li class="breadcrumb-item active">OauthClients</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">

            <div class="row">
                <div class="col-sm-6">
                    <h3 class="box-title">
                        <p class="text-right">
                            <a href="{!! action('Admin\OauthClientController@create') !!}" class="btn btn-block btn-primary btn-sm" style="width: 125px;">@lang('admin.pages.common.buttons.create')</a>
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
            <table class="table table-bordered" id="oauth-clients-index">
                <tr>
                    <th style="width: 10px">{!! \PaginationHelper::sort('id', 'ID') !!}</th>
                    <th>{!! \PaginationHelper::sort('name', trans('admin.pages.oauth-clients.columns.name')) !!}</th>
                    <th>{!! \PaginationHelper::sort('secret', trans('admin.pages.oauth-clients.columns.secret')) !!}</th>
                    <th>{!! \PaginationHelper::sort('personal_access_client', trans('admin.pages.oauth-clients.columns.personal_access_client')) !!}</th>
                    <th>{!! \PaginationHelper::sort('password_client', trans('admin.pages.oauth-clients.columns.password_client')) !!}</th>
                    <th>{!! \PaginationHelper::sort('revoked', trans('admin.pages.oauth-clients.columns.revoked')) !!}</th>

                    <th style="width: 40px">@lang('admin.pages.common.label.actions')</th>
                </tr>
                @foreach( $oauthClients as $oauthClient )
                    <tr>
                        <td>{{ $oauthClient->id }}</td>
                        <td>{{ $oauthClient->name }}</td>
                        <td>{{ $oauthClient->secret }}</td>

                        <td>
                            @if( $oauthClient->personal_access_client )
                                <span class="badge bg-green">Yes</span>
                            @else
                                <span class="badge bg-red">No</span>
                            @endif
                        </td>
                        <td>
                            @if( $oauthClient->password_client )
                                <span class="badge bg-green">Yes</span>
                            @else
                                <span class="badge bg-red">No</span>
                            @endif
                        </td>
                        <td>
                            @if( $oauthClient->revoked )
                                <span class="badge bg-green">Yes</span>
                            @else
                                <span class="badge bg-red">No</span>
                            @endif
                        </td>
                        <td>
                            <a href="{!! action('Admin\OauthClientController@show', $oauthClient->id) !!}"
                               class="btn btn-block btn-primary btn-xs">@lang('admin.pages.common.buttons.edit')</a>
                            <a href="#" class="btn btn-block btn-danger btn-xs delete-button"
                               data-delete-url="{!! action('Admin\OauthClientController@destroy', $oauthClient->id) !!}">@lang('admin.pages.common.buttons.delete')</a>
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