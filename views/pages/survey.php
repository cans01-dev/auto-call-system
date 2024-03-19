<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item"><a href="/surveys/<?= $survey["id"] ?>"><?= $survey["title"] ?></a></li>
    <li class="breadcrumb-item active">会話と音声</li>
  </ol>
</nav>
<?= Components::h2("{$survey["title"]}: 会話と音声") ?>
<div class="d-flex gap-3">
  <div class="w-100">
    <section id="greeting-ending">
      <?= Components::h3("グリーティング・エンディング") ?>
      <div class="form-text mb-2">
        グリーティングで通話の最初に流れるテキストを編集できます。<br>
        エンディングは回答の結果によって変更することができます。
      </div>
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3"><span class="badge bg-secondary-subtle text-black me-3">グリーティング</span></h5>
          <p class="card-text mb-0"><?= $survey["greeting"] ?></p>
          <div class="position-absolute top-0 end-0 p-3">
            <button type="button" class="btn btn-outline-dark icon-link me-2" data-bs-toggle="modal" data-bs-target="#greetingModal">
              <i class="fa-solid fa-volume-high"></i>
              設定と音声
            </button>
          </div>
          <?php if (!$survey["greeting_voice_file"]): ?>
            <div class="alert alert-danger mt-3 mb-0" role="alert">
              グリーティングの読み上げ文章を更新して音声ファイルを生成してください
            </div>
          <?php endif; ?>
        </div>
      </div>
      <?= Components::hr(3) ?>
      <?php if ($survey["endings"]): ?>
        <div class="vstack gap-3 mb-3">
          <?php foreach ($survey["endings"] as $ending): ?>
            <div class="card">
              <div class="card-body">
                <h5 class="card-title mb-3"><span class="badge bg-dark-subtle text-black me-2">
                  エンディング</span><?= $ending["title"] ?>
                </h5>
                <p class="card-text mb-0"><?= $ending["text"] ?></p>
                <div class="position-absolute top-0 end-0 p-3">
                  <?php if ($survey["success_ending_id"] === $ending["id"]): ?>
                    <span class="badge bg-success me-2">成功</span>
                  <?php endif; ?>
                  <button type="button" class="btn btn-outline-dark icon-link me-2" data-bs-toggle="modal" data-bs-target="#endingModal<?= $ending["id"] ?>">
                    <i class="fa-solid fa-volume-high"></i>
                    設定と音声
                  </button>
                </div>
                <?php if (!$ending["voice_file"]): ?>
                  <div class="alert alert-danger mt-3 mb-0" role="alert">
                    エンディングの読み上げ文章を更新して音声ファイルを生成してください
                  </div>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <?= Components::noContent("エンディングがありません") ?>
      <?php endif; ?>
      <?= Components::modalOpenButton("endingsCreateModal") ?>
    </section>
    <?= Components::hr() ?>
    <section id="faqs">
      <?= Components::h3("質問一覧") ?>
      <div class="form-text mb-2">
        一番上に配置された質問が最初の質問（グリーティングの後に再生される質問）となります
      </div>
      <?php if ($survey["faqs"]): ?>
        <div class="vstack gap-3 mb-3">
          <?php foreach ($survey["faqs"] as $faq): ?>
            <div class="card" id="faq<?= $faq["id"] ?>">
              <div class="card-body">
                <h5 class="card-title mb-3">
                  <span class="badge bg-primary-subtle text-black me-2">質問</span><?= $faq["title"] ?>
                </h5>
                <p class="card-text"><?= $faq["text"] ?></p>
                <?php if ($faq["options"] = Fetch::get2("options", [["faq_id", "=", $faq["id"]]], "dial")): ?>
                  <table class="table table-sm mb-0">
                    <thead>
                      <tr>
                        <th scope="col">ダイヤル番号</th>
                        <th scope="col">TITLE</th>
                        <th scope="col">NEXT</th>
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
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                <?php endif; ?>
                <?php if (!$faq["voice_file"]): ?>
                  <div class="alert alert-danger mt-3 mb-0" role="alert">
                    質問の読み上げ文章を更新して音声ファイルを生成してください
                  </div>
                <?php endif; ?>
                <div class="position-absolute top-0 end-0 py-2 px-3">
                  <?php if (!$faq["order_num"]): ?>
                    <span class="badge bg-info me-2">最初の質問</span>
                  <?php endif; ?>
                  <form action="/faqs/<?= $faq["id"] ?>/order" id="upFaq<?= $faq["id"] ?>" method="post" hidden>
                    <?= csrf() ?>
                    <input type="hidden" name="to" value="up">
                  </form>
                  <form action="/faqs/<?= $faq["id"] ?>/order" id="downFaq<?= $faq["id"] ?>" method="post" hidden>
                    <?= csrf() ?>
                    <input type="hidden" name="to" value="down">
                  </form>
                  <div class="btn-group me-2" role="group" aria-label="Basic outlined example">
                    <button
                    type="submit"
                    class="btn btn-outline-primary" <?= !$faq["order_num"] ? "disabled" : ""; ?>
                    form="upFaq<?= $faq["id"] ?>"
                    >
                      <i class="fa-solid fa-angle-up"></i>
                    </button>
                    <button
                    type="submit"
                    class="btn btn-outline-primary" <?= $faq["order_num"] === max(array_column($survey["faqs"], "order_num")) ? "disabled" : ""; ?>
                    form="downFaq<?= $faq["id"] ?>"
                    >
                      <i class="fa-solid fa-angle-down"></i>
                    </button>
                  </div>
                  <a href="/faqs/<?= $faq["id"] ?>" class="btn btn-outline-dark icon-link me-2">
                    <i class="fa-solid fa-volume-high"></i>
                    設定と音声
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <?= Components::noContent("質問がありません") ?>
      <?php endif; ?>
      <?= Components::modalOpenButton("faqsCreateModal"); ?>
    </section>
  </div>
  <div class="flex-shrink-0 sticky-aside" style="width: 300px;">
    <div class="sticky-top">
      <section id="setting">
        <?= Components::h4("設定"); ?>
        <div class="mb-2">
          <form method="post">
            <?= csrf() ?>
            <?= method("PUT") ?>
            <div class="mb-3">
              <label class="form-label">アンケートのタイトル</label>
              <input type="text" name="title" class="form-control" value="<?= $survey["title"] ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">アンケートの説明（任意）</label>
              <textarea class="form-control" name="note" rows="3"><?= $survey["note"] ?></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">成功エンディング</label>
              <select class="form-select" name="success_ending_id">
                <option value="">--未設定--</option>
                <?php foreach ($survey["endings"] as $ending): ?>
                  <option
                    value="<?= $ending["id"] ?>"
                    <?= $ending["id"] === $survey["success_ending_id"] ? "selected" : ""; ?>
                  >
                  <?= $ending["title"] ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">
                生成する音声のタイプ
                <?= Components::infoBtn() ?>
              </label>
              <select class="form-select" name="voice_name">
                <?php foreach (VOICES as $voice): ?>
                  <option
                    value="<?= $voice["name"] ?>"
                    <?= $voice["name"] === $survey["voice_name"] ? "selected" : ""; ?>
                  >
                  <?= "{$voice["name"]} ({$voice["gender"]})" ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="text-end mb-2">
              <button type="submit" class="btn btn-dark">更新</button>
            </div>
            <div class="form-text">
              音声タイプの変更は、既に生成された音声ファイルには影響せず、既存の文章に反映させるには全て更新する必要があります
            </div>
          </form>
        </div>
        <div class="text-end">
          <button type="button" class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#advancedSettingsModal">
            詳細設定
          </button>
        </div>
      </section>
    </div>
  </div>
