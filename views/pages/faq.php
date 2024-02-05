<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="sticky-top">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item"><a href="/surveys/<?= $survey["id"] ?>"><?= $survey["title"] ?></a></li>
    <li class="breadcrumb-item active"><?= $faq["title"] ?></li>
  </ol>
</nav>
<?= Components::h2($faq["title"]) ?>

<section id="summary">
  <?= Components::h3("設定"); ?>
  <div style="max-width: 480px;">
    <form method="post">
      <?= csrf() ?>
      <?= method("PUT") ?>
      <div class="mb-3">
        <label class="form-label">質問のタイトル</label>
        <input type="text" name="title" class="form-control" value="<?= $faq["title"] ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">質問の読み上げ文章</label>
        <textarea class="form-control" name="text" rows="8"><?= $faq["text"] ?></textarea>
      </div>
      <div class="text-end">
        <button type="submit" class="btn btn-dark">更新</button>
      </div>
    </form>
    <form method="post" onsubmit="return window.confirm('本当に削除しますか？\r\n遷移先がこの質問になっている他の質問の選択肢は自動的に「聞き直し」に変更されます')">
      <?= csrf() ?>
      <?= method("DELETE") ?>
      <div class="text-end">
        <input type="submit" class="btn btn-link" value="この質問を削除">
      </div>
    </form>
  </div>
</section>
<?= Components::hr() ?>
<section id="options">
  <?= Components::h3("選択肢") ?>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">ダイヤル番号</th>
        <th scope="col">TITLE</th>
        <th scope="col">NEXT</th>
        <th scope="col">操作</th>
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
        <td>
          <a href="/options/<?= $option["id"] ?>" class="btn btn-primary me-2">編集</a>
          <form action="/options/<?= $option["id"] ?>/order" id="upOption<?= $option["id"] ?>" method="post" hidden>
            <?= csrf() ?>
            <input type="hidden" name="to" value="up">
          </form>
          <form action="/options/<?= $option["id"] ?>/order" id="downOption<?= $option["id"] ?>" method="post" hidden>
            <?= csrf() ?>
            <input type="hidden" name="to" value="down">
          </form>
          <div class="btn-group" role="group" aria-label="Basic outlined example">
            <button
            type="submit"
            class="btn btn-outline-primary" <?= !$option["dial"] ? "disabled" : ""; ?>
            form="upOption<?= $option["id"] ?>"
            >
              <i class="fa-solid fa-angle-up"></i>
            </button>
            <button
            type="submit"
            class="btn btn-outline-primary" <?= $option["dial"] === max(array_column($faq["options"], "dial")) ? "disabled" : ""; ?>
            form="downOption<?= $option["id"] ?>"
            >
              <i class="fa-solid fa-angle-down"></i>
            </button>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?= Components::modalOpenButton("optionsCreateModal"); ?>
</section>

<!-- optionsCreateModal -->
<div class="modal fade" id="optionsCreateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">選択肢を新規作成</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/options" method="post">
          <?= csrf() ?>
          <div class="mb-3">
            <label class="form-label">選択肢のタイトル</label>
            <input type="text" name="title" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">NEXT</label>
            <select class="form-select" name="next" required>
              <?php foreach ($survey["faqs"] as $f): ?>
                <option value="f<?= $f["id"] ?>">
                  <?= $f["title"] ?><?= $f["id"] === $option["faq_id"] ? "（聞き直し）": ""; ?>
                </option>
              <?php endforeach; ?>
              <?php foreach (Fetch::get("endings", $survey["id"], "survey_id") as $e): ?>
                <option value="e<?= $e["id"] ?>">
                  <?= $e["title"] ?>
                </option>
              <?php endforeach; ?>
            </select>
            <div id="passwordHelpBlock" class="form-text">
              この選択肢が選択された場合の次の操作を指定してください
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">ダイヤル番号</label>
            <input type="number" class="form-control" value="<?= count($faq["options"]) ? max(array_column($faq["options"], "dial")) + 1 : 0 ?>" disabled>
            <div id="passwordHelpBlock" class="form-text">
              ダイヤル番号は作成後に質問ページの選択肢一覧から変更できます
            </div>
          </div>
          <div class="text-end">
            <input type="hidden" name="faq_id" value="<?= $faq["id"] ?>">
            <button type="submit" class="btn btn-primary">作成</button>
          </div>
        </form>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require './views/templates/footer.php'; ?>