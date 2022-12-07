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
    'register.request' => Route::post('/inscription', [AuthController::class, 'register']),
    'login.form' => Route::get('/connexion', [AuthController::class, 'loginForm']),
    'login.request' => Route::post('/connexion', [AuthController::class, 'login']),
    'logout' => Route::post('/deconnexion', [AuthController::class, 'logout']),

    // Espace membre
    'home' => Route::get('/compte', [HomeController::class, 'index']),
    'home.updateName' => Route::patch('/compte', [HomeController::class, 'updateName']),
];