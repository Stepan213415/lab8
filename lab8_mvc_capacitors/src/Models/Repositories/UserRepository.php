<?php
declare(strict_types=1);
namespace App\Models\Repositories;

use App\Models\User;
use PDO;

final class UserRepository
{
    public function __construct(private PDO $pdo) {}

    public function findByLogin(string $login): ?User
    {
        $stmt = $this->pdo->prepare('SELECT id, login, password_hash FROM users WHERE login = :login');
        $stmt->execute(['login' => $login]);
        $row = $stmt->fetch();
        return $row ? new User((int)$row['id'], $row['login'], $row['password_hash']) : null;
    }

    public function create(string $login, string $password): User
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare('INSERT INTO users (login, password_hash) VALUES (:login, :hash)');
        $stmt->execute(['login' => $login, 'hash' => $hash]);
        return new User((int)$this->pdo->lastInsertId(), $login, $hash);
    }
}
