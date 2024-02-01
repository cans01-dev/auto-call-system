<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="sticky-top">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item active"><?= $survey["title"] ?></li>
  </ol>
</nav>
<?= Components::h2($survey["title"]) ?>

<div class="d-flex gap-3">
  <div class="w-100" data-bs-spy="scroll" data-bs-target="#navbar-example2" tabindex="0">
    <section id="greeting-ending">
      <?= Components::h3("グリーティング・エンディング") ?>
      <div class="form-text mb-2">
        グリーティングで通話の最初に流れるテキストを編集できます。<br>
        エンディングは回答の結果によって変更することができます。
      </div>
      <div>
        <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title mb-3"><span class="badge bg-dark-subtle text-black me-2">グリーティング</span></h5>
            <p class="card-text mb-0"><?= $survey["greeting"] ?></p>
            <div class="position-absolute top-0 end-0 p-3">
              <button type="button" class="btn btn-outline-dark me-2" data-bs-toggle="modal" data-bs-target="#greetingModal">設定</button>
              <button href="" class="btn btn-outline-primary" disabled>
                <i class="fa-solid fa-volume-high"></i>
                音声
              </button>
            </div>
          </div>
        </div>
        <?php foreach ($survey["endings"] as $ending): ?>
        <div class="card mb-2">
          <div class="card-body">
            <h5 class="card-title"><span class="badge bg-dark-subtle text-black me-2">エンディング</span><?= $ending["title"] ?></h5>
            <p class="card-text mb-0"><?= $ending["text"] ?></p>
            <div class="position-absolute top-0 end-0 p-3">
              <button type="button" class="btn btn-outline-dark me-2" data-bs-toggle="modal" data-bs-target="#endingModal<?= $ending["id"] ?>">設定</button>
              <button href="" class="btn btn-outline-primary" disabled>
                <i class="fa-solid fa-volume-high"></i>
                音声
              </button>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
        <?= Components::modalOpenButton("endingsCreateModal") ?>
      </div>
    </section>
    <?= Components::hr() ?>
    <section id="faqs">
      <?= Components::h3("質問一覧") ?>
      <div class="form-text mb-2">
        一番上に配置された質問が最初の質問（グリーティングの後に再生される質問）となります
      </div>
      <div>
        <?php foreach ($survey["faqs"] as $faq): ?>
          <div class="card mb-2">
            <div class="card-body">
              <h5 class="card-title mb-3">
                <span class="badge bg-primary-subtle text-black me-2">質問</span><?= $faq["title"] ?>
              </h5>
              <p class="card-text"><?= $faq["text"] ?></p>
              <?php if ($faq["options"] = Fetch::get2("options", [["faq_id", "=", $faq["id"]]], "dial")): ?>
                <table class="table table-sm mb-0">
                  <thead>
                    <tr>
                      <th scope="col">ダイヤル番号</th>
                      <th scope="col">TITLE</th>
                      <th scope="col">NEXT</th>
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
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              <?php endif; ?>
              <div class="position-absolute top-0 end-0 p-3">
                <?php if (!$faq["order_num"]): ?>
                  <span class="badge bg-info me-2">最初の質問</span>
                <?php endif; ?>
                <form action="/faqs/<?= $faq["id"] ?>/order" id="upFaq<?= $faq["id"] ?>" method="post" hidden>
                  <?= csrf() ?>
                  <input type="hidden" name="to" value="up">
                </form>
                <form action="/faqs/<?= $faq["id"] ?>/order" id="downFaq<?= $faq["id"] ?>" method="post" hidden>
                  <?= csrf() ?>
                  <input type="hidden" name="to" value="down">
                </form>
                <div class="btn-group me-2" role="group" aria-label="Basic outlined example">
                  <button
                  type="submit"
                  class="btn btn-outline-primary" <?= !$faq["order_num"] ? "disabled" : ""; ?>
                  form="upFaq<?= $faq["id"] ?>"
                  >
                    <i class="fa-solid fa-angle-up"></i>
                  </button>
                  <button
                  type="submit"
                  class="btn btn-outline-primary" <?= $faq["order_num"] === max(array_column($survey["faqs"], "order_num")) ? "disabled" : ""; ?>
                  form="downFaq<?= $faq["id"] ?>"
                  >
                    <i class="fa-solid fa-angle-down"></i>
                  </button>
                </div>
                <a href="/faqs/<?= $faq["id"] ?>" class="btn btn-primary">設定</a>
                <button href="" class="btn btn-outline-primary" disabled>
                  <i class="fa-solid fa-volume-high"></i>
                  音声
                </button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <?= Components::modalOpenButton("faqsCreateModal"); ?>
      </div>
    </section>
    <?= Components::hr() ?>
    <section id="calendar">
      <?= Components::h3("カレンダー") ?>
      <div class="text-center mb-4">
        <div class="btn-group">
          <a
          href="/surveys/<?= $survey["id"] ?>?month=<?= date("m", $calendar->getPrev()) ?>&year=<?= date("Y", $calendar->getPrev()) ?>#calendar"
          class="btn btn-outline-dark px-3"
          >
            <i class="fa-solid fa-angle-left fa-xl"></i>
          </a>
          <a href="#" class="btn btn-outline-dark px-5 active">
            <span class="fw-bold"><?= date("Y", $calendar->getCurrent()) ?>年 <?= date("n", $calendar->getCurrent()) ?>月</span>
          </a>
          <a
          href="/surveys/<?= $survey["id"] ?>?month=<?= date("m", $calendar->getNext()) ?>&year=<?= date("Y", $calendar->getNext()) ?>#calendar"
          class="btn btn-outline-dark px-3"
          >
            <i class="fa-solid fa-angle-right fa-xl"></i>
          </a>
        </div>
      </div>
      <table class="calendar-table table table-sm table-bordered">
        <thead class="text-center">
          <tr>
            <?php foreach (range(0, 6) as $w): ?>
              <th scope="col"><?= Calendar::jweek($w)  ?></th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($calendar->getCalendar() as $week): ?>
            <tr>
              <?php foreach ($week as $day): ?>
                <td class="position-relative" style="height: 100px;">
                  <?php if ($day): ?>
                    <div class="text-center mb-1">
                      <span class="<?= $day->today ? "text-bg-primary badge" : ""; ?>">
                        <?= date("j", $day->timestamp); ?>
                      </span>
                    </div>
                    <?php if ($reserve = $day->schedule): ?>
                      <a
                      class="badge text-bg-<?= RESERVATION_STATUS[$reserve["status"]]["bg"] ?> bg-gradient text-wrap w-100" style="text-decoration: none;"
                      href="/reserves/<?= $reserve["id"] ?><?= $reserve["status"]? "/result": null; ?>"
                      >
                        <?= date("H:i", strtotime($reserve["start"])) ?> - <?= date("H:i", strtotime($reserve["end"])) ?><br>
                        <?php if (count($reserve["areas"]) < 4): ?>
                          <?php foreach ($reserve["areas"] as $area): ?>
                            <?= $area["title"] ?>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <?= count($reserve["areas"]) ?>件のエリア
                        <?php endif; ?>
                      </a>
                    <?php else: ?>
                      <?php if (time() < $day->timestamp + RESERVATION_DEADLINE_HOUR * 3600): ?>
                        <button
                        type="button"
                        class="day-modal-button"
                        data-bs-toggle="modal"
                        data-bs-target="#dayModal"
                        data-bs-whatever="<?= $day->timestamp ?>"
                        >
                          <i class="fa-solid fa-plus fa-2xl"></i>
                        </button>
                      <?php endif; ?>
                    <?php endif; ?>
                  <?php endif; ?>
                </td>
              <?php endforeach; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="form-text">
        <div class="d-flex align-items-center gap-2">
          ステータスの凡例: 
          <?php foreach (RESERVATION_STATUS as $status): ?>
            <span class="badge text-bg-<?= $status["bg"] ?> bg-gradient" style="font-size: 14px;">
              <?= $status["text"] ?>
            </span>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?= Components::hr() ?>
    <section id="area">
      <?= Components::h3("エリア") ?>
      <table class="table">
        <thead>
          <tr>
            <th scope="col">エリア</th>
            <th scope="col">進捗率(総コール数 / エリア内番号数)</th>
            <th scope="col">有効コール率</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($areas as $area): ?>
          <tr>
            <th scope="row">関東・甲信越</th>
            <td>
              <div class="progress" role="progressbar" aria-label="Example with label" aria-valuenow="44" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar" style="width: 44%">44%</div>
              </div>
              <span>(2234 / <?= $area["all_numbers"] ?>)</span>
            </td>
            <td>36%</td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>
  </div>
  <div class="flex-shrink-0" style="width: 300px;">
    <div class="sticky-top">
      <section id="summary">
        <?= Components::h4("設定"); ?>
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
          <div class="text-end">
            <button type="submit" class="btn btn-dark">更新</button>
          </div>
        </form>        
      </section>
      <?= Components::hr(4) ?>
      <section id="settings">
        <?= Components::h4("予約パターン"); ?>
        <div class="form-text mb-2 vstack gap-1">
          <span>開始・終了時間やエリア設定のテンプレートを利用してスムーズに予約の指定ができます。</span>
          <span>予約パターンの適用後に各日付ごとに設定を変更することも可能です。</span>
        </div>
        <?php foreach ($survey["favorites"] as $favorite): ?>
          <div class="card mb-2">
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
                  </tr>
                </tbody>
              </table>
              <div class="position-absolute top-0 end-0 p-3">
                <a href="/favorites/<?= $favorite["id"] ?>" class="card-link">編集</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <?= Components::modalOpenButton("favoritesCreateModal"); ?>
      </section>
    </div>
  </div>
