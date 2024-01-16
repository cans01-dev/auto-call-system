<?php require './views/templates/header.php'; ?>

<?php

$calendar = new Calendar(date("m"), date("Y"));
$weeks = $calendar->getCalendar();

?>

<h2 class="display-1 pt-4 pb-3">新規作成</h2>

<h3>カレンダー</h3>
<table class="table">
  <thead>
    <tr>
      <?php foreach (range(0, 6) as $w): ?>
        <th scope="col"><?= Calendar::jweek($w)  ?></th>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($weeks as $week): ?>
      <tr>
        <?php foreach ($week as $day): ?>
          <td style="height: 140px;">
            <?php if ($day): ?>
              <div>
                <?= !is_null($day) ? date("j", $day["timestamp"]) : "・"; ?>
              </div>
            <?php endif; ?>
          </td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php require './views/templates/footer.php'; ?>