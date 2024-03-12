<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item"><a href="/surveys/<?= $survey["id"] ?>"><?= $survey["title"] ?></a></li>
    <li class="breadcrumb-item active">統計</li>
  </ol>
</nav>
<?= Components::h2("{$survey["title"]}: 統計") ?>
<div class="d-flex gap-3">
  <div class="w-100">
    <section id="area">
      <?= Components::h3("エリア、リスト別の統計を見る") ?>
      <h6>デフォルトのエリア</h6>
      <div class="mb-4">
        <?php foreach ($def_areas as $area): ?>
          <a class="btn btn-dark btn-sm mb-1" href="/surveys/<?= $survey["id"] ?>/stats/areas/<?= $area["id"] ?>">
            <?= $area["title"] ?>
          </a>
        <?php endforeach; ?>
      </div>
      <h6>マイエリア</h6>
      <div class="mb-4">
        <?php foreach ($my_areas as $area): ?>
          <a class="btn btn-dark btn-sm mb-1" href="/surveys/<?= $survey["id"] ?>/stats/areas/<?= $area["id"] ?>">
            <?= $area["title"] ?>
          </a>
        <?php endforeach; ?>
      </div>
      <h6>マイリスト</h6>
      <div class="mb-4">
        <?php foreach ($my_lists as $number_list): ?>
          <a class="btn btn-dark btn-sm mb-1" href="/number_lists/<?= $number_list["id"] ?>">
            <?= $area["title"] ?>
          </a>
        <?php endforeach; ?>
      </div>
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
  </div>
  <div class="flex-shrink-0 sticky-aside" style="width: 300px;">
    <div class="sticky-top">
      <div class="card bg-light">
        <div class="card-body">
          <table class="table table-light mb-0">
            <tr>
              <th>集計済の予約数</th>
              <td><?= $stats["collected_reserves"] ?>件</td>
            </tr>
            <tr>
              <th>総コール数</th>
              <td><?= $stats["all_calls"] ?>件</td>
            </tr>
            <tr>
              <th>応答率</th>
              <td>
                <?= round($stats["responsed_calls"] / $stats["all_calls"] * 100) ?>%<br>
                (<?= $stats["responsed_calls"] ?> / <?= $stats["all_calls"] ?>)
              </td>
            </tr>
            <tr>
              <th>成功率</th>
              <td>
                <?= round($stats["success_calls"] / $stats["responsed_calls"] * 100) ?>%<br>
                (<?= $stats["success_calls"] ?> / <?= $stats["responsed_calls"] ?>)
              </td>
            </tr>
            <tr>
              <th>平均アクション数</th>
              <td><?= round($stats["all_actions"] / $stats["responsed_calls"], 2) ?>回</td>
            </tr>
            <tr>
              <th>アクション率</th>
              <td>
                <?= round($stats["action_calls"] / $stats["responsed_calls"] * 100) ?>%<br>
                (<?= $stats["action_calls"] ?> / <?= $stats["responsed_calls"] ?>)
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require './views/templates/footer.php'; ?>