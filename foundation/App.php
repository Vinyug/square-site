<?php declare(strict_types=1);

namespace VGuyomarch\Foundation;

use Illuminate\Database\Capsule\Manager as Capsule;
use VGuyomarch\Foundation\Exceptions\HttpException;
use VGuyomarch\Foundation\Router\Router;
use Symfony\Component\Routing\Generator\UrlGenerator;

class App {

    protected Router $router;

    // Initialisation des composants (BDD, routes, sessions, PHP dotenv...)
    public function __construct()
    {
        $this->initDotenv();
        // vérifier si la clé d'environnement egale à production, alors on utilise le gestionnaire d'erreur perso (pour client)
        if(Config::get('app.env') === 'production') {
            $this->initProductionExceptionHandler();
        }
        $this->initSession();
        $this->initDatabase();
        $this->router = new Router(require ROOT.'/app/routes.php');
        
    }

    // méthode pour récupérer les variables d'environnement
    protected function initDotenv(): void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(ROOT);
        // empêche erreur en cas d'absence .env
        $dotenv->safeLoad();
    }

    protected function initProductionExceptionHandler(): void
    {
        set_exception_handler(
            fn () => HttpException::render(500, 'Houston, on a un problème! 🚀')
        );
    }
    
    protected function initSession(): void
    {
        Session::init();
        // correction faille CSRF par génération d'un token
        Session::add('_token', Session::get('_token') ?? $this->generateCsrfToken());
    }

    protected function generateCsrfToken(): string
    {
        // convertir bin vers hexa
        $length = Config::get('hashing.csrf_token_length');
        $token = bin2hex(random_bytes($length));
        return $token;
    }

    // initialisation gestion bdd
    protected function initDatabase(): void
    {
        // définir fuseau horaire BDD
        date_default_timezone_set(Config::get('app.timezone'));
        
        $capsule = new Capsule();
        $capsule->addConnection([
            'driver'   => Config::get('database.driver'),
            'host'     => Config::get('database.host'),
            'database' => Config::get('database.name'),
            'username' => Config::get('database.username'),
            'password' => Config::get('database.password'),
        ]);
        // permet de l'utiliser n'importe où
        $capsule->setAsGlobal();
        // Démarrer Eloquent
        $capsule->bootEloquent();
    }

    // méthode pour rendre la réponse et transmettre au client
    public function render(): void 
    {
        // echo 'Hello world';
        $this->router->getInstance();
        Session::resetFlash();
    }

    public function getGenerator(): UrlGenerator
    {
        return $this->router->getGenerator();
    }
}