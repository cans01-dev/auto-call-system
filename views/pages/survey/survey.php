<?php require './views/templates/header.php'; ?>

<h2 class="display-1 pt-4 pb-3">リフォームのアンケート</h2>

<div class="row gx-4">
  <div class="col-8">
    <h3>質問</h3>
    <div>
      <?php for ($i = 1; $i < 6; $i++): ?>
        <div class="card mt-4">
          <div class="card-body">
            <h5 class="card-title"><span class="badge bg-secondary me-2">ID: <?= $i ?></span>質問タイトル</h5>
            <h6 class="card-subtitle mb-2 text-body-secondary">Card subtitle</h6>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" class="btn btn-primary me-2">設定</a>
            <a href="#" class="btn btn-outline-primary">
              <i class="fa-solid fa-volume-high"></i>
              音声
            </a>
          </div>
        </div>
      <?php endfor; ?>
    </div>
  </div>
  <div class="col-4">
    <form action="/surveys" method="post">
      <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">アンケートのタイトル</label>
        <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="〇〇のアンケート">
      </div>
      <div class="mb-3">
        <label for="exampleFormControlTextarea1" class="form-label">アンケートの説明（任意）</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
      </div>
      <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
        <label class="form-check-label" for="flexSwitchCheckChecked">採用フラグ</label>
      </div>
    </form>
  </div>
</div>

<?php require './views/templates/footer.php'; ?>