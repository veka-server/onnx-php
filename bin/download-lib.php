<?php

$root_path = dirname(__DIR__, 4) ;

// utilisation du loader de composer
require $root_path.'/vendor/autoload.php';

Onnx\Download::check();