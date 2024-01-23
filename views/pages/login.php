<?php require './views/templates/headerLogin.php'; ?>
<main class="form-signin w-100 m-auto">
  <form action="/login" method="post">
    <h1 class="h3 mb-3 fw-normal">ログインしてください</h1>
    <div class="form-floating">
      <input type="email" class="form-control" name="email" placeholder="name@example.com">
      <label for="floatingInput">メールアドレス</label>
    </div>
    <div class="form-floating mb-3">
      <input type="password" class="form-control" name="password" placeholder="Password">
      <label for="floatingPassword">パスワード</label>
    </div>
    <?= csrf() ?>
    <button class="btn btn-primary w-100 py-2" type="submit">Sign in</button>
    <p class="mt-5 mb-3 text-body-secondary">&copy; cans 2024</p>
  </form>
</main>
<?php require './views/templates/footerLogin.php'; ?>