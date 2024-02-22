<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item"><a href="/surveys/<?= $survey["id"] ?>#calendar"><?= $survey["title"] ?></a></li>
    <li class="breadcrumb-item active">結果: <?= date("n月d日", strtotime($reserve["date"])) ?></li>
  </ol>
</nav>
<?= Components::h2("結果: " . date("n月d日", strtotime($reserve["date"]))) ?>

<div style="max-width: 690px;">
  <section id="summary">
    <?= Components::h3("概要"); ?>
    <dl>
      <dt>ステータス</dt>
      <dd>
        <span class="badge text-bg-<?= RESERVATION_STATUS[$reserve["status"]]["bg"] ?> bg-gradient fs-6 me-1">
          <?= RESERVATION_STATUS[$reserve["status"]]["text"] ?>
        </span>
      </dd>
      <dt>開始 ~ 終了時間</dt>
      <dd><?= date("H:i", strtotime($reserve["start"])) ?> ~ <?= date("H:i", strtotime($reserve["end"])) ?></dd>
      <dt>エリア</dt>
      <dd>
        <?php foreach (Fetch::areasByReserveId($reserve["id"]) as $area): ?>
          <span class="badge text-bg-secondary fs-6 ms-1 mt-2"><?= $area["title"] ?></span>
        <?php endforeach; ?>
      </dd>
    </dl>
  </section>
  <?= Components::hr(4) ?>
  <section id="file">
    <?= Components::h3("ファイル"); ?>
    <div class="mb-4">
      <div class="mb-2">予約情報ファイル</div>
      <?php if ($reserve["reserve_file"]): ?>
        <a class="btn btn-primary" href="<?= url("/storage/users/{$survey["user_id"]}/{$reserve["reserve_file"]}") ?>" download>
          <span class="me-1">
            <i class="fa-solid fa-download fa-lg"></i>
          </span>ダウンロード
        </a>
      <?php else: ?>
        <button class="btn btn-primary" disabled>
          <span class="me-1">
            <i class="fa-solid fa-download fa-lg"></i>
          </span>ダウンロード
        </button>
      <?php endif; ?>
      <div class="form-text">確定済になるとダウンロードが可能になります</div>
    </div>
    <div>
      <div class="mb-2">結果ファイル</div>
      <?php if ($reserve["result_file"]): ?>
        <a class="btn btn-primary" href="<?= url("/storage/uploads/{$reserve["result_file"]}") ?>" download>
          <span class="me-1">
            <i class="fa-solid fa-download fa-lg"></i>
          </span>ダウンロード
        </a>
      <?php else: ?>
        <button class="btn btn-primary" disabled>
          <span class="me-1">
            <i class="fa-solid fa-download fa-lg"></i>
          </span>ダウンロード
        </button>
      <?php endif; ?>
      <div class="form-text">集計済になるとダウンロードが可能になります</div>
    </div>
  </section>
  <?php if ($reserve["status"] === 4): ?>
    <?= Components::hr(4) ?>
    <section id="result">
      <?= Components::h3("結果") ?>
      <table class="table mb-0">
        <thead>
          <tr>
            <th scope="col">応答率(応答コール数 / 総コール数)</th>
            <th scope="col">成功率(成功数 / 応答コール数)</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <?= round($survey["response_rate"] * 100) ?>% (<?= $survey["responsed_numbers"] ?> / <?= $survey["called_numbers"] ?>)
            </td>
            <td>
              <?= round($survey["success_rate"] * 100) ?>% (<?= $survey["success_numbers"] ?> / <?= $survey["responsed_numbers"] ?>)
            </td>
          </tr>
        </tbody>
      </table>
      <?= Components::hr(3) ?>
      <?php foreach ($survey["faqs"] as $faq): ?>
        <div class="card mb-2" id="faq<?= $faq["id"] ?>">
          <div class="card-body">
            <h5 class="card-title mb-3">
              <span class="badge bg-primary-subtle text-black me-2">質問</span><?= $faq["title"] ?>
            </h5>
            <p class="card-text"><?= $faq["text"] ?></p>
            <?php if ($faq["options"]): ?>
              <table class="table table-sm mb-0">
                <thead>
                  <tr>
                    <th scope="col">ダイヤル番号</th>
                    <th scope="col">TITLE</th>
                    <th scope="col">NEXT</th>
                    <th class="table-primary" style="text-align: right;">count</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($faq["options"] as $option): ?>
                  <tr>
                    <th scope="row"><span class=""><?= $option["dial"] ?></span></th>
                    <td><?= $option["title"] ?></td>
                    <td>
                      <?php if ($option["next_faq"] = Fetch::find2("faqs", [["id", "=", $option["next_faq_id"]]])): ?>
                        <?php if ($option["next_faq"]["id"] !== $faq["id"]): ?>
                          <a href="/faqs/<?= $option["next_faq"]["id"] ?>" class="badge bg-primary-subtle text-black" style="text-decoration: none;">
                            <?= $option["next_faq"]["title"]; ?>
                          </a>
                        <?php else: ?>
                          <span class="badge bg-info-subtle text-black">聞き直し</span>
                        <?php endif; ?>
                        <?php elseif ($option["next_ending"] = Fetch::find2("endings", [["id", "=", $option["next_ending_id"]]])): ?>
                          <span class="badge bg-dark-subtle text-black"><?= $option["next_ending"]["title"] ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="table-primary" style="text-align: right;"><?= $option["count"] ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php endif; ?>
            <?php if (!$faq["voice_file"]): ?>
              <div class="alert alert-danger mt-3 mb-0" role="alert">
                質問の読み上げ文章を更新して音声ファイルを生成してください
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </section>
  <?php endif; ?>
</div>

<?php if (Auth::user()["id"] !== $survey["user_id"]): ?>
  <div class="toast-container position-fixed top-0 start-50 p-3 translate-middle-x">
    <div class="p-2 align-items-center text-bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">管理者としてこのページを閲覧しています</div>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php require './views/templates/footer.php'; ?>