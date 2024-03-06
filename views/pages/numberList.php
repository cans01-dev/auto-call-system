<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item"><a href="/surveys/<?= $survey["id"] ?>"><?= $survey["title"] ?></a></li>
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
      <p>件数: <b><?= Fetch::query("SELECT COUNT(*) FROM numbers WHERE number_list_id = {$number_list["id"]}", "fetchColumn") ?></b></p>
      <?php foreach ($numbers as $number): ?>
        <span class="badge bg-light text-black fs-6 mb-1">
          <form action="/numbers/<?= $number["id"] ?>" method="post">
            <?= csrf() ?>
            <?= method("DELETE") ?>
            <?= $number["number"] ?>
            <button type="submit" class="bg-transparent border-0">
              <i class="fa-solid fa-xmark"></i>
            </button>
          </form>
      </span>
      <?php endforeach; ?>
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

<?php require './views/templates/footer.php'; ?>