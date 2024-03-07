<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item"><a href="/surveys/<?= $survey["id"] ?>"><?= $survey["title"] ?></a></li>
    <li class="breadcrumb-item active">コール一覧</li>
  </ol>
</nav>
<?= Components::h2("{$survey["title"]}: コール一覧") ?>
<div class="card p-2 mb-4">
  <form method="post" id="csvForm"><?= csrf() ?></form>
  <form id="params">
    <table class="table table-borderless mb-0">
      <tbody>
        <tr>
          <td>ステータス</td>
          <td class="d-flex gap-4">
            <?php foreach (CALL_STATUS as $key => $value): ?>
              <div class="form-check">
                <input
                  class="form-check-input"
                  type="checkbox"
                  value="<?= $key ?>"
                  name="status[]"
                  id="callStatus<?= $key ?>"
                  <?= in_array($key, $status_arr) ? "checked" : "" ?>
                >
                <label class="form-check-label" for="callStatus<?= $key ?>">
                  <span class="badge bg-<?= $value["bg"] ?>"><?= $value["text"] ?></span>
                </label>
              </div>
            <?php endforeach; ?>
          </td>
        </tr>
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
        <tr>
          <td>電話番号（ハイフン必須）</td>
          <td>
            <input
              type="text"
              name="number"
              class="form-control form-control"
              value="<?= @$_GET["number"] ?>"
              style="max-width:180px;"
              list="stationsList"
            >
            <datalist id="stationsList">
              <?php foreach (Fetch::all("stations") as $station): ?>
                <option value="<?= $station["prefix"] ?>"><?= $station["title"] ?></option>
              <?php endforeach; ?>
            </datalist>
          </td>
        </tr>
        <tr>
          <td><label for="actionCountRange" class="form-label">アクション数</label></td>
          <td>
            <div class="d-flex justify-content-between">
              <?php for ($i = 0; $i <= count($faqs); $i++) echo "<div>{$i}~</div>" ?>
            </div>
            <input
              type="range" name="action_count" class="form-range"
              min="0" max="<?= count($faqs) ?>" id="actionCountRange"
              value="<?= @$_GET["action_count"] ?? 0 ?>"
            >
          </td>
        </tr>
      </tbody>
    </table>
    <div class="position-absolute top-0 end-0 p-2">  
      <button class="btn btn-outline-success" form="csvForm">CSV出力</button>
      <button class="btn btn-primary">確定</button>
    </div>
  </form>
</div>
<p><?= "{$pgnt["current"]} / {$pgnt["last_page"]}ページ目 - {$pgnt["current_start"]}~{$pgnt["current_end"]} / {$pgnt["sum"]}件表示中" ?></p>
<?php if ($calls): ?>
  <div class="calls-table-container">
    <table class="table table-bordered table-hover calls-table">
      <thead class="sticky-top">
        <tr>
          <th>ID</th>
          <th>日付・時間</th>
          <th>電話番号</th>
          <th>ステータス</th>
          <th>通話成立時間</th>
          <th>アクション数</th>
          <?php foreach ($faqs as $faq): ?>
            <th><a href="/faqs/<?= $faq["id"] ?>"><?= $faq["title"] ?></a></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($calls as $call): ?>
          <tr onclick="window.location.assign('/calls/<?= $call['id'] ?>')">
            <td><?= $call["id"] ?></td>
            <td><a href="/reserves/<?= $call["reserve_id"] ?>"><?= $call["date"] ?></a> |  <?= $call["time"] ?></td>
            <td><?= $call["number"] ?></td>
            <td><?= CALL_STATUS[$call["status"]]["text"] ?></td>
            <td><?= $call["duration"] ?></td>
            <td><?= $call["action_count"] ?></td>
            <?php foreach ($call["faqs"] as $faq): ?>
              <td>
                <?= @$faq["option_title"] ? $faq["option_title"] : "-" ?>
              </td>
            <?php endforeach; ?>
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

<?= Auth::user()["id"] !== $survey["user_id"]? Components::watchOnAdmin("管理者としてこのページを閲覧しています") : "" ?>

<?php require './views/templates/footer.php'; ?>