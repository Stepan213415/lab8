<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Auth;
use App\Models\Repositories\UserRepository;
use PDOException;

final class AuthController extends Controller
{
    public function showLogin(array $params): void
    {
        if (Auth::check()) { $this->redirect('/capacitors'); }
        $this->render('auth/login', ['error' => null]);
    }

    public function login(array $params): void
    {
        $login = trim((string)($_POST['login'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        if (!Auth::attempt($login, $password)) {
            $this->render('auth/login', ['error' => 'Невірний логін або пароль'], 422);
            return;
        }
        $this->redirect('/capacitors');
    }

    public function showRegister(array $params): void
    {
        if (Auth::check()) { $this->redirect('/capacitors'); }
        $this->render('auth/register', ['error' => null]);
    }

    public function register(array $params): void
    {
        $login = trim((string)($_POST['login'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        if (!preg_match('/^[A-Za-z0-9_.-]{3,50}$/D', $login)) {
            $this->render('auth/register', ['error' => 'Логін: 3–50 латинських літер, цифр або символів . _ -'], 422);
            return;
        }
        if (strlen($password) < 6 || strlen($password) > 72) {
            $this->render('auth/register', ['error' => 'Пароль повинен містити від 6 до 72 символів'], 422);
            return;
        }
        $repo = new UserRepository(db());
        if ($repo->findByLogin($login) !== null) {
            $this->render('auth/register', ['error' => 'Такий логін уже зайнятий'], 422);
            return;
        }
        try {
            $repo->create($login, $password);
        } catch (PDOException $ex) {
            if ($ex->getCode() !== '23000') { throw $ex; }
            $this->render('auth/register', ['error' => 'Такий логін уже зайнятий'], 422);
            return;
        }
        $this->redirect('/login?registered=1');
    }

    public function logout(array $params): void
    {
        Auth::logout();
        $this->redirect('/capacitors');
    }
}
