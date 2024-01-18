<?php require './views/templates/header.php'; ?>

<?= Components::h2("ホーム") ?>

<?= Components::h3("アンケート一覧") ?>

<div class="row row-cols-2 g-3">
  <?php for ($i = 0; $i < 10; $i++): ?>
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">リフォームのアンケート</h5>
          <h6 class="card-subtitle mb-2 text-body-secondary">コール済: 4631</h6>
          <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
          <a href="/surveys/<?= $i ?>" class="btn btn-primary">設定</a>
        </div>
      </div>
    </div>
  <?php endfor; ?>
</div>

<?php require './views/templates/footer.php'; ?>