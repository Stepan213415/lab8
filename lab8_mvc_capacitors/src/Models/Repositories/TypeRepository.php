<?php
declare(strict_types=1);
namespace App\Models\Repositories;

use App\Models\CapacitorType;
use PDO;

final class TypeRepository
{
    public function __construct(private PDO $pdo) {}

    public function findAll(string $query = ''): array
    {
        if ($query === '') {
            $rows = $this->pdo->query('SELECT * FROM capacitor_types ORDER BY name')->fetchAll();
        } else {
            $stmt = $this->pdo->prepare('SELECT * FROM capacitor_types WHERE name LIKE :q ORDER BY name');
            $stmt->execute(['q' => '%' . $query . '%']);
            $rows = $stmt->fetchAll();
        }
        return array_map(fn(array $r) => new CapacitorType((int)$r['id'], $r['name']), $rows);
    }

    public function findById(int $id): ?CapacitorType
    {
        $stmt = $this->pdo->prepare('SELECT * FROM capacitor_types WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? new CapacitorType((int)$row['id'], $row['name']) : null;
    }

    public function nameExists(string $name): bool
    {
        $stmt = $this->pdo->prepare('SELECT 1 FROM capacitor_types WHERE name = :name LIMIT 1');
        $stmt->execute(['name' => $name]);
        return $stmt->fetchColumn() !== false;
    }

    public function create(string $name): CapacitorType
    {
        $stmt = $this->pdo->prepare('INSERT INTO capacitor_types (name) VALUES (:name)');
        $stmt->execute(['name' => $name]);
        return new CapacitorType((int)$this->pdo->lastInsertId(), $name);
    }
}
