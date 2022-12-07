<?php declare(strict_types=1);

namespace App\Controllers;

use VGuyomarch\Foundation\AbstractController;
use VGuyomarch\Foundation\Authentication as Auth;
use VGuyomarch\Foundation\Session;
use VGuyomarch\Foundation\Validator;
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
    
    // enregistrer nouveau user
    public function register(): void
    {
        if(Auth::check()) {
            $this->redirection('home');
        }

        // récupération des données de form pour validation
        $validator = Validator::get($_POST);
        // indiquer toutes les règles à respecter par field
        $validator->mapFieldsRules([
            'name' => ['required', ['lengthMin', 5]],
            'email' => ['required', 'email', ['unique', 'email', 'users']],
            'password' => ['required', ['lengthMin', 8], ['equals', 'password_confirmation']],
        ]);
        
        // action si fields non renseigné correctement
        if(!$validator->validate()) {
            // récupère le message d'erreur
            Session::addFlash(Session::ERRORS, array_column($validator->errors, 0));
            // field reprend une valeur déjà renseigné
            Session::addFlash(Session::OLD, $_POST);
            $this->redirection('register.form');
        }
    }
}