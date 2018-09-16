<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\LogRepositoryInterface;
use App\Http\Requests\PaginationRequest;

class LogController extends Controller {

    /** @var \App\Repositories\LogRepositoryInterface */
    protected $logRepository;


    public function __construct(
        LogRepositoryInterface $logRepository
    ) {
        $this->logRepository = $logRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\PaginationRequest $request
     *
     * @return \Response
     */
    public function index( PaginationRequest $request ) {
        $paginate[ 'offset' ]   = $request->offset();
        $paginate[ 'limit' ]        = $request->limit();
        $paginate[ 'order' ]        = $request->order();
        $paginate[ 'direction' ]    = $request->direction();
        $paginate[ 'baseUrl' ]      = action( 'Admin\LogController@index' );

        $filter = [];
        $keyword = $request->get('keyword');
        if (!empty($keyword)) {
            $filter['query'] = $keyword;
        }

        $count = $this->logRepository->countByFilter($filter);
        $logs = $this->logRepository->getByFilter($filter, $paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']);

        return view(
            'pages.admin.' . config('view.admin') . '.logs.index',
            [
                'logs'     => $logs,
                'count'    => $count,
                'paginate' => $paginate,
                'keyword'  => $keyword
            ]
        );
    }
}
