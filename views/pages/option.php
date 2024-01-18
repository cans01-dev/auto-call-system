<?php require './views/templates/header.php'; ?>

<?= Components::h2("選択肢: 回答あああ") ?>

<section id="summary">
  <?= Components::h3("設定"); ?>
  <div style="max-width: 480px;">
    <form action="" method="post">
      <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">選択肢のタイトル</label>
        <input type="text" class="form-control" id="exampleFormControlInput1">
      </div>
      <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">NEXT</label>
        <select class="form-select" aria-label="Default select example" required>
          <option selected value="">選択してください</option>
          <option value="1">質問AAA</option>
          <option value="2">質問あああ</option>
          <option value="3">終了</option>
        </select>
        <div id="passwordHelpBlock" class="form-text">
          この選択肢が選択された場合の次の操作を指定してください
        </div>
      </div>
      <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
        <label class="form-check-label" for="flexSwitchCheckChecked">採用フラグ</label>
      </div>
      <div class="text-end">
        <button type="submit" class="btn btn-dark">更新</button>
      </div>
    </form>
  </div>
</section>
<?php require './views/templates/footer.php'; ?>