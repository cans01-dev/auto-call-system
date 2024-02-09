<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="sticky-top">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active">ホーム</li>
  </ol>
</nav>
<?= Components::h2("ホーム") ?>

<div class="d-flex gap-3">
  <div class="w-100">
  </div>
  <div class="flex-shrink-0" style="width: 300px;">
    <div class="sticky-top">
      <?= Components::h4("アンケート一覧") ?>
      <?php if ($surveys): ?>
        <?php foreach ($surveys as $survey): ?>
          <div class="card mb-2">
            <div class="card-body">
              <h5 class="card-title"><?= $survey["title"] ?></h5>
              <div><?= $survey["note"] ?></div>
              <div class="position-absolute top-0 end-0 p-3">
                <a href="/surveys/<?= $survey["id"] ?>" class="card-link">編集</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="text-center py-2 rounded border mb-2">アンケートがありません</div>
      <?php endif; ?>
      <?= Components::modalOpenButton("surveysCreateModal") ?>
    </div>
  </div>
</div>

<?= Components::modal("surveysCreateModal", "アンケートを新規作成", <<<EOM
  <form action="/surveys" method="post">
    CSRF
    <div class="mb-3">
      <label class="form-label">アンケートのタイトル</label>
      <input type="text" name="title" class="form-control" placeholder="〇〇のアンケート"  required>
    </div>
    <div class="mb-3">
      <label class="form-label">アンケートの説明（任意）</label>
      <textarea class="form-control" name="note" rows="3"></textarea>
    </div>
    <div class="text-end">
      <button type="submit" class="btn btn-primary">作成</button>
    </div>
  </form>
EOM); ?>

<?php require './views/templates/footer.php'; ?>