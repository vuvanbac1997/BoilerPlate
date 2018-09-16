＠extends('pages.admin.' . config('view.admin') . '.layout.application', ['menu' => '{{$viewFolder}}'] )

＠section('metadata')
＠stop

＠section('styles')
    <link rel="stylesheet" href="｛!! \URLHelper::asset('libs/datetimepicker/css/bootstrap-datetimepicker.min.css', 'admin') !!}">
＠stop

＠section('scripts')
    <script src="｛{ \URLHelper::asset('libs/moment/moment.min.js', 'admin') }}"></script>
    <script src="｛{ \URLHelper::asset('libs/datetimepicker/js/bootstrap-datetimepicker.min.js', 'admin') }}"></script>
    <script>
        $('.datetime-field').datetimepicker({'format': 'YYYY-MM-DD HH:mm:ss', 'defaultDate': new Date()});

        $(document).ready(function () {
            $('#cover-image').change(function (event) {
                $('#cover-image-preview').attr('src', URL.createObjectURL(event.target.files[0]));
            });
        });
    </script>
＠stop

＠section('title')
    {{$modelName}} | Admin | ｛{ config('site.name') }}
＠stop

＠section('header')
    {{$modelName}}
＠stop

＠section('breadcrumb')
    <li class="breadcrumb-item"><a href="｛!! action('Admin\{{$modelName}}Controller＠index') !!}"><i class="fa fa-files-o"></i> {{$modelName}}</a></li>
    ＠if( $isNew )
        <li class="breadcrumb-item">New</li>
    ＠else
        <li class="breadcrumb-item active">｛{ ${{$objectName}}->id }}</li>
    ＠endif
＠stop

＠section('content')
    ＠if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <ul>
                ＠foreach ($errors->all() as $error)
                    <li>｛{ $error }}</li>
                ＠endforeach
            </ul>
        </div>
    ＠endif

    <form action="＠if($isNew) ｛!! action('Admin\{{$modelName}}Controller＠store') !!} ＠else ｛!! action('Admin\{{$modelName}}Controller＠update', [${{$objectName}}->id]) !!} ＠endif" method="POST" enctype="multipart/form-data">
        ＠if( !$isNew ) <input type="hidden" name="_method" value="PUT"> ＠endif
        ｛!! csrf_field() !!}

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <a href="｛!! URL::action('Admin\{{$modelName}}Controller＠index') !!}" class="btn btn-block btn-secondary btn-sm" style="width: 125px;">
                        ＠lang('admin.pages.common.buttons.back')
                    </a>
                </h3>
            </div>

            <div class="box-body">
                @foreach($columns as $column)
                    @if( ($column['name'] == 'id') )
                        @continue;
                    @elseif( ends_with($column['name'], 'image_id') )
                        @php $relation = substr(camel_case('cover_image_id'), 0, strlen(camel_case('cover_image_id')) - 2); @endphp
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group text-center" style="max-width: 500px;">
                                    ＠if( !empty(${{$objectName}}->present()->{{$relation}}()) )
                                    <img id="cover-image-preview" style="max-width: 100%;" src="｛!! ${{$objectName}}->present()->{{$relation}}()->present()->url !!}" alt="" class="margin"/>
                                    ＠else
                                    <img id="cover-image-preview" style="max-width: 100%;" src="｛!! \URLHelper::asset('img/no_image.jpg', 'common') !!}" alt="" class="margin"/>
                                    ＠endif
                                    <input type="file" style="display: none;" id="cover-image" name="cover-image">
                                    <p class="help-block" style="font-weight: bolder; display: block; width: 100%; text-align: center;">
                                        ＠lang('admin.pages.{{$viewFolder}}.columns.{{$column['name']}}')
                                        <label for="cover-image" style="font-weight: 100; color: #549cca; margin-left: 10px; cursor: pointer;">＠lang('admin.pages.common.buttons.edit')</label>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @elseif( $column['type'] == 'StringType' )
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="{{$column['name']}}">＠lang('admin.pages.{{$viewFolder}}.columns.{{$column['name']}}')</label>
                                    <input type="text" class="form-control m-input" name="{{$column['name']}}" id="{{$column['name']}}" required placeholder="＠lang('admin.pages.{{$viewFolder}}.columns.{{$column['name']}}')" value="｛{ old('{{$column['name']}}') ? old('{{$column['name']}}') : ${{$objectName}}->{{$column['name']}} }}">
                                </div>
                            </div>
                        </div>
                    @elseif( ($column['type'] == 'IntegerType') || ($column['type'] == 'BigIntType') )
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="{{$column['name']}}">＠lang('admin.pages.{{$viewFolder}}.columns.{{$column['name']}}')</label>
                                    <input type="number" min="0" class="form-control m-input" name="{{$column['name']}}" id="{{$column['name']}}" required placeholder="＠lang('admin.pages.{{$viewFolder}}.columns.{{$column['name']}}')" value="｛{ old('{{$column['name']}}') ? old('{{$column['name']}}') : ${{$objectName}}->{{$column['name']}} }}">
                                </div>
                            </div>
                        </div>
                    @elseif( $column['type'] == 'BooleanType' )
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="{{$column['name']}}" class="label-switch">＠lang('admin.pages.{{$viewFolder}}.columns.{{$column['name']}}')</label>
                                    <div class="switch">
                                        <input id="{{$column['name']}}" name="{{$column['name']}}" value="1" ＠if( ${{$objectName}}->{{$column['name']}}) checked ＠endif class="cmn-toggle cmn-toggle-round-flat" type="checkbox">
                                        <label for="{{$column['name']}}"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif( $column['type'] == 'TextType' )
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="{{$column['name']}}">＠lang('admin.pages.{{$viewFolder}}.columns.{{$column['name']}}')</label>
                                    <textarea name="{{$column['name']}}" id="{{$column['name']}}" class="form-control m-input" rows="3" placeholder="＠lang('admin.pages.{{$viewFolder}}.columns.{{$column['name']}}')">｛{ old('{{$column['name']}}') ? old('{{$column['name']}}') : ${{$objectName}}->{{$column['name']}} }}</textarea>
                                </div>
                            </div>
                        </div>
                    @elseif( $column['type'] == 'DateTimeType' )
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <label for="{{$column['name']}}" class="label-datetimepicker">＠lang('admin.pages.{{$viewFolder}}.columns.{{$column['name']}}')</label>
                                    <div class="input-group date datetime-field" style="margin-bottom: 10px;">
                                        <input type="text" class="form-control" style="margin: 0;" name="{{$column['name']}}" id="{{$column['name']}}" value="｛{ old('{{$column['name']}}') ? old('{{$column['name']}}') : ${{$objectName}}->{{$column['name']}} }}">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm" style="width: 125px;">＠lang('admin.pages.common.buttons.save')</button>
            </div>
        </div>
    </form>
＠stop
