<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Repositories\UserNotificationRepositoryInterface;
use App\Http\Requests\Admin\UserNotificationRequest;
use App\Http\Requests\PaginationRequest;
use App\Repositories\UserRepositoryInterface;

class UserNotificationController extends Controller
{
    /** @var \App\Repositories\UserNotificationRepositoryInterface */
    protected $userNotificationRepository;

    /** @var \App\Repositories\UserRepositoryInterface */
    protected $userRepository;


    public function __construct(
        UserNotificationRepositoryInterface $userNotificationRepository,
        UserRepositoryInterface             $userRepository
    ) {
        $this->userNotificationRepository   = $userNotificationRepository;
        $this->userRepository               = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\PaginationRequest $request
     *
     * @return \Response
     */
    public function index( PaginationRequest $request ) {
        $paginate[ 'offset' ] = $request->offset();
        $paginate[ 'limit' ] = $request->limit();
        $paginate[ 'order' ] = $request->order();
        $paginate[ 'direction' ] = $request->direction();
        $paginate[ 'baseUrl' ] = action( 'Admin\UserNotificationController@index' );

        $filter = [];
        $keyword = $request->get('keyword');
        if (!empty($keyword)) {
            $filter['query'] = $keyword;
        }

        $count = $this->userNotificationRepository->countByFilter($filter);
        $notifications = $this->userNotificationRepository->getByFilter($filter, $paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']);

        return view(
            'pages.admin.' . config('view.admin') . '.user-notifications.index',
            [
                'notifications' => $notifications,
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
    public function create() {
        return view(
            'pages.admin.' . config('view.admin') . '.user-notifications.edit',
            [
                'isNew'            => true,
                'userNotification' => $this->userNotificationRepository->getBlankModel(),
                'users'            => $this->userRepository->all()
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     *
     * @return \Response
     */
    public function store( UserNotificationRequest $request )
    {
        $input = $request->only(
            [
                'category_type',
                'type',
                'data',
                'content',
                'locale',
                'sent_at'
            ]
        );

        $input['sent_at'] = ($input['sent_at'] != "") ? $input['sent_at'] : null;
        $input['read']    = $request->get('read', 0);
        $input['user_id'] = $request->get('user_id', 0);

        $model = $this->userNotificationRepository->create( $input );

        if( empty( $model ) ) {
            return redirect()
                ->back()
                ->withErrors( trans( 'admin.errors.general.save_failed' ) );
        }

        return redirect()
            ->action( 'Admin\UserNotificationController@index' )
            ->with( 'message-success', trans( 'admin.messages.general.create_success' ) );
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Response
     */
    public function show( $id )
    {
        $model = $this->userNotificationRepository->find( $id );
        if( empty( $model ) ) {
            abort( 404 );
        }

        return view(
            'pages.admin.' . config('view.admin') . '.user-notifications.edit',
            [
                'isNew'            => false,
                'userNotification' => $model,
                'users'            => $this->userRepository->all()
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Response
     */
    public function edit( $id ) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param      $request
     *
     * @return \Response
     */
    public function update( $id, UserNotificationRequest $request )
    {
        /** @var \App\Models\UserNotification $model */
        $model = $this->userNotificationRepository->find( $id );
        if( empty( $model ) ) {
            abort( 404 );
        }

        $input = $request->only(
            [
                'category_type',
                'type',
                'data',
                'content',
                'locale',
                'sent_at'
            ]
        );

        $input['sent_at'] = ($input['sent_at'] != "") ? $input['sent_at'] : null;
        $input['read']    = $request->get('read', 0);
        $input['user_id'] = $request->get('user_id', 0);
 
        $this->userNotificationRepository->update( $model, $input );

        return redirect()
            ->action( 'Admin\UserNotificationController@show', [$id] )
            ->with( 'message-success', trans( 'admin.messages.general.update_success' ) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Response
     */
    public function destroy( $id ) {
        /** @var \App\Models\UserNotification $model */
        $model = $this->userNotificationRepository->find( $id );
        if( empty( $model ) ) {
            abort( 404 );
        }
        $this->userNotificationRepository->delete( $model );

        return redirect()
            ->action( 'Admin\UserNotificationController@index' )
            ->with( 'message-success', trans( 'admin.messages.general.delete_success' ) );
    }

}
