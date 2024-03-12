<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <?php if ($survey): ?>
      <li class="breadcrumb-item"><a href="/surveys/<?= $survey["id"] ?>/calendar"><?= $survey["title"] ?></a></li>
    <?php endif; ?>
    <li class="breadcrumb-item active">マイエリア: <?= $area["title"] ?></li>
  </ol>
</nav>
<?= Components::h2($area["survey_id"] ? "マイエリア: {$area["title"]}" : "エリア: {$area["title"]}") ?>
<?php $number = "09012345678" ?>
<div class="d-flex gap-3">
  <div class="w-100">
    <?php if ($stats = @$area["stats"]): ?>
      <section id="area">
        <?= Components::h3("エリア別の統計を見る") ?>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">エリア</th>
              <th scope="col">進捗率(総コール数 / エリア内番号数)</th>
              <th scope="col">応答率</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">
                <?= $area["title"] ?>
                <?php if ($area["survey_id"]): ?>
                  <span class="badge bg-primary">マイエリア</span>
                <?php endif; ?>
              </th>
              <td>
                <div class="progress" role="progressbar" aria-label="Example with label" aria-valuenow="44" aria-valuemin="0" aria-valuemax="100">
                  <div class="progress-bar" style="width: <?= round($stats["progress_rate"] * 100) ?>%">
                    <?= round($stats["progress_rate"] * 100) ?>%
                  </div>
                </div>
                <span>(<?= $stats["called_numbers"] ?> / <?= $stats["all_numbers"] ?>) <?= round($stats["progress_rate"] * 100, 4) ?>%</span>
              </td>
              <td>
                <?= round($stats["response_rate"] * 100) ?>% (<?= $stats["responsed_numbers"] ?> / <?= $stats["called_numbers"] ?>)
              </td>
            </tr>
          </tbody>
        </table>
      </section>
    <?php else: ?>
      <?php if ($area["survey_id"]): ?>
        <section id="addStation">
        <?= Components::h3("局番を追加"); ?>
        <div class="card text-bg-light mb-3">
          <div class="card-header">
            プレフィックスを入力して局番を追加
          </div>
          <div class="card-body">
            <p class="mb-2">ハイフン有り、6桁の番号を入力してください</p>
            <form action="/areas/<?= $area["id"] ?>/stations" method="post">
              <?= csrf() ?>
              <div class="input-group" style="max-width: 320px;">
                <input type="text" name="prefix" class="form-control" placeholder="090-123" pattern="^0[789]0-[0-9]{3}$">
                <button class="btn btn-outline-secondary" id="button-addon1">追加</button>
              </div>
            </form>
          </div>
        </div>
        <div class="card text-bg-light mb-3">
          <div class="card-header">
            デフォルトのエリアから局番を追加
          </div>
          <div class="card-body area-list-group">
            <div class="vstack gap-3">
              <?php foreach (Fetch::query("SELECT * FROM areas WHERE survey_id IS NULL", "fetchAll") as $a): ?>
                <div>
                  <h6><?= $a["title"] ?></h6>
                  <?php foreach (Fetch::get("stations", $a["id"], "area_id") as $s): ?>
                    <div class="d-inline-block">
                      <form action="/areas/<?= $area["id"] ?>/stations" method="post">
                        <?= csrf() ?>
                        <input type="hidden" name="prefix" value="<?= $s["prefix"] ?>">
                        <button
                          class="btn btn-light btn-sm stationButton"
                          <?= in_array($s["prefix"], array_column($area["stations"], "prefix")) ? "disabled" : "" ?>
                        >
                          <?= $s["prefix"] ?>
                        </button>
                      </form>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        </section>
      <?php endif; ?>
    <?php endif; ?>
  </div>
  <div class="flex-shrink-0 sticky-aside" style="width: 300px;">
    <div class="sticky-top">
      <section id="summary">
        <?= Components::h4("設定"); ?>
        <div class="mb-3">
          <form action="/areas/<?= $area["id"] ?>" method="post" id="editAreaForm">
            <?= csrf() ?>
            <?= method("PUT") ?>
            <label class="form-label">タイトル</label>
            <input type="text" name="title" class="form-control" value="<?= $area["title"] ?>" <?= !$area["survey_id"] ? "disabled" : "" ?>>
          </form>
        </div>
        <div class="mb-3">
          <label class="form-label">局番</label>
          <?php if ($area["stations"]): ?>
            <div>
              <?php foreach ($area["stations"] as $station): ?>
                <span class="badge bg-secondary fs-6 mb-1">
                  <form action="/stations/<?= $station["id"] ?>" method="post">
                    <?= csrf() ?>
                    <?= method("DELETE") ?>
                    <?= $station["prefix"] ?>
                    <button type="submit" class="bg-transparent border-0" <?= !$area["survey_id"] ? "disabled" : "" ?>>
                      <i class="fa-solid fa-xmark text-white"></i>
                    </button>
                  </form>
                </span>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <?= Components::noContent("局番が設定されていません") ?>
          <?php endif; ?>
        </div>
        <div class="text-end">
          <button type="submit" class="btn btn-dark" form="editAreaForm" <?= !$area["survey_id"] ? "disabled" : "" ?>>更新</button>
        </div>
        <?php if ($area["survey_id"]): ?>
          <form method="post" onsubmit="return window.confirm('本当に削除しますか？')">
            <?= csrf() ?>
            <?= method("DELETE") ?>
            <div class="text-end">
              <input type="submit" class="btn btn-link" value="このマイエリアを削除">
            </div>
          </form>
        <?php endif; ?>
      </section>
    </div>
  </div>
</div>

<?php if ($survey): ?>
  <?= Auth::user()["id"] !== $survey["user_id"]? Components::watchOnAdmin("管理者として閲覧専用でこのページを閲覧しています") : "" ?>
<?php endif; ?>

<?php require './views/templates/footer.php'; ?>