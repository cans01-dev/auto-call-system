<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item active">アカウント</li>
  </ol>
</nav>
<?= Components::h2("アカウント") ?>
<div class="d-flex gap-3">
  <div class="w-100">
    <section id="summary">
      <?= Components::h3("設定"); ?>
      <div style="max-width: 480px;">
        <form action="/users/<?= $user["id"] ?>" method="post">
          <?= csrf() ?>
          <?= method('PUT') ?>
          <div class="mb-3">
            <label class="form-label">ステータス</label>
            <div>
              <span class="badge fs-6 bg-<?= USER_STATUS[$user["status"]]["bg"] ?>">
                <?= USER_STATUS[$user["status"]]["text"]; ?>
              </span>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">ユーザー名</label>
            <input type="text" name="name" class="form-control" value="<?= $user["name"] ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">メールアドレス</label>
            <input type="email" name="email" class="form-control" value="<?= $user["email"] ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">回線数</label>
            <input type="numbers" name="number_of_lines" class="form-control" value="<?= $user["number_of_lines"] ?>" disabled>
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
  </div>
  <div class="flex-shrink-0 sticky-aside" style="width: 300px;">
    <section id="sendEmails">
      <?= Components::h3("送信先メールアドレス") ?>
      <div style="max-width: 480px;">
        <?php if ($user["sendEmails"]): ?>
          <?php foreach ($user["sendEmails"] as $sendEmail): ?>
            <div class="card mb-2">
              <div class="card-body">
                <span class="fw-bold me-2"><?= $sendEmail["email"] ?></span>
                <?php if ($sendEmail["enabled"]): ?>
                  <span class="badge text-bg-dark">有効</span>
                <?php else: ?>
                  <span class="badge text-bg-secondary">無効</span>
                <?php endif; ?>
                <div class="position-absolute top-0 end-0 p-3">
                  <button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#editSendEmail<?= $sendEmail["id"] ?>Modal">
                    編集
                  </button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <?= Components::noContent("送信先メールがありません") ?>
        <?php endif; ?>
        <?= Components::modalOpenButton("sendEmailsCreateModal"); ?>
      </div>
    </section>
  </div>
</div>

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
  <form action="/users/{$user["id"]}/send-emails" method="post">
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

<?php foreach ($user["sendEmails"] as $sendEmail): ?>
  <div class="modal fade" id="editSendEmail<?= $sendEmail["id"] ?>Modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel"><?= $sendEmail["email"] ?></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/send-emails/<?= $sendEmail["id"] ?>" method="post">
            <?= csrf() ?>
            <?= method("PUT") ?>
            <div class="mb-3">
              <label class="form-label">メールアドレス</label>
              <input type="email" name="email" class="form-control" value="<?= $sendEmail["email"] ?>">
            </div>
            <div class="form-check form-switch mb-3">
              <label class="form-label">無効 / 有効</label>
              <input class="form-check-input" type="checkbox" value="1" name="enabled" <?= $sendEmail["enabled"] ? "checked" : ""; ?>>
            </div>
            <div class="text-end">
              <button type="submit" class="btn btn-dark">更新</button>
            </div>
          </form>
          <form action="/send-emails/<?= $sendEmail["id"] ?>" method="post" onsubmit="return window.confirm('本当に削除しますか？')">
            <?= csrf() ?>
            <?= method("DELETE") ?>
            <div class="text-end">
              <input type="submit" class="btn btn-link" value="この送信先メールアドレスを削除">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div> 
<?php endforeach; ?>

<?= Auth::user()["id"] !== $user["id"]? Components::watchOnAdmin("管理者として閲覧専用でこのページを閲覧しています") : "" ?>

<?php require './views/templates/footer.php'; ?>