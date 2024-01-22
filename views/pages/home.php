<?php require './views/templates/header.php'; ?>

<?= Components::h2("ホーム") ?>

<div class="d-flex gap-3">
  <div class="w-100">

  </div>
  <div class="flex-shrink-0" style="width: 300px;">
    <div class="sticky-top">
      <?= Components::h4("アンケート一覧") ?>
      <?php for ($i = 0; $i < 3; $i++): ?>
        <div class="card mb-2">
          <div class="card-body">
            <h5 class="card-title">リフォームのアンケート</h5>
            <h6 class="card-subtitle mb-2 text-body-secondary">コール済: 4631</h6>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <div class="position-absolute top-0 end-0 p-3">
              <a href="/surveys/<?= $i ?>" class="card-link">編集</a>
            </div>
          </div>
        </div>
      <?php endfor; ?>
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
          <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">アンケートのタイトル</label>
            <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="〇〇のアンケート">
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