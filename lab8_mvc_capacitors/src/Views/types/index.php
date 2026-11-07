<div class="headline"><div><h1>Типи конденсаторів</h1><p class="muted">Пошук за текстовим полем <code>name</code> із SQL LIKE.</p></div></div>
<form class="search" method="get" action="/types">
  <input type="search" name="q" placeholder="Пошук за назвою типу..." value="<?= e($query) ?>">
  <button type="submit">Знайти</button>
  <?php if ($query !== ''): ?><a class="button secondary" href="/types">Скинути</a><?php endif; ?>
</form>
<div class="panel narrow"><div class="panel-head"><h2>Типи</h2><span class="count">Знайдено: <?= count($types) ?></span></div>
  <?php if (!$types): ?><p class="muted">Типів не знайдено.</p><?php endif; ?>
  <ul class="simple-list"><?php foreach ($types as $type): ?><li><span><?= e($type->getName()) ?></span><small>ID: <?= $type->getId() ?></small></li><?php endforeach; ?></ul>
</div>
<?php if ($error): ?><div class="error" role="alert"><?= e($error) ?></div><?php endif; ?>
<?php if ($isLoggedIn): ?>
<div class="panel narrow"><h2>Додати тип</h2>
  <form method="post" action="/types" class="stack compact">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <label>Назва типу <input type="text" name="name" maxlength="50" required placeholder="Електролітичний"></label>
    <button type="submit">Додати тип</button>
  </form>
</div>
<?php else: ?><p class="hint"><a href="/login">Увійди</a>, щоб створювати нові типи.</p><?php endif; ?>
