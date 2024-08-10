<?php

namespace Task\classes;

class Image
{
    /**
     * @throws \Exception
     */
    public static function createImageGDFromPath($path) {

        $pathParts = pathinfo( $path);
        switch ( strtolower($pathParts["extension"])) {
            case 'png':
                $image = imagecreatefrompng($path);
                break;
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($path);
                break;
            case 'gif':
                $image = imagecreatefromgif($path);
                break;
            default:
                throw new \Exception('Format de fichier non reconnu');
        }

        return $image;
    }

    /**
     * @param $path
     * @return string
     */
    public static function imageToB64($path) {
        $data = file_get_contents($path);
        $pathParts = pathinfo( $path);
        return  'data:image/' . strtolower($pathParts["extension"]) . ';base64,' . base64_encode($data);
    }

    /**
     * @param $image
     * @param $width
     * @param $height
     * @return false|\GdImage|resource
     * @throws \Exception
     */
    public static function getGDImageFromImg($image, $width, $height)
    {
        $img = self::createImageGDFromPath($image);

        if(imagesx($img) > imagesy($img)) {
            $reduction = imagesx($img) / $width;
        } else {
            $reduction = imagesy($img) / $height;
        }

        /** converti l'image en $this->dimenssion_imagex$this->dimenssion_image  */
        $image2 = imagecreatetruecolor($width, $height); /* dimmension fixe */
        imagefill($image2,0,0,0x7fff0000); /* remplir avec de la transparence */
        imagecopyresampled($image2, $img, 0, 0, 0, 0, floor(imagesx($img)/$reduction), floor(imagesy($img)/$reduction), imagesx($img), imagesy($img)); /* copier l'image */

        return $image2;
    }

    /**
     * @param $img
     * @param $format
     * @param $rescale_factor
     * @return array
     */
    public static function getPixels($img, $format = 'rgb', $rescale_factor = 1)
    {
        $pixels = [];
        $width = imagesx($img);
        $height = imagesy($img);

        // Mapping for different formats
        $formats = [
            'bgr' => ['blue', 'green', 'red'],
            'rgb' => ['red', 'green', 'blue'],
        ];

        // Ensure the format exists, otherwise default to 'rgb'
        $format = $formats[strtolower($format)] ?? $formats['rgb'];

        for ($y = 0; $y < $height; $y++) {
            $row = [];
            for ($x = 0; $x < $width; $x++) {
                $rgb = imagecolorat($img, $x, $y);
                $color = imagecolorsforindex($img, $rgb);
                $row[] = [
                    $color[$format[0]] * $rescale_factor,
                    $color[$format[1]] * $rescale_factor,
                    $color[$format[2]] * $rescale_factor
                ];
            }
            $pixels[] = $row;
        }
        return $pixels;
    }

}