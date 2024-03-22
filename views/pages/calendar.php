<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item"><a href="/surveys/<?= $survey["id"] ?>"><?= $survey["title"] ?></a></li>
    <li class="breadcrumb-item active">カレンダー <?= $year ?>年 <?= $month ?>月</li>
  </ol>
</nav>
<?= Components::h2("{$survey["title"]}: カレンダー") ?>
<div class="d-flex gap-3">
  <div class="w-100">
    <section id="calendar">
      <div class="text-center mb-4">
        <div class="btn-group">
          <a href="<?= url_param_change(["month" => date("m", $calendar->getPrev()), "year" => date("Y", $calendar->getPrev())]) ?>" class="btn btn-outline-dark px-3">
            <i class="fa-solid fa-angle-left fa-xl"></i>
          </a>
          <button class="btn btn-dark px-5">
            <span class="fw-bold">
              <?= date("Y", $calendar->getCurrent()) ?>年 <?= date("n", $calendar->getCurrent()) ?>月
            </span>
          </button>
          <a href="<?= url_param_change(["month" => date("m", $calendar->getNext()), "year" => date("Y", $calendar->getNext())]) ?>" class="btn btn-outline-dark px-3">
            <i class="fa-solid fa-angle-right fa-xl"></i>
          </a>
        </div>
      </div>
      <?php if ($c_mode === "month"): ?>
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
                        class="badge lh-sm text-bg-<?= RESERVATION_STATUS[$reserve["status"]]["bg"] ?> bg-gradient text-wrap w-100" style="text-decoration: none;"
                        href="/reserves/<?= $reserve["id"] ?>"
                        >
                          <?= date("H:i", strtotime($reserve["start"])) ?> - <?= date("H:i", strtotime($reserve["end"])) ?>
                          <?php if ($myList = Fetch::find("number_lists", $reserve["number_list_id"])): ?>
                            マイリスト: <?= $myList["title"] ?>
                          <?php elseif (count($reserve["areas"]) === 0): ?>
                            <div class="text-danger py-2">
                              <span href="#" data-bs-toggle="tooltip" data-bs-title="エリアが指定されていません">
                                <i class="fa-solid fa-circle-exclamation fa-2xl"></i>
                              </span>
                            </div>
                          <?php elseif (count($reserve["areas"]) < 4): ?>
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
      <?php elseif ($c_mode === "schedule"): ?>
        <div class="mb-3">
          <?php if ($reserves = $survey["reserves"]): ?>
            <div class="calls-table-container mb-4">
              <table class="table mb-0">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>日付</th>
                    <th>開始・終了時間</th>
                    <th>マイリスト</th>
                    <th>ステータス</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($reserves as $reserve): ?>
                    <tr>
                      <th><?= $reserve["id"] ?></th>
                      <td><a href="/reserves/<?= $reserve['id'] ?>"><?= $reserve["date"] ?></a></td>
                      <td><?= substr($reserve["start"], 0, -3) . " ~ " . substr($reserve["end"], 0, -3) ?></td>
                      <td>
                        <?php if ($reserve["number_list_id"]): ?>
                          <a href="/number_lists/<?= $reserve["number_list_id"] ?>">
                            <?= Fetch::find("number_lists", $reserve["number_list_id"])["title"] ?>
                          </a>
                        <?php endif; ?>
                      </td>
                      <td>
                        <span class="badge text-bg-<?= RESERVATION_STATUS[$reserve["status"]]["bg"] ?> bg-gradient fs-6 me-1">
                          <?= RESERVATION_STATUS[$reserve["status"]]["text"] ?>
                        </span>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <?= Components::noContent("該当するデータがありません、検索条件を変更して再検索してください") ?>
          <?php endif; ?>
          <?= Components::modalOpenButton("createReserveModal"); ?>
        </div>
      <?php endif; ?>
      <div class="row justify-content-between">
        <div class="col-8 form-text">
          <div class="d-flex align-items-center gap-2">
            ステータスの凡例: 
            <?php foreach (RESERVATION_STATUS as $status): ?>
              <span class="badge text-bg-<?= $status["bg"] ?> bg-gradient" style="font-size: 14px;">
                <?= $status["text"] ?>
              </span>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="col-4 btn-group" role="group" aria-label="Basic outlined example">
          <a href="<?= url_param_change(["calendar" => "month"]) ?>"
          class="btn btn-outline-primary <?= $c_mode === "month" ? "active" : "" ?>">
            <i class="fa-solid fa-calendar fa-lg" aria-hidden="true"></i>
          </a>
          <a href="<?= url_param_change(["calendar" => "schedule"]) ?>"
          class="btn btn-outline-primary <?= $c_mode === "schedule" ? "active" : "" ?>">
            <i class="fa-solid fa-table-list fa-lg" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </section>
  </div>
  <div class="flex-shrink-0 sticky-aside" style="width: 300px;">
    <div class="sticky-top">
      <section id="favorite">
        <?= Components::h4("予約パターン"); ?>
        <div class="form-text mb-2 vstack gap-1">
          <span>開始・終了時間やエリア設定のテンプレートを利用してスムーズに予約の指定ができます。</span>
          <span>予約パターンの適用後に各日付ごとに設定を変更することも可能です。</span>
        </div>
        <?php if ($survey["favorites"]): ?>
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
          <?php endforeach; ?>
        <?php else: ?>
          <?= Components::noContent("予約パターンがありません") ?>
        <?php endif; ?>
        <a href="/surveys/<?= $survey["id"] ?>/asset#favorite" class="btn btn-link">予約パターンを編集</a>
      </section>
    </div>
  </div>
</div>

<!-- dayModal -->
<div class="modal fade" id="dayModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5"></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pb-5">
        <?= Components::h4("予約パターンから予約") ?>
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
                <form action="/reserves" method="post">
                  <?= csrf() ?>
                  <input type="hidden" name="survey_id" value="<?= $survey["id"] ?>">
                  <input type="hidden" name="date" class="date-input">
                  <input type="hidden" name="favorite_id" value="<?= $favorite["id"] ?>">
                  <button type="submit" class="btn btn-primary">このパターンで予約</button>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <?= Components::hr(4) ?>
        <?= Components::h4("手動で予約") ?>
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

<!-- createReserveModal -->
<div class="modal fade" id="createReserveModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">予約</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pb-5">
        <form action="/reserves" method="post">
          <?= csrf() ?>
          <div class="mb-3">
            <label class="form-label">日付</label>
            <input type="date" name="date" class="form-control" min="<?= date("Y-m-d", strtotime("+1 day")) ?>" required>
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
            <button type="submit" class="btn btn-secondary">ページを移動してエリアを設定</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

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

<?= Auth::user()["id"] !== $survey["user_id"]? Components::watchOnAdmin("管理者として閲覧専用でこのページを閲覧しています") : "" ?>

<?php require './views/templates/footer.php'; ?>