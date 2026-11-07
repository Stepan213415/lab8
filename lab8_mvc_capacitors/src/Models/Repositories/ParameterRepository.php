<?php
declare(strict_types=1);
namespace App\Models\Repositories;

use PDO;

final class ParameterRepository
{
    public function __construct(private PDO $pdo) {}

    public function findAll(string $query = ''): array
    {
        $sql = 'SELECT p.id, p.name, p.value, p.capacitor_id, c.designation FROM parameters p '
             . 'JOIN capacitors c ON c.id = p.capacitor_id';
        if ($query !== '') {
            $stmt = $this->pdo->prepare($sql . ' WHERE p.name LIKE :q1 OR p.value LIKE :q2 OR c.designation LIKE :q3 ORDER BY p.id DESC');
            $like = '%' . $query . '%';
            $stmt->execute(['q1' => $like, 'q2' => $like, 'q3' => $like]);
            return $stmt->fetchAll();
        }
        return $this->pdo->query($sql . ' ORDER BY p.id DESC')->fetchAll();
    }
}
