<?php declare(strict_types=1);

namespace App\Controllers;

use VGuyomarch\Foundation\AbstractController;
use VGuyomarch\Foundation\Authentication;
use VGuyomarch\Foundation\View;

class AuthController extends AbstractController
{
    public function registerForm(): void
    {
        // si user authenticate redirection vers home
        if(Authentication::check()) {
            $this->redirection('home');
        }

        View::render('auth.register');
    }
}