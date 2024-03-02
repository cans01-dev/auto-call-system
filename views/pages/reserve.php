<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <?php if (@$favorite): ?>
      <li class="breadcrumb-item"><a href="/surveys/<?= $survey["id"] ?>#calendar"><?= $survey["title"] ?></a></li>
      <li class="breadcrumb-item active">予約パターン: <?= $favorite["title"] ?></li>
    <?php else: ?>
      <li class="breadcrumb-item">
        <a href="/surveys/<?= $survey["id"] ?>?month=<?= date("n", $reserve["date_ts"]) ?>&year=<?= date("Y", $reserve["date_ts"]) ?>#calendar">
          <?= $survey["title"] ?>
        </a>
      </li>
      <li class="breadcrumb-item active">予約: <?= date("n月d日", strtotime($reserve["date"])) ?></li>
    <?php endif; ?>
  </ol>
</nav>
<?php if (@$favorite): ?>
  <?= Components::h2("予約パターン: " . $favorite["title"]) ?>
<?php else: ?>
  <?= Components::h2("予約: " . date("n月d日", strtotime($reserve["date"]))) ?>
<?php endif; ?>

<div class="d-flex gap-3">
  <div class="w-100" data-bs-spy="scroll" data-bs-target="#navbar-example2" tabindex="0">
    <?php if (@$favorite || $reserve["status"] === 0): ?>
      <section id="area">
        <?= Components::h3("エリア設定"); ?>
        <div class="form-text mb-2">
          指定されたエリアからランダムで電話番号が指定されコールされます
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
                  <?php if (@$favorite): ?>
                    <form action="/favorites/<?= $favorite["id"] ?>/areas" method="post">
                  <?php else: ?>
                    <form action="/reserves/<?= $reserve["id"] ?>/areas" method="post">
                  <?php endif; ?>
                    <?= csrf() ?>
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
            <div class="">デフォルトのエリア</div>
          </div>
          <div class="p-2 bg-white text-bg-light">
            <p>地域名を入力してまとめて選択</p>
            <?php if (@$favorite): ?>
              <form action="/favorites/<?= $favorite["id"] ?>/areas_by_word" method="post">
            <?php else: ?>
              <form action="/reserves/<?= $reserve["id"] ?>/areas_by_word" method="post">
            <?php endif; ?>
              <?= csrf() ?>
              <div class="input-group" style="max-width: 320px;">
                <input type="text" class="form-control" name="word" placeholder="関東、中部などと入力して実行" required>
                <button type="submit" class="btn btn-outline-secondary">実行</button>
              </div>
            </form>
          </div>
          <ul class="list-group list-group-flush area-list-group">
            <?php foreach (Fetch::query("SELECT * FROM areas WHERE survey_id IS NULL", "fetchAll") as $area): ?>
              <li class="list-group-item d-flex align-items-center justify-content-between">
                <div>
                  <?= $area["title"] ?>
                  <a href="/areas/<?= $area["id"] ?>" class="text-body-tertiary">
                    <i class="fa-solid fa-circle-info"></i>
                  </a>
                </div>
                <?php if (@$favorite): ?>
                  <form action="/favorites/<?= $favorite["id"] ?>/areas" method="post">
                <?php else: ?>
                  <form action="/reserves/<?= $reserve["id"] ?>/areas" method="post">
                <?php endif; ?>
                  <?= csrf() ?>
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
      </section>
    <?php else: ?>
      <section id="file">
        <?= Components::h3("ファイル"); ?>
        <div class="row">
          <div class="col">
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
          <div class="col">
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
        </div>
      </section>
      <?php if ($reserve["status"] === 4): ?>
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
    <?php endif; ?>
  </div>
  <div class="flex-shrink-0 sticky-aside" style="width: 300px;">
    <div class="sticky-top">
      <section id="summary">
        <?= Components::h4("基本設定"); ?>
        <form method="post" id="updateReserveForm">
          <?= csrf() ?>
          <?= method("PUT") ?>
          <div class="mb-3">
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
          </div>
          <div class="mb-3">
            <label class="form-label">エリアモード</label>
            <select class="form-select" name="number_list_id">
              <option value="">デフォルト</option>
              <!-- 2024 3/2 エリアモードの実装をする -->
              <?php foreach ($survey["number_lists"] as $myList): ?>>
                <option
                  value="<?= $myList["id"] ?>"
                  <?= $myList["id"] === $reserve["number_list_id"] ? "selected" : ""; ?>
                >
                  <?= $myList["title"] ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <?php if (@$favorite): ?>
            <div class="mb-3">
              <label class="form-label">予約パターンのタイトル</label>
              <input type="text" name="title" class="form-control" value="<?= $favorite["title"] ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">ラベルカラー</label>
              <input type="color" name="color" class="form-control form-control-color" value="<?= $favorite["color"] ?>" required>
            </div>
          <?php else: ?>
            <div class="mb-3">
              <label class="form-label">ステータス</label>
              <div>
                <span class="badge text-bg-<?= RESERVATION_STATUS[$reserve["status"]]["bg"] ?> bg-gradient fs-6">
                  <?= RESERVATION_STATUS[$reserve["status"]]["text"] ?>
                </span>
              </div>
            </div>
          <?php endif; ?>
        </form>
        <div class="mb-3">
          <label class="form-label">エリア</label>
          <?php if ($reserve["areas"]): ?>
            <div>
              <?php foreach ($reserve["areas"] as $area): ?>
                <span class="badge bg-secondary fs-6 mb-1">
                  <?php if (@$favorite): ?>
                    <form action="/favorites_areas/<?= $area["fa_id"] ?>" method="post">
                  <?php else: ?>
                    <form action="/reserves_areas/<?= $area["ra_id"] ?>" method="post">
                  <?php endif; ?>
                    <?= csrf() ?>
                    <?= method("DELETE") ?>
                    <?= $area["title"] ?>
                    <?php if (@$favorite || $reserve["status"] === 0): ?>
                      <button type="submit" class="d-inline bg-transparent border-0">
                        <i class="fa-solid fa-xmark text-white"></i>
                      </button>
                    <?php endif; ?>
                  </form>
                </span>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <?= Components::noContent("局番が設定されていません") ?>
          <?php endif; ?>
        </div>
        <div class="text-end">
          <button
            type="submit" class="btn btn-dark" form="updateReserveForm"
            <?= @$favorite || $reserve["status"] === 0 ? "" : "disabled" ?>
          >
            更新
          </button>
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
      <section id="myArea">
        <?= Components::h4("マイエリア"); ?>
        <?php if ($survey["areas"]): ?>
          <?php foreach ($survey["areas"] as $myArea): ?>
            <div class="card mb-2">
              <div class="card-body">
                <h5 class="card-title">
                  <?= $myArea["title"] ?>
                </h5>
                <?php if ($myArea["stations"] = Fetch::get("stations", $myArea["id"], "area_id")): ?>
                  <?php if (count($myArea["stations"]) < 4): ?>
                    <div>
                      <?php foreach ($myArea["stations"] as $station): ?>
                        <span class="badge bg-secondary fs-6">
                          <?= $station["prefix"] ?>
                        </span>
                      <?php endforeach; ?>
                    </div>
                  <?php else: ?>
                    <span class="badge bg-secondary fs-6"><?= count($myArea["stations"]) ?>件の局番</span>
                  <?php endif; ?>
                <?php else: ?>
                  <?= Components::noContent("局番が設定されていません") ?>
                <?php endif; ?>
                <div class="position-absolute top-0 end-0 p-3">
                  <a href="/areas/<?= $myArea["id"] ?>" class="card-link">編集</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <?= Components::noContent("マイエリアがありません") ?>
        <?php endif; ?>
        <?= Components::modalOpenButton("areaCreateModal"); ?>
      </section>
      <?= Components::hr(4) ?>
      <section id="myList">
        <?= Components::h4("マイリスト"); ?>
        <?php if ($survey["number_lists"]): ?>
          <?php foreach ($survey["number_lists"] as $myList): ?>
            <div class="card mb-2">
              <div class="card-body">
                <h5 class="card-title">
                  <?= $myList["title"] ?>
                </h5>
                <span class="badge bg-secondary fs-6"><?= $myList["count"] ?>件の電話番号</span>
                <div class="position-absolute top-0 end-0 p-3">
                  <form action="/number_lists/<?= $myList["id"] ?>" onsubmit="return window.confirm('本当に削除しますか？')" method="post">
                    <?= csrf() ?>
                    <?= method("DELETE") ?>
                    <button class="btn btn-link">削除</button>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <?= Components::noContent("マイリストがありません") ?>
        <?php endif; ?>
        <?= Components::modalOpenButton("numberListCreateModal"); ?>
      </section>
    </div>
  </div>
