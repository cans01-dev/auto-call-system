<?php require './views/templates/header.php'; ?>

<?= Components::h2("結果: 1/28") ?>

<div style="max-width: 480px;">
  <section class="mb-5" id="summary">
    <?= Components::h3("概要"); ?>
    <dl>
      <dt>開始 ~ 終了時間</dt><dd>17:00 ~ 21:00</dd>
      <dt>エリア</dt>
      <dd>
        <?php for ($i = 0; $i < 3; $i++): ?>
          <span class="badge text-bg-secondary me-1">関東・甲信越</span>
        <?php endfor; ?>
      </dd>
    </dl>
  </section>
  <section id="file">
    <?= Components::h3("ファイル"); ?>
    <div class="form-text mb-1">
      結果ファイルをダウンロードすることができます
    </div>
    <a class="btn btn-primary" href="/dev/sample.txt" download>
      <span class="me-1">
        <i class="fa-solid fa-download fa-lg"></i>
      </span>ダウンロード
    </a>
  </section>
</div>


<?php require './views/templates/footer.php'; ?>