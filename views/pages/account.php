<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="sticky-top">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item active">アカウント</li>
  </ol>
</nav>
<?= Components::h2("アカウント") ?>

<section id="summary">
  <?= Components::h3("設定"); ?>
  <div style="max-width: 480px;">
    <form action="/account/email" method="post">
      <?= csrf() ?>
      <?= method('PUT') ?>
      <div class="mb-3">
        <label class="form-label">メールアドレス</label>
        <input type="email" name="email" class="form-control" value="<?= Auth::user()["email"] ?>">
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
    <?php foreach ($sendEmails as $sendEmail): ?>
      <div class="card mb-2">
        <div class="card-body">
          <span class="fw-bold me-2"><?= $sendEmail["email"] ?></span>
          <?php if ($sendEmail["enabled"]): ?>
            <span class="badge text-bg-dark">有効</span>
          <?php else: ?>
            <span class="badge text-bg-secondary">無効</span>
          <?php endif; ?>
          <div class="position-absolute top-0 end-0 p-3">
            <a href="/send-emails/<?= $sendEmail["id"] ?>" class="card-link">編集</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
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
        <form action="/account/password" method="post">
          <?= csrf() ?>
          <?= method("PUT") ?>
          <div class="mb-3">
            <label class="form-label">現在のパスワード</label>
            <input type="password" name="old_password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">新しいパスワード</label>
            <input type="password" name="new_password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">新しいパスワード（再入力）</label>
            <input type="password" name="new_password_confirm" class="form-control" required>
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
        <form action="/send-emails" method="post">
          <?= csrf() ?>
          <div class="mb-3">
            <label class="form-label">メールアドレス</label>
            <input type="email" name="email" class="form-control" required>
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