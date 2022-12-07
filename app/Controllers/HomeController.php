<?php declare(strict_types=1);

namespace App\Controllers;

use VGuyomarch\Foundation\AbstractController;
use VGuyomarch\Foundation\Authentication as Auth;
use VGuyomarch\Foundation\View;

class HomeController extends AbstractController
{
    public function index(): void
    {
        // si user non authenticate, redirect login form
        if(!Auth::check()) {
            $this->redirection('login.form');
        }

        // récupérer les datas de User pour compléter la view home (compte)
        $user = Auth::get();

        // View home
        View::render('home', [
            'user' => $user,
        ]);
    }
}