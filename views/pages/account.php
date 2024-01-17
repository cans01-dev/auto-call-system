<?php require './views/templates/header.php'; ?>

<?= Components::h2("アカウント") ?>

<section id="summary">
  <?= Components::h3("設定"); ?>
  <div style="max-width: 480px;">
    <form action="/surveys" method="post">
      <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">メールアドレス</label>
        <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="foobar@example.com">
      </div>
      <div class="mb-3">
        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
          パスワードを変更する
        </button>
      </div>
      <div class="text-end">
        <button type="submit" class="btn btn-dark">更新</button>
      </div>
    </form>
  </div>
</section>
<?= Components::hr() ?>
<section id="sendEmails">
  <?= Components::h3("送信先メールアドレス") ?>
  <div style="max-width: 480px;">
    <?php for ($i = 0; $i < 3; $i++): ?>
      <div class="card mb-2">
        <div class="card-body">
          <span class="fw-bold me-2">test@test.com</span><span class="badge text-bg-dark">有効</span>
          <div class="position-absolute top-0 end-0 p-3">
            <a href="/send-emails/<?= $i ?>" class="card-link">編集</a>
          </div>
        </div>
      </div>
    <?php endfor; ?>
    <?= Components::modalOpenButton("sendEmailsCreateModal"); ?>
  </div>
</section>

<!-- changePasswordModal -->
<div class="modal fade" id="changePasswordModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">パスワードを変更</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/options" method="post">
          <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">現在のパスワード</label>
            <input type="password" class="form-control" id="exampleFormControlInput1">
          </div>
          <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">新しいパスワード</label>
            <input type="password" class="form-control" id="exampleFormControlInput1">
          </div>
          <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">新しいパスワード（再入力）</label>
            <input type="password" class="form-control" id="exampleFormControlInput1">
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-primary">更新</button>
          </div>
        </form>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- sendEmailsCreateModal -->
<div class="modal fade" id="sendEmailsCreateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">送信先メールアドレス新規登録</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/options" method="post">
          <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">メールアドレス</label>
            <input type="email" class="form-control" id="exampleFormControlInput1">
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-primary">登録</button>
          </div>
        </form>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require './views/templates/footer.php'; ?>