<?php require './views/templates/header.php'; ?>

<?= Components::h2("〇〇に関する質問") ?>

<section id="summary">
  <?= Components::h3("設定"); ?>
  <form action="/surveys" method="post">
    <div class="mb-3">
      <label for="exampleFormControlInput1" class="form-label">質問のタイトル</label>
      <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="〇〇に関する質問">
    </div>
    <div class="mb-3">
      <label for="exampleFormControlTextarea1" class="form-label">質問の読み上げ文章</label>
      <textarea class="form-control" id="exampleFormControlTextarea1" rows="5"></textarea>
    </div>
    <div class="form-check form-switch mb-3">
      <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
      <label class="form-check-label" for="flexSwitchCheckChecked">採用フラグ</label>
    </div>
    <div class="text-end">
      <button type="submit" class="btn btn-dark">更新</button>
    </div>
  </form>
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
      <?php for ($i = 0; $i < 4; $i++): ?>
      <tr>
        <th scope="row"><span class=""><?= $i ?></span></th>
        <td>回答あああ</td>
        <td><a class="badge text-bg-secondary">〇△に関する質問</a></td>
        <td>
          <a href="/options/<?= $i ?>" class="btn btn-primary me-2">編集</a>
          <div class="btn-group" role="group" aria-label="Basic outlined example">
            <button type="button" class="btn btn-outline-primary">
              <i class="fa-solid fa-angle-up"></i>
            </button>
            <button type="button" class="btn btn-outline-primary">
              <i class="fa-solid fa-angle-down"></i>
            </button>
          </div>
        </td>
      </tr>
      <?php endfor; ?>
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
          <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">選択肢のタイトル</label>
            <input type="email" class="form-control" id="exampleFormControlInput1">
          </div>
          <div class="text-end">
            <input type="hidden" name="faqId" value="<?= $faqId ?>">
            <button type="submit" class="btn btn-primary">作成</button>
          </div>
        </form>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require './views/templates/footer.php'; ?>