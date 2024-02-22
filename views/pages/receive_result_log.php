<?php require "./views/templates/header.php"; ?>

<div class="pt-3">
  <?= Components::h2("結果ファイル受信ログ") ?>
</div>
<button
  type="button"
  class="btn btn-primary me-2"
  data-bs-toggle="modal"
  data-bs-target="#sendResultModal"
>手動で結果ファイルを受信</button>
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

<?= Components::modal("sendResultModal", "手動で結果ファイルを受信", <<<EOL
  <form action="/api/receive_result.php" method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">結果ファイル</label>
      <input type="file" name="file" class="form-control" required>
    </div>
    <div class="text-end">
      <button type="submit" class="btn btn-success">実行</button>
    </div>
    <div class="form-text">
      実行を押すとBasic認証のユーザー名、パスワードが求められます<br>
      ページが遷移しますが手動でブラウザバックしてこのページに戻ってください
    </div>
  </form>
EOL); ?>

<?php require './views/templates/footer.php'; ?>