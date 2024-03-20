<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item"><a href="/surveys/<?= $survey["id"] ?>/calendar"><?= $survey["title"] ?></a></li>
    <?php if ($referer = Session::get("referer")): ?>
      <li class="breadcrumb-item"><a href="<?= $referer["link"] ?>"><?= $referer["text"] ?></a></li>
    <?php endif; ?>
    <?php if ($referer2 = Session::get("referer2")): ?>
      <li class="breadcrumb-item"><a href="<?= $referer2["link"] ?>"><?= $referer2["text"] ?></a></li>
    <?php endif; ?>
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
      <?= Components::h3("統計") ?>
      <?php $stats = $number_list["stats"] ?>
      <?php if ($stats["all_calls"]): ?>
      <div class="card bg-light mb-3">
        <div class="card-body">
          <table class="table table-light table-sm mb-0">
            <tr>
              <th>進捗率</th>
              <td>
                <?= round($stats["all_calls"] / $stats["all_numbers"] * 100) ?>%<br>
                (<?= number_format($stats["all_calls"]) ?> / <?= number_format($stats["all_numbers"]) ?>)
              </td>
              <th>応答率</th>
              <td>
                <?= round($stats["responsed_calls"] / $stats["all_calls"] * 100) ?>%<br>
                (<?= number_format($stats["responsed_calls"]) ?> / <?= number_format($stats["all_calls"]) ?>)
              </td>
            </tr>
            <tr>
              <th>成功率</th>
              <td>
                <?= round($stats["success_calls"] / $stats["responsed_calls"] * 100) ?>%<br>
                (<?= $stats["success_calls"] ?> / <?= number_format($stats["responsed_calls"]) ?>)
              </td>
              <th>平均アクション数</th>
              <td><?= round($stats["all_actions"] / $stats["responsed_calls"], 2) ?>回</td>
            </tr>
            <tr>
              <th>アクション率</th>
              <td>
                <?= round($stats["action_calls"] / $stats["responsed_calls"] * 100) ?>%<br>
                (<?= number_format($stats["action_calls"]) ?> / <?= number_format($stats["responsed_calls"]) ?>)
              </td>
              <th>料金</th>
              <td>
                ¥<?= number_format(round($stats["total_duration"] * PRICE_PER_SECOND)) ?><br>
                (<?= number_format($stats["total_duration"]) ?>秒 x ¥<?= PRICE_PER_SECOND ?>)
              </td>
            </tr>
          </table>
        </div>
      </div>
      <?php else: ?>
        <?= Components::noContent("データがありません") ?>
        <?= $stats["all_numbers"] ?> 件の電話番号
      <?php endif; ?>
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
          <div class="mb-3">
            <label class="form-label">マイリストの電話番号数</label>
            <span class="badge bg-primary fs-6"><?= number_format($stats["all_numbers"]) ?>件</span>
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