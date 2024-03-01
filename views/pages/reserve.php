<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item">
      <a href="/surveys/<?= $survey["id"] ?>?month=<?= date("n", $reserve["date_ts"]) ?>&year=<?= date("Y", $reserve["date_ts"]) ?>#calendar">
        <?= $survey["title"] ?>
      </a>
    </li>
    <li class="breadcrumb-item active">予約済: <?= date("n月d日", strtotime($reserve["date"])) ?></li>
  </ol>
</nav>
<?= Components::h2("予約: " . date("n月d日", strtotime($reserve["date"]))) ?>

<div class="d-flex gap-3">
  <div class="w-100" data-bs-spy="scroll" data-bs-target="#navbar-example2" tabindex="0">
    <?php if ($reserve["status"] === 0): ?>
      <section id="area">
        <?= Components::h3("エリア設定"); ?>
          <div class="mb-4">
            <div class="card border-dark">
              <div class="card-header">選択済のエリア</div>
              <ul class="list-group list-group-flush area-list-group">
                <?php if ($reserve["areas"]): ?>
                  <?php foreach ($reserve["areas"] as $area): ?>
                    <li class="list-group-item d-flex align-items-center justify-content-between">
                      <div>
                        <?= $area["title"] ?>
                        <?php if ($area["survey_id"]): ?>
                          <span class="badge bg-primary">マイエリア</span>
                        <?php else: ?>
                          <a href="/areas/<?= $area["id"] ?>" class="text-body-tertiary">
                            <i class="fa-solid fa-circle-info"></i>
                          </a>
                        <?php endif; ?>
                      </div>
                      <form action="/reserves_areas/<?= $area["ra_id"] ?>" method="post">
                        <?= csrf() ?>
                        <?= method("DELETE") ?>
                        <button type="submit" class="btn btn-danger">削除</button>
                      </form>
                    </li>
                  <?php endforeach; ?>
                <?php else: ?>
                  <li class="list-group-item">エリアが選択されていません</li>
                <?php endif; ?>
              </ul>
            </div>
            <?php if (!$reserve["areas"]): ?>
              <div class="alert alert-danger mt-2 mb-0" role="alert">
                エリアが指定されていません
              </div>
            <?php endif; ?>
          </div>
          <div class="card mb-4">
            <div class="card-header">
              マイエリア
            </div>
            <?php if ($survey["areas"]): ?>
              <ul class="list-group list-group-flush area-list-group">
                <?php foreach ($survey["areas"] as $area): ?>
                  <li class="list-group-item d-flex align-items-center justify-content-between">
                    <div>
                      <?= $area["title"] ?>
                      <span class="badge bg-primary">マイエリア</span>
                    </div>
                    <form action="/reserves_areas" method="post">
                      <?= csrf() ?>
                      <input type="hidden" name="reserve_id" value="<?= $reserve["id"] ?>">
                      <input type="hidden" name="area_id" value="<?= $area["id"] ?>">
                      <button
                        type="submit" class="btn btn-primary"
                        <?= in_array($area["id"], array_column($reserve["areas"], "id")) ? "disabled" : "" ?>
                      >
                        追加
                      </button>
                    </form>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <div class="text-center py-3">マイエリアが登録されていません</div>
            <?php endif; ?>
          </div>
          <div class="card mb-4">
            <div class="card-header">
              <div class="mb-2">デフォルトのエリア</div>
              <div class="border p-2 mb-2 bg-white">
                <p>地域名を入力してまとめて選択</p>
                <form action="/reserves_areas/by-word" method="post">
                  <?= csrf() ?>
                  <input type="hidden" name="reserve_id" value="<?= $reserve["id"] ?>">
                  <div class="input-group">
                    <input type="text" class="form-control" name="word" placeholder="関東、中部などと入力して実行" required>
                    <button type="submit" class="btn btn-outline-secondary">実行</button>
                  </div>
                </form>
              </div>
            </div>
            <ul class="list-group list-group-flush area-list-group">
              <?php foreach ($areas as $area): ?>
                <li class="list-group-item d-flex align-items-center justify-content-between">
                  <div>
                    <?= $area["title"] ?>
                    <a href="/areas/<?= $area["id"] ?>" class="text-body-tertiary">
                      <i class="fa-solid fa-circle-info"></i>
                    </a>
                  </div>
                  <form action="/reserves_areas" method="post">
                    <?= csrf() ?>
                    <input type="hidden" name="reserve_id" value="<?= $reserve["id"] ?>">
                    <input type="hidden" name="area_id" value="<?= $area["id"] ?>">
                    <button
                      type="submit" class="btn btn-primary"
                      <?= in_array($area["id"], array_column($reserve["areas"], "id")) ? "disabled" : "" ?>
                    >
                      追加
                    </button>
                  </form>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
          <div class="form-text">
            指定されたエリアからランダムで電話番号が指定されコールされます
          </div>
      </section>
    <?php elseif ($reserve["status"] === 1): ?>
      <?= Components::noContent("この予約は締め切りを過ぎているので変更できません、集計済になると結果が閲覧できます") ?>
    <?php elseif ($reserve["status"] === 4): ?>
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
      <?= Components::hr(4) ?>
      <section id="result">
        <?= Components::h3("結果") ?>
        <?php if ($calls): ?>
          <dl class="container mb-2">
            <div class="row">
              <div class="col">
                <dt>応答率(応答コール数 / 総コール数)</dt>
                <dd>
                  <?= round($survey["response_rate"] * 100) ?>% (<?= $survey["responsed_numbers"] ?> / <?= $survey["called_numbers"] ?>)
                </dd>
              </div>
              <div class="col">
                <dt>成功率(成功数 / 応答コール数)</dt>
                <dd>
                  <?= round($survey["success_rate"] * 100) ?>% (<?= $survey["success_numbers"] ?> / <?= $survey["responsed_numbers"] ?>)
                </dd>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <dt>平均アクション数(聞き直しを除く)</dt>
                <dd><?= round($survey["action_avg"], 2) . " / " . count($survey["faqs"]) ?></dd>
              </div>
              <div class="col">
                <dt>アクション率<br><small>(最低一回でもアクションがあった率 / 応答コール数)</small></dt>
                <dd><?= round($survey["action_rate"] * 100) ?>% (<?= $survey["action_numbers"] ?> / <?= $survey["responsed_numbers"] ?>)</dd>
              </div>
            </div>
          </dl>
          <div class="mb-3">
            <a
              href="/surveys/<?= $survey["id"] ?>/calls?start=<?= $reserve["date"] ?>&end=<?= $reserve["date"] ?>"
              class="btn btn-outline-primary"
            >
              <span class="me-1"><i class="fa-solid fa-table fa-lg"></i></span>コール一覧
            </a>
            <div class="form-text">CSVファイルのダウンロードはこちらからできます</div>
          </div>
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
                        <th class="table-primary" style="text-align: right; width: 90px;">count</th>
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
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>データがありません</p>
        <?php endif; ?>
      </section>
    <?php endif; ?>
  </div>
  <div class="flex-shrink-0 sticky-aside" style="width: 300px;">
    <div class="sticky-top">
      <section id="summary">
        <?= Components::h4("基本設定"); ?>
        <div class="mb-3">
          <form method="post" id="updateReserveForm">
            <?= csrf() ?>
            <?= method("PUT") ?>
            <label class="form-label">開始時間・終了時間</label>
            <div class="input-group">
              <select name="start" class="form-select" required <?= $reserve["status"] ? "disabled" : "" ?>>
                <option value="">選択してください</option>
                <?php foreach (make_times(MIN_TIME, MAX_TIME, TIME_STEP) as $ts): ?>
                <option value="<?= date("H:i", $ts) ?>" <?= $reserve["start"] == date("H:i:s", $ts) ? "selected" : ""; ?>>
                  <?= date("H:i", $ts) ?>
                </option>
                <?php endforeach; ?>
              </select>
              <span class="input-group-text">~</span>
              <select name="end" class="form-select" required <?= $reserve["status"] ? "disabled" : "" ?>>
                <option value="">選択してください</option>
                <?php foreach (make_times(MIN_TIME, MAX_TIME, TIME_STEP) as $ts): ?>
                <option value="<?= date("H:i", $ts) ?>" <?= $reserve["end"] == date("H:i:s", $ts) ? "selected" : ""; ?>>
                  <?= date("H:i", $ts) ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
          </form>
        </div>
        <div class="mb-3">
          <label class="form-label">ステータス</label>
          <div>
            <span class="badge text-bg-<?= RESERVATION_STATUS[$reserve["status"]]["bg"] ?> bg-gradient fs-6">
              <?= RESERVATION_STATUS[$reserve["status"]]["text"] ?>
            </span>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">エリア</label>
          <?php if ($reserve["areas"]): ?>
            <div>
              <?php foreach ($reserve["areas"] as $area): ?>
                <span class="badge bg-secondary fs-6 mb-1">
                  <form action="/reserves_areas/<?= $area["ra_id"] ?>" method="post">
                    <?= csrf() ?>
                    <?= method("DELETE") ?>
                    <?= $area["title"] ?>
                    <button type="submit" class="d-inline bg-transparent border-0">
                      <!-- 2024 3/1 ｘボタンを白くして、予約済以外で押せないようにする、従来の「選択済のエリア」消す -->
                      <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                  </form>
                </span>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <?= Components::noContent("局番が設定されていません") ?>
          <?php endif; ?>
        </div>
        <div class="text-end">
          <button type="submit" class="btn btn-dark" form="updateReserveForm" <?= $reserve["status"] ? "disabled" : "" ?>>更新</button>
        </div>
        <form method="post" onsubmit="return window.confirm('本当に削除しますか？')">
          <?= csrf() ?>
          <?= method("DELETE") ?>
          <div class="text-end">
            <input type="submit" class="btn btn-link" value="この予約を削除">
          </div>
        </form>
      </section>
      <?= Components::hr(4) ?>
      <section id="setting">
        <?= Components::h4("マイエリア"); ?>
        <?php if ($survey["areas"]): ?>
          <?php foreach ($survey["areas"] as $area): ?>
            <div class="card mb-2">
              <div class="card-body">
                <h5 class="card-title">
                  <?= $area["title"] ?>
                </h5>
                <div class="position-absolute top-0 end-0 p-3">
                  <a href="/areas/<?= $area["id"] ?>" class="card-link">編集</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <?= Components::noContent("マイエリアがありません") ?>
        <?php endif; ?>
        <?= Components::modalOpenButton("areaCreateModal"); ?>
      </section>
    </div>
  </div>
</div>

<!-- areaCreateModal -->
<div class="modal fade" id="areaCreateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">マイエリアを新規登録</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/areas" method="post" id="areaCreateForm">
          <?= csrf() ?>
          <input type="hidden" name="survey_id" value="<?= $survey["id"] ?>">
          <div class="mb-3">
            <label class="form-label">マイエリアのタイトル</label>
            <input type="text" name="title" class="form-control" placeholder="〇〇のエリア" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" form="areaCreateForm">ページを移動して局番を設定</button>
      </div>
    </div>
  </div>
</div>

<?= Auth::user()["id"] !== $survey["user_id"]? Components::watchOnAdmin("管理者として閲覧専用でこのページを閲覧しています") : "" ?>

<?php require './views/templates/footer.php'; ?>