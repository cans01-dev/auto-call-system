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
<p><?= "{$pgnt["current"]} / {$pgnt["last_page"]}ページ目 - {$pgnt["current_start"]}~{$pgnt["current_end"]} / {$pgnt["sum"]}件表示中" ?></p>
<?php if ($logs): ?>
  <div class="calls-table-container">
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
              <a href="/reserves/<?= $log["reserve_id"] ?>"><?= $log["reserve_date"] ?></a>
            </td>
            <td><?= $log["status"] ?></td>
            <td><?= $log["message"] ?></td>
            <td><?= $log["created_at"] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
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
<?= Components::hr(3) ?>
<a class="btn btn-link" href="/dev/pse_api/receive_and_gen_result.php">【テスト用】予約ファイルから結果ファイルを生成</a>
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