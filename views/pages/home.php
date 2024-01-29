<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active">ホーム</li>
  </ol>
</nav>
<?= Components::h2("ホーム") ?>

<div class="d-flex gap-3">
  <div class="w-100">
    <?= Allow::option(Fetch::find("options", 3)) ?>
  </div>
  <div class="flex-shrink-0" style="width: 300px;">
    <div class="sticky-top">
      <?= Components::h4("アンケート一覧") ?>
      <?php foreach ($surveys as $survey): ?>
        <div class="card mb-2">
          <div class="card-body">
            <h5 class="card-title"><?= $survey["title"] ?></h5>
            <h6 class="card-subtitle mb-2 text-body-secondary">コール済: ---</h6>
            <p class="card-text"><?= $survey["note"] ?></p>
            <div class="position-absolute top-0 end-0 p-3">
              <a href="/surveys/<?= $survey["id"] ?>" class="card-link">編集</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
      <?= Components::modalOpenButton("surveysCreateModal") ?>
    </div>
  </div>
</div>


<!-- surveysCreateModal -->
<div class="modal fade" id="surveysCreateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">アンケートを新規作成</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/surveys" method="post">
          <?= csrf() ?>
          <div class="mb-3">
            <label class="form-label">アンケートのタイトル</label>
            <input type="text" name="title" class="form-control" placeholder="〇〇のアンケート"  required>
          </div>
          <div class="mb-3">
            <label class="form-label">アンケートの説明（任意）</label>
            <textarea class="form-control" name="note" rows="3"></textarea>
          </div>
          <div class="text-end">
            <input type="hidden" name="surveyId" value="<?= $surveyId ?>">
            <button type="submit" class="btn btn-primary">作成</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require './views/templates/footer.php'; ?>