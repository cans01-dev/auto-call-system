<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item"><a href="/surveys/<?= $survey["id"] ?>"><?= $survey["title"] ?></a></li>
    <li class="breadcrumb-item active">アセット</li>
  </ol>
</nav>
<?= Components::h2("{$survey["title"]}: アセット") ?>
<div class="d-flex gap-3">
  <div class="w-100">
    <section id="favorite">
      <?= Components::h3("予約パターン"); ?>
      <div class="form-text mb-2 vstack gap-1">
        <span>開始・終了時間やエリア設定のテンプレートを利用してスムーズに予約の指定ができます。</span>
        <span>予約パターンの適用後に各日付ごとに設定を変更することも可能です。</span>
      </div>
      <?php if ($survey["favorites"]): ?>
        <div class="row row-cols-2 g-2 mb-2">
          <?php foreach ($survey["favorites"] as $favorite): ?>
            <div class="col">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">
                    <div class="badge" style="background-color: <?= $favorite["color"] ?>;">　</div>
                    <?= $favorite["title"] ?>
                  </h5>
                  <table class="table table-sm mb-0">
                    <tbody>
                      <tr>
                        <th nowrap>時間</th>
                        <td><?= date("H:i", strtotime($favorite["start"])) ?> - <?= date("H:i", strtotime($favorite["end"])) ?></td>
                      </tr>
                      <tr>
                        <?php if ($favorite["number_list_id"]): ?>
                          <th nowrap>マイリスト</th>
                          <td><?= Fetch::find("number_lists", $favorite["number_list_id"])["title"] ?></td>
                        <?php else: ?>
                          <th nowrap>エリア</th>
                          <td>
                            <?php if (count(Fetch::areasByFavoriteId($favorite["id"])) < 4): ?>
                              <?php foreach (Fetch::areasByFavoriteId($favorite["id"]) as $area): ?>
                                <?= $area["title"] ?>
                              <?php endforeach; ?>
                            <?php else: ?>
                              <?= count(Fetch::areasByFavoriteId($favorite["id"])) ?>件のエリア
                            <?php endif; ?>
                          </td>
                        <?php endif; ?>
                      </tr>
                    </tbody>
                  </table>
                  <div class="position-absolute top-0 end-0 p-3">
                    <a href="/favorites/<?= $favorite["id"] ?>" class="card-link">編集</a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <?= Components::noContent("予約パターンがありません") ?>
      <?php endif; ?>
      <?= Components::modalOpenButton("favoritesCreateModal"); ?>
    </section>
    <?= Components::hr(4, 0) ?>
    <section id="area">
      <?= Components::h3("マイエリア") ?>
      <?php if ($survey["areas"]): ?>
        <div class="row row-cols-2 g-2 mb-2">
          <?php foreach ($survey["areas"] as $area): ?>
            <div class="col">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">
                    <?= $area["title"] ?>
                  </h5>
                  <table class="table table-sm mb-0">
                    <tbody>
                      <tr>
                        <th nowrap>局番</th>
                        <td>
                          <?php if (count($area["stations"]) < 4): ?>
                            <?php foreach ($area["stations"] as $station): ?>
                              <?= $station["prefix"] ?>
                            <?php endforeach; ?>
                          <?php else: ?>
                            <?= count($area["stations"]) ?>件の局番
                          <?php endif; ?>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="position-absolute top-0 end-0 p-3">
                    <a href="/areas/<?= $area["id"] ?>" class="card-link">編集</a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <?= Components::noContent("マイエリアが登録されていません") ?>
      <?php endif; ?>
      <?= Components::modalOpenButton("areaCreateModal"); ?>
    </section>
    <?= Components::hr(4, 0) ?>
    <section id="mylist">
      <?= Components::h3("マイリスト") ?>
      <?php if ($survey["number_lists"]): ?>
        <div class="row row-cols-2 g-2 mb-2">
          <?php foreach ($survey["number_lists"] as $myList): ?>
            <div class="col">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">
                    <?= $myList["title"] ?>
                  </h5>
                  <table class="table table-sm mb-0">
                    <tbody>
                      <tr>
                        <th nowrap>電話番号</th>
                        <td>
                          <?php if (count($myList["numbers"]) < 4): ?>
                            <?php foreach ($myList["numbers"] as $number): ?>
                              <?= $number["number"] ?>
                            <?php endforeach; ?>
                          <?php else: ?>
                            <?= count($myList["numbers"]) ?>件の電話番号
                          <?php endif; ?>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="position-absolute top-0 end-0 p-3">
                    <a href="/number_lists/<?= $myList["id"] ?>" class="card-link">編集</a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <?= Components::noContent("マイリストが登録されていません") ?>
      <?php endif; ?>
      <?= Components::modalOpenButton("numberListCreateModal"); ?>
    </section>
  </div>
  <div class="flex-shrink-0 sticky-aside" style="width: 300px;">
    <div class="sticky-top">
      <section id="setting">
        <?= Components::h4("設定"); ?>
        <div class="mb-2">
          <form action="/surveys/<?= $survey["id"] ?>" method="post">
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
                <option value="">--未設定--</option>
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

<?= Components::modal("surveysCreateModal", "アンケートを新規作成", <<<EOM
  <form action="/surveys" method="post">
    CSRF
    <div class="mb-3">
      <label class="form-label">アンケートのタイトル</label>
      <input type="text" name="title" class="form-control" placeholder="〇〇のアンケート"  required>
    </div>
    <div class="mb-3">
      <label class="form-label">アンケートの説明（任意）</label>
      <textarea class="form-control" name="note" rows="3"></textarea>
    </div>
    <div class="text-end">
      <button type="submit" class="btn btn-primary">作成</button>
    </div>
  </form>
EOM); ?>

<!-- favoritesCreateModal -->
<div class="modal fade" id="favoritesCreateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">予約パターンを新規作成</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/favorites" method="post">
          <?= csrf() ?>
          <div class="mb-3">
            <label class="form-label">予約パターンのタイトル</label>
            <input type="text" name="title" class="form-control" placeholder="〇〇の予約パターン" required>
          </div>
          <div class="mb-3">
            <label class="form-label">ラベルカラーを選択</label>
            <div class="d-flex gap-4">
              <?php foreach (COLOR_PALLET as $k => $color): ?>
              <div class="form-check">
                <input
                  class="form-check-input" type="radio" name="color" value="<?= $color ?>" id="i<?= $color ?>"
                  <?= $k === 0 ? "checked" : "" ?>
                >
                <label class="form-check-label" for="i<?= $color ?>">
                  <div class="badge border border-dark" style="background-color: <?= $color ?>;">　</div>
                </label>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">開始時間・終了時間</label>
            <div class="input-group">
              <select name="start" class="form-select" required>
                <option value="">選択してください</option>
                <?php foreach (make_times(MIN_TIME, MAX_TIME, TIME_STEP) as $ts): ?>
                <option value="<?= date("H:i", $ts) ?>">
                  <?= date("H:i", $ts) ?>
                </option>
                <?php endforeach; ?>
              </select>
              <span class="input-group-text">~</span>
              <select name="end" class="form-select" required>
                <option value="">選択してください</option>
                <?php foreach (make_times(MIN_TIME, MAX_TIME, TIME_STEP) as $ts): ?>
                <option value="<?= date("H:i", $ts) ?>">
                  <?= date("H:i", $ts) ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="text-end">
            <input type="hidden" name="survey_id" value="<?= $survey["id"] ?>">
            <button type="submit" class="btn btn-primary">ページを移動してエリアを設定</button>
          </div>
        </form>
      </div>
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

<?php require './views/templates/footer.php'; ?>