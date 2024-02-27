<?php require './views/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="breadcrumb-nav">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">ホーム</a></li>
    <li class="breadcrumb-item"><a href="/surveys/<?= $survey["id"] ?>/calls">コール一覧</a></li>
    <li class="breadcrumb-item active"><?= $call["number"] ?></li>
  </ol>
</nav>
<?= Components::h2($call["number"]) ?>
<table class="table table-bordered">
  <thead style="top: 43px;">
    <tr>
      <th>ID</th>
      <th>日付・時間</th>
      <th>電話番号</th>
      <th>ステータス</th>
      <th>通話成立時間</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><?= $call["id"] ?></td>
      <td><a href="/reserves/<?= $call["reserve_id"] ?>/result"><?= $call["date"] ?></a> | <?= $call["time"] ?></td>
      <td><?= $call["number"] ?></td>
      <td><?= $call["status"] ?></td>
      <td><?= $call["duration"] ?></td>
    </tr>
  </tbody>
</table>
<?= Components::hr(3) ?>
<?php foreach ($answers as $answer): ?>
  <div class="card mb-2" id="faq<?= $answer["id"] ?>">
    <div class="card-body">
      <h5 class="card-title mb-3">
        <span class="badge bg-primary-subtle text-black me-2">質問</span><?= $answer["title"] ?>
      </h5>
      <p class="card-text"><?= $answer["text"] ?></p>
      <?php if ($answer["options"] = Fetch::get("options", $answer["id"], "faq_id")): ?>
        <table class="table table-sm mb-0">
          <thead>
            <tr>
              <th scope="col">ダイヤル番号</th>
              <th scope="col">TITLE</th>
              <th scope="col">NEXT</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($answer["options"] as $option): ?>
            <tr class="<?= $option["id"] === $answer["option_id"] ? "table-success" : "" ?>">
              <th scope="row"><span class=""><?= $option["dial"] ?></span></th>
              <td><?= $option["title"] ?></td>
              <td>
                <?php if ($option["next_faq"] = Fetch::find2("faqs", [["id", "=", $option["next_faq_id"]]])): ?>
                  <?php if ($option["next_faq"]["id"] !== $answer["id"]): ?>
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
    </div>
  </div>
<?php endforeach; ?>


<?php require './views/templates/footer.php'; ?>