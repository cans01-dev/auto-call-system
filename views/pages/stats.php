<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item active"><?= $survey["title"] ?></li>
  </ol>
</nav>
<?= Components::h2($survey["title"]) ?>

<div class="d-flex gap-3">
  <div class="w-100" data-bs-spy="scroll" data-bs-target="#navbar-example2" tabindex="0">
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
                <th scope="row"><?= $area["title"] ?></th>
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
  </div>
  <div class="flex-shrink-0 sticky-aside" style="width: 300px;">
    <div class="sticky-top">
      <section id="summary">
        <?= Components::h4("設定"); ?>
        <div class="mb-2">
          <form method="post">
            <?= csrf() ?>
            <?= method("PUT") ?>
            <div class="mb-3">
              <label class="form-label">アンケートのタイトル</label>
              <input type="text" name="title" class="form-control" value="<?= $survey["title"] ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">アンケートの説明（任意）</label>
              <textarea class="form-control" name="note" rows="3"><?= $survey["note"] ?></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">成功エンディング</label>
              <select class="form-select" name="success_ending_id">
                <?php foreach ($survey["endings"] as $ending): ?>
                  <option
                    value="<?= $ending["id"] ?>"
                    <?= $ending["id"] === $survey["success_ending_id"] ? "selected" : ""; ?>
                  >
                  <?= $ending["title"] ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">
                生成する音声のタイプ
                <?= Components::infoBtn() ?>
              </label>
              <select class="form-select" name="voice_name">
                <?php foreach (VOICES as $voice): ?>
                  <option
                    value="<?= $voice["name"] ?>"
                    <?= $voice["name"] === $survey["voice_name"] ? "selected" : ""; ?>
                  >
                  <?= "{$voice["name"]} ({$voice["gender"]})" ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="text-end mb-2">
              <button type="submit" class="btn btn-dark">更新</button>
            </div>
            <div class="form-text">
              音声タイプの変更は、既に生成された音声ファイルには影響せず、既存の文章に反映させるには全て更新する必要があります
            </div>
          </form>
        </div>
        <div class="text-end">
          <button type="button" class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#advancedSettingsModal">
            詳細設定
          </button>
        </div>
      </section>
    </div>
  </div>
</div>

<?= Components::modal("advancedSettingsModal", "詳細設定", <<<EOL
  <div class="mb-3">
    <form
    action="/surveys/{$survey["id"]}/all_voice_file_re_gen"
    method="post"
    onsubmit="return window.confirm('本当に実行しますか？この操作には時間がかかることがあります')"
    >
      CSRF
      <button class="btn btn-outline-danger">全ての音声ファイルを更新する</button>
      <div class="form-text">
        このアンケートのグリーティングや全てのエンディング、質問の音声ファイルが現在の設定で再生成されます。
      </div>
    </form>
  </div>
  <div class="mb-3">
    <form
    action="/surveys/{$survey["id"]}"
    method="post"
    onsubmit="return window.confirm('本当に削除しますか？')"
    >
      CSRF
      METHOD_DELETE
      <button class="btn btn-outline-danger">アンケートを削除する</button>
      <div class="form-text">
        このアンケートに関連する全てのデータ（質問、予約、結果など）が削除されます。
      </div>
    </form>
  </div>
EOL); ?>

<?php if (Auth::user()["id"] !== $survey["user_id"]): ?>
  <div class="toast-container position-fixed top-0 start-50 p-3 translate-middle-x">
    <div class="p-2 align-items-center text-bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">管理者として閲覧専用でこのページを閲覧しています</div>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php require './views/templates/footer.php'; ?>