<?php declare(strict_types=1);

namespace VGuyomarch\Foundation;

use Symfony\Component\Routing\Generator\UrlGenerator;
use VGuyomarch\Foundation\Exceptions\HttpException;
use VGuyomarch\Foundation\Router\Router;

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