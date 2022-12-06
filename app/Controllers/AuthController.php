<?php declare(strict_types=1);

namespace App\Controllers;

use VGuyomarch\Foundation\AbstractController;
use VGuyomarch\Foundation\Authentication as Auth;
use VGuyomarch\Foundation\View;

class AuthController extends AbstractController
{
    public function registerForm(): void
    {
        // si user authenticate redirection vers home
        if(Auth::check()) {
            $this->redirection('home');
        }

        View::render('auth.register');
    }
}