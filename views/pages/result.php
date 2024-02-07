<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="sticky-top">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item"><a href="/surveys/<?= $survey["id"] ?>"><?= $survey["title"] ?></a></li>
    <li class="breadcrumb-item active">結果: <?= date("n月d日", strtotime($reserve["date"])) ?></li>
  </ol>
</nav>
<?= Components::h2("結果: " . date("n月d日", strtotime($reserve["date"]))) ?>

<div style="max-width: 480px;">
  <section class="mb-5" id="summary">
    <?= Components::h3("概要"); ?>
    <dl>
      <dt>ステータス</dt>
      <dd>
        <span class="badge text-bg-<?= RESERVATION_STATUS[$reserve["status"]]["bg"] ?> bg-gradient fs-6 me-1">
          <?= RESERVATION_STATUS[$reserve["status"]]["text"] ?>
        </span>
      </dd>
      <dt>開始 ~ 終了時間</dt>
      <dd><?= date("H:i", strtotime($reserve["start"])) ?> ~ <?= date("H:i", strtotime($reserve["end"])) ?></dd>
      <dt>エリア</dt>
      <dd>
        <?php foreach ($selectedAreas as $area): ?>
          <span class="badge text-bg-secondary fs-6 me-1"><?= $area["title"] ?></span>
        <?php endforeach; ?>
      </dd>
    </dl>
  </section>
  <section id="file">
    <?= Components::h3("ファイル"); ?>
    <div class="mb-4">
      <div class="mb-2">予約情報ファイル</div>
      <?php if ($reserve["reserve_file"]): ?>
        <a class="btn btn-primary" href="<?= url("/storage/outputs/".$reserve["reserve_file"]) ?>" download>
          <span class="me-1">
            <i class="fa-solid fa-download fa-lg"></i>
          </span>ダウンロード
        </a>
      <?php else: ?>
        <button class="btn btn-primary" disabled>
          <span class="me-1">
            <i class="fa-solid fa-download fa-lg"></i>
          </span>ダウンロード
        </button>
      <?php endif; ?>
      <div class="form-text">確定済になるとダウンロードが可能になります</div>
    </div>
    <div>
      <div class="mb-2">結果ファイル</div>
      <?php if ($reserve["result_file"]): ?>
        <a class="btn btn-primary" href="<?= url("/storage/uploads/".$reserve["result_file"]) ?>" download>
          <span class="me-1">
            <i class="fa-solid fa-download fa-lg"></i>
          </span>ダウンロード
        </a>
      <?php else: ?>
        <button class="btn btn-primary" disabled>
          <span class="me-1">
            <i class="fa-solid fa-download fa-lg"></i>
          </span>ダウンロード
        </button>
      <?php endif; ?>
      <div class="form-text">集計済になるとダウンロードが可能になります</div>
    </div>
  </section>
</div>


<?php require './views/templates/footer.php'; ?>