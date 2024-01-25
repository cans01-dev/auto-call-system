<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item"><a href="/surveys/<?= $survey["id"] ?>"><?= $survey["title"] ?></a></li>
    <li class="breadcrumb-item active">予約: <?= date("n月d日", strtotime($reserve["date"])) ?></li>
  </ol>
</nav>
<?= Components::h2("予約: " . date("n月d日", strtotime($reserve["date"]))) ?>

<div style="max-width: 480px;">
  <section id="summary">
    <?= Components::h3("基本設定"); ?>
    <form method="post">
      <?= csrf() ?>
      <?= method("PUT") ?>
      <div class="mb-3">
        <label class="form-label">開始時間・終了時間</label>
        <div class="input-group">
          <input type="time" name="start" class="form-control" value="<?= $reserve["start"] ?>" required>
          <span class="input-group-text">~</span>
          <input type="time" name="end" class="form-control" value="<?= $reserve["end"] ?>" required>
        </div>
      </div>
      <div class="text-end">
        <button type="submit" class="btn btn-dark">更新</button>
      </div>
    </form>
    <form method="post" onsubmit="return window.confirm('本当に削除しますか？')">
      <?= csrf() ?>
      <?= method("DELETE") ?>
      <div class="text-end">
        <input type="submit" class="btn btn-link" value="この予約を削除">
      </div>
    </form>
  </section>
  <?= Components::hr() ?>
  <section id="area">
    <?= Components::h3("エリア設定"); ?>
      <div class="card mb-4">
        <div class="card-header">選択済のエリア</div>
        <ul class="list-group list-group-flush">
          <?php if ($selectedAreas): ?>
            <?php foreach ($selectedAreas as $area): ?>
              <li class="list-group-item d-flex align-items-center justify-content-between">
                <div>
                  <?= $area["title"] ?>
                  <a href="/areas/<?= $area["id"] ?>" class="text-body-tertiary">
                    <i class="fa-solid fa-circle-info"></i>
                  </a>
                </div>
                <form action="/reserves_areas/<?= $area["ra_id"] ?>" method="post">
                  <?= csrf() ?>
                  <?= method("DELETE") ?>
                  <button type="submit" class="btn btn-danger">削除</button>
                </form>
              </li>
            <?php endforeach; ?>
          <?php else: ?>
            <li class="list-group-item">エリアが選択されていません</li>
          <?php endif; ?>
        </ul>
      </div>
      <div class="card mb-4">
        <div class="card-header">
          エリアを追加する
        </div>
        <ul class="list-group list-group-flush">
          <?php foreach ($notSelectedAreas as $area): ?>
            <li class="list-group-item d-flex align-items-center justify-content-between">
              <div>
                <?= $area["title"] ?>
                <a href="/areas/<?= $area["id"] ?>" class="text-body-tertiary">
                  <i class="fa-solid fa-circle-info"></i>
                </a>
              </div>
              <form action="/reserves_areas" method="post">
                <?= csrf() ?>
                <input type="hidden" name="reserve_id" value="<?= $reserve["id"] ?>">
                <input type="hidden" name="area_id" value="<?= $area["id"] ?>">
                <button type="submit" class="btn btn-primary">追加</button>
              </form>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="form-text">
        指定されたエリアからランダムで電話番号が指定されコールされます
      </div>
  </section>
</div>


<?php require './views/templates/footer.php'; ?>