<?php $editing = $item !== null; ?>
<div class="headline"><div>
  <a class="back" href="<?= $editing ? '/capacitors/' . $item->getId() : '/capacitors' ?>">← Повернутися</a>
  <h1><?= $editing ? 'Редагувати конденсатор' : 'Новий конденсатор' ?></h1>
  <p class="muted">Дані зберігаються в MySQL через PDO-репозиторій.</p>
</div></div>
<div class="panel narrow">
  <?php if ($error): ?><div class="error" role="alert"><?= e($error) ?></div><?php endif; ?>
  <?php if (!$types): ?><p class="error">Немає типів. Спочатку <a href="/types">створи тип конденсатора</a>.</p><?php endif; ?>
  <form method="post" action="<?= $editing ? '/capacitors/' . $item->getId() . '/update' : '/capacitors' ?>" class="stack">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <label>Позначення
      <input type="text" name="designation" maxlength="50" required value="<?= e($_POST['designation'] ?? ($editing ? $item->getDesignation() : '')) ?>" placeholder="Наприклад, К50-35">
    </label>
    <label>Тип конденсатора
      <select name="type_id" required>
        <option value="">— Обери тип —</option>
        <?php foreach ($types as $type): ?>
          <option value="<?= $type->getId() ?>" <?= (string)($_POST['type_id'] ?? ($editing ? $item->getType()->getId() : '')) === (string)$type->getId() ? 'selected' : '' ?>><?= e($type->getName()) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <div class="columns">
      <label>Ємність (мкФ)
        <input type="number" name="capacitance" min="0.000001" max="999999" step="any" required value="<?= e($_POST['capacitance'] ?? ($editing ? $item->getCapacitance() : '')) ?>" placeholder="47">
      </label>
      <label>Напруга (В)
        <input type="number" name="voltage" min="0.01" max="9999999" step="any" required value="<?= e($_POST['voltage'] ?? ($editing ? $item->getVoltage() : '')) ?>" placeholder="25">
      </label>
    </div>
    <button type="submit" <?= !$types ? 'disabled' : '' ?>><?= $editing ? 'Зберегти зміни' : 'Додати конденсатор' ?></button>
  </form>
</div>
