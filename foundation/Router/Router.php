<?php declare(strict_types=1);

namespace VGuyomarch\Foundation\Router;

use VGuyomarch\Foundation\Exceptions\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Router 
{
    protected RouteCollection $routes;
    protected RequestContext $context;
    protected Request $request;
    protected array $params;
    protected string $controller;
    protected string $method;


    public function __construct(array $routes)
    {
        $this->initCSRF();
        // ajouter les routes qui sont dans $routes au router symfony
        $this->provisionRoutes($routes);
        $this->makeRequestContext();

        // try url et si ne correspond pas alors on renvoi à HttpException
        try {
            [$this->controller, $this->method] = $this->urlMatching();
        } catch (\Exception) {
            HttpException::render();
        }
    }

    // vérification du token sur le site
    protected function initCSRF(): void
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if(!isset($_POST['_token']) || $_POST['_token'] !== $_SESSION['_token']) {
                    throw new HttpException();
                }
            } catch(HttpException) {
                HttpException::render(403, 'Vous ne pouvez pas faire ça !');
            }
        }
    }

    protected function provisionRoutes(array $routes): void
    {
        $this->routes = new RouteCollection();
        foreach ($routes as $key => $route) {
            $this->routes->add($key, $route);
        }
    }

    protected function makeRequestContext(): void
    {
        $this->request = Request::createFromGlobals();
        $this->context = new RequestContext();
        $this->context->fromRequest($this->request);
        
        if(isset($_POST['_method']) && in_array(strtoupper($_POST['_method']), Route::HTTP_METHODS)) {
            $this->context->setMethod($_POST['_method']);
        }
    }

    // permet de trouver url parmi la collection de route
    protected function urlMatching(): array
    {
        $matcher = new UrlMatcher($this->routes, $this->context);
        $this->params = $matcher->match($this->request->getPathInfo());
        
        return [$this->params['_controller'], $this->params['_method']];
    }

    public function getInstance(): void
    {
        $this->cleanParams();
        call_user_func_array([new $this->controller(), $this->method], $this->params);
    }

    protected function cleanParams(): void
    {
        foreach ($this->params as $key => $param) {
            // On veut clean les params qui commence par _
            if(str_starts_with($key, '_')) {
                unset($this->params[$key]);
            }
        }
    }

    public function getGenerator(): UrlGenerator
    {
        return new UrlGenerator($this->routes, $this->context);
    }

    public static function get(string $name, array $data = []): string
    {
        $generator = $GLOBALS['app']->getGenerator();
        $uri = $generator->generate($name, $data);
        return $uri;
    }
}