<?php require './views/templates/header.php'; ?>

<?= Components::h2("予約パターン: vdsadva") ?>

<section id="summary">
  <?= Components::h3("設定"); ?>
  <div style="max-width: 480px;">
    <form action="" method="post">
      <div class="form-text mb-2 vstack gap-1">
        <span>開始・終了時間やエリアの設定の変更は既に登録済の予約に影響することはありません。</span>
      </div>
      <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">予約パターンのタイトル</label>
        <input type="text" class="form-control" id="exampleFormControlInput1">
      </div>
      <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">開始時間・終了時間</label>
        <div class="input-group">
          <input type="time" class="form-control" placeholder="Username" aria-label="Username" required>
          <span class="input-group-text">~</span>
          <input type="time" class="form-control" placeholder="Server" aria-label="Server" required>
        </div>
      </div>
      <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">エリア指定</label>
        <ul class="list-group">
          <?php for ($i = 0; $i < 10; $i++): ?>
          <li class="list-group-item">
            <input class="form-check-input me-1" name="areas" type="checkbox" value="<?= $i ?>" id="firstCheckboxStretched<?= $i ?>">
            <label class="form-check-label stretched-link" for="firstCheckboxStretched<?= $i ?>">エリア <?= $i ?></label>
          </li>
          <?php endfor; ?>
        </ul>
        <div id="passwordHelpBlock" class="form-text">
          指定されたエリアからランダムで電話番号が指定されコールされます
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