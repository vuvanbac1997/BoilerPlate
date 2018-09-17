<?php

namespace App\Console\Commands\Generators;

use function ICanBoogie\pluralize;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class AdminCRUDMakeCommand extends GeneratorCommandBase
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:admin-crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Admin CRUD related files';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'AdminCRUD';

    protected function generate($modelName)
    {
        $modelName = $this->getModelName($modelName);

        if (!$this->generateController($modelName)) {
            return false;
        }

        if (!$this->generateRequest($modelName)) {
            return false;
        }

        if (!$this->generateViews($modelName)) {
            return false;
        }

        if (!$this->generateUnitTest($modelName)) {
        }

        if (!$this->addItemToSubMenu($modelName)) {
        }
        $this->generateLanguageFile($modelName);

        return $this->addRoute($modelName);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function generateController($controllerName)
    {
        $path = $this->getControllerPath($controllerName);
        if ($this->alreadyExists($controllerName)) {
            $this->error($path.' already exists.');

            return false;
        }

        view()->addLocation(implode(DIRECTORY_SEPARATOR, [__DIR__, 'stubs']));

        $fileContent = view(
            'crud.admin.controller',
            [
                'controllerName' => $controllerName,
                'modelName'      => $controllerName,
                'reposName'      => strtolower(substr($controllerName, 0, 1)).substr($controllerName, 1),
                'viewFolder'     => snake_case(str_plural($controllerName), '-'),
                'objectName'     => strtolower(substr($controllerName, 0, 1)).substr($controllerName, 1),
                'requestName'    => $controllerName,
                'columnNames'    => $this->getColumns($controllerName),
            ]
        )->render();
        $fileContent = '<?php' . PHP_EOL . $fileContent;
        
        $this->makeDirectory($path);
        $this->files->put($path, $fileContent);

        return true;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getModelName($name)
    {
        if (preg_match('/([A-Za-z0-9]+)(Controller)?$/', $name, $matches)) {
            $name = $matches[1];
        }

        return ucwords($name);
    }

    protected function getColumns($name)
    {
        $modelFullName = '\\App\\Models\\'.$name;
        /** @var \App\Models\Base $model */
        $model = new $modelFullName();

        return $model->getFillableColumns();
    }

    /**
     * @param string $stub
     * @param string $modelName
     */
    protected function replaceTemplateVariables(&$stub, $modelName)
    {
        $this->replaceTemplateVariable($stub, 'CLASS', $modelName);
        $this->replaceTemplateVariable($stub, 'CLASSES', \StringHelper::pluralize($modelName));
        $this->replaceTemplateVariable($stub, 'class', strtolower(substr($modelName, 0, 1)).substr($modelName, 1));
        $this->replaceTemplateVariable($stub, 'classes', \StringHelper::pluralize(strtolower($modelName)));
        $this->replaceTemplateVariable($stub, 'classes-spinal',
            \StringHelper::camel2Spinal(\StringHelper::pluralize($modelName)));
        $this->replaceTemplateVariable($stub, 'classes-snake',
            \StringHelper::camel2Snake(\StringHelper::pluralize($modelName)));

        $columns = $this->getColumnNamesAndTypes($modelName);
        $columnNames = $this->getColumns($modelName);
        $params = [];
        $updates = '';
        foreach ($columns as $column) {
            if (!in_array($column['name'], $columnNames)) {
                continue;
            }
            if ($column['name'] == 'id' || $column['name'] == 'is_enabled') {
                continue;
            }
            if (\StringHelper::endsWith($column['name'], '_id')) {
                continue;
            }
            switch ($column['type']) {
                case 'BooleanType':
                    $updates .= '        $input[\''.$column['name'].'\'] = $request->get(\''.$column['name'].'\', 0);'.PHP_EOL;
                    break;
                case 'DateTimeType':
                case 'TextType':
                case 'StringType':
                case 'IntegerType':
                default:
                    $params[] = $column['name'];
            }
        }

        $list = implode(',', array_map(function ($name) {
            return "'".$name."'";
        }, $params));

        $this->replaceTemplateVariable($stub, 'COLUMNS', $list);
        $this->replaceTemplateVariable($stub, 'COLUMNUPDATES', $updates);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getControllerPath($name)
    {
        return $this->laravel['path'].'/Http/Controllers/Admin/'.$name.'Controller.php';
    }

    /**
     * @return string
     */
    protected function getStubForController()
    {
        return __DIR__.'/stubs/admin-crud-controller.stub';
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function generateRequest($name)
    {
        $path = $this->getRequestPath($name);
        if ($this->alreadyExists($path)) {
            $this->error($path.' already exists.');

            return false;
        }

        $this->makeDirectory($path);

        $stub = $this->files->get($this->getStubForRequest());
        $this->replaceTemplateVariables($stub, $name);
        $this->files->put($path, $stub);

        return true;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getRequestPath($name)
    {
        return $this->laravel['path'].'/Http/Requests/Admin/'.$name.'Request.php';
    }

    /**
     * @return string
     */
    protected function getStubForRequest()
    {
        return __DIR__.'/stubs/admin-crud-request.stub';
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function generateViews($modelName)
    {
        view()->addLocation(implode(DIRECTORY_SEPARATOR, [__DIR__, 'stubs']));
        $columns = $this->getColumnNamesAndTypes($modelName);

        foreach (['index', 'edit'] as $type) {
            $path = $this->getViewPath($modelName, $type);
            if ($this->alreadyExists($path)) {
                $this->error($path.' already exists.');

                return false;
            }
            $this->makeDirectory($path);

            if ($type == 'index') {
                $fileContent = view(
//                    'crud.admin.views.' . config('view.admin') . '.index',
                    'crud.admin.views.' . 'index',
                    [
                        'modelName'  => $modelName,
                        'objectName' => strtolower(substr($modelName, 0, 1)).substr($modelName, 1),
                        'viewFolder' => snake_case(str_plural($modelName), '-'),
                        'columns'    => $columns,
                    ]
                )->render();
                $fileContent = str_replace('＠', '@', $fileContent);
                $fileContent = str_replace('｛', '{', $fileContent);
                $fileContent = str_replace('｝', '}', $fileContent);

                $this->files->put($path, $fileContent);
            } elseif ($type == 'edit') {
                $fileContent = view(
//                    'crud.admin.views.' . config('view.admin') . '.edit',
                    'crud.admin.views.' . 'edit',
                    [
                        'modelName'  => $modelName,
                        'objectName' => strtolower(substr($modelName, 0, 1)).substr($modelName, 1),
                        'viewFolder' => snake_case(str_plural($modelName), '-'),
                        'columns'    => $columns,
                    ]
                )->render();
                $fileContent = str_replace('＠', '@', $fileContent);
                $fileContent = str_replace('｛', '{', $fileContent);
                $fileContent = str_replace('｝', '}', $fileContent);

                $this->files->put($path, $fileContent);
            }
        }

        return true;
    }

    protected function addItemToSubMenu($name)
    {
        $sideMenu = $this->files->get($this->getSideBarViewPath());

        $theme = config('view.admin');
        if( $theme == 'metronic' ) {
            $value = '<li class="m-menu__item @if( $menu==\'' . snake_case(pluralize($name)) . '\') m-menu__item--active @endif" aria-haspopup="true">
                <a href="{!! \URL::action(\'Admin\\' . $name . 'Controller@index\') !!}" class="m-menu__link">
                    <i class="m-menu__link-icon la la-sticky-note"></i>
                    <span class="m-menu__link-title">
                        <span class="m-menu__link-wrap">
                            <span class="m-menu__link-text">
                                @lang(\'admin.menu.' . snake_case(pluralize($name)) . '\')
                            </span>
                        </span>
                    </span>
                </a>
            </li>' . PHP_EOL . PHP_EOL .'            <!-- %%SIDEMENU%% -->';
        } else{
            $value = '<li class="nav-item" @if( $menu==\'' . snake_case(pluralize($name)) . '\') class="active" @endif ><a class="nav-link" href="{!! \URL::action(\'Admin\\'.$name.'Controller@index\') !!}"><i class="fa fa-users"></i> <span> @lang(\'admin.menu.' . snake_case(pluralize($name)) . '\') </span></a></li>'.PHP_EOL.'            <!-- %%SIDEMENU%% -->';
        }

        $sideMenu = str_replace('<!-- %%SIDEMENU%% -->', $value, $sideMenu);
        $this->files->put($this->getSideBarViewPath(), $sideMenu);
    }

    protected function getSideBarViewPath()
    {
        $theme = config('view.admin');
        if( $theme == 'metronic' ) {
            return $this->laravel['path'].'/../resources/views/pages/admin/metronic/layout/left_aside.blade.php';
        }

//        return $this->laravel['path'].'/../resources/views/pages/admin/adminlte/layout/left_navigation.blade.php';
        return $this->laravel['path'].'/../resources/views/pages/admin/layout/left_navigation.blade.php';
    }

    /**
     * @param string $name
     * @param string $type
     *
     * @return string
     */
    protected function getViewPath($name, $type)
    {
        $directoryName = \StringHelper::camel2Spinal(\StringHelper::pluralize($name));

        //return $this->laravel['path'].'/../resources/views/pages/admin/' . config('view.admin') . '/'.$directoryName.'/'.$type.'.blade.php';
        return $this->laravel['path'].'/../resources/views/pages/admin/'  . '/'.$directoryName.'/'.$type.'.blade.php';
    }

    /**
     * @param  string
     *
     * @return string
     */
    protected function getStubForView($type)
    {
        return __DIR__.'/stubs/admin-crud-view-'.$type.'.stub';
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function addRoute($name)
    {
        $directoryName = \StringHelper::camel2Spinal(\StringHelper::pluralize($name));

        $routes = $this->files->get($this->getRoutesPath());
        $key = '/* NEW ADMIN RESOURCE ROUTE */';
        $route = '\\Route::resource(\''.$directoryName.'\', \'Admin\\'.$name.'Controller\');'.PHP_EOL.'        '.$key;
        $routes = str_replace($key, $route, $routes);
        $this->files->put($this->getRoutesPath(), $routes);

        return true;
    }

    protected function getRoutesPath()
    {
        return $this->laravel['path'].'/../routes/admin.php';
    }

    protected function generateLanguageFile( $name ) {
        $directoryName = \StringHelper::camel2Spinal( \StringHelper::pluralize( $name ) );

        $languages = $this->files->get( $this->getLanguageFilePath() );
        $languages1 = $this->files->get( $this->getLanguageFilePath() );
        $key = '/* NEW PAGE STRINGS */';

        $key1 ='/* NEW MENU STRINGS */'; //Generate menu item
        $data1 =  "'".$directoryName."'     =>  '". ucwords($directoryName)."'";

        $columns = $this->getColumns($name);
        $data = "'".$directoryName."'   => [".PHP_EOL."            'columns'  => [".PHP_EOL;
        foreach ($columns as $column) {
            $data .= "                '".$column."' => '" . ucfirst($column) . "',".PHP_EOL;
        }
        $data .= '            ],'.PHP_EOL.'        ],'.PHP_EOL.'        '.$key;

        $languages = str_replace( $key, $data, $languages );
        $languages1 = str_replace( $key1, $data1, $languages1);

        $this->files->put( $this->getLanguageFilePath(), $languages );
        $this->files->put( $this->getLanguageFilePath(), $languages1 );
        return true;
    }

    protected function generateForm($name)
    {
        $columns = $this->getColumnNamesAndTypes($name);
        $result = '';
        foreach ($columns as $column) {
            if ($column['name'] == 'id' || $column['name'] == 'is_enabled') {
                continue;
            }

            if (\StringHelper::endsWith($column['name'], 'image_id')) {
                $fieldName = substr($column['name'], 0, strlen($column['name']) - 3);
                $relationName = lcfirst(\StringHelper::snake2Camel($fieldName));
                $idName = \StringHelper::camel2Spinal($relationName);

                $template = '                    <div class="row">'
                    .PHP_EOL.'                        <div class="col-md-12">'
                    .PHP_EOL.'                            <div class="form-group text-center">'
                    .PHP_EOL.'                                @if( !empty($%%class%%->%%relation%%) )'
                    .PHP_EOL.'                                    <img id="%%id%%-preview"  style="max-width: 500px; width: 100%;" src="{!! $%%class%%->present()->%%relation%%->present()->url !!}" alt="" class="margin" />'
                    .PHP_EOL.'                                @else'
                    .PHP_EOL.'                                    <img id="%%id%%-preview" style="max-width: 500px; width: 100%;" src="{!! \URLHelper::asset(\'img/no_image.jpg\', \'common\') !!}" alt="" class="margin" />'
                    .PHP_EOL.'                                @endif'
                    .PHP_EOL.'                                <input type="file" style="display: none;"  id="%%id%%" name="%%field%%">'
                    .PHP_EOL.'                                <p class="help-block" style="font-weight: bolder;">'
                    .PHP_EOL.'                                    @lang(\'admin.pages.%%classes-spinal%%.columns.%%column%%\')'
                    .PHP_EOL.'                                    <label for="%%id%%" style="font-weight: 100; color: #549cca; margin-left: 10px; cursor: pointer;">@lang(\'admin.pages.common.buttons.edit\')</label>'
                    .PHP_EOL.'                                </p>'
                    .PHP_EOL.'                            </div>'
                    .PHP_EOL.'                        </div>'
                    .PHP_EOL.'                    </div>';
                $this->replaceTemplateVariable($template, 'column', $column['name']);
                $this->replaceTemplateVariable($template, 'field', $fieldName);
                $this->replaceTemplateVariable($template, 'relation', $relationName);
                $this->replaceTemplateVariable($template, 'id', $idName);
                $result = $result.PHP_EOL.$template.PHP_EOL;
                continue;
            }

            if (\StringHelper::endsWith($column['name'], '_id')) {
                continue;
            }

            switch ($column['type']) {
                case 'TextType':
                    $template =  '                    <div class="row">'
                        .PHP_EOL.'                        <div class="col-md-12">'
                        .PHP_EOL.'                            <div class="form-group @if ($errors->has(\'%%column%%\')) has-error @endif">'
                        .PHP_EOL.'                                <label for="%%column%%">@lang(\'admin.pages.%%classes-spinal%%.columns.%%column%%\')</label>'
                        .PHP_EOL.'                                <textarea name="%%column%%" class="form-control" rows="5" required placeholder="@lang(\'admin.pages.%%classes-spinal%%.columns.%%column%%\')">{{ old(\'%%column%%\') ? old(\'%%column%%\') : $%%class%%->%%column%% }}</textarea>'
                        .PHP_EOL.'                            </div>'
                        .PHP_EOL.'                        </div>'
                        .PHP_EOL.'                    </div>';
                    $this->replaceTemplateVariable($template, 'column', $column['name']);
                    $this->replaceTemplateVariable($template, 'class', strtolower(substr($name, 0, 1)).substr($name, 1));
                    $this->replaceTemplateVariable($template, 'classes-spinal',
                   \StringHelper::camel2Spinal(\StringHelper::pluralize($name)));
                    $result = $result.PHP_EOL.$template.PHP_EOL;
                    break;
                case 'BooleanType':
                    $template =  '                    <div class="row">'
                        .PHP_EOL.'                        <div class="col-md-12">'
                        .PHP_EOL.'                            <div class="form-group">'
                        .PHP_EOL.'                                <div class="checkbox">'
                        .PHP_EOL.'                                    <label>'
                        .PHP_EOL.'                                        <input type="checkbox" name="%%column%%" required value="1"'
                        .PHP_EOL.'                                        @if( $%%class%%->%%column%%) checked @endif >'
                        .PHP_EOL.'                                        @lang(\'admin.pages.%%classes-spinal%%.columns.%%column%%\')'
                        .PHP_EOL.'                                   </label>'
                        .PHP_EOL.'                                </div>'
                        .PHP_EOL.'                            </div>'
                        .PHP_EOL.'                        </div>'
                        .PHP_EOL.'                    </div>';
                    $this->replaceTemplateVariable($template, 'column', $column['name']);
                    $this->replaceTemplateVariable($template, 'class', strtolower(substr($name, 0, 1)).substr($name, 1));
                    $result = $result.PHP_EOL.$template.PHP_EOL;
                    break;
                case 'DateTimeType':
                    $template =  '                    <div class="row">'
                        .PHP_EOL.'                        <div class="col-md-12">'
                        .PHP_EOL.'                            <div class="form-group">'
                        .PHP_EOL.'                                <label for="%%column%%">@lang(\'admin.pages.%%classes-spinal%%.columns.%%column%%\')</label>'
                        .PHP_EOL.'                                <div class="input-group date datetime-field">'
                        .PHP_EOL.'                                    <input id="datetimepicker" type="text" class="form-control" name="%%column%%" required'
                        .PHP_EOL.'                                         value="{{ old(\'%%column%%\') ? old(\'%%column%%\') : $%%class%%->%%column%% }}">'
                        .PHP_EOL.'                                    <div class="input-group-append">'
                        .PHP_EOL.'                                        <span class="input-group-text fa fa-calendar"></span>'
                        .PHP_EOL.'                                    </div>'
                        .PHP_EOL.'                                </div>'
                        .PHP_EOL.'                            </div>'
                        .PHP_EOL.'                        </div>'
                        .PHP_EOL.'                    </div>';
                    $this->replaceTemplateVariable($template, 'column', $column['name']);
                    $this->replaceTemplateVariable($template, 'class', strtolower(substr($name, 0, 1)).substr($name, 1));
                    $result = $result.PHP_EOL.$template.PHP_EOL;
                    break;
                case 'StringType':
                case 'IntegerType':
                default:
                    $template =  '                    <div class="row">'
                        .PHP_EOL.'                        <div class="col-md-12">'
                        .PHP_EOL.'                            <div class="form-group @if ($errors->has(\'%%column%%\')) has-error @endif">'
                        .PHP_EOL.'                                <label for="%%column%%">@lang(\'admin.pages.%%classes-spinal%%.columns.%%column%%\')</label>'
                        .PHP_EOL.'                                <input type="text" class="form-control" id="%%column%%" name="%%column%%" required value="{{ old(\'%%column%%\') ? old(\'%%column%%\') : $%%class%%->%%column%% }}">'
                        .PHP_EOL.'                            </div>'
                        .PHP_EOL.'                        </div>'
                        .PHP_EOL.'                    </div>';
                    $this->replaceTemplateVariable($template, 'column', $column['name']);
                    $this->replaceTemplateVariable($template, 'class', strtolower(substr($name, 0, 1)).substr($name, 1));
                    $this->replaceTemplateVariable($template, 'classes-spinal',
                   \StringHelper::camel2Spinal(\StringHelper::pluralize($name)));
                    $result = $result.PHP_EOL.$template.PHP_EOL;
            }
        }

        return $result;
    }

    protected function getLanguageFilePath()
    {
        return $this->laravel['path'].'/../resources/lang/gb/admin.php';
    }

    protected function generateUnitTest($name)
    {
        $path = $this->getUnitTestPath($name);
        if ($this->alreadyExists($path)) {
            $this->error($path.' already exists.');

            return false;
        }

        $this->makeDirectory($path);

        $stub = $this->files->get($this->getStubForUnitTest());
        $this->replaceTemplateVariables($stub, $name);
        $this->files->put($path, $stub);

        return true;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getUnitTestPath($name)
    {
        return $this->laravel['path'].'/../tests/Controllers/Admin/'.$name.'ControllerTest.php';
    }

    /**
     * @return string
     */
    protected function getStubForUnitTest()
    {
        return __DIR__.'/stubs/admin-crud-controller-unittest.stub';
    }

    protected function getColumnNamesAndTypes($name)
    {
        $columNames = $this->getColumns($name);
        $tableName = $this->getTableName($name);

        $hasDoctrine = interface_exists('Doctrine\DBAL\Driver');
        if (!$hasDoctrine) {
            return [];
        }
        $ret = [];
        $schema = \DB::getDoctrineSchemaManager();
        $columns = $schema->listTableColumns($tableName);
        if ($columns) {
            foreach ($columns as $column) {
                if ($column->getAutoincrement()) {
                    continue;
                }
                $columnName = $column->getName();
                $columnType = array_slice(explode('\\', get_class($column->getType())), -1)[0];

                if (in_array($columnName, $columNames)) {
                    $ret[] = [
                        'name' => $columnName,
                        'type' => $columnType,
                    ];
                }
            }
        }

        return $ret;
    }

    protected function getTableName($name)
    {
        return \StringHelper::pluralize(\StringHelper::camel2Snake($name));
    }
}
