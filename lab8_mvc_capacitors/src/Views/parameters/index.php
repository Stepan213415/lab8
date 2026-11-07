<div class="headline"><div><h1>Параметри конденсаторів</h1>
<p class="muted">Пошук за назвою параметра, його значенням або позначенням конденсатора.</p></div></div>
<form method="get" action="/parameters" class="search">
  <input type="search" name="q" placeholder="Пошук параметра (наприклад, колір, 105°C)..." value="<?= e($query) ?>">
  <button type="submit">Знайти</button>
  <?php if ($query !== ''): ?><a href="/parameters" class="button secondary">Скинути</a><?php endif; ?>
</form>
<div class="panel"><div class="panel-head"><h2>Усі параметри</h2><span class="count">Знайдено: <?= count($parameters) ?></span></div>
  <?php if (!$parameters): ?><p class="muted">Параметрів не знайдено. Додати параметр можна на сторінці конкретного конденсатора.</p>
  <?php else: ?><div class="table-wrap"><table><thead><tr><th>Конденсатор</th><th>Параметр</th><th>Значення</th></tr></thead><tbody>
    <?php foreach ($parameters as $p): ?><tr>
      <td><a href="/capacitors/<?= (int)$p['capacitor_id'] ?>"><?= e($p['designation']) ?></a></td>
      <td><?= e($p['name']) ?></td><td><?= e($p['value']) ?></td>
    </tr><?php endforeach; ?></tbody></table></div><?php endif; ?>
</div>
