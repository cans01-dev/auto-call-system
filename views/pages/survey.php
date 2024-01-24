<?php require './views/templates/header.php'; ?>

<?= Components::h2($survey["title"]) ?>

<div class="d-flex gap-3">
  <div class="w-100">
    <section id="faqs">
      <?= Components::h3("質問一覧") ?>
      <div>
        <?php foreach ($faqs as $faq): ?>
          <div class="card mb-2">
            <div class="card-body">
              <h5 class="card-title"><span class="badge bg-secondary me-2">ID: <?= $faq["id"] ?></span><?= $faq["title"] ?></h5>
              <h6 class="card-subtitle mb-2 text-body-secondary">---</h6>
              <p class="card-text"><?= $faq["text"] ?></p>
              <a href="/faqs/<?= $faq["id"] ?>" class="btn btn-primary me-2">設定</a>
              <button href="" class="btn btn-outline-primary" disabled>
                <i class="fa-solid fa-volume-high"></i>
                音声
              </button>
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
          <a href="<?= url_param_change(["month" => date("m", $prev), "year" => date("Y", $prev)]) ?>#calendar" class="btn btn-outline-dark px-3">
            <i class="fa-solid fa-angle-left fa-xl"></i>
          </a>
          <a href="#" class="btn btn-outline-dark px-5 active">
            <span class="fw-bold"><?= date("Y", $current) ?>年 <?= date("n", $current) ?>月</span>
          </a>
          <a href="<?= url_param_change(["month" => date("m", $next), "year" => date("Y", $next)]) ?>#calendar" class="btn btn-outline-dark px-3">
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
                    <div class="text-center mb-2">
                      <span class="<?= $day->today ? "text-bg-primary badge" : ""; ?>">
                        <?= date("j", $day->timestamp); ?>
                      </span>
                    </div>
                    <?php if ($schedule = $day->schedule): ?>
                      <a
                      class="badge text-bg-<?= $schedule["status"]? "secondary": "primary"; ?> text-wrap w-100" style="text-decoration: none;"
                      href="/reserves/1<?= $schedule["status"]? "/result": null; ?>"
                      >
                        17:00 - 21:00<br>関東・甲信越
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
          <?php for ($i = 0; $i < 3; $i++): ?>
          <tr>
            <th scope="row">関東・甲信越</th>
            <td>
              <div class="progress" role="progressbar" aria-label="Example with label" aria-valuenow="44" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar" style="width: 44%">44%</div>
              </div>
              <span>(2234 / 50000)</span>
            </td>
            <td>36%</td>
          </tr>
          <?php endfor; ?>
        </tbody>
      </table>
    </section>
  </div>
  <div class="flex-shrink-0" style="width: 300px;">
    <div class="sticky-top">
      <section id="summary">
        <?= Components::h4("設定"); ?>
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
          <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" role="switch" checked>
            <label class="form-check-label">採用フラグ</label>
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-dark">更新</button>
          </div>
        </form>        
      </section>
      <?= Components::hr() ?>
      <section id="settings">
        <?= Components::h4("予約パターン"); ?>
        <div class="form-text mb-2 vstack gap-1">
          <span>開始・終了時間やエリア設定のテンプレートを利用してスムーズに予約の指定ができます。</span>
          <span>予約パターンの適用後に各日付ごとに設定を変更することも可能です。</span>
        </div>
        <?php for ($i = 0; $i < 3; $i++): ?>
          <div class="card mb-2">
            <div class="card-body">
              <h5 class="card-title">
                <span class="badge text-bg-warning me-2"> </span>  
                Primary card title
              </h5>
              <table>
                <tbody>
                  <tr><th>時間</th><td>17:00 - 21:00</td></tr>
                  <tr><th>エリア</th><td>関東・甲信越</td></tr>
                </tbody>
              </table>
              <div class="position-absolute top-0 end-0 p-3">
                <a href="/settings/<?= $i ?>" class="card-link">編集</a>
              </div>
            </div>
          </div>
        <?php endfor; ?>
        <?= Components::modalOpenButton("settingsCreateModal"); ?>
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
        <?php for ($i = 0; $i < 3; $i++): ?>
          <div class="card mb-2">
            <div class="card-body">
              <h5 class="card-title">
                <span class="badge text-bg-warning me-2"> </span>  
                Primary card title
              </h5>
              <table>
                <tbody>
                  <tr><th>時間</th><td>17:00 - 21:00</td></tr>
                  <tr><th>エリア</th><td>関東・甲信越</td></tr>
                </tbody>
              </table>
              <div class="position-absolute top-0 end-0 p-3">
                <form action="/reserves" method="post">
                  <input type="hidden" name="timestamp" value="">
                  <input type="hidden" name="setting_id" value="">
                  <button type="submit" class="btn btn-primary">このパターンで予約</button>
                </form>
              </div>
            </div>
          </div>
        <?php endfor; ?>
        <?= Components::hr(4) ?>
        <?= Components::h4("手動で個別に予約") ?>
        <form action="/reserves" method="post">
          <div class="mb-3">
            <label class="form-label">開始時間・終了時間</label>
            <div class="input-group">
              <input type="time" name="start" class="form-control" required>
              <span class="input-group-text">~</span>
              <input type="time" name="end" class="form-control" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">エリア指定</label>
            <ul class="list-group">
              <?php for ($i = 0; $i < 10; $i++): ?>
              <li class="list-group-item">
                <input class="form-check-input me-1" name="areas[]" type="checkbox" value="<?= $i ?>" id="firstCheckboxStretched<?= $i ?>">
                <label class="form-check-label stretched-link" for="firstCheckboxStretched<?= $i ?>">エリア <?= $i ?></label>
              </li>
              <?php endfor; ?>
            </ul>
            <div class="form-text">
              指定されたエリアからランダムで電話番号が指定されコールされます
            </div>
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-dark">予約</button>
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

<!-- settingsCreateModal -->
<div class="modal fade" id="settingsCreateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">予約パターンを新規作成</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/settings" method="post">
          <div class="mb-3">
            <label class="form-label">予約パターンのタイトル</label>
            <input type="text" class="form-control" placeholder="〇〇の予約パターン">
          </div>
          <div class="text-end">
            <input type="hidden" name="surveyId" value="<?= $surveyId ?>">
            <button type="submit" class="btn btn-primary">作成</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require './views/templates/footer.php'; ?>