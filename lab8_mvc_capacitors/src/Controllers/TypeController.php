<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Auth;
use App\Models\Repositories\TypeRepository;
use PDOException;

final class TypeController extends Controller
{
    private TypeRepository $types;

    public function __construct()
    {
        $this->types = new TypeRepository(db());
    }

    public function index(array $params, ?string $error = null): void
    {
        $q = is_string($_GET['q'] ?? '') ? trim($_GET['q'] ?? '') : '';
        $this->render('types/index', ['types' => $this->types->findAll($q), 'query' => $q, 'error' => $error]);
    }

    public function store(array $params): void
    {
        Auth::requireLogin();
        $name = trim((string)($_POST['name'] ?? ''));
        if ($name === '' || text_length($name) > 50) {
            $this->index([], 'Назва типу обов’язкова (максимум 50 символів).');
            return;
        }
        if ($this->types->nameExists($name)) {
            $this->index([], 'Такий тип уже існує.');
            return;
        }
        try {
            $this->types->create($name);
        } catch (PDOException $ex) {
            if ($ex->getCode() !== '23000') { throw $ex; }
            $this->index([], 'Такий тип уже існує.');
            return;
        }
        $this->redirect('/types');
    }
}
