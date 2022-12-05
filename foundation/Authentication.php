<?php declare(strict_types=1);

namespace VGuyomarch\Foundation;

use App\Models\User;

// verification d'authentification user, si visiteur ou enregistré
class Authentication
{
    protected const SESSION_ID = 'user_id';

    // si user connecté
    public static function check(): bool
    {
        // operateur de cast (type) : on récupère l'équivalent de l'expression qui suit
        return (bool) Session::get(static::SESSION_ID);
    }

    // si user est admin
    public static function checkIsAdmin(): bool
    {
        // verif si connecté et si dans la BDD le role du user dispose de droit d'admin
        return static::check() && static::get()->role === 'admin';
    }
    
    // vérifier email et mdp dans form sont bien dans la bdd
    public static function verify(string $email, string $password): bool
    {
        // requete avec clause Where
        $user = User::where('email', $email)->first();
        // condition sur password où l'on vérifie le password du champ et celui de la bdd hashé
        return $user && password_verify($password, $user->password);
    }

    // créer une variable de session
    public static function authenticate(int $id): void
    {
        Session::add(static::SESSION_ID, $id);
    }

    // déconnexion de l'user
    public static function logout(): void
    {
        Session::remove(static::SESSION_ID);
    }

    // récupérer les informations d'une session
    // ?User : soit on récupère Null soit int
    public static function id(): ?int
    {
        return Session::get(static::SESSION_ID);
    }

    // récupérer les infos sur l'user actuellement authentifier
    public static function get(): ?User
    {
        return User::find(static::id());
    }
}