<?php
declare(strict_types=1);
namespace App;

use App\Models\Repositories\UserRepository;

final class Auth
{
    public static function attempt(string $login, string $password): bool
    {
        $user = (new UserRepository(db()))->findByLogin($login);
        if ($user === null || !password_verify($password, $user->passwordHash)) {
            return false;
        }
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user->id;
        $_SESSION['login'] = $user->login;
        return true;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: /login', true, 303);
            exit;
        }
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires' => time() - 3600,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => $params['samesite'] ?? 'Lax',
            ]);
        }
        session_destroy();
    }
}
