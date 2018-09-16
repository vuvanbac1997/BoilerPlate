<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\OauthClientRepositoryInterface;
use App\Http\Requests\Admin\OauthClientRequest;
use App\Http\Requests\PaginationRequest;

class OauthClientController extends Controller
{

    /** @var \App\Repositories\OauthClientRepositoryInterface */
    protected $oauthClientRepository;

    public function __construct(
        OauthClientRepositoryInterface $oauthClientRepository
    )
    {
        $this->oauthClientRepository = $oauthClientRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\PaginationRequest $request
     * @return \Response
     */
    public function index(PaginationRequest $request)
    {
        $paginate['offset']     = $request->offset();
        $paginate['limit']      = $request->limit();
        $paginate['order']      = $request->order('id');
        $paginate['direction']  = $request->direction('asc');
        $paginate['baseUrl']    = action( 'Admin\OauthClientController@index' );

        $filter = [];
        $keyword = $request->get('keyword');
        if (!empty($keyword)) {
            $filter['query'] = $keyword;
        }

        $count = $this->oauthClientRepository->countByFilter($filter);
        $oauthClients = $this->oauthClientRepository->getByFilter($filter, $paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']);

        return view(
            'pages.admin.' . config('view.admin') . '.oauth-clients.index',
            [
                'oauthClients' => $oauthClients,
                'count'        => $count,
                'paginate'     => $paginate,
                'keyword'      => $keyword
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
            'pages.admin.' . config('view.admin') . '.oauth-clients.edit',
            [
                'isNew'       => true,
                'oauthClient' => $this->oauthClientRepository->getBlankModel(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Response
     */
    public function store(OauthClientRequest $request)
    {
        $name = $request->get('name', 'Test');

        \Artisan::call(
            'passport:client',
            [
                '--password' => true,
                '--name'     => $name
            ]
        );

        return redirect()->action('Admin\OauthClientController@index')
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
        $oauthClient = $this->oauthClientRepository->find($id);
        if (empty( $oauthClient )) {
            abort(404);
        }

        return view(
            'pages.admin.' . config('view.admin') . '.oauth-clients.edit',
            [
                'isNew'       => false,
                'oauthClient' => $oauthClient,
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
    public function update($id, OauthClientRequest $request)
    {
        /** @var \App\Models\OauthClient $oauthClient */
        $oauthClient = $this->oauthClientRepository->find($id);
        if (empty( $oauthClient )) {
            abort(404);
        }
        $input = $request->only(['name','redirect']);
        $input['personal_access_client'] = $request->get('personal_access_client', 0);
        $input['password_client'] = $request->get('password_client', 0);
        $input['revoked'] = $request->get('revoked', 0);

        $this->oauthClientRepository->update($oauthClient, $input);

        return redirect()->action('Admin\OauthClientController@show', [$id])
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
        /** @var \App\Models\OauthClient $oauthClient */
        $oauthClient = $this->oauthClientRepository->find($id);
        if (empty( $oauthClient )) {
            abort(404);
        }
        $this->oauthClientRepository->delete($oauthClient);

        return redirect()->action('Admin\OauthClientController@index')
            ->with('message-success', trans('admin.messages.general.delete_success'));
    }

}
