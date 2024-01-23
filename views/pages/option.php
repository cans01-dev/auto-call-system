<?php require './views/templates/header.php'; ?>

<?= Components::h2("選択肢: {$option["title"]}") ?>

<section id="summary">
  <?= Components::h3("設定"); ?>
  <div style="max-width: 480px;">
    <form action="/options/<?= $option["id"] ?>" method="post">
      <?= csrf() ?>
      <?= method("PUT") ?>
      <div class="mb-3">
        <label class="form-label">選択肢のタイトル</label>
        <input type="text" name="title" class="form-control" value="<?= $option["title"] ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">NEXT</label>
        <select class="form-select" name="next_faq_id" required>
          <option value="0" <?= !$option["next_faq_id"] ? "selected" : ""; ?>>終了</option>
          <?php foreach ($surveyFaqs as $surveyFaq): ?>
          <option
          value="<?= $surveyFaq["id"] ?>"
          <?= $option["next_faq_id"] === $surveyFaq["id"] ? "selected" : ""; ?>
          >
          <?= $surveyFaq["title"] ?>
          </option>
          <?php endforeach; ?>
        </select>
        <div id="passwordHelpBlock" class="form-text">
          この選択肢が選択された場合の次の操作を指定してください
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">ダイヤル番号</label>
        <input type="number" class="form-control" value="<?= $option["dial"] ?>" disabled>
        <div id="passwordHelpBlock" class="form-text">
          ダイヤル番号は質問ページの選択肢一覧から変更できます
        </div>
      </div>
      <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" role="switch" checked>
        <label class="form-check-label">採用フラグ</label>
      </div>
      <div class="text-end">
        <button type="submit" class="btn btn-dark">更新</button>
      </div>
    </form>
  </div>
</section>
<?php require './views/templates/footer.php'; ?>