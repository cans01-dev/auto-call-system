<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item"><a href="/surveys/<?= $survey["id"] ?>#faqs"><?= $survey["title"] ?></a></li>
    <li class="breadcrumb-item active">質問: <?= $faq["title"] ?></li>
  </ol>
</nav>
<?= Components::h2("質問: {$faq["title"]}") ?>
<div class="d-flex gap-3">
  <div class="w-100">
    <section id="summary">
      <?= Components::h3("設定"); ?>
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
        <div class="form-text">更新すると入力されたテキストから音声ファイルが自動的に生成されます</div>
      </form>
      <form method="post" onsubmit="return window.confirm('本当に削除しますか？\r\n遷移先がこの質問になっている他の質問の選択肢は自動的に「聞き直し」に変更されます')">
        <?= csrf() ?>
        <?= method("DELETE") ?>
        <div class="text-end">
          <input type="submit" class="btn btn-link" value="この質問を削除">
        </div>
      </form>
    </section>
    <?= Components::hr(3) ?>
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
            <td><?= $option["dial"] ?></td>
            <td><?= $option["title"] ?></td>
            <td>
              <form action="/options/<?= $option["id"] ?>" method="post">
                <?= csrf() ?>
                <?= method("PUT") ?>
                <input type="hidden" name="title" value="<?= $option["title"] ?>" required>
                <select class="form-select" name="next" onchange="submit(this.form)" required>
                  <?php foreach ($survey["faqs"] as $f): ?>
                    <option value="f<?= $f["id"] ?>" <?= $option["next_faq_id"] === $f["id"] ? "selected" : ""; ?>>
                      <?= $f["id"] === $option["faq_id"] ? "【聞き直し】": "【質問】"; ?><?= $f["title"] ?>
                    </option>
                  <?php endforeach; ?>
                  <?php foreach (Fetch::get("endings", $survey["id"], "survey_id") as $ending): ?>
                    <option value="e<?= $ending["id"] ?>" <?= $option["next_ending_id"] === $ending["id"] ? "selected" : ""; ?>>
                      【エンディング】<?= $ending["title"] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </form>
            </td>
            <td>
              <button
                type="button"
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#optionsEditModal<?= $option["id"] ?>"
              >
                編集
              </button>
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
  </div>
  <div class="flex-shrink-0 sticky-aside" style="width: 300px;">
    <div class="sticky-top">
      <section id="summary">
        <?= Components::h4("音声"); ?>
        <?php if ($faq["voice_file"]): ?>
          <div class="text-center py-2">
            <audio controls src="<?= url("/storage/users/{$survey["user_id"]}/{$faq["voice_file"]}") ?>"></audio>
          </div>
        <?php else: ?>
          <?= Components::noContent("音声ファイルがありません") ?>
        <?php endif; ?>
      </section>
    </div>
  </div>
</div>

<!-- optionsCreateModal -->
<div class="modal fade" id="optionsCreateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
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
                <option value="f<?= $f["id"] ?>" <?= $option["next_faq_id"] === $f["id"] ? "selected" : ""; ?>>
                  <?= $f["id"] === $option["faq_id"] ? "【聞き直し】": "【質問】"; ?><?= $f["title"] ?>
                </option>
              <?php endforeach; ?>
              <?php foreach (Fetch::get("endings", $survey["id"], "survey_id") as $ending): ?>
                <option value="e<?= $ending["id"] ?>" <?= $option["next_ending_id"] === $ending["id"] ? "selected" : ""; ?>>
                  【エンディング】<?= $ending["title"] ?>
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
      </div>
    </div>
  </div>
</div>

<?php foreach ($faq["options"] as $option): ?>
  <!-- optionsEditModal<?= $option["id"] ?> -->
  <div class="modal fade" id="optionsEditModal<?= $option["id"] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">選択肢: <?= $option["title"] ?></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post" action="/options/<?= $option["id"] ?>">
            <?= csrf() ?>
            <?= method("PUT") ?>
            <div class="mb-3">
              <label class="form-label">選択肢のタイトル</label>
              <input type="text" name="title" class="form-control" value="<?= $option["title"] ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">次の操作</label>
              <select class="form-select" name="next" onchange="submit(this.form)" required>
                <?php foreach ($survey["faqs"] as $f): ?>
                  <option value="f<?= $f["id"] ?>" <?= $option["next_faq_id"] === $f["id"] ? "selected" : ""; ?>>
                    <?= $f["id"] === $option["faq_id"] ? "【聞き直し】": "【質問】"; ?><?= $f["title"] ?>
                  </option>
                <?php endforeach; ?>
                <?php foreach (Fetch::get("endings", $survey["id"], "survey_id") as $ending): ?>
                  <option value="e<?= $ending["id"] ?>" <?= $option["next_ending_id"] === $ending["id"] ? "selected" : ""; ?>>
                    【エンディング】<?= $ending["title"] ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">ダイヤル番号</label>
              <input type="number" class="form-control" value="<?= $option["dial"] ?>" disabled>
              <div id="passwordHelpBlock" class="form-text">
                ダイヤル番号は並び替えで変更できます
              </div>
            </div>
            <div class="text-end">
              <button type="submit" class="btn btn-dark">更新</button>
            </div>
          </form>
          <form method="post" action="/options/<?= $option["id"] ?>" onsubmit="return window.confirm('本当に削除しますか？\r\n削除を実行すると空いたダイヤル番号に他の選択肢が割り当てられます')">
            <?= csrf() ?>
            <?= method("DELETE") ?>
            <div class="text-end">
              <input type="submit" class="btn btn-link" value="この選択肢を削除">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>

<?php require './views/templates/footer.php'; ?>