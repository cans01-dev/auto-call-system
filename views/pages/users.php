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
      <th>操作</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
      <tr>
        <th><?= $user["id"] ?></th>
        <td><?= $user["email"] ?></td>
        <td>
          <span class="badge bg-<?= USER_STATUS[$user["status"]]["bg"] ?>">
            <?= USER_STATUS[$user["status"]]["text"]; ?>
          </span>
        </td>
        <td>
        <button
          type="button"
          class="btn btn-primary me-2"
          data-bs-toggle="modal"
          data-bs-target="#changeStatusModal<?= $user["id"] ?>"
        >ステータスを変更</button>
        <button
          type="button"
          class="btn btn-dark"
          data-bs-toggle="modal"
          data-bs-target="#changePasswordModal<?= $user["id"] ?>"
        >パスワードを変更</button>
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

  <!-- changeStatusModal<?= $user["id"] ?> -->
  <div class="modal fade" id="changeStatusModal<?= $user["id"] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">ステータスを変更</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/admin/users/<?= $user["id"] ?>/status" method="post">
            <?= csrf() ?>
            <?= method("PUT") ?>
            <div class="mb-3">
              <label class="form-label"><b>変更を行う管理者</b>のパスワード</label>
              <input type="password" name="admin_password" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label"><b><?= $user["email"] ?></b>のステータス</label>
              <?php foreach (USER_STATUS as $k => $v): ?>
                <div class="form-check">
                  <input
                    class="form-check-input"
                    type="radio"
                    name="new_status"
                    id="status<?= $k ?>"
                    value="<?= $k ?>"
                    <?= $user["status"] === $k ? "checked" : "" ?>
                  >
                  <label class="form-check-label" for="status<?= $k ?>">
                  <span class="badge bg-<?= $v["bg"] ?>">
                    <?= $v["text"]; ?>
                  </span>
                  </label>
                </div>
              <?php endforeach; ?>
            </select>
            </div>
            <div class="text-end">
              <input type="hidden" name="user_id" value="<?= $user["id"] ?>">
              <button type="submit" class="btn btn-success">作成</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
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