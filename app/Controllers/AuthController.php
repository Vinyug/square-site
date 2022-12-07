<?php declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use VGuyomarch\Foundation\AbstractController;
use VGuyomarch\Foundation\Authentication as Auth;
use VGuyomarch\Foundation\Session;
use VGuyomarch\Foundation\Validator;
use VGuyomarch\Foundation\View;

class AuthController extends AbstractController
{
    // view register
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
        
        // action si fields non renseignés correctement
        if(!$validator->validate()) {
            // récupère le message d'erreur
            Session::addFlash(Session::ERRORS, array_column($validator->errors(), 0));
            // field reprend une valeur déjà renseigné
            Session::addFlash(Session::OLD, $_POST);
            $this->redirection('register.form');
        }

        // insert en BDD
        // hashing du password pour sécurité en BDD
        $user = User::create([
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        ]);

        // authentifier l'user avec un id
        Auth::authenticate($user->id);
        $this->redirection('home');
    } 

    // view login
    public function loginForm(): void
    {
        // si user authenticate redirection vers home
        if(Auth::check()) {
            $this->redirection('home');
        }
        
        View::render('auth.login');
    }
    
    // créer la route
    public function login(): void
    {
        // si user authenticate redirection vers home
        if(Auth::check()) {
            $this->redirection('home');
        }

        // récupération des données de form pour validation
        $validator = Validator::get($_POST);
        // indiquer toutes les règles à respecter par field
        $validator->mapFieldsRules([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // vérifier que les données renseignées sont respectées
        // vérifier en BDD si user exist déjà
        if($validator->validate() && Auth::verify($_POST['email'], $_POST['password'])) {
            $user = User::where('email', $_POST['email'])->first();
            Auth::authenticate($user->id);
            $this->redirection('home');
        }

        // actions si fields mal renseignés
        Session::addFlash(Session::ERRORS, ['Identifiants erronés']);
        Session::addFlash(Session::OLD, $_POST);
        $this->redirection('login.form');
    }

    // gérer deconnexion
    public function logout(): void 
    {
        // si user authenticate deconnect son id session
        if(Auth::check()) {
            Auth::logout();
        }

        $this->redirection('login.form');
    }
}