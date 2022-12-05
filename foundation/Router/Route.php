<?php declare(strict_types=1);

namespace VGuyomarch\Foundation\Router;

use VGuyomarch\Foundation\AbstractController;
use Symfony\Component\Routing\Route as SymfonyRoute;

class Route
{
    // indiquer toutes les méthodes http que l'on autorise
    // HEAD : comme GET mais uniquement les en-tête de réponse
    // PUT = modifier une ressources 
    // PATCH = MAJ d'une ressource
    public const HTTP_METHODS = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE'];

    public static function __callStatic(string $httpMethod, array $arguments): SymfonyRoute
    {
        // Vérifier si la méthode static est une méthode acceptée dans HTTP_METHODS
        if(!in_array(strtoupper($httpMethod), static::HTTP_METHODS)) {
            throw new \BadMethodCallException(
                sprintf('Méthode HTTP indisponible (%s)', $httpMethod)
            );
        }
        [$uri, $action] = $arguments;
        return static::make($uri, $action, $httpMethod);
    }

    // faire la route
    protected static function make(string $uri, array $action, string $httpMethod): SymfonyRoute
    {
        [$controller, $method] = $action;

        // verifier si l'action récupérer est valide, s'il existe bien une méthode qui correspond
        if(!static::checkIfActionExists($controller, $method)) {
            throw new \InvalidArgumentException(
                sprintf('L\'action n\'existe pas (%s)', implode(', ', $action))
            );
        }
        return new SymfonyRoute($uri, [
            '_controller' => $controller,
            '_method' => $method,
        ], 
        methods: [$httpMethod],
        options: [
            'utf8' => true,
        ]);
    }

    protected static function checkIfActionExists(string $controller, string $method): bool
    {
        return class_exists($controller) && is_subclass_of($controller, AbstractController::class) && method_exists($controller, $method);
    }
}

