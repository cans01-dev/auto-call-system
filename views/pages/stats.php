<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item"><a href="/surveys/<?= $survey["id"] ?>"><?= $survey["title"] ?></a></li>
    <li class="breadcrumb-item active">統計</li>
  </ol>
</nav>
<?= Components::h2("統計") ?>
<section id="area">
  <?= Components::h3("エリア") ?>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">エリア</th>
        <th scope="col">進捗率(総コール数 / エリア内番号数)</th>
        <th scope="col">応答率</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($areas): ?>
        <?php foreach ($areas as $area): ?>
          <tr>
            <th scope="row">
              <?= $area["title"] ?>
              <?php if ($area["survey_id"]): ?>
                <span class="badge bg-primary">マイエリア</span>
              <?php endif; ?>
            </th>
            <td>
              <div class="progress" role="progressbar" aria-label="Example with label" aria-valuenow="44" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar" style="width: <?= round($area["progress_rate"] * 100) ?>%">
                  <?= round($area["progress_rate"] * 100) ?>%
                </div>
              </div>
              <span>(<?= $area["called_numbers"] ?> / <?= $area["all_numbers"] ?>) <?= round($area["progress_rate"] * 100, 4) ?>%</span>
            </td>
            <td>
              <?= round($area["response_rate"] * 100) ?>% (<?= $area["responsed_numbers"] ?> / <?= $area["called_numbers"] ?>)
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="4">
            <?= Components::noContent("データがありません") ?>
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</section>
<?= Components::hr() ?>
<section id="billing">
  <?= Components::h3("料金") ?>
  <p>ここで表示される料金は概算であり、実際の請求と異なる場合があります</p>
  <?php if (@$survey["billings"]): ?>
    <div class="accordion accordion-flush border" id="accordionFlushExample">
      <?php foreach ($survey["billings"] as $i => $billing): ?>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?= $i ?>" aria-expanded="false" aria-controls="flush-collapse<?= $i ?>">
              <?= date("Y年 n月", $billing["timestamp"]) ?> 料金
            </button>
          </h2>
          <div id="flush-collapse<?= $i ?>" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <dl>
                <dt>通話成立時間(秒)</dt>
                <dd><?= $billing["total_duration"] ?></dd>
                <dt>料金<span class="badge bg-secondary ms-2">通話成立時間(秒) x <?= PRICE_PER_SECOND ?>円</span></dt>
                <dd>\<?= round($billing["total_duration"] * PRICE_PER_SECOND) ?></dd>
              </dl>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <?= Components::noContent("データがありません") ?>
  <?php endif; ?>
</section>

<?php require './views/templates/footer.php'; ?>