<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item"><a href="/surveys/<?= $survey["id"] ?>"><?= $survey["title"] ?></a></li>
    <li class="breadcrumb-item active">マイエリア: <?= $area["title"] ?></li>
  </ol>
</nav>
<?= Components::h2("マイエリア: {$area["title"]}") ?>
<div class="d-flex gap-3">
  <div class="w-100" data-bs-spy="scroll" data-bs-target="#navbar-example2" tabindex="0">
    <section id="addStation">
      <?= Components::h3("局番を追加"); ?>
    </section>
  </div>
  <div class="flex-shrink-0 sticky-aside" style="width: 300px;">
    <div class="sticky-top">
      <section id="summary">
        <?= Components::h4("設定"); ?>
        <div style="max-width: 480px;">
          <form method="post">
            <?= csrf() ?>
            <?= method('PUT') ?>
            <div class="mb-3">
              <label class="form-label">タイトル</label>
              <input type="text" name="title" class="form-control" value="<?= $area["title"] ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">局番</label>
              <?php if ($area["stations"]): ?>
                <?php foreach ($area["stations"] as $station): ?>
                  <span class="badge bg-secondary"><?= $station["prefix"] ?></span>
                <?php endforeach; ?>
              <?php else: ?>
                <?= Components::noContent("局番が設定されていません") ?>
              <?php endif; ?>
            </div>
            <div class="text-end">
              <button type="submit" class="btn btn-dark">更新</button>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>

<?php require './views/templates/footer.php'; ?>