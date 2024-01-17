<?php require "./views/templates/header.php"; ?>

  <p>405 method not allowed</p>
  <p>httpMethod: <?= $httpMethod ?></p>
  <p>allowedMethods: <?php print_r($allowedMethods) ?></p>


<?php require './views/templates/footer.php'; ?>