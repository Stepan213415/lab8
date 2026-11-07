<!doctype html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Лабораторна №8 — Конденсатори MVC</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header class="topbar">
  <div class="wrap nav">
    <a class="brand" href="/capacitors">⚡ Каталог конденсаторів <span>MVC</span></a>
    <nav aria-label="Навігація">
      <a href="/capacitors">Конденсатори</a>
      <a href="/types">Типи</a>
      <a href="/parameters">Параметри</a>
    </nav>
    <div class="authlinks">
      <?php if ($isLoggedIn): ?>
        <span class="login-name">👤 <?= e($_SESSION['login'] ?? '') ?></span>
        <form method="post" action="/logout" class="inline">
          <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
          <button type="submit" class="ghost">Вийти</button>
        </form>
      <?php else: ?>
        <a href="/login">Увійти</a>
        <a class="button small" href="/register">Реєстрація</a>
      <?php endif; ?>
    </div>
  </div>
</header>
<main class="wrap">
  <div class="eyebrow">Лабораторна робота №8 · PHP · MySQL · MVC</div>
  <?= $content ?>
</main>
<footer class="wrap foot">MVC · Router · SQL LIKE · PDO · Sessions · password_hash()</footer>
</body>
</html>
