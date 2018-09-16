<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\TestRepositoryInterface;
use App\Http\Requests\Admin\TestRequest;
use App\Http\Requests\PaginationRequest;

class TestController extends Controller
{
    /** @var  \App\Repositories\TestRepositoryInterface */
    protected $testRepository;

    public function __construct(
        TestRepositoryInterface $testRepository
    ) {
        $this->testRepository = $testRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param    \App\Http\Requests\PaginationRequest $request
     * @return  \Response
     */
    public function index(PaginationRequest $request)
    {
        $paginate['limit']      = $request->limit();
        $paginate['offset']     = $request->offset();
        $paginate['order']      = $request->order();
        $paginate['direction']  = $request->direction();
        $paginate['baseUrl']    = action('Admin\TestController@index');

        $filter = [];
        $keyword = $request->get('keyword');
        if (!empty($keyword)) {
            $filter['query'] = $keyword;
        }

        $count = $this->testRepository->countByFilter($filter);
        $tests = $this->testRepository->getByFilter($filter, $paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']);

        return view(
            'pages.admin.' . config('view.admin') . '.tests.index',
            [
                'tests'    => $tests,
                'count'         => $count,
                'paginate'      => $paginate,
                'keyword'       => $keyword
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Response
     */
    public function create()
    {
        return view(
            'pages.admin.' . config('view.admin') . '.tests.edit',
            [
                'isNew'     => true,
                'test' => $this->testRepository->getBlankModel(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    $request
     * @return  \Response
     */
    public function store(TestRequest $request)
    {
        $input = $request->only(
            [
                            'title',
                            'content',
                        ]
        );

        $input['is_enabled'] = $request->get('is_enabled', 0);
        $test = $this->testRepository->create($input);

        if( empty($test) ) {
            return redirect()->back()->with('message-error', trans('admin.errors.general.save_failed'));
        }

        return redirect()->action('Admin\TestController@index')
            ->with('message-success', trans('admin.messages.general.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param    int $id
     * @return  \Response
     */
    public function show($id)
    {
        $test = $this->testRepository->find($id);
        if( empty($test) ) {
            abort(404);
        }

        return view(
            'pages.admin.' . config('view.admin') . '.tests.edit',
            [
                'isNew' => false,
                'test' => $test,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    int $id
     * @return  \Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    int $id
     * @param            $request
     * @return  \Response
     */
    public function update($id, TestRequest $request)
    {
        /** @var  \App\Models\Test $test */
        $test = $this->testRepository->find($id);
        if( empty($test) ) {
            abort(404);
        }

        $input = $request->only(
            [
                            'title',
                            'content',
                        ]
        );

        $input['is_enabled'] = $request->get('is_enabled', 0);
        $this->testRepository->update($test, $input);

        return redirect()->action('Admin\TestController@show', [$id])
                    ->with('message-success', trans('admin.messages.general.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    int $id
     * @return  \Response
     */
    public function destroy($id)
    {
        /** @var  \App\Models\Test $test */
        $test = $this->testRepository->find($id);
        if( empty($test) ) {
            abort(404);
        }
        $this->testRepository->delete($test);

        return redirect()->action('Admin\TestController@index')
                    ->with('message-success', trans('admin.messages.general.delete_success'));
    }

}
