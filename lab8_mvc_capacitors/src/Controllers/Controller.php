<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Auth;

abstract class Controller
{
    // Відображення сторінки
    protected function render(
        string $view,
        array $data = [],
        int $status = 200
    ): void {
        http_response_code($status);

        // Перевірка авторизації користувача
        $isLoggedIn = Auth::check();

        // Передача даних у подання
        extract($data, EXTR_SKIP);

        // Формування HTML сторінки
        ob_start();

        require __DIR__ . '/../Views/' . $view . '.php';

        $content = ob_get_clean();

        // Підключення головного шаблону
        require __DIR__ . '/../Views/layout.php';
    }

    // Перенаправлення користувача
    protected function redirect(string $uri): never
    {
        header('Location: ' . $uri, true, 303);
        exit;
    }

    // Обробка помилки 404
    protected function notFound(
        string $description = 'Запис не знайдено'
    ): void {
        $this->render(
            'error',
            ['error' => $description],
            404
        );
    }
}
