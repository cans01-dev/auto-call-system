<?php require "./views/templates/header.php"; ?>

<div class="pt-3">
  <?= Components::h2("ユーザー管理") ?>
</div>
<table class="table">
  <thead>
    <tr>
      <th>ID</th>
      <th>メールアドレス</th>
      <th>ステータス</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
      <tr>
        <th><?= $user["id"] ?></th>
        <td><?= $user["email"] ?></td>
        <td><?= $user["status"] === 0? "一般": ($user["status"] === 1 ? "管理" : ""); ?></td>
        <td>
        <button
          type="button"
          class="btn btn-dark"
          data-bs-toggle="modal"
          data-bs-target="#changePasswordModal<?= $user["id"] ?>"
        >
        パスワードを変更
        </button>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?= Components::modalOpenButton("usersCreateModal") ?>

<?php foreach ($users as $user): ?>
  <?= Components::modal("changePasswordModal{$user["id"]}", "パスワードを変更", <<<EOL
    <form action="/admin/users/{$user["id"]}/password" method="post">
      CSRF
      METHOD_PUT
      <div class="mb-3">
        <label class="form-label"><b>変更を行う管理者</b>のパスワード</label>
        <input type="password" name="admin_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label"><b>{$user["email"]}</b>の新しいパスワード</label>
        <input type="password" name="new_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">新しいパスワード（再入力）</label>
        <input type="password" name="new_password_confirm" class="form-control" required>
      </div>
      <div class="text-end">
        <input type="hidden" name="user_id" value="{$user["id"]}">
        <button type="submit" class="btn btn-primary">更新</button>
      </div>
      <div class="form-text">
        パスワードは8文字以上の半角英数字を指定してください
      </div>
    </form>
  EOL); ?>
<?php endforeach; ?>

<?= Components::modal("usersCreateModal", "ユーザーを新規作成", <<<EOL
  <form action="/admin/users" method="post">
    CSRF
    <div class="mb-3">
      <label class="form-label">ステータス</label>
      <select class="form-select" name="status">
        <option value="0">一般</option>
        <option value="1">管理</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">メールアドレス</label>
      <input type="email" name="email" class="form-control">
    </div>
    <div class="mb-3">
      <label class="form-label">パスワード</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">パスワード（再入力）</label>
      <input type="password" name="password_confirm" class="form-control" required>
    </div>
    <div class="text-end">
      <button type="submit" class="btn btn-success">作成</button>
    </div>
    <div class="form-text">
      パスワードは8文字以上の半角英数字を指定してください
    </div>
  </form>
EOL); ?>

<?php require './views/templates/footer.php'; ?>