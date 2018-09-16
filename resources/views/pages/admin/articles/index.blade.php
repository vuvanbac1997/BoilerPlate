@extends('pages.admin.' . config('view.admin') . '.layout.application', ['menu' => 'articles'] )

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
    Articles
@stop

@section('breadcrumb')
    <li class="breadcrumb-item active">Articles</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">

            <div class="row">
                <div class="col-sm-6">
                    <h3 class="box-title">
                        <p class="text-right">
                            <a href="{!! URL::action('Admin\ArticleController@create') !!}"
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
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width: 10px">{!! \PaginationHelper::sort('id', 'ID') !!}</th>
                        <th>{!! \PaginationHelper::sort('title', trans('admin.pages.articles.columns.title')) !!}</th>
                        <th>{!! \PaginationHelper::sort('slug', trans('admin.pages.articles.columns.slug')) !!}</th>
                        <th>{!! \PaginationHelper::sort('publish_started_at', trans('admin.pages.articles.columns.publish_started_at')) !!}</th>
                        <th>{!! \PaginationHelper::sort('publish_ended_at', trans('admin.pages.articles.columns.publish_ended_at')) !!}</th>

                        <th style="width: 40px">{!! \PaginationHelper::sort('is_enabled', trans('admin.pages.common.label.is_enabled')) !!}</th>
                        <th style="width: 40px">@lang('admin.pages.common.label.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $articles as $article )
                        <tr>
                            <td>{{ $article->id }}</td>
                            <td>{{ $article->title }}</td>
                            <td>{{ $article->slug }}</td>
                            <td>{{ $article->publish_started_at }}</td>
                            <td>{{ $article->publish_ended_at }}</td>

                            <td>
                                @if( $article->is_enabled )
                                    <span class="badge bg-green">@lang('admin.pages.common.label.is_enabled_true')</span>
                                @else
                                    <span class="badge bg-red">@lang('admin.pages.common.label.is_enabled_false')</span>
                                @endif
                            </td>
                            <td>
                                <a href="{!! URL::action('Admin\ArticleController@show', $article->id) !!}"
                                   class="btn btn-block btn-primary btn-xs">@lang('admin.pages.common.buttons.edit')</a>
                                <a href="#" class="btn btn-block btn-danger btn-xs delete-button"
                                   data-delete-url="{!! action('Admin\ArticleController@destroy', $article->id) !!}">@lang('admin.pages.common.buttons.delete')</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
        <div class="box-footer">
            {!! \PaginationHelper::render($paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit'], $count, $paginate['baseUrl'], []) !!}
        </div>
    </div>
@stop