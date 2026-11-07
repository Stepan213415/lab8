<?php
declare(strict_types=1);
namespace App;

final class Router
{
    private array $routes = [];

    public function add(string $method, string $pattern, callable $handler): void
    {
        $this->routes[] = [strtoupper($method), $pattern, $handler];
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        foreach ($this->routes as [$routeMethod, $pattern, $handler]) {
            if ($routeMethod !== strtoupper($method)) {
                continue;
            }
            $regex = preg_replace('/\{([a-zA-Z_][a-zA-Z_0-9]*)\}/', '(?P<$1>[^/]+)', $pattern);
            if (preg_match('#^' . $regex . '$#D', $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $handler($params);
                return;
            }
        }
        http_response_code(404);
        echo '404 — Сторінку не знайдено';
    }
}
