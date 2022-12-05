<?php declare(strict_types=1);

namespace VGuyomarch\Foundation\Exceptions;

use VGuyomarch\Foundation\View;

class HttpException extends \Exception 
{
    // afficher une erreur HTTP destiné au client
    public static function render(int $httpCode = 404, string $message = 'Page non trouvé'): void
    {
        http_response_code($httpCode);
        View::render('errors.default', [
            'httpCode' => $httpCode,
            'message' => $message
        ]);
        die;
    }
}