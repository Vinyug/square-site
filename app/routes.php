<?php declare(strict_types=1);

use App\Controllers\BaseController;
use VGuyomarch\Foundation\Router\Route;

// routage avec symphony rooting
// Extraire l'URL et regarder l'URI
// On pourra demander au rooter quel action effectuer

return [
    'index' => Route::get('/',[BaseController::class, 'index']),
];