<?php declare(strict_types=1);

use VGuyomarch\Foundation\Authentication;
use VGuyomarch\Foundation\Router\Router;
use VGuyomarch\Foundation\Session;
use VGuyomarch\Foundation\View;

// accès à la classe Authentication
if(!function_exists('auth')) {
    function auth(): Authentication
    {
        return new Authentication();
    }
}

// générer des routes
if(!function_exists('route')) {
    function route(string $name, array $data = []): string
    {
        return Router::get($name, $data);
    }
}

// récupérer les infos de session
// erreurs
if(!function_exists('errors')) {
    function errors(?string $field = null): ?array
    {
        $errors = Session::getFlash(Session::ERRORS);
        if($field) {
            return $errors[$field] ?? null;
        }
        return $errors;
    }
}

// message d'information status, old
if(!function_exists('status')) {
    function status(): ?string
    {
        return Session::getFlash(Session::STATUS);
    }
}

if(!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return View::csrfField();
    }
}

if(!function_exists('method')) {
    function method(string $httpMethod): string
    {
        return View::method($httpMethod);
    }
}

if(!function_exists('old')) {
    function old(string $key, mixed $default = null): mixed
    {
        return View::old($key, $default);
    }
}