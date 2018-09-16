namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\{{$controllerName}}RepositoryInterface;
use App\Http\Requests\Admin\{{$requestName}}Request;
use App\Http\Requests\PaginationRequest;

class {{$controllerName}}Controller extends Controller
{
    /** @var \App\Repositories\{{$controllerName}}RepositoryInterface */
    protected ${{$reposName}}Repository;

    public function __construct(
        {{$controllerName}}RepositoryInterface ${{$reposName}}Repository
    ) {
        $this->{{$reposName}}Repository = ${{$reposName}}Repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\PaginationRequest $request
     * @return \Response
     */
    public function index(PaginationRequest $request)
    {
        $paginate['limit']      = $request->limit();
        $paginate['offset']     = $request->offset();
        $paginate['order']      = $request->order();
        $paginate['direction']  = $request->direction();
        $paginate['baseUrl']    = action('Admin\{{$controllerName}}Controller@index');

        $filter = [];
        $keyword = $request->get('keyword');
        if (!empty($keyword)) {
            $filter['query'] = $keyword;
        }

        $count = $this->{{$reposName}}Repository->countByFilter($filter);
        ${{str_plural($objectName)}} = $this->{{$reposName}}Repository->getByFilter($filter, $paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']);

        return view(
            'pages.admin.' . config('view.admin') . '.{{$viewFolder}}.index',
            [
                '{{str_plural($objectName)}}'    => ${{str_plural($objectName)}},
                'count'         => $count,
                'paginate'      => $paginate,
                'keyword'       => $keyword
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Response
     */
    public function create()
    {
        return view(
            'pages.admin.' . config('view.admin') . '.{{$viewFolder}}.edit',
            [
                'isNew'     => true,
                '{{$objectName}}' => $this->{{$reposName}}Repository->getBlankModel(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Response
     */
    public function store({{$requestName}}Request $request)
    {
        $input = $request->only(
            [
            @foreach($columnNames as $columnName)
                '{{$columnName}}',
            @endforeach
            ]
        );

        $input['is_enabled'] = $request->get('is_enabled', 0);
        ${{$objectName}} = $this->{{$reposName}}Repository->create($input);

        if( empty(${{$objectName}}) ) {
            return redirect()->back()->with('message-error', trans('admin.errors.general.save_failed'));
        }

        return redirect()->action('Admin\{{$controllerName}}Controller@index')
            ->with('message-success', trans('admin.messages.general.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Response
     */
    public function show($id)
    {
        ${{$objectName}} = $this->{{$reposName}}Repository->find($id);
        if( empty(${{$objectName}}) ) {
            abort(404);
        }

        return view(
            'pages.admin.' . config('view.admin') . '.{{$viewFolder}}.edit',
            [
                'isNew' => false,
                '{{$objectName}}' => ${{$objectName}},
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param      $request
     * @return \Response
     */
    public function update($id, {{$requestName}}Request $request)
    {
        /** @var \App\Models\{{$modelName}} ${{$objectName}} */
        ${{$objectName}} = $this->{{$reposName}}Repository->find($id);
        if( empty(${{$objectName}}) ) {
            abort(404);
        }

        $input = $request->only(
            [
            @foreach($columnNames as $columnName)
                '{{$columnName}}',
            @endforeach
            ]
        );

        $input['is_enabled'] = $request->get('is_enabled', 0);
        $this->{{$reposName}}Repository->update(${{$objectName}}, $input);

        return redirect()->action('Admin\{{$controllerName}}Controller@show', [$id])
                    ->with('message-success', trans('admin.messages.general.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Response
     */
    public function destroy($id)
    {
        /** @var \App\Models\{{$modelName}} ${{$objectName}} */
        ${{$objectName}} = $this->{{$reposName}}Repository->find($id);
        if( empty(${{$objectName}}) ) {
            abort(404);
        }
        $this->{{$reposName}}Repository->delete(${{$objectName}});

        return redirect()->action('Admin\{{$controllerName}}Controller@index')
                    ->with('message-success', trans('admin.messages.general.delete_success'));
    }

}
