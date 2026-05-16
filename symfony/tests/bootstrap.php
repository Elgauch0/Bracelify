<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env.test');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}

$_SERVER['APP_ENV'] = 'test';
$_ENV['APP_ENV'] = 'test';
$_SERVER['APP_DEBUG'] = '0';

// On recrée la base test à chaque run
passthru('php bin/console doctrine:database:drop --force --env=test --if-exists');
passthru('php bin/console doctrine:database:create --env=test');
passthru('php bin/console doctrine:migrations:migrate --no-interaction --env=test');
