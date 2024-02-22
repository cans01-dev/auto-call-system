<?php require "./views/templates/header.php"; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active">サポート</li>
  </ol>
</nav>
<?= Components::h2("ドキュメント") ?>

<?= Components::h4("お問い合わせ") ?>
<div class="form-text mb-2">ドキュメントを読んでも分からない、その他バグの報告・要望などはこちら</div>
<button
  class="btn btn-primary"
  data-bs-toggle="modal"
  data-bs-target="#contactModal"
>お問い合わせ</button>
<?= Components::hr(4) ?>

<?= Components::h4("音声タイプのサンプル") ?>
<table class="table table-sm">
  <thead>
    <tr>
      <td>名前</td>
      <td>性別</td>
      <td>サンプル</td>
    </tr>
  </thead>
  <tbody>
    <?php foreach (VOICES as $voice): ?>
      <tr>
        <td><?= $voice["name"] ?></td>
        <td><?= $voice["gender"] ?></td>
        <td>
          <audio src="<?= "/assets/samples/{$voice["name"]}.wav" ?>" controls></audio>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?= Components::hr(4) ?>


<div class="markdown-body w-100 pt-3">
  <?= $markdown ?>
</div>

<div class="modal fade" id="contactModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">お問い合わせ</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/support/contact" method="post">
          <?= csrf() ?>
          <div class="mb-3">
            <label class="form-label">ユーザー</label>
            <input type="text" class="form-control" value="<?= Auth::user()["email"] ?>" disabled>
          </div>
          <div class="mb-3">
            <label class="form-label">ご連絡の種類</label>
            <select name="type" class="form-select">
              <?php foreach (CONTACT_TYPE as $k => $v): ?>
                <option value="<?= $k ?>"><?= $v["text"] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">本文</label>
            <textarea class="form-control" name="text" rows="10"></textarea>
          </div>
          <div class="text-end">
            <input type="hidden" name="user_id" value="<?= Auth::user()["id"] ?>">
            <button type="submit" class="btn btn-primary">送信</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require './views/templates/footer.php'; ?>