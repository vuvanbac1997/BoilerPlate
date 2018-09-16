<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\ArticleRequest;
use App\Http\Requests\APIRequest;
use App\Http\Requests\BaseRequest;
use App\Repositories\ArticleRepositoryInterface;
use App\Services\FileUploadServiceInterface;
use App\Repositories\ImageRepositoryInterface;
use App\Http\Responses\API\V1\Response;
use App\Services\APIUserServiceInterface;

class ArticleController extends Controller
{
    /** @var \App\Repositories\ArticleRepositoryInterface */
    protected $articleRepository;

    /** @var FileUploadServiceInterface $fileUploadService */
    protected $fileUploadService;

    /** @var ImageRepositoryInterface $imageRepository */
    protected $imageRepository;

    /** @var ImageRepositoryInterface $imageRepository */
    protected $userService;

    public function __construct(
        ArticleRepositoryInterface  $articleRepository,
        FileUploadServiceInterface  $fileUploadService,
        ImageRepositoryInterface    $imageRepository,
        APIUserServiceInterface     $userService
    ) {
        $this->articleRepository    = $articleRepository;
        $this->fileUploadService    = $fileUploadService;
        $this->imageRepository      = $imageRepository;
        $this->userService          = $userService;
    }

    public function index(BaseRequest $request)
    {
        $data = $request->all();

        $articles = $this->articleRepository->get($data['order'], $data['direction'], $data['offset'], $data['limit']); // change get() to geEnabled as requirement
        foreach( $articles as $key => $article ) {
            $articles[$key] = $article->toAPIArray();
        }

        return Response::response(200, $articles);
    }

    public function show($id, APIRequest $request)
    {
        if( !is_numeric($id) || ($id <= 0) ) {
            return Response::response(40001);
        }

        $article = $this->articleRepository->find($id);
        if( empty($article) ) {
            return Response::response(20004);
        }

        return Response::response(200, $article->toAPIArray());
    }

    public function store(ArticleRequest $request)
    {
        $data = $request->only(
            [
                'slug',
                'title',
                'keywords',
                'description',
                'content',
                'locale',
                'publish_started_at',
                'publish_ended_at',
            ]
        );
        $data['locale'] = $request->get('locale', 'vn');
        $data['is_enabled'] = $request->get('is_enabled', 0);

        try {
            $article = $this->articleRepository->create($data);
        } catch (\Exception $e) {
            return Response::response(50002);
        }

        if( empty( $article ) ) {
            return Response::response(50002);
        }

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $mediaType = $file->getClientMimeType();
            $path = $file->getPathname();
            $image = $this->fileUploadService->upload(
                'article-cover-image',
                $path,
                $mediaType,
                [
                    'entityType' => 'article-cover-image',
                    'entityId'   => $article->id,
                    'title'      => $request->input('title', ''),
                ]
            );

            if (!empty($image)) {
                $article = $this->articleRepository->update($article, ['cover_image_id' => $image->id]);
            }
        }

        return Response::response(200, $article->toAPIArray());
    }

    public function update($id, ArticleRequest $request)
    {
        if( !is_numeric($id) || ($id <= 0) ) {
            return Response::response(40001);
        }

        $article = $this->articleRepository->find($id);
        if( empty($article) ) {
            return Response::response(20004);
        }

        $data = $request->only(
            [
                'slug',
                'title',
                'keywords',
                'description',
                'content',
                'locale',
                'publish_started_at',
                'publish_ended_at',
            ]
        );

        try {
            $this->articleRepository->update($article, $data);
        } catch (\Exception $e) {
            return Response::response(50002);
        }

        if( $request->hasFile( 'cover_image' ) ) {
            $currentImage = $article->coverImage;
            $file = $request->file( 'cover_image' );
            $mediaType = $file->getClientMimeType();
            $path = $file->getPathname();
            $newImage = $this->fileUploadService->upload(
                'article-cover-image',
                $path,
                $mediaType,
                [
                    'entityType' => 'article',
                    'entityId'   => $article->id,
                    'title'      => $request->input( 'name', '' ),
                ]
            );

            if( !empty( $newImage ) ) {
                $article = $this->articleRepository->update( $article, ['cover_image_id' => $newImage->id] );

                if( !empty( $currentImage ) ) {
                    $this->fileUploadService->delete( $currentImage );
                    $this->imageRepository->delete( $currentImage );
                }
            }
        }

        return Response::response(200, $article->toAPIArray());
    }

    public function destroy($id)
    {
        if( !is_numeric($id) || ($id <= 0) ) {
            return Response::response(40001);
        }

        $article = $this->articleRepository->find($id);
        if( empty($article) ) {
            return Response::response(20004);
        }

        try {
            $this->articleRepository->delete($article);
        } catch (\Exception $e) {
            return Response::response(50002);
        }

        return Response::response(200);
    }
}
