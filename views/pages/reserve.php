<?php require './views/templates/header.php'; ?>

<?= Components::h2("予約: 1/28") ?>

<section id="summary">
  <?= Components::h3("設定"); ?>
  <div style="max-width: 480px;">
    <form action="" method="post">
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
        <ul class="list-group" style="height: 400px; overflow-y: scroll;">
          <?php foreach (Fetch::allAreas() as $area): ?>
          <li class="list-group-item">
            <input
            class="form-check-input me-1" name="areas[]" type="checkbox"
            value="<?= $area["id"] ?>" id="checkbox<?= $area["id"] ?>"
            <?= in_array($area["id"], $selectedAreaIdArray) ? "selected" : ""; ?>
            >
            <label class="form-check-label stretched-link" for="checkbox<?= $area["id"] ?>"><?= $area["title"] ?></label>
          </li>
          <?php endforeach; ?>
        </ul>
        <div class="form-text">
          指定されたエリアからランダムで電話番号が指定されコールされます
        </div>
      </div>
      <div class="text-end">
        <button type="submit" class="btn btn-dark">更新</button>
      </div>
    </form>
  </div>
</section>


<?php require './views/templates/footer.php'; ?>