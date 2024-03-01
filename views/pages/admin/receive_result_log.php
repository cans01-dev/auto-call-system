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
<p><?= "{$pgnt["current"]} / {$pgnt["last_page"]}ページ目 - {$pgnt["current_start"]}~{$pgnt["current_end"]} / {$pgnt["sum"]}件表示中" ?></p>
<?php if ($logs): ?>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>予約情報</th>
        <th>ステータス</th>
        <th>メッセージ</th>
        <th>ログ日時</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($logs as $log): ?>
        <tr>
          <th><?= $log["id"] ?></th>
          <td>
            <?= $log["email"] ?> | 
            <a href="/surveys/<?= $log["survey_id"] ?>"><?= $log["title"] ?></a> | 
            <a href="/reserves/<?= $log["reserve_id"] ?>"><?= $log["reserve_date"] ?></a>
          </td>
          <td><?= $log["status"] ?></td>
          <td><?= $log["message"] ?></td>
          <td><?= $log["created_at"] ?></td>
          <td>
            <form method="post" action="/admin/receive_result_log/<?= $log["id"] ?>" onsubmit="return window.confirm('本当に削除しますか？')">
              <?= csrf() ?>
              <?= method("DELETE") ?>
              <button class="btn btn-dark">取消</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <ul class="pagination mb-0 mt-4" style="justify-content: center;">
    <?php if ($pgnt["first"]): ?>
      <li class="page-item">
        <button name="page" value="<?= $pgnt["first"] ?>" form="params" class="page-link" href="#">
          <i class="fa-solid fa-angles-left"></i>
        </button>
      </li>
    <?php endif; ?>
    <?php if ($pgnt["pprev"]): ?>
      <li class="page-item">
        <button name="page" value="<?= $pgnt["pprev"]  ?>" form="params" class="page-link" href="#">
          <?= $pgnt["pprev"] ?>
        </button>
      </li>
    <?php endif; ?>
    <?php if ($pgnt["prev"]): ?>
      <li class="page-item">
        <button name="page" value="<?= $pgnt["prev"]  ?>" form="params" class="page-link" href="#">
          <?= $pgnt["prev"] ?>
        </button>
      </li>
    <?php endif; ?>
    <?php if ($pgnt["current"]): ?>
      <li class="page-item">
        <button name="page" value="<?= $pgnt["current"] ?>" form="params" class="page-link active" href="#">
          <?= $pgnt["current"] ?>
        </button>
      </li>
    <?php endif; ?>
    <?php if ($pgnt["next"]): ?>
      <li class="page-item">
        <button name="page" value="<?= $pgnt["next"]  ?>" form="params" class="page-link" href="#">
          <?= $pgnt["next"] ?>
        </button>
      </li>
    <?php endif; ?>
    <?php if ($pgnt["nnext"]): ?>
      <li class="page-item">
        <button name="page" value="<?= $pgnt["nnext"]  ?>" form="params" class="page-link" href="#">
          <?= $pgnt["nnext"] ?>
        </button>
      </li>
    <?php endif; ?>
    <?php if ($pgnt["last"]): ?>
      <li class="page-item">
        <button name="page" value="<?= $pgnt["last"]  ?>" form="params" class="page-link" href="#">
          <i class="fa-solid fa-angles-right"></i>
        </button>
      </li>
    <?php endif; ?>
  </ul>
<?php else: ?>
  <?= Components::noContent("該当するデータがありません、検索条件を変更して再検索してください") ?>
<?php endif; ?>
<?= Components::modal("sendResultModal", "手動で結果ファイルを受信", <<<EOL
  <form action="/api/receive_result.php" method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">結果ファイル</label>
      <input type="file" name="file" class="form-control" required>
    </div>
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" name="ignore" role="switch" id="flexSwitchCheckDefault">
      <label class="form-check-label" for="flexSwitchCheckDefault">
        「この結果ファイルは既に受信されています」エラーを無視する
      </label>
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