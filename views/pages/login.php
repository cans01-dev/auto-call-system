<?php require './views/templates/headerLogin.php'; ?>
<main class="w-100 container text-center">
  <div class="row justify-content-center">
    <div class="col form-signin me-5">
      <img src="<?= url("/assets/logos/logo_tate.png") ?>" alt="" style="width: 100%;">
    </div>
    <div class="col form-signin card">
      <form action="/login" method="post">
        <h1 class="h3 mb-3 fw-normal">ログイン</h1>
        <div class="form-floating">
          <input type="email" class="form-control" name="email" placeholder="name@example.com" required>
          <label for="floatingInput">メールアドレス</label>
        </div>
        <div class="form-floating mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password" required>
          <label for="floatingPassword">パスワード</label>
        </div>
        <?= csrf() ?>
        <!-- <input type="hidden" name="redirect" value="<?= @$_SERVER["HTTP_REFERER"] ?? "" ?>"> -->
        <button class="btn btn-primary w-100 py-2" type="submit">ログイン</button>
        <p class="mt-5 mb-3 text-body-secondary">&copy; AutoCallシステム</p>
      </form>
    </div>
  </div>
</main>
<?php require './views/templates/footerLogin.php'; ?>