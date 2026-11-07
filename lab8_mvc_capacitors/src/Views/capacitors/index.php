<div class="headline">
  <div>
    <h1>Конденсатори</h1>
    <p class="muted">Список конденсаторів із бази даних. Перегляд і пошук доступні всім.</p>
  </div>
  <?php if ($isLoggedIn): ?>
    <a class="button" href="/capacitors/create">+ Додати конденсатор</a>
  <?php endif; ?>
</div>
<form method="get" action="/capacitors" class="search">
  <input type="search" name="q" value="<?= e($query) ?>" placeholder="Пошук за позначенням (наприклад, C1 або К50)..." aria-label="Пошук конденсатора">
  <button type="submit">Знайти</button>
  <?php if ($query !== ''): ?><a href="/capacitors" class="button secondary">Скинути</a><?php endif; ?>
</form>
<section class="panel">
  <div class="panel-head"><h2>Каталог</h2><span class="count">Знайдено: <?= count($capacitors) ?></span></div>
  <?php if (!$capacitors): ?>
    <p class="muted">Конденсаторів не знайдено. Спробуй інше позначення або додай новий запис після входу.</p>
  <?php else: ?>
    <div class="table-wrap">
    <table><thead><tr><th>Фото</th><th>Позначення</th><th>Тип</th><th>Ємність, мкФ</th><th>Напруга, В</th><th>Дії</th></tr></thead>
      <tbody>
      <?php foreach ($capacitors as $item): ?>
        <tr>
          <td>
            <?php if ($item->getPhoto()): ?>
              <img class="thumb" src="/uploads/<?= e(basename($item->getPhoto())) ?>" alt="Фото <?= e($item->getDesignation()) ?>">
            <?php else: ?><span class="placeholder">⚡</span><?php endif; ?>
          </td>
          <td><strong><?= e($item->getDesignation()) ?></strong></td>
          <td><?= e($item->getType()->getName()) ?></td>
          <td><?= e($item->getCapacitance()) ?></td>
          <td><?= e($item->getVoltage()) ?></td>
          <td><a href="/capacitors/<?= $item->getId() ?>">Деталі</a></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    </div>
  <?php endif; ?>
</section>
<?php if (!$isLoggedIn): ?>
<p class="hint">Щоб додавати, редагувати та видаляти дані, <a href="/login">увійди</a> або <a href="/register">зареєструйся</a>.</p>
<?php endif; ?>
