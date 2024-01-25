<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item"><a href="/surveys/<?= $survey["id"] ?>"><?= $survey["title"] ?></a></li>
    <li class="breadcrumb-item active">お気に入り: <?= $favorite["title"] ?></li>
  </ol>
</nav>
<?= Components::h2("予約パターン: {$favorite["title"]}") ?>

<section id="summary">
  <?= Components::h3("基本設定"); ?>
  <div style="max-width: 480px;">
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
      <div class="text-end">
        <button type="submit" class="btn btn-dark">更新</button>
      </div>
    </form>
    <form method="post" onsubmit="return window.confirm('本当に削除しますか？')">
      <?= csrf() ?>
      <?= method("DELETE") ?>
      <div class="text-end">
        <input type="submit" class="btn btn-link" value="お気に入り登録を削除">
      </div>
    </form>
    <?= Components::hr(4) ?>
    <div class="mb-3">
      <dl>
        <dt>開始 ~ 終了時間</dt>
      <dd><?= date("H:i", strtotime($reserve["start"])) ?> ~ <?= date("H:i", strtotime($reserve["end"])) ?></dd>
      <dt>エリア</dt>
      <dd>
        <?php foreach (Fetch::areasByReserveId($reserve["id"]) as $area): ?>
          <span class="badge text-bg-secondary fs-6 me-1"><?= $area["title"] ?></span>
          <?php endforeach; ?>
        </dd>
      </dl>
    </div>
  </div>
</section>
<?php require './views/templates/footer.php'; ?>