</div>

<!-- dayModal -->
<div class="modal fade" id="dayModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5"></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pb-5">
        <?= Components::h4("予約パターンから自動で予約") ?>
        <?php foreach ($survey["favorites"] as $favorite): ?>
          <div class="card mb-2">
            <div class="card-body">
              <h5 class="card-title">
                <span class="badge me-2 p-2" style="background-color: <?= $favorite["color"] ?>;"> </span>  
                <?= $favorite["title"] ?>
              </h5>
              <table class="table table-sm mb-0">
                <tbody>
                  <tr>
                    <th nowrap>時間</th>
                    <td><?= date("H:i", strtotime($favorite["start"])) ?> - <?= date("H:i", strtotime($favorite["end"])) ?></td>
                  </tr>
                  <tr>
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
                  </tr>
                </tbody>
              </table>
              <div class="position-absolute top-0 end-0 p-3">
                <a href="/favorites/<?= $favorite["id"] ?>" class="card-link">編集</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <?= Components::hr(4) ?>
        <?= Components::h4("手動で個別に予約") ?>
        <form action="/reserves" method="post">
          <?= csrf() ?>
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
            <input type="hidden" name="date" class="date-input">
            <button type="submit" class="btn btn-secondary">ページを移動してエリアを設定</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- faqsCreateModal -->
