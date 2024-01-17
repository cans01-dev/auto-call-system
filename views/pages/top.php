<?php require './views/templates/header.php'; ?>

<h2 class="display-1 pt-4 mb-5">ホーム</h2>

<h3 class="display-6 mb-4">アンケート一覧</h2>

<div class="row row-cols-2 g-3">
  <?php for ($i = 0; $i < 10; $i++): ?>
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">リフォームのアンケート</h5>
          <h6 class="card-subtitle mb-2 text-body-secondary">コール済: 4631</h6>
          <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
          <a href="#" class="btn btn-primary">設定</a>
        </div>
      </div>
    </div>
  <?php endfor; ?>
</div>

<?php require './views/templates/footer.php'; ?>