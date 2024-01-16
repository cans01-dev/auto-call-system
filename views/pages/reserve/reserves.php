<?php require './views/templates/header.php'; ?>

<h2 class="display-1 pt-4 mb-5">新規作成</h2>

<div class="row gx-4">
  <div class="col-8">
    <div class="btn-group mb-4">
      <a href="<?= url_param_change(["month" => date("m", $prev), "year" => date("Y", $prev)]) ?>" class="btn btn-outline-dark px-3">
        <i class="fa-solid fa-angle-left fa-xl"></i>
      </a>
      <a href="#" class="btn btn-outline-dark px-5 active">
        <span class="fw-bold"><?= date("Y", $current) ?>年 <?= date("n", $current) ?>月</span>
      </a>
      <a href="<?= url_param_change(["month" => date("m", $next), "year" => date("Y", $next)]) ?>" class="btn btn-outline-dark px-3">
        <i class="fa-solid fa-angle-right fa-xl"></i>
      </a>
    </div>

    <table class="calendar-table table table-sm table-bordered">
      <thead class="text-center">
        <tr>
          <?php foreach (range(0, 6) as $w): ?>
            <th scope="col"><?= Calendar::jweek($w)  ?></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($calendar->getCalendar() as $week): ?>
          <tr>
            <?php foreach ($week as $day): ?>
              <td style="height: 100px;">
                <?php if ($day): ?>
                  <p class="text-center"><span class="<?= $day["today"] ? "text-bg-primary badge" : ""; ?>"><?= date("j", $day["timestamp"]); ?></span></p>
                <?php endif; ?>
              </td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require './views/templates/footer.php'; ?>