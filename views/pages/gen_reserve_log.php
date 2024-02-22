<?php require "./views/templates/header.php"; ?>

<div class="pt-3">
  <?= Components::h2("予約情報ファイル生成ログ") ?>
</div>
<button
  type="button"
  class="btn btn-primary me-2"
  data-bs-toggle="modal"
  data-bs-target="#genReserveModal"
>手動で予約情報ファイルを生成</button>
<?= Components::hr(3) ?>
<table class="table">
  <thead>
    <tr>
      <th>ID</th>
      <th>予約情報</th>
      <th>ステータス</th>
      <th>メッセージ</th>
      <th>ログ日時</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($logs as $log): ?>
      <tr>
        <th><?= $log["id"] ?></th>
        <td>
          <?= $log["email"] ?> | 
          <a href="/surveys/<?= $log["survey_id"] ?>"><?= $log["title"] ?></a> | 
          <a href="/reserves/<?= $log["reserve_id"] ?>/result"><?= $log["reserve_date"] ?></a>
        </td>
        <td><?= $log["status"] ?></td>
        <td><?= $log["message"] ?></td>
        <td><?= $log["created_at"] ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?= Components::modal("genReserveModal", "手動で予約情報ファイルを生成", <<<EOL
  <form action="/admin/gen_reserve" method="post" enctype="multipart/form-data">
    CSRF
    <div class="mb-3">
      <label class="form-label">生成する日付</label>
      <input type="date" name="date" class="form-control" required>
    </div>
    <div class="text-end">
      <button type="submit" class="btn btn-success">生成</button>
    </div>
    <div class="form-text">
      その日付に含まれる複数のファイルが生成されます
    </div>
  </form>
EOL); ?>

<?php require './views/templates/footer.php'; ?>