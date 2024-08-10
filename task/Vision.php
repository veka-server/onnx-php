<?php

namespace Task;

use Onnx\DType;
use Onnx\Tensor;
use Task\classes\Image;

class Vision
{
    protected array $tags = [];
    protected $provider = 'CPUExecutionProvider';
    protected \Onnx\Model $model;
    protected $rescale_factor= 1 ;
    protected $format = 'rgb';
    protected $height = 256;
    protected $width = 256;
    protected $shape = 'bchw';
    protected mixed $modelNameOrPath;

    public function __construct(array $config = [], $lib = null) {

        if(!empty($config)) {
            $this->tags = $config['tags'] ?? [] ;
            $this->rescale_factor = $config['rescale_factor'] ?? 1 ;
            $this->modelNameOrPath = $config['modelNameOrPath'] ?? null ;
            $this->format = $config['format'] ?? 'rgb' ;
            $this->height = $config['height'] ?? 256 ;
            $this->width = $config['width'] ?? 256 ;
            $this->shape = $config['shape'] ?? 'bchw' ;
        }

        if(!empty($lib)) {

            if(!is_file($lib)) {
                throw new \Exception( 'Unable to find the lib file : ' . dirname($lib) );
            }

            \Onnx\FFI::$lib = $lib;

            if (\FFI\WorkDirectory\WorkDirectory::set(dirname($lib)) === false) {
                throw new \Exception( 'FAILED to CWD has been updated to: ' . dirname($lib) );
            }

        }
    }

    public function loadModel($model = null) {
        if(!empty($model)) {
            $this->modelNameOrPath = $model;
        }

        if(empty($this->modelNameOrPath)) {
            throw new \Exception('model missing');
        }

        /** chargement du model */
        $this->model = new \Onnx\Model($this->modelNameOrPath, providers: [$this->provider]);
    }

    public function getTags($image){

        /** resize de l'image pour qu'elle soit dans le bon format */
        $img = Image::getGDImageFromImg( image: $image, width: $this->width, height: $this->height );

        /** Extraction des pixels */
        $pixels = Image::getPixels($img, $this->format, $this->rescale_factor);

        /** converti le shape  */
        $pixels = $this->transposeImage([$pixels], $this->shape);

        /** Récuperation du nom de l'input depuis le model */
        $input_name = $this->model->inputs()[0]['name'];

        $tensor = Tensor::fromArray($pixels,DType::Float32);

        /** prediction IA */
        $result = $this->model->predict([$input_name => $tensor]);

        $clean_result = $this->postprocess($result);

        return $clean_result;
    }

    function transposeImage($pixels, $format = 'bhwc') {

        if(strtolower($format) == 'bhwc') {
            return $pixels;
        }

        $transposedImage = [];

        if($format == 'bchw') {
            foreach ($pixels as $b => $batch) {
                foreach ($batch as $h => $row) {
                    foreach ($row as $w => $pixel) {
                        foreach ($pixel as $c => $value) {
                            $transposedImage[$b][$c][$h][$w] = $value;
                        }
                    }
                }
            }
        }

        return $transposedImage;
    }

    protected function postprocess($result) {

        $t = [];
        $output_name = $this->model->outputs()[0]['name'];

        foreach ($result[$output_name][0] as $idx => $v) {

            if(empty($this->tags[$idx])) {
                continue;
            }

            $t[$this->tags[$idx]] = $v;

        }

        return $t;
    }

    public function setProvider(string $provider = null) :void
    {
        if(empty($provider)) {
            $this->provider = 'CPUExecutionProvider';
            return ;
        }

        $this->provider = $provider;
    }

}