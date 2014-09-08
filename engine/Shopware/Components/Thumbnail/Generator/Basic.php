<?php
/**
 * Shopware 4
 * Copyright © shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace Shopware\Components\Thumbnail\Generator;

/**
 * Shopware Basic Thumbnail Generator
 *
 * This is a generator which creates image objects
 * based on the passed image path which will be used
 * for further manipulation.
 *
 * Class Basic
 * @category    Shopware
 * @package     Shopware\Component\Thumbnail\Generator
 * @copyright   Copyright (c) shopware AG (http://www.shopware.de)
 */
class Basic implements GeneratorInterface
{
    /**
     * This method creates a new thumbnail based on the given parameters
     *
     * @param String $imagePath - full path of the original image
     * @param String $destination - full path of the thumbnail where it should be created
     * @param Int $width - width of the thumbnail
     * @param Int $height - height of the thumbnail
     * @param bool $keepProportions - Whether or not keeping the proportions of the original image, the size can be affected when true
     * @throws \Exception
     * @return void
     */
    public function createThumbnail($imagePath, $destination, $width, $height, $keepProportions = false)
    {
        if (!file_exists($imagePath)) {
            throw new \Exception("File not found: " . $imagePath);
        }

        // Saves image data to memory for usage
        $image = $this->createFileImage($imagePath);

        if($image === false){
            throw new \Exception("Image could not be created: " . $imagePath);
        }

        // Determines the width and height of the original image
        $originalSize = $this->getOriginalImageSize($imagePath);

        if (empty($height)) {
            $height = $width;
        }

        $newSize = array('width' => $width, 'height' => $height);

        if($keepProportions === true){
            $newSize = $this->calculateProportionalThumbnailSize($originalSize, $width, $height);
        }

        // Creates a new image with given size
        $newImage = imagecreatetruecolor($newSize['width'], $newSize['height']);

        // Disables blending
        imagealphablending($newImage, false);
        // Saves the alpha informations
        imagesavealpha($newImage, true);
        // Copies the original image into the new created image with resampling
        imagecopyresampled(
            $newImage,
            $image,
            0,
            0,
            0,
            0,
            $newSize['width'],
            $newSize['height'],
            $originalSize['width'],
            $originalSize['height']
        );

        // saves the image information into a specific file extension
        switch(strtolower($this->getImageExtension($destination))){
            case 'png':
                imagepng($newImage, $destination);
                break;
            case 'gif':
                imagegif($newImage, $destination);
                break;
            default:
                imagejpeg($newImage, $destination, 90);
                break;
        }

        // Removes both the original and the new created image from memory
        imagedestroy($newImage);
        imagedestroy($image);
    }

    /**
     * Returns an array with a width and height index
     * according to the passed sizes
     *
     * @param $path
     * @return array
     */
    private function getOriginalImageSize($path)
    {
        $size = getimagesize($path);

        return array('width' => $size[0], 'height' => $size[1]);
    }

    /**
     * Determines the extension of the file according to
     * the given path and calls the right creation
     * method for the image extension
     *
     * @param $path
     * @return bool|resource
     * @throws \Exception
     */
    private function createFileImage($path)
    {
        // Determines the image creation by the file extension
        switch (strtolower($this->getImageExtension($path))) {
            case 'gif':
                $image = imagecreatefromgif($path);
                break;
            case 'png':
                $image = imagecreatefrompng($path);
                break;
            case 'jpg':
                $image = imagecreatefromjpeg($path);
                break;
            default:
                throw new \Exception("Extension is not supported");
        }

        return $image;
    }

    /**
     * Returns the extension of the file with passed path
     *
     * @param $path
     * @return mixed
     */
    private function getImageExtension($path)
    {
        $pathInfo = pathinfo($path);
        return $pathInfo['extension'];
    }

    /**
     * Calculate image proportion and set the new resolution
     * @param $originalSize
     * @param $width
     * @param $height
     * @return array
     */
    private function calculateProportionalThumbnailSize(array $originalSize, $width, $height)
    {
        // Source image size
        $srcWidth = $originalSize['width'];
        $srcHeight = $originalSize['height'];

        // Calculate the scale factor
        if($width === 0) {
            $factor = $height / $srcHeight;
        } else if($height === 0) {
            $factor = $width / $srcWidth;
        } else {
            $factor = min($width / $srcWidth, $height / $srcHeight);
        }

        // Get the destination size
        $dstWidth = round($srcWidth * $factor);
        $dstHeight = round($srcHeight * $factor);

        return array(
            'width' => $dstWidth,
            'height' => $dstHeight,
            'proportion' => $factor
        );
    }
}