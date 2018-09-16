<?php

namespace App\Services\Production;

use App\Repositories\FileRepositoryInterface;
use App\Repositories\ImageRepositoryInterface;
use App\Services\FileUploadServiceInterface;
use App\Services\ImageServiceInterface;
use Aws\S3\S3Client;

class FileUploadService extends BaseService implements FileUploadServiceInterface
{
    const IMAGE_ID_SESSION_KEY = 'image-id-session-key';

    /** @var \App\Repositories\FileRepositoryInterface */
    protected $fileRepository;

    /** @var \App\Repositories\ImageRepositoryInterface */
    protected $imageRepository;

    /** @var \App\Services\ImageServiceInterface */
    protected $imageService;

    public function __construct(
        FileRepositoryInterface $fileRepository,
        ImageRepositoryInterface $imageRepository,
        ImageServiceInterface $imageService
    ) {
        $this->fileRepository = $fileRepository;
        $this->imageRepository = $imageRepository;
        $this->imageService = $imageService;
    }

    /**
     * @param string $categoryType
     * @param string $text
     * @param string $mediaType
     * @param array  $metaInputs
     *
     * @return \App\Models\Image|\App\Models\File|null
     */
    public function uploadFromText($categoryType, $text, $mediaType, $metaInputs)
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'upload');
        $handle = fopen($tempFile, 'w');
        fwrite($handle, $text);
        fclose($handle);

        $file = $this->upload($categoryType, $tempFile, $mediaType, $metaInputs);

        unlink($handle);

        return $file;
    }

    /**
     * @params  string  $configKey
     *          object  $file {path, mimeType, size ...}
     *          array   $storageInfo [ entityType, entityId, title ]
     *
     * @return \App\Models\Image | \App\Models\File | null
     */
    public function upload($configKey, $file, $storageInfo)
    {
        $conf = config('file.categories.' . $configKey);
        if (empty($conf)) {
            return false;
        }

        $acceptableFileList = config('file.acceptable.' . $conf['type']);
        $mediaType          = $file->getClientMimeType();
        if (!array_key_exists($mediaType, $acceptableFileList)) {
            return false;
        }
        $ext    = array_get($acceptableFileList, $mediaType);
        $path   = $file->getPathname();

        $model = null;
        switch ($conf['type']) {
            case 'image':
                $model = $this->uploadImage($configKey, $path, $mediaType, $ext, $storageInfo);
                break;
            case 'file':
                $model = $this->uploadFile($configKey, $path, $mediaType, $ext, $storageInfo);
                break;
        }

        return $model;
    }

    /**
     * @params  \App\Models\Image | \App\Models\File
     *
     * @return  boolean
     */
    public function delete($model)
    {
        $configKey = $model['file_category_type'];
        $conf = config('file.categories.'.$configKey);
        if( empty($conf) ) {
            return false;
        }

        if( env('LOCAL_STORAGE') ) {
            $filePath  = 'static/' . $conf['local_type'] . '/' . $conf['local_path'] . $model->getOriginal('url');
            
            if( file_exists($filePath) ) {
                unlink($filePath);
            }
        } else {
            $bucket = $model->s3_bucket;
            $region = $model->s3_region;
            $key    = $model->s3_key;

            if (empty($key)) {
                return false;
            }

            $this->deleteS3($region, $bucket, $key);

            switch ($conf['type']) {
                case 'image':
                    foreach (array_get($conf, 'thumbnails', []) as $thumbnail) {
                        $thumbnailKey = $this->getThumbnailKeyFromKey($key, $thumbnail);
                        if (!empty($thumbnailKey)) {
                            $this->deleteS3($region, $bucket, $thumbnailKey);
                        }
                    }
                    break;
                case 'file':
                    break;
            }
        }

        switch ($conf['type']) {
            case 'image':
                $this->imageRepository->delete($model);
                break;
            case 'file':
                $this->fileRepository->delete($model);
                break;
        }

        return true;
    }

    /**
     * @params  string  $configKey
     *          string  $path
     *          string  $mediaType
     *          string  $ext
     *          array   $storageInfo [ entityType, entityId, title ]
     *
     * @return \App\Models\File | null
     */
    private function uploadFile($configKey, $path, $mediaType, $ext, $storageInfo)
    {
        $input = [
            'title'              => array_get($storageInfo, 'title', ''),
            'entity_type'        => array_get($storageInfo, 'entity_type', ''),
            'entity_id'          => array_get($storageInfo, 'entity_id', ''),
            'file_category_type' => $configKey,
            'media_type'         => $mediaType,
            'format'             => $ext,
            'file_size'          => filesize($path),
            'is_enabled'         => true
        ];

        $seed     = array_get(config('file.categories.' . $configKey), 'seed_prefix', '').time().rand();
        $fileName = $this->generateFileName($seed, null, $ext);


        if( env('LOCAL_STORAGE') ) {
            $localPath  = 'static/' . config('file.categories.' . $configKey . '.local_type') . '/' . config('file.categories.' . $configKey . '.local_path');
            if (!is_dir($localPath)) {
                mkdir($localPath);
            }

            move_uploaded_file( $path, $localPath . $fileName );

            $input['url']      = $fileName;
            $input['is_local'] = true;
        } else {
            $bucket = $this->decideBucket(array_get(config('file.categories.' . $configKey), 'buckets', ''));
            $region = array_get(config('file.categories.' . $configKey), 'region', 'ap-northeast-1');
            $url = $this->uploadToS3($path, $region, $bucket, $fileName, $mediaType);

            $input['url']          = $url;
            $input['is_local']     = false;
            $input['s3_key']       = $fileName;
            $input['s3_bucket']    = $bucket;
            $input['s3_region']    = $region;
            $input['s3_extension'] = $ext;
        }

        /** @var  \App\Models\File | null $file */
        $file = $this->fileRepository->create($input);

        return $file;
    }

    /**
     * @params  string  $configKey
     *          string  $path
     *          string  $mediaType
     *          string  $ext
     *          array   $storageInfo [ entityType, entityId, title ]
     *
     * @return \App\Models\Image | null
     */
    private function uploadImage($configKey, $path, $mediaType, $ext, $storageInfo)
    {
        $input = [
            'title'              => array_get($storageInfo, 'title', ''),
            'entity_type'        => array_get($storageInfo, 'entity_type', ''),
            'entity_id'          => array_get($storageInfo, 'entity_id', ''),
            'file_category_type' => $configKey,
            'media_type'         => $mediaType,
            'format'             => $ext,
            'is_enabled'         => true
        ];

        $seed     = array_get(config('file.categories.' . $configKey), 'seed_prefix', '').time().rand();
        $fileName = $this->generateFileName($seed, null, $ext);

        $localPath  = 'static/' . config('file.categories.' . $configKey . '.local_type') . '/' . config('file.categories.' . $configKey . '.local_path');
        if (!is_dir($localPath)) {
            mkdir($localPath);
        }

        $fileUploadedPath = $localPath . $fileName;

        $this->imageService->resizeImage($path, config('file.categories.' . $configKey . '.size'), $fileUploadedPath);
        if( !file_exists($fileUploadedPath) ) {
            return false;
        }
        $input['file_size'] = filesize($fileUploadedPath);
        $input['width']     = getimagesize($fileUploadedPath)[0];
        $input['height']    = getimagesize($fileUploadedPath)[1];

        if( env('LOCAL_STORAGE') ) {
            $input['url']      = $fileName;
            $input['is_local'] = true;

            foreach (array_get(config('file.categories.' . $configKey), 'thumbnails', []) as $thumbnail) {
                $thumbnailKey = $this->getThumbnailKeyFromKey($fileName, $thumbnail);
                $this->imageService->resizeImage($path, $thumbnail, $localPath . $thumbnailKey);
            }
        } else {
            $bucket = $this->decideBucket(array_get(config('file.categories.' . $configKey), 'buckets', ''));
            $region = array_get(config('file.categories.' . $configKey), 'region', 'ap-northeast-1');
            $url = $this->uploadToS3($fileUploadedPath, $region, $bucket, $fileName, $mediaType);

            $input['url']          = $url;
            $input['is_local']     = false;
            $input['s3_key']       = $fileName;
            $input['s3_bucket']    = $bucket;
            $input['s3_region']    = $region;
            $input['s3_extension'] = $ext;

            foreach (array_get(config('file.categories.' . $configKey), 'thumbnails', []) as $thumbnail) {
                $thumbnailKey = $this->getThumbnailKeyFromKey($fileName, $thumbnail);
                $this->imageService->resizeImage($path, $thumbnail, $localPath . $thumbnailKey);
                $this->uploadToS3($localPath . $thumbnailKey, $region, $bucket, $thumbnailKey, $mediaType);
            }
        }

        /** @var  \App\Models\Image | null $image */
        $file = $this->imageRepository->create($input);

        return $file;
    }

    /**
     * @param string $path
     * @param string $region
     * @param string $bucket
     * @param string $key
     * @param string $mediaType
     *
     * @return null|string
     */
    private function uploadToS3($path, $region, $bucket, $key, $mediaType = 'binary/octet-stream')
    {
        $client = $this->getS3Client($region);

        if (!file_exists($path)) {
            return null;
        }

        $client->putObject([
            'Bucket' => $bucket,
            'Key' => $key,
            'SourceFile' => $path,
            'ContentType' => $mediaType,
            'ACL' => 'public-read',
        ]);

        unlink($path);

        return $client->getObjectUrl($bucket, $key);
    }

    /**
     * @param string $key
     * @param array  $size
     *
     * @return null|string
     */
    private function getThumbnailKeyFromKey($key, $size)
    {
        if (preg_match('/^(.+?)\.([^\.]+)$/', $key, $match)) {
            return $match[1].'_'.$size[0].'_'.$size[1].'.'.$match[2];
        }

        return null;
    }

    /**
     * @param string      $seed
     * @param string|null $postFix
     * @param string|null $ext
     *
     * @return string
     */
    private function generateFileName($seed, $postFix, $ext)
    {
        $filename = md5($seed);
        if (!empty($postFix)) {
            $filename .= '_'.$postFix;
        }
        if (!empty($ext)) {
            $filename .= '.'.$ext;
        }

        return $filename;
    }

    /**
     * @param array $candidates
     *
     * @return string
     */
    private function decideBucket($candidates)
    {
        $pos = ord(time() % 10) % count($candidates);

        return $candidates[$pos];
    }

    /**
     * @param string $region
     * @param string $bucket
     * @param string $key
     */
    private function deleteS3($region, $bucket, $key)
    {
        $client = $this->getS3Client($region);

        $client->deleteObject([
            'Bucket' => $bucket,
            'Key' => $key,
        ]);
    }

    /**
     * @param string $region
     *
     * @return S3Client
     */
    private function getS3Client($region)
    {
        $config = config('aws');

        return new S3Client([
            'credentials' => [
                'key' => array_get($config, 'key'),
                'secret' => array_get($config, 'secret'),
            ],
            'region' => $region,
            'version' => 'latest',
        ]);
    }
}
