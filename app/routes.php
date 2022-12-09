<?php declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\BaseController;
use App\Controllers\HomeController;
use App\Controllers\PostController;
use VGuyomarch\Foundation\Router\Route;

// routage avec symphony rooting
// Extraire l'URL et regarder l'URI
// On pourra demander au rooter quel action effectuer

return [
        // Authentification
    'register.form' => Route::get('/inscription', [AuthController::class, 'registerForm']),
    'register.request' => Route::post('/inscription', [AuthController::class, 'register']),
    'login.form' => Route::get('/connexion', [AuthController::class, 'loginForm']),
    'login.request' => Route::post('/connexion', [AuthController::class, 'login']),
    'logout' => Route::post('/deconnexion', [AuthController::class, 'logout']),

    // Espace membre
    'home' => Route::get('/compte', [HomeController::class, 'index']),
    'home.updateName' => Route::patch('/compte', [HomeController::class, 'updateName']),
    'home.updateEmail' => Route::patch('/compte/email', [HomeController::class, 'updateEmail']),
    'home.updatePassword' => Route::patch('/compte/password', [HomeController::class, 'updatePassword']),

    // Blog
    'index' => Route::get('/', [PostController::class, 'index']),
    'posts.create' => Route::get('/posts/creer', [PostController::class, 'create']),
    'posts.store' => Route::post('/posts/creer', [PostController::class, 'store']),
    'posts.show' => Route::get('/posts/{slug}', [PostController::class, 'show']),
    'posts.comment' => Route::post('/posts/{slug}', [PostController::class, 'comment']),
    'posts.delete' => Route::delete('/posts/{slug}', [PostController::class, 'delete']),
    'posts.edit' => Route::get('/posts/{slug}/modifier', [PostController::class, 'edit']),
    'posts.update' => Route::patch('/posts/{slug}/modifier', [PostController::class, 'update']),
];