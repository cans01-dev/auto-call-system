<?php

require "../config.php";
require "../models/Fetch.php";
require "../models/DB.php";
require "../functions.php";
require "./functions.php";

$pdo = new_pdo();

$logs = Fetch::all("receive_result_log");

?>

<pre>
  <?= print_r($logs) ?>
</pre>