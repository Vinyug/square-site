<?php declare(strict_types=1);

namespace VGuyomarch\Foundation;

use Illuminate\Database\Capsule\Manager as Capsule;
use Valitron\Validator as ValitronValidator;

// Utilisation de valitron pour simplifier la validation de données (formulaire)
class Validator
{
    public static function get(array $data): ValitronValidator
    {
        $validator = new ValitronValidator(
            data: $data, 
            lang: 'fr'
        );
        $validator->labels(require ROOT.'/resources/lang/validation.php');
        static::addCustomRules($validator);
        return $validator;
    }

    protected static function addCustomRules(ValitronValidator $validator): void
    {
        // règle valeur unique d'un champ
        $validator->addRule('unique', function (string $field, mixed $value, array $params, array $fields) {
            // requete return un bool pour vérifier si value exist dans BDD
            return !Capsule::table($params[1])->where($params[0], $value)->exists();
        }, '{field} est invalide');
        
        // règle password en cas d'update password
        $validator->addRule('password', function (string $field, mixed $value, array $params, array $fields) {
            // vérifier password BDD = password saisi
            $user = Authentication::get();
            return password_verify($value, $user->password);
        }, '{field} est erroné');
        
        // règle pour vérifier si le fichier est présent et s'il n'y a pas eu d'erreur d'upload
        $validator->addRule('required_file', function (string $field, mixed $value, array $params, array $fields) {
            return isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK;
        }, '{field} est obligatoire');
        
        // règle pour vérifier qu'il s'agit d'une image (à partir du type mime)
        $validator->addRule('image', function (string $field, mixed $value, array $params, array $fields) {
            if(isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                return str_starts_with($_FILES[$field]['type'], 'image/');
            }
            return false;
        }, '{field} doit être une image');

        // règle vérifier si image est carré
        $validator->addRule('square', function (string $field, mixed $value, array $params, array $fields) {
            if(isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                [$width, $height] = getimagesize($_FILES[$field]['tmp_name']);
                return $width === $height;
            }
            return false;
        }, '{field} doit être carré (largeur = hauteur)');
    }
}