</div>

<?= Components::modal("areaCreateModal", "マイエリアを新規登録", <<<EOL
  <form action="/areas" method="post" id="areaCreateForm">
    CSRF
    <input type="hidden" name="survey_id" value="{$survey["id"]}">
    <div class="mb-3">
      <label class="form-label">マイエリアのタイトル</label>
      <input type="text" name="title" class="form-control" placeholder="〇〇のエリア" required>
    </div>
    <div class="text-end">
      <button type="submit" class="btn btn-primary">ページを移動して局番を設定</button>
    </div>
  </form>
EOL); ?>

<?= Components::modal("numberListCreateModal", "マイリストを新規登録", <<<EOL
  <form action="/surveys/{$survey["id"]}/number_lists" method="post" enctype="multipart/form-data" id="numberListCreateForm">
    CSRF
    <div class="mb-3">
      <label class="form-label">マイリストのタイトル</label>
      <input type="text" name="title" class="form-control" placeholder="〇〇のリスト" required>
    </div>
    <div class="mb-3">
      <label class="form-label">インポートするファイル</label>
      <input type="file" name="file" class="form-control" accept="text/csv" required>
    </div>
    <div class="text-end">
      <button type="submit" class="btn btn-primary">登録</button>
    </div>
    <div class="form-text">
      一列目がハイフン有りの電話番号になっているCSVファイルを指定してください
    </div>
  </form>
EOL); ?>

<?= Auth::user()["id"] !== $survey["user_id"]? Components::watchOnAdmin("管理者として閲覧専用でこのページを閲覧しています") : "" ?>

<?php require './views/templates/footer.php'; ?>