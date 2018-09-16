<?php

namespace App\Services;

interface FileUploadServiceInterface extends BaseServiceInterface
{
    /**
     * @param string $categoryType
     * @param string $text
     * @param string $mediaType
     * @param array  $metaInputs
     *
     * @return \App\Models\Image|\App\Models\File|null
     */
    public function uploadFromText($categoryType, $text, $mediaType, $metaInputs);

    /**
     * @params  string  $configKey
     *          object  $file {path, mimeType, size ...}
     *          array   $storageInfo [ entityType, entityId, title ]
     *
     * @return \App\Models\Image | \App\Models\File | null
     */
    public function upload($configKey, $file, $storageInfo);

    /**
     * @param \App\Models\Image|\App\Models\File $model
     *
     * @return bool|null
     */
    public function delete($model);
}
