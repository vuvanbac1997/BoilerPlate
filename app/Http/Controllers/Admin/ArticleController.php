<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BaseRequest;
use App\Http\Controllers\Controller;
use App\Repositories\ArticleRepositoryInterface;
use App\Http\Requests\Admin\ArticleRequest;
use App\Http\Requests\PaginationRequest;
use App\Repositories\ImageRepositoryInterface;
use App\Services\ArticleServiceInterface;
use App\Services\FileUploadServiceInterface;
use App\Services\ImageServiceInterface;

class ArticleController extends Controller
{
    /** @var \App\Repositories\ArticleRepositoryInterface */
    protected $articleRepository;

    /** @var ArticleServiceInterface $articleService */
    protected $articleService;

    /** @var FileUploadServiceInterface $fileUploadService */
    protected $fileUploadService;

    /** @var ImageRepositoryInterface $imageRepository */
    protected $imageRepository;

    /** @var  ImageServiceInterface $imageService */
    protected $imageService;

    public function __construct(
        ArticleRepositoryInterface      $articleRepository,
        ArticleServiceInterface         $articleService,
        FileUploadServiceInterface      $fileUploadService,
        ImageRepositoryInterface        $imageRepository,
        ImageServiceInterface           $imageService
    )
    {
        $this->articleRepository        = $articleRepository;
        $this->articleService           = $articleService;
        $this->fileUploadService        = $fileUploadService;
        $this->imageRepository          = $imageRepository;
        $this->imageService             = $imageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\PaginationRequest $request
     *
     * @return \Response
     */
    public function index( PaginationRequest $request )
    {
        $paginate[ 'offset' ]    = $request->offset();
        $paginate[ 'limit' ]     = $request->limit();
        $paginate[ 'order' ]     = $request->order();
        $paginate[ 'direction' ] = $request->direction();
        $paginate[ 'baseUrl' ]   = action( 'Admin\ArticleController@index' );

        $filter = [];
        $keyword = $request->get('keyword');
        if (!empty($keyword)) {
            $filter['query'] = $keyword;
        }

        $count = $this->articleRepository->countByFilter($filter);
        $articles = $this->articleRepository->getByFilter($filter, $paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']);

        return view(
            'pages.admin.' . config('view.admin') . '.articles.index',
            [
                'articles' => $articles,
                'count'    => $count,
                'paginate' => $paginate,
                'keyword'  => $keyword
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
            'pages.admin.' . config('view.admin') . '.articles.edit',
            [
                'isNew'   => true,
                'article' => $this->articleRepository->getBlankModel(),
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
    public function store(ArticleRequest $request)
    {
        $input = $request->only(
            [
                'slug',
                'title',
                'keywords',
                'description',
                'content',
                'publish_started_at',
                'publish_ended_at',
            ]
        );

        $input['is_enabled']         = $request->get('is_enabled', 0);
        $input['locale']             = $request->get('locale', 'vi');
        $input['publish_started_at'] = ($input['publish_started_at'] != "") ? $input['publish_started_at'] : null;
        $input['publish_ended_at']   = ($input['publish_ended_at'] != "") ? $input['publish_ended_at'] : null;

        $model = $this->articleRepository->create($input);

        if (empty($model)) {
            return redirect()
                ->back()
                ->withErrors(trans('admin.errors.general.save_failed'));
        }

        $imageIds = $this->articleService->getImageIdsFromSession();
        $images = $this->imageRepository->allByIds($imageIds);
        foreach ($images as $image) {
            $image->entity_id = $model->id;
            $image->save();
        }
        $this->articleService->resetImageIdSession();

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');

            $image = $this->fileUploadService->upload(
                'article_cover_image',
                $file,
                [
                    'entity_type' => 'article_cover_image',
                    'entity_id'   => $model->id,
                    'title'       => $request->input('name', ''),
                ]
            );

            if (!empty($image)) {
                $this->articleRepository->update($model, ['cover_image_id' => $image->id]);
            }
        }

        return redirect()
            ->action('Admin\ArticleController@index')
            ->with(
                'message-success',
                trans('admin.messages.general.create_success')
            );
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Response
     */
    public function show($id)
    {
        $article = $this->articleRepository->find($id);
        if (empty($article)) {
            abort(404);
        }

        return view(
            'pages.admin.' . config('view.admin') . '.articles.edit',
            [
                'isNew'   => false,
                'article' => $article,
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
    public function edit( $id )
    {
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
    public function update($id, ArticleRequest $request)
    {
        /** @var \App\Models\Article $article */
        $article = $this->articleRepository->find($id);
        if (empty($article)) {
            abort(404);
        }

        $input = $request->only(
            [
                'slug',
                'title',
                'keywords',
                'description',
                'content',
            ]
        );

        $input['is_enabled'] = $request->get('is_enabled', 0);
        $input['locale']     = $request->get('locale', 'vi');
        if ($request->get('publish_started_at') != "") {
            $input['publish_started_at'] = $request->get('publish_started_at');
        }
        if ($request->get('publish_ended_at') != "") {
            $input['publish_ended_at'] = $request->get('publish_ended_at');
        }

        $article = $this->articleRepository->update($article, $input);

        if ($request->hasFile('cover_image')) {
            $currentImage = $article->coverImage;
            $file = $request->file('cover_image');

            $newImage = $this->fileUploadService->upload(
                'article_cover_image',
                $file,
                [
                    'entity_type' => 'article_cover_image',
                    'entity_id'   => $article->id,
                    'title'       => $request->input('title', ''),
                ]
            );

            if (!empty($newImage)) {
                $this->articleRepository->update($article, ['cover_image_id' => $newImage->id]);

                if (!empty($currentImage)) {
                    $this->fileUploadService->delete($currentImage);
                }
            }
        }

        return redirect()
            ->action('Admin\ArticleController@show', [$id])
            ->with(
                'message-success',
                trans('admin.messages.general.update_success')
            );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Response
     */
    public function destroy($id)
    {
        /** @var \App\Models\Article $article */
        $article = $this->articleRepository->find($id);
        if (empty($article)) {
            abort(404);
        }

        $this->articleRepository->delete($article);

        return redirect()
            ->action('Admin\ArticleController@index')
            ->with('message-success', trans('admin.messages.general.delete_success'));
    }

    /**
     * @param BaseRequest $request
     *
     * @return \Response
     */
    public function preview(BaseRequest $request)
    {
        $locale   = $request->input('language');
        $content  = $this->articleService->filterContent($request->input('content'), $locale);
        $title    = $request->input('title');
        $response = response()->view(
            'pages.admin.' . config('view.admin') . '.articles.preview',
            [
                'content' => $content,
                'title'   => $title,
            ]
        );

        //        $response->headers->set('Content-Security-Policy', "default-src 'self' 'unsafe-inline'");
        $response->headers->set('X-XSS-Protection', '0');

        return $response;
    }

    public function getImages(PaginationRequest $request)
    {
        $entityId = intval($request->input('article_id', 0));
        $type = $request->input('type', 'article_image');

        if ($entityId == 0) {
            $imageIds = $this->articleService->getImageIdsFromSession();
            $models   = $this->imageRepository->allByIds($imageIds);
        } else {
            /** @var \App\Models\Image[] $models */
            $models = $this->imageRepository->allByFileCategoryTypeAndEntityId($type, $entityId);
        }

        $result = [];
        foreach ($models as $model) {
            $result[] = [
                'id'    => $model->id,
                'url'   => $model->present()->url(),
                'thumb' => '',
                'tag'   => ''
            ];
        }

        return response()->json($result);
    }

    public function postImage(BaseRequest $request)
    {
        if (!$request->hasFile('file')) {
            // [TODO] ERROR JSON
            abort(400, 'No Image File');
        }

        $type     = $request->input('type', 'article_image');
        $entityId = $request->input('article_id', 0);

        $conf = config('file.categories.' . $type);
        if (empty($conf)) {
            abort(400, 'Invalid type: ' . $type);
        }

        $file = $request->file('file');

        $image = $this->fileUploadService->upload(
            'article_image',
            $file,
            [
                'entity_type' => $type,
                'entity_id'   => $entityId,
                'title'       => $request->input('title', ''),
            ]
        );


        if ($entityId == 0) {
            $this->articleService->addImageIdToSession($image->id);
        }

        return response()->json(
            [
                'id'   => $image->id,
                'link' => $image->present()->url(),
            ]
        );
    }

    public function deleteImage(BaseRequest $request)
    {
        $url = $request->input('src');
        if (empty($url)) {
            abort(400, 'No URL Given');
        }
        $url = basename($url);

        /** @var \App\Models\Image|null $image */
        $image = $this->imageRepository->findByUrl($url);
        if (empty($image)) {
            abort(404);
        }

        $entityId = $request->input('article_id', 0);
        if ($entityId != $image->entity_id) {
            abort(400, 'Article ID Mismatch');
        } else {
            if ($entityId == 0 && !$this->articleService->hasImageIdInSession($image->id)) {
                abort(400, 'Entity ID Mismatch');
            }
        }

        $this->fileUploadService->delete($image);

        if ($entityId == 0) {
            $this->articleService->removeImageIdFromSession($image->id);
        }

        return response()->json(['status' => 'ok'], 204);
    }
}
