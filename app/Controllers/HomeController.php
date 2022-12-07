<?php declare(strict_types=1);

namespace App\Controllers;

use VGuyomarch\Foundation\AbstractController;
use VGuyomarch\Foundation\Authentication as Auth;
use VGuyomarch\Foundation\Session;
use VGuyomarch\Foundation\Validator;
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

    // update name
    public function updateName(): void
    {
        // si user non authenticate, redirect login form
        if(!Auth::check()) {
            $this->redirection('login.form');
        }

        // règle Validator
        $validator = Validator::get($_POST);
        $validator->mapFieldsRules([
            'name' => ['required', ['lengthMin', 5]],
        ]);

        // action si invalide
        if(!$validator->validate()) {
            Session::addFlash(Session::ERRORS, $validator->errors());
            Session::addFlash(Session::OLD, $_POST);
            $this->redirection('home');
        }

        // si validé
        $user = Auth::get();
        $user->name = $_POST['name'];
        $user->save();

        // status MAJ
        Session::addFlash(Session::STATUS, 'Votre nom a été mis à jour !');
        $this->redirection('home');
    }

    // update email
    public function updateEmail(): void
    {
        // si user non authenticate, redirect login form
        if(!Auth::check()) {
            $this->redirection('login.form');
        }

        // règle Validator
        $validator = Validator::get($_POST);
        $validator->mapFieldsRules([
            'email' => ['required', 'email', ['unique', 'email', 'users']],
        ]);

        // action si invalide
        if(!$validator->validate()) {
            Session::addFlash(Session::ERRORS, $validator->errors());
            Session::addFlash(Session::OLD, $_POST);
            $this->redirection('home');
        }

        // si validé
        $user = Auth::get();
        $user->email = $_POST['email'];
        $user->save();

        // status MAJ
        Session::addFlash(Session::STATUS, 'Votre adresse email a été mise à jour !');
        $this->redirection('home');
    }
}