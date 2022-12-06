<?php declare(strict_types=1);

namespace VGuyomarch\Foundation;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

// utilisation de twig, permet une syntaxe particulière et intègre par défaut une sécurité de la faille XSS
// avec htmlspecialchars()

class View
{
    public static function render(string $view, array $data = []): void 
    {
        $view = str_replace('.', '/', $view);
        // verif si une view exist
        if(!static::viewExists($view)) {
            throw new \InvalidArgumentException(
                sprintf('La vue %s n\'existe pas', $view)
            );
        }
        $twig = static::initTwig();
        echo $twig->render(
            sprintf('%s.%s', $view, Config::get('twig.template_extension')), 
            $data
        );
    }

    protected static function viewExists(string $view): bool
    {
        return file_exists(
            sprintf('%s/resources/views/%s.%s', ROOT, $view, Config::get('twig.template_extension'))
        );
    }

    protected static function initTwig(): Environment 
    {
        // indiquer a Twig le path des views
        $loader = new FilesystemLoader(ROOT.'/resources/views');
        $twig = new Environment($loader, [
            // cache permet d'optimiser le rendu d'une view
            'cache' => ROOT.'/cache/twig',
            'auto_reload' => true,
        ]);
        foreach (Config::get('twig.functions') as $helper) {
            $twig->addFunction(new TwigFunction($helper, $helper));
        }
        return $twig;
    }

    // retourner un field html hidden avec un token pour protéger du CSRF
    public static function csrfField(): string
    {
        return sprintf('<input type="hidden" name="_token" value="%s">', Session::get('_token'));
    }
    
    // retourner un field html hidden et spécifier la method http
    public static function method(string $httpMethod): string
    {
        return sprintf('<input type="hidden" name="_method" value="%s">', $httpMethod);
    }

    // récupérer les fields précédemment renseigner et les remettre (pour ne pas tout retaper)
    public static function old(string $key, mixed $default = null): mixed
    {
        $old = Session::getFlash(Session::OLD);
        return $old[$key] ?? $default;
    }
}