<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item"><a href="/surveys/<?= $survey["id"] ?>"><?= $survey["title"] ?></a></li>
    <li class="breadcrumb-item active">質問回答一覧</li>
  </ol>
</nav>
<?= Components::h2("質問回答一覧") ?>
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
          <td>回答パターン</td>
          <td>
            <table class="table table-sm mb-0">
              <tbody>
                <?php foreach ($faqs as $faq): ?>
                  <tr>
                    <td class="fw-bold"><?= $faq["title"] ?></td>
                    <td>
                      <div class="d-flex gap-4">
                        <?php foreach ($faq["options"] as $option): ?>
                          <div class="form-check">
                            <input
                              class="form-check-input"
                              type="checkbox"
                              value="<?= $option["id"] ?>"
                              name="options[]"
                              id="faq<?= $faq["id"] ?>answer<?= $option["id"] ?>"
                              <?= in_array($option["id"], @$_GET["options"] ?? []) ? "checked" : "" ?>
                            >
                            <label class="form-check-label" for="faq<?= $faq["id"] ?>answer<?= $option["id"] ?>">
                              <?= $option["title"] ?>
                            </label>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <div class="form-text mt-2">チェックされた選択肢に一つでも当てはまるコールが表示されます</div>
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
<table class="table table-bordered table-hover" style="font-size: 0.875em;">
  <thead class="sticky-top" style="top: 43px;">
    <tr>
      <th>ID</th>
      <th>日付・時間</th>
      <th>電話番号</th>
      <th>通話成立時間</th>
      <th>質問</th>
      <th>回答</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($answers as $answer): ?>
      <tr onclick="window.location.assign('/calls/<?= $answer['call_id'] ?>')">
        <td><?= $answer["id"] ?></td>
        <td><a href="/reserves/<?= $answer["reserve_id"] ?>/result"><?= $answer["date"] ?></a> |  <?= $answer["time"] ?></td>
        <td><?= $answer["number"] ?></td>
        <td><?= $answer["duration"] ?></td>
        <td><?= $answer["faq_title"] ?></td>
        <td><?= $answer["option_title"] ?></td>
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

<?php require './views/templates/footer.php'; ?>