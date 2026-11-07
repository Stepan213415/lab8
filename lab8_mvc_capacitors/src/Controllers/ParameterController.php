<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Models\Repositories\ParameterRepository;

final class ParameterController extends Controller
{
    private ParameterRepository $parameters;

    public function __construct()
    {
        $this->parameters = new ParameterRepository(db());
    }

    public function index(array $params): void
    {
        $q = is_string($_GET['q'] ?? '') ? trim($_GET['q'] ?? '') : '';
        $this->render('parameters/index', ['parameters' => $this->parameters->findAll($q), 'query' => $q]);
    }
}
