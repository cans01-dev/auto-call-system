<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item"><a href="/surveys/<?= $survey["id"] ?>#calendar"><?= $survey["title"] ?></a></li>
    <li class="breadcrumb-item active">予約パターン: <?= $favorite["title"] ?></li>
  </ol>
</nav>
<?= Components::h2("予約パターン: {$favorite["title"]}") ?>

<div style="max-width: 480px;">
  <section id="summary">
    <?= Components::h3("基本設定"); ?>
    <form method="post">
      <?= csrf() ?>
      <?= method("PUT") ?>
      <div class="mb-3">
        <label class="form-label">予約パターンのタイトル</label>
        <input type="text" name="title" class="form-control" value="<?= $favorite["title"] ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">ラベルカラー</label>
        <input type="color" name="color" class="form-control form-control-color" value="<?= $favorite["color"] ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">開始時間・終了時間</label>
        <div class="input-group">
          <select name="start" class="form-select" required>
            <option value="">選択してください</option>
            <?php foreach (make_times(MIN_TIME, MAX_TIME, TIME_STEP) as $ts): ?>
            <option value="<?= date("H:i", $ts) ?>" <?= $favorite["start"] == date("H:i:s", $ts) ? "selected" : ""; ?>>
              <?= date("H:i", $ts) ?>
            </option>
            <?php endforeach; ?>
          </select>
          <span class="input-group-text">~</span>
          <select name="end" class="form-select" required>
            <option value="">選択してください</option>
            <?php foreach (make_times(MIN_TIME, MAX_TIME, TIME_STEP) as $ts): ?>
            <option value="<?= date("H:i", $ts) ?>" <?= $favorite["end"] == date("H:i:s", $ts) ? "selected" : ""; ?>>
              <?= date("H:i", $ts) ?>
            </option>
            <?php endforeach; ?>
          </select>
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
        <input type="submit" class="btn btn-link" value="この予約パターンを削除">
      </div>
    </form>
  </section>
  <?= Components::hr() ?>
  <section id="area">
    <?= Components::h3("エリア設定"); ?>
      <div class="card mb-4">
        <div class="card-header">選択済のエリア</div>
        <ul class="list-group list-group-flush">
          <?php if ($favorite["areas"]): ?>
            <?php foreach ($favorite["areas"] as $area): ?>
              <li class="list-group-item d-flex align-items-center justify-content-between">
                <div>
                  <?= $area["title"] ?>
                  <a href="/areas/<?= $area["id"] ?>" class="text-body-tertiary">
                    <i class="fa-solid fa-circle-info"></i>
                  </a>
                </div>
                <form action="/favorites_areas/<?= $area["fa_id"] ?>" method="post">
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
      <div class="border p-2 mb-2">
        <p>地域名を入力してまとめて選択</p>
        <form action="/favorites_areas/by-word" method="post">
          <?= csrf() ?>
          <input type="hidden" name="favorite_id" value="<?= $favorite["id"] ?>">
          <div class="input-group">
            <input type="text" class="form-control" name="word" placeholder="（部分一致）" required>
            <button type="submit" class="btn btn-outline-secondary">実行</button>
          </div>
        </form>
      </div>
      <div class="card mb-4">
        <div class="card-header">
          エリアを追加する
        </div>
        <ul class="list-group list-group-flush">
          <?php foreach (Fetch::all("areas") as $area): ?>
            <?php if (!in_array($area["id"], array_column($favorite["areas"], "id"))): ?>
              <li class="list-group-item d-flex align-items-center justify-content-between">
                <div>
                  <?= $area["title"] ?>
                  <a href="/areas/<?= $area["id"] ?>" class="text-body-tertiary">
                    <i class="fa-solid fa-circle-info"></i>
                  </a>
                </div>
                <form action="/favorites_areas" method="post">
                  <?= csrf() ?>
                  <input type="hidden" name="favorite_id" value="<?= $favorite["id"] ?>">
                  <input type="hidden" name="area_id" value="<?= $area["id"] ?>">
                  <button type="submit" class="btn btn-primary">追加</button>
                </form>
              </li>
            <?php endif; ?>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="form-text">
        指定されたエリアからランダムで電話番号が指定されコールされます
      </div>
  </section>
</div>

<?php require './views/templates/footer.php'; ?>