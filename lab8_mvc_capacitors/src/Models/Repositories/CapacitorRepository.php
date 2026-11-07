<?php
declare(strict_types=1);
namespace App\Models\Repositories;

use App\Models\Capacitor;
use App\Models\Parameter;
use PDO;

final class CapacitorRepository
{
    private TypeRepository $types;

    public function __construct(private PDO $pdo)
    {
        $this->types = new TypeRepository($pdo);
    }

    public function findAll(string $query = ''): array
    {
        if ($query === '') {
            $rows = $this->pdo->query('SELECT * FROM capacitors ORDER BY id DESC')->fetchAll();
        } else {
            // Parametrized LIKE prevents SQL injection (lab 8 requirement).
            $stmt = $this->pdo->prepare('SELECT * FROM capacitors WHERE designation LIKE :q ORDER BY id DESC');
            $stmt->execute(['q' => '%' . $query . '%']);
            $rows = $stmt->fetchAll();
        }
        return array_map(fn(array $row) => $this->hydrate($row), $rows);
    }

    public function findById(int $id): ?Capacitor
    {
        $stmt = $this->pdo->prepare('SELECT * FROM capacitors WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    public function create(string $designation, int $typeId, float $capacitance, float $voltage): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO capacitors (designation, type_id, capacitance_uf, voltage_v) VALUES (:name, :type, :cap, :voltage)'
        );
        $stmt->execute(['name' => $designation, 'type' => $typeId, 'cap' => $capacitance, 'voltage' => $voltage]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, string $designation, int $typeId, float $capacitance, float $voltage): bool
    {
        $stmt = $this->pdo->prepare('UPDATE capacitors SET designation=:name, type_id=:type, capacitance_uf=:cap, voltage_v=:voltage WHERE id=:id');
        $stmt->execute(['name' => $designation, 'type' => $typeId, 'cap' => $capacitance, 'voltage' => $voltage, 'id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM capacitors WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function attachPhoto(int $id, string $fileName): void
    {
        $stmt = $this->pdo->prepare('UPDATE capacitors SET photo = :photo WHERE id = :id');
        $stmt->execute(['photo' => $fileName, 'id' => $id]);
    }

    public function addParameter(int $capacitorId, string $name, string $value): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO parameters (capacitor_id, name, value) VALUES (:id, :name, :value)');
        $stmt->execute(['id' => $capacitorId, 'name' => $name, 'value' => $value]);
    }

    private function hydrate(array $row): Capacitor
    {
        $type = $this->types->findById((int)$row['type_id']);
        if ($type === null) {
            throw new \RuntimeException('Невідомий тип конденсатора в базі даних');
        }
        $item = new Capacitor((int)$row['id'], $row['designation'], $type, (float)$row['capacitance_uf'], (float)$row['voltage_v']);
        $item->setPhoto($row['photo'] ?? null);
        $stmt = $this->pdo->prepare('SELECT name, value FROM parameters WHERE capacitor_id = :id ORDER BY id');
        $stmt->execute(['id' => $item->getId()]);
        foreach ($stmt->fetchAll() as $r) {
            $item->addParameter(new Parameter($r['name'], $r['value']));
        }
        return $item;
    }
}
