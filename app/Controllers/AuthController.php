<?php declare(strict_types=1);

namespace App\Controllers;

use VGuyomarch\Foundation\AbstractController;
use VGuyomarch\Foundation\View;

class AuthController extends AbstractController
{
    public function registerForm(): void
    {
        View::render('auth.register');
    }
}