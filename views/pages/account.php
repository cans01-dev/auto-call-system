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
        <label class="form-label">ステータス</label>
        <div>
          <?php if (Auth::user()["status"] === 0): ?>
              <span class="badge bg-dark-subtle text-black fs-6">一般</span>
            <?php elseif (Auth::user()["status"] === 1): ?>
              <span class="badge bg-dark-subtle text-black fs-6">管理</span>
          <?php endif; ?>
        </div>
      </div>
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

<?= Components::modal("changePasswordModal", "パスワードを変更", <<<EOL
  <form action="/account/password" method="post">
    CSRF
    METHOD_PUT
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
    <div class="form-text">
      パスワードは8文字以上の半角英数字を指定してください
    </div>
  </form>
EOL); ?>

<?= Components::modal("sendEmailsCreateModal", "送信先メールアドレス新規登録", <<<EOL
  <form action="/send-emails" method="post">
    CSRF
    <div class="mb-3">
      <label class="form-label">メールアドレス</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="text-end">
      <button type="submit" class="btn btn-primary">登録</button>
    </div>
  </form>
EOL); ?>

<?php require './views/templates/footer.php'; ?>