＠extends('pages.admin.' . config('view.admin') . '.layout.application', ['menu' => '{{$viewFolder}}'] )

＠section('metadata')
＠stop

＠section('styles')
＠stop

＠section('scripts')
    <script src="｛!! \URLHelper::asset('js/delete_item.js', 'admin/adminlte') !!}"></script>
＠stop

＠section('title')
＠stop

＠section('header')
    {{$modelName}} | Admin | ｛{ config('site.name') }}
＠stop

＠section('breadcrumb')
    <li class="breadcrumb-item active">{{$modelName}}</li>
＠stop

＠section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="box-title">
                        <p class="text-right">
                            <a href="｛!! URL::action('Admin\{{$modelName}}Controller＠create') !!}" class="btn btn-block btn-primary btn-sm" style="width: 125px;">＠lang('admin.pages.common.buttons.create')</a>
                        </p>
                    </h3>
                    <br>
                    <p style="display: inline-block;">＠lang('admin.pages.common.label.search_results', ['count' => $count])</p>
                </div>

                <div class="col-md-6 wrap-top-pagination">
                    <div class="heading-page-pagination">
                        ｛!! \PaginationHelper::render($paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit'], $count, $paginate['baseUrl'], ['keyword' => $keyword], $count, 'shared.topPagination') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="box-body" style=" overflow-x: scroll; ">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 10px">｛!! \PaginationHelper::sort('id', 'ID') !!}</th>
                            @foreach($columns as $column)
                                @if( ($column['name'] == 'id') || (ends_with($column['name'], '_id')) || ($column['type'] == 'TextType') )
                                    @continue;
                                @endif
                                <th>｛!! \PaginationHelper::sort('{{$column['name']}}', trans('admin.pages.{{$viewFolder}}.columns.{{$column['name']}}')) !!}</th>
                            @endforeach

                            <th style="width: 40px">＠lang('admin.pages.common.label.actions')</th>
                        </tr>
                    </thead>

                    <tbody>
                    ＠foreach( ${{str_plural($objectName)}} as ${{$objectName}} )
                        <tr>
                            <td>｛{ ${{$objectName}}->id }}</td>
                            @foreach($columns as $column)
                                @if( ($column['name'] == 'id') || (ends_with($column['name'], '_id')) || ($column['type'] == 'TextType') )
                                    @continue;
                                @endif
                                <td>｛{ ${{$objectName}}->{{$column['name']}} }}</td>
                            @endforeach
                            <td>
                                <a href="｛!! URL::action('Admin\{{$modelName}}Controller@show', ${{$objectName}}->id) !!}" class="btn btn-block btn-primary btn-xs">＠lang('admin.pages.common.buttons.edit')</a>
                                <a href="#" class="btn btn-block btn-danger btn-xs delete-button" data-delete-url="｛!! action('Admin\{{$modelName}}Controller@destroy', ${{$objectName}}->id) !!}">＠lang('admin.pages.common.buttons.delete')</a>
                            </td>
                        </tr>
                    ＠endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box-footer">
            ｛!! \PaginationHelper::render($paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit'], $count, $paginate['baseUrl'], ['keyword' => $keyword]) !!}
        </div>
    </div>
＠stop