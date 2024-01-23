<?php require './views/templates/header.php'; ?>

<?= Components::h2($faq["title"]) ?>

<section id="summary">
  <?= Components::h3("設定"); ?>
  <div style="max-width: 480px;">
    <form action="/faqs/<?= $faq["id"] ?>" method="post">
      <?= csrf() ?>
      <?= method("PUT") ?>
      <div class="mb-3">
        <label class="form-label">質問のタイトル</label>
        <input type="text" name="title" class="form-control" value="<?= $faq["title"] ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">質問の読み上げ文章</label>
        <textarea class="form-control" name="text" rows="5"><?= $faq["text"] ?></textarea>
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
      <?php foreach ($options as $option): ?>
      <tr>
        <th scope="row"><span class=""><?= $option["dial"] ?></span></th>
        <td><?= $option["title"] ?></td>
        <td>
          <?php if ($option["next_faq_id"]): ?>
          <a href="/faqs/<?= $option["next_faq_id"] ?>" class="badge text-bg-info"><?= $option["next_faq_id"] ?></a>
          <?php else: ?>
          <span class="badge text-bg-secondary">終了</span>
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
            class="btn btn-outline-primary" <?= $option["dial"] === $maxDial ? "disabled" : ""; ?>
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