</div>

<?= Components::modal("audioModal", "読み上げ音声を再生", <<<EOL
  <div class="text-center py-2">
    <audio controls></audio>
  </div>
EOL); ?>

<?= Components::modal("faqsCreateModal", "質問を新規作成", <<<EOL
  <form action="/faqs" method="post">
    CSRF
    <div class="mb-3">
      <label class="form-label">タイトル</label>
      <input type="text" name="title" class="form-control" placeholder="〇〇に関する質問" required>
    </div>
    <div class="mb-3">
      <label class="form-label">読み上げテキスト</label>
      <textarea name="text" class="form-control" rows="5" required></textarea>
    </div>
    <div class="text-end">
      <input type="hidden" name="survey_id" value="{$survey["id"]}">
      <button type="submit" class="btn btn-primary">作成</button>
    </div>
    <div class="form-text">
      質問を作成すると自動的に「0: 聞き直し」の選択肢が設定されます
    </div>
  </form>
EOL); ?>

<?php $greeting_voice_file_url = url("/storage/users/{$survey["user_id"]}/{$survey["greeting_voice_file"]}") ?>
<?= Components::modal("greetingModal", "グリーティングを編集", <<<EOL
  <div class="text-center mb-3">
    <audio controls src="{$greeting_voice_file_url}"></audio>
  </div>
  <form action="/surveys/{$survey["id"]}/greeting" method="post">
    CSRF
    METHOD_PUT
    <div class="mb-3">
      <label class="form-label">テキスト</label>
      <textarea name="greeting" class="form-control" rows="5">{$survey["greeting"]}</textarea>
    </div>
    <div class="text-end">
      <input type="hidden" name="survey_id" value="{$survey["id"]}">
      <button type="submit" class="btn btn-primary">更新</button>
    </div>
    <div class="form-text">更新すると入力されたテキストから音声ファイルが自動的に生成されます</div>
  </form>
EOL); ?>

