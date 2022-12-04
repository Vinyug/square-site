<?php declare(strict_types = 1);

// constante pour récupérer le dossier PVC et non public
// on remplace /public par '' et on récupère le chemin absolu
define('ROOT', str_replace('\public', '', __DIR__));

require_once ROOT.'\vendor\autoload.php';

$app = new VGuyomarch\Foundation\App();
$app->render();
