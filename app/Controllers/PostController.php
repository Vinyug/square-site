<?php declare(strict_types=1);

namespace App\Controllers;

use VGuyomarch\Foundation\AbstractController;
use VGuyomarch\Foundation\Authentication as Auth;
use VGuyomarch\Foundation\View;

class PostController extends AbstractController
{
    public function create(): void
    {
        if(!Auth::checkIsAdmin()) {
            $this->redirection('login.form');
        }

        View::render('posts.create');
    }
}