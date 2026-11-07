<?php
declare(strict_types=1);
require dirname(__DIR__) . '/bootstrap.php';

use App\Router;
use App\Controllers\AuthController;
use App\Controllers\CapacitorController;
use App\Controllers\ParameterController;
use App\Controllers\TypeController;

try {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        verify_csrf();
    }
    $router = new Router();
    $c = new CapacitorController();
    $t = new TypeController();
    $p = new ParameterController();
    $auth = new AuthController();

    $router->add('GET', '/', [$c, 'index']);
    $router->add('GET', '/capacitors', [$c, 'index']);
    $router->add('GET', '/capacitors/create', [$c, 'createForm']);
    $router->add('POST', '/capacitors', [$c, 'store']);
    $router->add('GET', '/capacitors/{id}', [$c, 'detail']);
    $router->add('GET', '/capacitors/{id}/edit', [$c, 'editForm']);
    $router->add('POST', '/capacitors/{id}/update', [$c, 'update']);
    $router->add('POST', '/capacitors/{id}/delete', [$c, 'delete']);
    $router->add('POST', '/capacitors/{id}/photo', [$c, 'uploadPhoto']);
    $router->add('POST', '/capacitors/{id}/parameters', [$c, 'addParameter']);

    $router->add('GET', '/types', [$t, 'index']);
    $router->add('POST', '/types', [$t, 'store']);
    $router->add('GET', '/parameters', [$p, 'index']);

    $router->add('GET', '/login', [$auth, 'showLogin']);
    $router->add('POST', '/login', [$auth, 'login']);
    $router->add('GET', '/register', [$auth, 'showRegister']);
    $router->add('POST', '/register', [$auth, 'register']);
    $router->add('POST', '/logout', [$auth, 'logout']);

    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Throwable $ex) {
    // Hide credentials and SQL from visitors. Check the server console for details.
    error_log((string)$ex);
    http_response_code(500);
    echo '<!doctype html><html lang="uk"><meta charset="utf-8"><title>Помилка</title>'
        . '<body style="font:16px Arial;max-width:720px;margin:50px auto">'
        . '<h1>Не вдалося відкрити застосунок</h1>'
        . '<p>Перевірте запуск MySQL, імпорт database/schema.sql та налаштування DB_HOST, DB_NAME, DB_USER, DB_PASSWORD.</p>'
        . '<p>Деталі помилки — у консолі PHP.</p></body></html>';
}
