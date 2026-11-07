<div class="panel auth-panel">
  <h1>Реєстрація</h1>
  <p class="muted">Паролі зберігаються у вигляді хешів <code>password_hash()</code>, а не як звичайний текст.</p>
  <?php if ($error): ?><div class="error" role="alert"><?= e($error) ?></div><?php endif; ?>
  <form method="post" action="/register" class="stack">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <label>Логін (3–50 латинських символів) <input type="text" name="login" minlength="3" maxlength="50" autocomplete="username" pattern="[A-Za-z0-9_.-]{3,50}" required></label>
    <label>Пароль (мінімум 6 символів) <input type="password" name="password" minlength="6" maxlength="72" autocomplete="new-password" required></label>
    <button type="submit">Зареєструватися</button>
  </form>
  <p class="muted">Вже є обліковий запис? <a href="/login">Увійти</a></p>
</div>
