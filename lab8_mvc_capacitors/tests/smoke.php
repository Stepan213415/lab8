<?php
declare(strict_types=1);
require dirname(__DIR__) . '/bootstrap.php';

use App\Router;
use App\Models\Capacitor;
use App\Models\CapacitorType;
use App\Models\Parameter;

function assertEqual(mixed $expected, mixed $actual, string $label): void
{
    if ($expected !== $actual) {
        throw new RuntimeException("FAIL: $label (" . var_export($actual, true) . ")");
    }
    echo "OK: {$label}\n";
}

$router = new Router();
$found = null;
$router->add('GET', '/capacitors/{id}', function (array $params) use (&$found): void {
    $found = $params['id'];
});
$router->dispatch('GET', '/capacitors/42?x=1');
assertEqual('42', $found, 'Router dynamic {id} parameter');

$type = new CapacitorType(3, 'Керамічний');
$item = new Capacitor(7, 'К10-17', $type, 0.1, 50.0);
$item->addParameter(new Parameter('Діелектрик', 'кераміка'));
assertEqual('К10-17', $item->toArray()['designation'], 'Capacitor model');
assertEqual('кераміка', $item->toArray()['parameters'][0]['value'], 'Parameter model');

assertEqual('&lt;script&gt;', e('<script>'), 'Escaping user strings');
assertEqual(true, strlen(csrf_token()) === 64, 'CSRF token exists');

$hash = password_hash('example-password', PASSWORD_DEFAULT);
assertEqual(true, password_verify('example-password', $hash), 'Secure password hashing');
assertEqual(false, password_verify('incorrect', $hash), 'Invalid password is rejected');
echo "All smoke checks passed (these checks do not require MySQL).\n";
