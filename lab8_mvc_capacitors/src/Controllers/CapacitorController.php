<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Auth;
use App\Models\Capacitor;
use App\Models\Repositories\CapacitorRepository;
use App\Models\Repositories\TypeRepository;

final class CapacitorController extends Controller
{
    private CapacitorRepository $capacitors;
    private TypeRepository $types;

    public function __construct()
    {
        $pdo = db();
        $this->capacitors = new CapacitorRepository($pdo);
        $this->types = new TypeRepository($pdo);
    }

    public function index(array $params): void
    {
        $q = is_string($_GET['q'] ?? '') ? trim($_GET['q'] ?? '') : '';
        $this->render('capacitors/index', ['capacitors' => $this->capacitors->findAll($q), 'query' => $q]);
    }

    public function detail(array $params, ?string $error = null): void
    {
        $item = $this->capacitors->findById((int)$params['id']);
        if (!$item) { $this->notFound('Конденсатор не знайдено'); return; }
        $this->render('capacitors/detail', ['item' => $item, 'error' => $error]);
    }

    public function createForm(array $params): void
    {
        Auth::requireLogin();
        $this->renderForm(null);
    }

    public function store(array $params): void
    {
        Auth::requireLogin();
        [$data, $error] = $this->validate($_POST);
        if ($error !== null) { $this->renderForm(null, $error); return; }
        $id = $this->capacitors->create(...$data);
        $this->redirect('/capacitors/' . $id);
    }

    public function editForm(array $params): void
    {
        Auth::requireLogin();
        $item = $this->capacitors->findById((int)$params['id']);
        if (!$item) { $this->notFound(); return; }
        $this->renderForm($item);
    }

    public function update(array $params): void
    {
        Auth::requireLogin();
        $id = (int)$params['id'];
        $item = $this->capacitors->findById($id);
        if (!$item) { $this->notFound(); return; }
        [$data, $error] = $this->validate($_POST);
        if ($error !== null) { $this->renderForm($item, $error); return; }
        $this->capacitors->update($id, ...$data);
        $this->redirect('/capacitors/' . $id);
    }

    public function delete(array $params): void
    {
        Auth::requireLogin();
        $this->capacitors->delete((int)$params['id']);
        $this->redirect('/capacitors');
    }

    public function addParameter(array $params): void
    {
        Auth::requireLogin();
        $id = (int)$params['id'];
        if (!$this->capacitors->findById($id)) { $this->notFound(); return; }
        $name = trim((string)($_POST['name'] ?? ''));
        $value = trim((string)($_POST['value'] ?? ''));
        if ($name === '' || $value === '' || text_length($name) > 100 || text_length($value) > 255) {
            $this->detail(['id' => $id], 'Введіть назву (до 100 символів) та значення (до 255 символів).');
            return;
        }
        $this->capacitors->addParameter($id, $name, $value);
        $this->redirect('/capacitors/' . $id);
    }

    public function uploadPhoto(array $params): void
    {
        Auth::requireLogin();
        $id = (int)$params['id'];
        if (!$this->capacitors->findById($id)) { $this->notFound(); return; }
        $file = $_FILES['photo'] ?? null;
        if (!is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            $this->detail(['id' => $id], 'Не вдалося отримати фотографію.');
            return;
        }
        $temp = $file['tmp_name'] ?? '';
        if (($file['size'] ?? 0) > 2 * 1024 * 1024 || !is_uploaded_file($temp)) {
            $this->detail(['id' => $id], 'Максимальний розмір фотографії — 2 МБ.');
            return;
        }
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($temp);
        if (!isset($allowed[$mime]) || @getimagesize($temp) === false) {
            $this->detail(['id' => $id], 'Потрібне зображення у форматі JPG, PNG або WEBP.');
            return;
        }
        $fileName = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
        $destination = __DIR__ . '/../../public/uploads/' . $fileName;
        if (!move_uploaded_file($temp, $destination)) {
            $this->detail(['id' => $id], 'Не вдалося записати файл у public/uploads.');
            return;
        }
        $this->capacitors->attachPhoto($id, $fileName);
        $this->redirect('/capacitors/' . $id);
    }

    private function renderForm(?Capacitor $item, ?string $error = null): void
    {
        $this->render('capacitors/form', ['item' => $item, 'types' => $this->types->findAll(), 'error' => $error], $error ? 422 : 200);
    }

    /** @return array{array{string,int,float,float},?string} */
    private function validate(array $post): array
    {
        $name = trim((string)($post['designation'] ?? ''));
        $typeId = filter_var($post['type_id'] ?? null, FILTER_VALIDATE_INT) ?: 0;
        $capacitance = filter_var($post['capacitance'] ?? null, FILTER_VALIDATE_FLOAT);
        $voltage = filter_var($post['voltage'] ?? null, FILTER_VALIDATE_FLOAT);
        if ($name === '' || text_length($name) > 50) {
            return [[], 'Позначення обов’язкове (максимум 50 символів).'];
        }
        if (!$this->types->findById($typeId)) {
            return [[], 'Вибери наявний тип конденсатора.'];
        }
        if ($capacitance === false || $capacitance <= 0 || $capacitance > 999999) {
            return [[], 'Ємність має бути додатним числом не більшим за 999999 мкФ.'];
        }
        if ($voltage === false || $voltage <= 0 || $voltage > 9999999) {
            return [[], 'Напруга має бути додатним числом не більшим за 9999999 В.'];
        }
        return [[$name, $typeId, (float)$capacitance, (float)$voltage], null];
    }
}