<div class="modal fade" id="faqsCreateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">質問を新規作成</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/faqs" method="post">
          <?= csrf() ?>
          <div class="mb-3">
            <label class="form-label">質問のタイトル</label>
            <input type="text" name="title" class="form-control" placeholder="〇〇に関する質問">
          </div>
          <div class="text-end">
            <input type="hidden" name="survey_id" value="<?= $survey["id"] ?>">
            <button type="submit" class="btn btn-primary">作成</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- favoritesCreateModal -->
<div class="modal fade" id="favoritesCreateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
              <?php foreach (COLOR_PALLET as $color): ?>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="color" value="<?= $color ?>" id="i<?= $color ?>">
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

<!-- greetingModal -->
<div class="modal fade" id="greetingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">グリーティングを編集</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/surveys/<?= $survey["id"] ?>/greeting" method="post">
          <?= csrf() ?>
          <?= method("PUT") ?>
          <div class="mb-3">
            <label class="form-label">テキスト</label>
            <textarea name="greeting" class="form-control" rows="5"><?= $survey["greeting"] ?></textarea>
          </div>
          <div class="text-end">
            <input type="hidden" name="survey_id" value="<?= $survey["id"] ?>">
            <button type="submit" class="btn btn-primary">更新</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?= Components::modal("endingsCreateModal", "エンディングを作成", <<<EOM
  <form action="/endings" method="post">
    CSRF
    <div class="mb-3">
      <label class="form-label">エンディングのタイトル</label>
      <input type="text" name="title" class="form-control" placeholder="〇〇のエンディング">
    </div>
    <div class="mb-3">
      <label class="form-label">エンディングのテキスト</label>
      <textarea name="text" class="form-control" rows="5"></textarea>
    </div>
    <div class="text-end">
      <input type="hidden" name="survey_id" value="{$survey["id"]} ?>">
      <button type="submit" class="btn btn-primary">作成</button>
    </div>
  </form>
EOM); ?>

<!-- endingModals -->
<?php foreach ($survey["endings"] as $ending): ?>
  <?= Components::modal("endingModal{$ending["id"]}", "エンディングを編集", <<<EOM
    <form action="/endings/{$ending["id"]}" method="post">
      CSRF
      METHOD_PUT
      <div class="mb-3">
        <label class="form-label">エンディングのタイトル</label>
        <input type="text" name="title" class="form-control" value="{$ending["title"]}">
      </div>
      <div class="mb-3">
        <label class="form-label">テキスト</label>
        <textarea name="text" class="form-control" rows="5">{$ending["text"]}</textarea>
      </div>
      <div class="text-end">
        <button type="submit" class="btn btn-primary">更新</button>
      </div>
    </form>
    <form action="/endings/{$ending["id"]}" method="post"  onsubmit="return window.confirm('本当に削除しますか？')">
      CSRF
      METHOD_DELETE
      <div class="text-end">
        <button type="submit" class="btn btn-link">このエンディングを削除</button>
      </div>
    </form>
  EOM) ?>
<?php endforeach; ?>

<?php require './views/templates/footer.php'; ?>