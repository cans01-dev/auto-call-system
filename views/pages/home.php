<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active">ホーム</li>
  </ol>
</nav>
<?= Components::h2("ホーム") ?>
<div class="d-flex gap-3">
  <div class="w-100">
    <section id="reserves">
      <?= Components::h3("全ての予約") ?>
      <div class="card p-2 mb-4">
        <form id="params">
          <table class="table table-borderless mb-0">
            <tbody>
              <tr>
                <td>開始日 ~ 終了日</td>
                <td>
                  <div class="input-group mb-0" style="max-width: 360px;">
                    <input
                    type="date" name="start" class="form-control form-control-sm"
                    value="<?= @$_GET["start"] ?? date('Y-m-d', strtotime('first day of this month')) ?>">
                    <span class="input-group-text">~</span>
                    <input
                    type="date" name="end" class="form-control form-control-sm"
                    value="<?= @$_GET["end"] ?? date('Y-m-d', strtotime('last day of this month')) ?>">
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="position-absolute top-0 end-0 p-2">  
            <button class="btn btn-primary">確定</button>
          </div>
        </form>
      </div>
      <p><?= "{$pgnt["current"]} / {$pgnt["last_page"]}ページ目 - {$pgnt["current_start"]}~{$pgnt["current_end"]} / {$pgnt["sum"]}件表示中" ?></p>
      <?php if ($reserves): ?>
        <div class="calls-table-container">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>アンケート</th>
                <th>ユーザー</th>
                <th>日付</th>
                <th>開始・終了時間</th>
                <th>マイリスト</th>
                <th>ステータス</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($reserves as $reserve): ?>
                <tr onclick="window.location.assign('/reserves/<?= $reserve['id'] ?>')">
                  <th><?= $reserve["id"] ?></th>
                  <td>
                    <a href="/surveys/<?= $reserve["survey_id"] ?>"><?= $reserve["title"] ?></a>
                  </td>
                  <td><a href="/users/<?= $reserve["user_id"] ?>"><?= $reserve["email"] ?></a></td>
                  <td><?= $reserve["date"] ?></td>
                  <td><?= substr($reserve["start"], 0, -3) . " ~ " . substr($reserve["end"], 0, -3) ?></td>
                  <td>
                    <?php if ($reserve["number_list_id"]): ?>
                      <?= Fetch::find("number_lists", $reserve["number_list_id"])["title"] ?>
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
        <ul class="pagination mb-0 mt-4" style="justify-content: center;">
          <?php if ($pgnt["first"]): ?>
            <li class="page-item">
              <button name="page" value="<?= $pgnt["first"] ?>" form="params" class="page-link" href="#">
                <i class="fa-solid fa-angles-left"></i>
              </button>
            </li>
          <?php endif; ?>
          <?php if ($pgnt["pprev"]): ?>
            <li class="page-item">
              <button name="page" value="<?= $pgnt["pprev"]  ?>" form="params" class="page-link" href="#">
                <?= $pgnt["pprev"] ?>
              </button>
            </li>
          <?php endif; ?>
          <?php if ($pgnt["prev"]): ?>
            <li class="page-item">
              <button name="page" value="<?= $pgnt["prev"]  ?>" form="params" class="page-link" href="#">
                <?= $pgnt["prev"] ?>
              </button>
            </li>
          <?php endif; ?>
          <?php if ($pgnt["current"]): ?>
            <li class="page-item">
              <button name="page" value="<?= $pgnt["current"] ?>" form="params" class="page-link active" href="#">
                <?= $pgnt["current"] ?>
              </button>
            </li>
          <?php endif; ?>
          <?php if ($pgnt["next"]): ?>
            <li class="page-item">
              <button name="page" value="<?= $pgnt["next"]  ?>" form="params" class="page-link" href="#">
                <?= $pgnt["next"] ?>
              </button>
            </li>
          <?php endif; ?>
          <?php if ($pgnt["nnext"]): ?>
            <li class="page-item">
              <button name="page" value="<?= $pgnt["nnext"]  ?>" form="params" class="page-link" href="#">
                <?= $pgnt["nnext"] ?>
              </button>
            </li>
          <?php endif; ?>
          <?php if ($pgnt["last"]): ?>
            <li class="page-item">
              <button name="page" value="<?= $pgnt["last"]  ?>" form="params" class="page-link" href="#">
                <i class="fa-solid fa-angles-right"></i>
              </button>
            </li>
          <?php endif; ?>
        </ul>
      <?php else: ?>
        <?= Components::noContent("該当するデータがありません、検索条件を変更して再検索してください") ?>
      <?php endif; ?>

    </section>
  </div>
  <div class="flex-shrink-0 sticky-aside" style="width: 300px;">
    <div class="sticky-top">
      <?= Components::h4("アンケート一覧") ?>
      <?php if ($surveys): ?>
        <?php foreach ($surveys as $survey): ?>
          <div class="card mb-2">
            <div class="card-body">
              <h5 class="card-title"><?= $survey["title"] ?></h5>
              <div><?= $survey["note"] ?></div>
              <div class="position-absolute top-0 end-0 p-3">
                <a href="/surveys/<?= $survey["id"] ?>" class="card-link">編集</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <?= Components::noContent("アンケートがありません") ?>
      <?php endif; ?>
      <?php if (count($surveys) >= 1): ?>
        <?= Components::noContent("1ユーザあたり1アンケートのみ作成できます") ?>
      <?php else: ?>
        <?= Components::modalOpenButton("surveysCreateModal") ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require './views/templates/footer.php'; ?>