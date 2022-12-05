<?php declare(strict_types=1);

namespace VGuyomarch\Foundation;

// Récupérer les valeurs de configuration qui se trouve dans le dossier config
// le but étant de ne mettre aucune information direct dans des fichiers qui ne sont pas de config
class Config 
{
    public static function get(string $config): mixed
    {
        // 'file.key'
        [$file, $key] = static::getFileAndKey($config);
        $path = static::getPath($file);
        $config = require $path;
        return $config[$key] ?? null;
    }

    protected static function getFileAndKey(string $config): array
    {
        // vérification regex sur la syntaxe du fichier
        if(!preg_match('/^[a-z_]+\.[a-z_]+$/i', $config)) {
            throw new \InvalidArgumentException(
                sprintf('Mauvais format (%s au lieu de fichier.clé (lettres et _ acceptés)', $config)
            );
        }
        // on créer un array quand . est trouvé dans un fichier
        return explode('.', $config);
    }

    protected static function getPath(string $file): string
    {
        $path = sprintf('%s/config/%s.php', ROOT, $file);
        if(!file_exists($path)) {
            throw new \InvalidArgumentException('Le fichier de configuration n\'existe pas');
        }
        return $path;
    }
}