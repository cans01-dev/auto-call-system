<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item"><a href="/surveys/<?= $survey["id"] ?>/calendar"><?= $survey["title"] ?></a></li>
    <li class="breadcrumb-item active">マイリスト: <?= $number_list["title"] ?></li>
  </ol>
</nav>
<?= Components::h2("マイリスト: {$number_list["title"]}") ?>
<div class="d-flex gap-3">
  <div class="w-100">
    <?php if ($result = @$_SESSION["storeNumberCsvResult"]): ?>
      <div class="alert alert-info">
        <h4>CSVファイルインポート結果</h4>
        <dl class="mb-0 row">
          <div class="col">
            <dt>成功</dt>
            <dd><?= $result["success"] ?>行</dd>
          </div>
          <div class="col">
            <dt>エラー</dt>
            <dd><?= $result["error"] ?>行</dd>
          </div>
          <div class="col">
            <dt>重複</dt>
            <dd><?= $result["dup"] ?>行</dd>
          </div>
        </dl>
      </div>
      <?= Components::hr(4) ?>
    <?php endif; ?>
    <section id="numbers">
      <?= Components::h3("電話番号を追加") ?>
      <div class="card text-bg-light mb-3">
        <div class="card-header">
          電話番号を入力して追加
        </div>
        <div class="card-body">
          <form action="/number_lists/<?= $number_list["id"] ?>/numbers" method="post">
            <?= csrf() ?>
            <div class="input-group mb-2" style="max-width: 320px;">
              <input type="text" name="number" class="form-control" placeholder="090-1234-5678" pattern="^0?[789]0-?[0-9]{4}-?[0-9]{4}$">
              <button class="btn btn-outline-secondary">追加</button>
            </div>
            <div class="form-text">※ハイフンの有無問わず、先頭のゼロ省略可能</div>
          </form>
        </div>
      </div>
      <div class="card text-bg-light">
        <div class="card-header">
          CSVファイルをアップロードして追加
        </div>
        <div class="card-body">
        <p class="mb-2">一列目が電話番号になっているCSVファイルを指定してください</p>
          <form action="/number_lists/<?= $number_list["id"] ?>/numbers_csv" enctype="multipart/form-data" method="post">
            <?= csrf() ?>
            <div class="input-group" style="max-width: 440px;">
              <input type="file" name="file" class="form-control" accept="text/csv" required>
              <button type="submit" class="btn btn-outline-secondary">登録</button>
            </div>
            <div class="form-text">※ハイフンの有無問わず、先頭のゼロ省略可能</div>
          </form>
        </div>
      </div>
    </section>
    <?= Components::hr(4) ?>
    <section id="numbers">
      <?= Components::h3("電話番号") ?>
      <div class="card p-2 mb-4">
        <form id="params">
          <table class="table table-borderless mb-0">
            <tbody>
              <tr>
                <td>ステータス</td>
                <td class="d-flex gap-4">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="call_status"
                      value=""
                      id="callStatusNull"
                      onchange="submit(this.form)"
                      <?= @$_GET["call_status"] ? "" : "checked" ?>
                    >
                    <label class="form-check-label" for="callStatusNull">
                      未コール
                    </label>
                  </div>
                  <?php foreach (CALL_STATUS as $key => $value): ?>
                    <div class="form-check">
                      <input
                        class="form-check-input"
                        type="radio"
                        value="<?= $key ?>"
                        name="call_status"
                        id="callStatus<?= $key ?>"
                        onchange="submit(this.form)"
                        <?= @$_GET["call_status"] == $key ? "checked" : "" ?>
                      >
                      <label class="form-check-label" for="callStatus<?= $key ?>">
                        <span class="badge bg-<?= $value["bg"] ?>"><?= $value["text"] ?></span>
                      </label>
                    </div>
                  <?php endforeach; ?>
                </td>
              </tr>
            </tbody>
          </table>
        </form>
      </div>
      <p><?= "{$pgnt["current"]} / {$pgnt["last_page"]}ページ目 - {$pgnt["current_start"]}~{$pgnt["current_end"]} / {$pgnt["sum"]}件表示中" ?></p>
      <div class="calls-table-container">
        <table class="table table-sm">
          <thead>
            <tr>
              <th>電話番号</th>
              <th>コールのステータス</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($numbers as $number): ?>
              <tr>
                <td><?= $number["number"] ?></td>
                <td><?= $number["call_status"] ? CALL_STATUS[$number["call_status"]]["text"] : "-" ?></td>
                <td>
                  <form action="/numbers/<?= $number["id"] ?>" method="post">
                    <?= csrf() ?>
                    <?= method("DELETE") ?>
                    <button type="submit" class="btn btn-sm btn-outline-dark">
                      削除
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <ul class="pagination mb-0 mt-4" style="justify-content: center;">
        <form id="params"></form>
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
    </section>
  </div>
  <div class="flex-shrink-0 sticky-aside" style="width: 300px;">
    <div class="sticky-top">
      <section id="summary">
        <?= Components::h4("設定"); ?>
        <form method="post">
          <div class="mb-3">
            <?= csrf() ?>
            <?= method("PUT") ?>
            <label class="form-label">マイリストのタイトル</label>
            <input type="text" name="title" class="form-control" value="<?= $number_list["title"] ?>">
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-dark">更新</button>
          </div>
        </form>
        <form onsubmit="return window.confirm('本当に削除しますか？')" method="post">
          <?= csrf() ?>
          <?= method("DELETE") ?>
          <button class="btn btn-link">削除</button>
        </form>
      </section>
    </div>
  </div>
</div>

<?= Auth::user()["id"] !== $survey["user_id"]? Components::watchOnAdmin("管理者として閲覧専用でこのページを閲覧しています") : "" ?>

<?php require './views/templates/footer.php'; ?>