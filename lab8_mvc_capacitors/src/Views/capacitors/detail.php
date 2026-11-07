<a class="back" href="/capacitors">← Повернутися до списку</a>
<div class="headline">
  <div><h1><?= e($item->getDesignation()) ?></h1><p class="muted">Інформація про конденсатор №<?= $item->getId() ?></p></div>
  <?php if ($isLoggedIn): ?><a class="button" href="/capacitors/<?= $item->getId() ?>/edit">Редагувати</a><?php endif; ?>
</div>
<?php if ($error): ?><div class="error" role="alert"><?= e($error) ?></div><?php endif; ?>
<div class="detail-grid">
<section class="panel">
  <h2>Характеристики</h2>
  <dl class="specs">
    <dt>Позначення</dt><dd><?= e($item->getDesignation()) ?></dd>
    <dt>Тип</dt><dd><?= e($item->getType()->getName()) ?></dd>
    <dt>Ємність</dt><dd><?= e($item->getCapacitance()) ?> мкФ</dd>
    <dt>Напруга</dt><dd><?= e($item->getVoltage()) ?> В</dd>
  </dl>
  <h3>Додаткові параметри</h3>
  <?php if (!$item->getParameters()): ?><p class="muted">Параметри ще не додано.</p><?php endif; ?>
  <ul class="param-list">
    <?php foreach ($item->getParameters() as $param): ?>
      <li><strong><?= e($param->getName()) ?></strong><span><?= e($param->getValue()) ?></span></li>
    <?php endforeach; ?>
  </ul>
</section>
<section class="panel">
  <h2>Фотографія</h2>
  <?php if ($item->getPhoto()): ?>
    <img class="large-photo" src="/uploads/<?= e(basename($item->getPhoto())) ?>" alt="Фото конденсатора">
  <?php else: ?><div class="empty-photo">📷<p>Фото ще не завантажено</p></div><?php endif; ?>
  <?php if ($isLoggedIn): ?>
    <form method="post" enctype="multipart/form-data" action="/capacitors/<?= $item->getId() ?>/photo" class="stack compact">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <label>Завантажити фото (JPG, PNG, WEBP до 2 МБ)
        <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" required>
      </label>
      <button type="submit">Завантажити фотографію</button>
    </form>
  <?php endif; ?>
</section>
</div>
<?php if ($isLoggedIn): ?>
<div class="panel narrow">
  <h2>Додати параметр</h2>
  <form method="post" action="/capacitors/<?= $item->getId() ?>/parameters" class="stack compact">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <label>Назва <input type="text" name="name" maxlength="100" required placeholder="Матеріал діелектрика"></label>
    <label>Значення <input type="text" name="value" maxlength="255" required placeholder="Кераміка"></label>
    <button type="submit">Додати параметр</button>
  </form>
</div>
<div class="danger-zone">
  <form method="post" action="/capacitors/<?= $item->getId() ?>/delete" onsubmit="return confirm('Видалити конденсатор?')">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <button class="danger" type="submit">Видалити конденсатор</button>
  </form>
</div>
<?php else: ?>
<p class="hint">Перегляд доступний усім. Щоб додати фото, змінити дані або додати параметри, <a href="/login">увійди в обліковий запис</a>.</p>
<?php endif; ?>
