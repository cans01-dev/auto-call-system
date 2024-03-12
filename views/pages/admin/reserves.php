<?php require "./views/templates/header.php"; ?>

<div class="pt-3">
  <?= Components::h2("全ての予約") ?>
</div>
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
        <tr>
          <td>ユーザー</td>
          <td>
            <div class="div" style="max-width: 320px;">
              <select class="form-select" name="user_id">
                <option value="">全てのユーザー</option>
                <?php foreach (Fetch::all("users") as $user): ?>
                  <option
                    value="<?= $user["id"] ?>"
                    <?= $user["id"] == @$_GET["user_id"] ? "selected" : ""; ?>
                  >
                    <?= $user["email"] ?>
                  </option>
                <?php endforeach; ?>
              </select>
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
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>アンケート</th>
        <th>ユーザー</th>
        <th>日付</th>
        <th>開始・終了時間</th>
        <th>マイリスト</th>
        <th>ステータス</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($reserves as $reserve): ?>
        <tr>
          <th><?= $reserve["id"] ?></th>
          <td><a href="/surveys/<?= $reserve["survey_id"] ?>"><?= $reserve["title"] ?></a></td>
          <td><a href="/users/<?= $reserve["user_id"] ?>"><?= $reserve["email"] ?></a></td>
          <td><a href="/reserves/<?= $reserve['id'] ?>"><?= $reserve["date"] ?></a></td>
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
          <td>
            <?php if ($reserve["status"] === 0): ?>
              <form action="/admin/reserves/<?= $reserve["id"] ?>/forward_confirmed" method="post">
                <?= csrf() ?>
                <button class="btn btn-outline-primary">確定済にする</button>
              </form>
            <?php elseif ($reserve["status"] === 1): ?>
              <form action="/admin/reserves/<?= $reserve["id"] ?>/back_reserved" method="post" id="reserve<?= $reserve["id"] ?>BackReservedForm">
                <?= csrf() ?>
              </form>
              <div class="btn-group">
                <button class="btn btn-outline-primary" form="reserve<?= $reserve["id"] ?>BackReservedForm">予約済に戻す</button>
                <button
                  type="button"
                  class="btn btn-outline-primary"
                  data-bs-toggle="modal"
                  data-bs-target="#reserve<?= $reserve["id"] ?>ForwardCollectedModal"
                >集計済にする</button>
              </div>
            <?php elseif ($reserve["status"] === 4): ?>
              <form action="/admin/reserves/<?= $reserve["id"] ?>/back_confirmed" method="post">
                <?= csrf() ?>
                <button class="btn btn-outline-primary">確定済に戻す</button>
              </form>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
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

<?php foreach ($reserves as $reserve): ?>
  <!-- reserve<?= $reserve["id"] ?>ForwardCollectedModal -->
  <div class="modal fade" id="reserve<?= $reserve["id"] ?>ForwardCollectedModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">確定済にする</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h6>対象の予約</h6>
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>アンケート</th>
              <th>ユーザー</th>
              <th>日付</th>
              <th>開始・終了時間</th>
            </tr>
          <tbody>
            <tr>
              <th><?= $reserve["id"] ?></th>
              <td><a href="/surveys/<?= $reserve["survey_id"] ?>"><?= $reserve["title"] ?></a></td>
              <td><a href="/users/<?= $reserve["user_id"] ?>"><?= $reserve["email"] ?></a></td>
              <td><a href="/reserves/<?= $reserve['id'] ?>"><?= $reserve["date"] ?></a></td>
              <td><?= substr($reserve["start"], 0, -3) . " ~ " . substr($reserve["end"], 0, -3) ?></td>
            </tr>
          </tbody>
        </table>
        <?= Components::hr(3) ?>
        <h6>結果ファイルをインポート</h6>
        <div style="max-width: 320px;">
          <form action="/admin/reserves/<?= $reserve["id"] ?>/forward_collected" method="post" enctype="multipart/form-data">
            <?= csrf() ?>
            <div class="mb-3">
              <label class="form-label">結果ファイル</label>
              <input type="file" name="file" class="form-control" required>
            </div>
            <div class="text-end">
              <button type="submit" class="btn btn-success">実行</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>  
<?php endforeach; ?>

<?php require './views/templates/footer.php'; ?>