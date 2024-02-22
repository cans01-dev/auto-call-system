<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item"><a href="/surveys/<?= $survey["id"] ?>"><?= $survey["title"] ?></a></li>
    <li class="breadcrumb-item active">コール一覧</li>
  </ol>
</nav>
<?= Components::h2("コール一覧") ?>
<div class="card p-2 mb-4">
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
      </tbody>
    </table>
    <div class="position-absolute top-0 end-0 p-2">
      <button class="btn btn-primary">確定</button>
    </div>
  </form>
</div>
<table class="table table-bordered table-hover">
  <thead class="sticky-top" style="top: 43px;">
    <tr>
      <th>ID</th>
      <th>日付・時間</th>
      <th>電話番号</th>
      <th>ステータス</th>
      <th>通話成立時間</th>
      <?php for ($i = 0; $i < 10; $i++): ?>
        <th><?= $i ?></th>
      <?php endfor; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($calls as $call): ?>
      <tr onclick="window.location.assign('/calls/<?= $call['id'] ?>')">
        <td><?= $call["id"] ?></td>
        <td><a href="/reserves/<?= $call["reserve_id"] ?>/result"><?= $call["date"] ?></a> |  <?= $call["time"] ?></td>
        <td><?= $call["number"] ?></td>
        <td><?= $call["status"] ?></td>
        <td><?= $call["duration"] ?></td>
        <?php for ($i = 0; $i < 10; $i++): ?>
          <td><?= $i ?></td>
        <?php endfor; ?>
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
        <?= $pgnt["current"] - 2 ?>
      </button>
    </li>
  <?php endif; ?>
  <?php if ($pgnt["prev"]): ?>
    <li class="page-item">
      <button name="page" value="<?= $pgnt["prev"]  ?>" form="params" class="page-link" href="#">
        <?= $pgnt["current"] - 1 ?>
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
        <?= $pgnt["current"] + 1 ?>
      </button>
    </li>
  <?php endif; ?>
  <?php if ($pgnt["nnext"]): ?>
    <li class="page-item">
      <button name="page" value="<?= $pgnt["next"]  ?>" form="params" class="page-link" href="#">
        <?= $pgnt["current"] + 2 ?>
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