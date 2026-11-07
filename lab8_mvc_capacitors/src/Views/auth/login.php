<div class="panel auth-panel">
  <h1>Вхід до системи</h1>
  <p class="muted">Увійди, щоб додавати, редагувати й видаляти конденсатори.</p>
  <?php if (($_GET['registered'] ?? null) === '1'): ?><div class="success">Обліковий запис створено. Тепер увійди.</div><?php endif; ?>
  <?php if ($error): ?><div class="error" role="alert"><?= e($error) ?></div><?php endif; ?>
  <form method="post" action="/login" class="stack">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <label>Логін <input type="text" name="login" maxlength="50" autocomplete="username" required></label>
    <label>Пароль <input type="password" name="password" autocomplete="current-password" required></label>
    <button type="submit">Увійти</button>
  </form>
  <p class="muted">Немає облікового запису? <a href="/register">Зареєструватися</a></p>
</div>
