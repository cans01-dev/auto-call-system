<?php require "./views/templates/header.php"; ?>

<div class="pt-3">
  <?= Components::h2("全ての予約") ?>
</div>
<div class="card p-2 mb-4">
  <form id="params">
    <table class="table table-borderless mb-0">
      <tbody>
        <tr>
          <td>開始日 ~ 終了日</td>
          <td>
            <div class="input-group mb-0" style="max-width: 360px;">
              <input
              type="date" name="start" class="form-control form-control-sm"
              value="<?= @$_GET["start"] ?? date('Y-m-d', strtotime('first day of this month')) ?>">
              <span class="input-group-text">~</span>
              <input
              type="date" name="end" class="form-control form-control-sm"
              value="<?= @$_GET["end"] ?? date('Y-m-d', strtotime('last day of this month')) ?>">
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="position-absolute top-0 end-0 p-2">  
      <button class="btn btn-primary">確定</button>
    </div>
  </form>
</div>
<p><?= "{$pgnt["current"]} / {$pgnt["last_page"]}ページ目 - {$pgnt["current_start"]}~{$pgnt["current_end"]} / {$pgnt["sum"]}件表示中" ?></p>
<?php if ($reserves): ?>
  <table class="table table-hover">
    <thead>
      <tr>
        <th>ID</th>
        <th>アンケート</th>
        <th>ユーザー</th>
        <th>日付</th>
        <th>開始・終了時間</th>
        <th>ステータス</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($reserves as $reserve): ?>
        <tr onclick="window.location.assign('/reserves/<?= $reserve['id'] ?>')">
          <th><?= $reserve["id"] ?></th>
          <td>
            <a href="/surveys/<?= $reserve["survey_id"] ?>"><?= $reserve["title"] ?></a>
          </td>
          <td><a href="/users/<?= $reserve["user_id"] ?>"><?= $reserve["email"] ?></a></td>
          <td><?= $reserve["date"] ?></td>
          <td><?= substr($reserve["start"], 0, -3) . " ~ " . substr($reserve["end"], 0, -3) ?></td>
          <td>
            <span class="badge text-bg-<?= RESERVATION_STATUS[$reserve["status"]]["bg"] ?> bg-gradient fs-6 me-1">
              <?= RESERVATION_STATUS[$reserve["status"]]["text"] ?>
            </span>
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