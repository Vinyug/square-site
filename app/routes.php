<?php declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\BaseController;
use App\Controllers\HomeController;
use VGuyomarch\Foundation\Router\Route;

// routage avec symphony rooting
// Extraire l'URL et regarder l'URI
// On pourra demander au rooter quel action effectuer

return [
    'index' => Route::get('/',[BaseController::class, 'index']),

    // Authentification
    'register.form' => Route::get('/inscription', [AuthController::class, 'registerForm']),

    // Espace membre
    'home' => Route::get('/compte', [HomeController::class, 'index']),
];