<?php

namespace App\Services\Production;

use App\Services\ImageServiceInterface;

class ImageService extends BaseService implements ImageServiceInterface
{
    /**
     * resize image as config
     *
     * @params  string  $path path to tmp upload image
     *          array   $config[width, height]
     *          string  $fileUploadedPath like public/static/common/images/products/product1.png
     *
     * @return  boolean
     */
    public function resizeImage($path, $config, $fileUploadedPath)
    {
        $image  = imagecreatefromstring(file_get_contents($path));
        $frame  = $this->getImageFrameToCrop( getimagesize($path), $config );

        $image = imagecrop($image, ['x' => $frame['x'], 'y' => $frame['y'], 'width' => $frame['width'], 'height' => $frame['height']]);
        if ($image !== false) {
            imagepng($image, $fileUploadedPath);
            return true;
        }

        return false;
    }

    /**
     * get coordinate and size to crop image
     *
     * @params  array   $imageSize  [width, height]
     *          array   $config     [width, height]
     *
     * @return  boolean
     */
    private function getImageFrameToCrop( $imageSize, $config)
    {
        $frame = [];
        if( ($imageSize[0]/$imageSize[1]) > ($config[0]/$config[1]) ) {
            $frame['height'] = min($config[1], $imageSize[1]);
            $frame['width']  = ($frame['height'] * $config[0]) / $config[1];
            $frame['x']      = ($imageSize[0] - $frame['width']) / 2;
            $frame['y']      = ($imageSize[1] - $frame['height']) / 2;
        } else {
            $frame['width']  = min($config[0], $imageSize[0]);
            $frame['height'] = ($frame['width'] * $config[1]) / $config[0];
            $frame['x']      = ($imageSize[0] - $frame['width']) / 2;
            $frame['y']      = ($imageSize[1] - $frame['height']) / 2;
        }

        return $frame;
    }
}