<?= Components::modal("advancedSettingsModal", "詳細設定", <<<EOL
  <div class="mb-3">
    <form
    action="/surveys/{$survey["id"]}/all_voice_file_re_gen"
    method="post"
    onsubmit="return window.confirm('本当に実行しますか？この操作には時間がかかることがあります')"
    >
      CSRF
      <button class="btn btn-outline-danger">全ての音声ファイルを更新する</button>
      <div class="form-text">
        このアンケートのグリーティングや全てのエンディング、質問の音声ファイルが現在の設定で再生成されます。
      </div>
    </form>
  </div>
  <div class="mb-3">
    <form
    action="/surveys/{$survey["id"]}"
    method="post"
    onsubmit="return window.confirm('本当に削除しますか？')"
    >
      CSRF
      METHOD_DELETE
      <button class="btn btn-outline-danger">アンケートを削除する</button>
      <div class="form-text">
        このアンケートに関連する全てのデータ（質問、予約、結果など）が削除されます。
      </div>
    </form>
  </div>
EOL); ?>

<?= Components::modal("endingsCreateModal", "エンディングを作成", <<<EOL
  <form action="/endings" method="post">
    CSRF
    <div class="mb-3">
      <label class="form-label">エンディングのタイトル</label>
      <input type="text" name="title" class="form-control" placeholder="〇〇のエンディング" required>
    </div>
    <div class="mb-3">
      <label class="form-label">エンディングのテキスト</label>
      <textarea name="text" class="form-control" rows="5" required></textarea>
    </div>
    <div class="text-end">
      <input type="hidden" name="survey_id" value="{$survey["id"]} ?>">
      <button type="submit" class="btn btn-primary">作成</button>
    </div>
  </form>
EOL); ?>

<!-- endingModals -->
<?php foreach ($survey["endings"] as $ending): ?>
  <?= Components::modal("endingModal{$ending["id"]}", "エンディングを編集", <<<EOM
    <form action="/endings/{$ending["id"]}" method="post">
      CSRF
      METHOD_PUT
      <div class="mb-3">
        <label class="form-label">エンディングのタイトル</label>
        <input type="text" name="title" class="form-control" value="{$ending["title"]}">
      </div>
      <div class="mb-3">
        <label class="form-label">テキスト</label>
        <textarea name="text" class="form-control" rows="5">{$ending["text"]}</textarea>
      </div>
      <div class="text-end">
        <button type="submit" class="btn btn-primary">更新</button>
      </div>
      <div class="form-text">更新すると入力されたテキストから音声ファイルが自動的に生成されます</div>
    </form>
    <form action="/endings/{$ending["id"]}" method="post"  onsubmit="return window.confirm('本当に削除しますか？')">
      CSRF
      METHOD_DELETE
      <div class="text-end">
        <button type="submit" class="btn btn-link">このエンディングを削除</button>
      </div>
    </form>
  EOM) ?>
<?php endforeach; ?>

<?= Auth::user()["id"] !== $survey["user_id"]? Components::watchOnAdmin("管理者として閲覧専用でこのページを閲覧しています") : "" ?>

<?php require './views/templates/footer.php'; ?>