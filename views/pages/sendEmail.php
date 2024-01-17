<?php require './views/templates/header.php'; ?>

<?= Components::h2("送信先メールアドレス: hoge@example.com") ?>

<section id="summary">
  <?= Components::h3("設定"); ?>
  <div style="max-width: 480px;">
    <form action="" method="post">
      <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">メールアドレス</label>
        <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="foobar@example.com">
      </div>
      <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
        <label class="form-check-label" for="flexSwitchCheckChecked">有効化</label>
      </div>
      <div class="text-end">
        <button type="submit" class="btn btn-dark">更新</button>
      </div>
    </form>
    <form action="" method="post" onsubmit="return window.confirm('本当に削除しますか？')">
      <input type="hidden" name="_method" value="delete">
      <div class="text-end">
        <input type="submit" class="btn btn-link" value="この送信先メールアドレスを削除">
      </div>
    </form>
  </div>
</section>

<?php require './views/templates/footer.php'; ?>