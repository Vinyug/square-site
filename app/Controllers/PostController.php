<?php declare(strict_types=1);

namespace App\Controllers;

use App\Models\Post;
use Cocur\Slugify\Slugify;
use VGuyomarch\Foundation\AbstractController;
use VGuyomarch\Foundation\Authentication as Auth;
use VGuyomarch\Foundation\Session;
use VGuyomarch\Foundation\Validator;
use VGuyomarch\Foundation\View;

class PostController extends AbstractController
{
    // afficher view
    public function create(): void
    {
        if(!Auth::checkIsAdmin()) {
            $this->redirection('login.form');
        }

        View::render('posts.create');
    }

    // enregistrer un nouveau post
    public function store(): void
    {
        if(!Auth::checkIsAdmin()) {
            $this->redirection('login.form');
        }

        // règle Validator
        // le + permet de fusionner les arrays des superglobals
        $validator = Validator::get($_POST + $_FILES);
        $validator->mapFieldsRules([
            'title' => ['required', ['lengthMin', 3]],
            'post' => ['required', ['lengthMin', 3]],
            'file' => ['required_file', 'image', 'square'],
            'body' => ['required', ['lengthMin', 3]],
        ]);

        // action si invalide
        if(!$validator->validate()) {
            Session::addFlash(Session::ERRORS, $validator->errors());
            Session::addFlash(Session::OLD, $_POST);
            $this->redirection('posts.create');
        }

        // Action si validé
        $slug = $this->slugify($_POST['title']);
        // récupère l'extension de l'image
        $ext = pathinfo(
            $_FILES['file']['name'],
            PATHINFO_EXTENSION
        );
        // utilisation du slug pour renommer l'image
        $filename = sprintf('%s.%s', $slug, $ext);

        // enregistrer le fichier en BDD
        if(!move_uploaded_file(
            $_FILES['file']['tmp_name'],
            sprintf('%s/public/img/%s', ROOT, $filename)
        )) {
            Session::addFlash(Session::ERRORS, ['file' => [
                'Il y a eu un problème lors de l\'envoi. Retentez votre chance !',
            ]]);
            Session::addFlash(Session::OLD, $_POST);
            $this->redirection('posts.create');
        }

        // Insert un post
        $post = Post::create([
            'users_id' => Auth::id(),
            'title' => $_POST['title'],
            'slug' => $slug,
            'body' => $_POST['post'], 
            'reading_time' => ceil(str_word_count($_POST['post']) / 238), 
            'img' => $filename,
        ]);

        // status MAJ
        Session::addFlash(Session::STATUS, 'Votre post a été publié !');
        // redirection vers posts.show
        // code en attente
    }

    // creation slug article avec cocur/slugify
    public function slugify(string $title): string 
    {
        $slugify = new Slugify();
        $slug = $slugify->slugify($title);
        // permettre d'avoir slug unique, si plusieurs titre en BDD sont identiques
        $i = 1;
        $unique_slug = $slug;
        while(Post::where('slug', $unique_slug)->exists()) {
            $unique_slug = sprintf('%s-%s', $slug, $i++);
        }
        return $unique_slug;
    }

}