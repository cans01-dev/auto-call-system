<?php 

function result($vars) {
  $id = $vars["id"];
  $reserve = Fetch::find("reserves", $id);
  $survey = Fetch::find("surveys", $reserve["survey_id"]);
  if (Auth::user()["status"] !== 1) {
    if (!Allow::reserve($reserve)) abort(403);
  }

  $calls = Fetch::get("calls", $reserve["id"], "reserve_id");

  if ($calls) {
    $sql = "SELECT COUNT(*) FROM calls WHERE reserve_id = {$reserve["id"]}";
    $survey["called_numbers"] = Fetch::query($sql, "fetchColumn");
  
    $sql = "SELECT COUNT(*) FROM calls WHERE reserve_id = {$reserve["id"]} AND status = 1";
    $survey["responsed_numbers"] = Fetch::query($sql, "fetchColumn");
  
    $survey["response_rate"] = $survey["responsed_numbers"] / $survey["called_numbers"];
  
    $sql = "SELECT COUNT(*) FROM answers as a JOIN calls as c ON a.call_id = c.id
            WHERE c.reserve_id = {$reserve["id"]} AND a.option_id IN (
              SELECT o.id FROM options as o JOIN faqs as f ON o.faq_id = f.id
              WHERE f.survey_id = {$survey["id"]} AND o.next_ending_id = {$survey["success_ending_id"]}
            )";
    $survey["success_numbers"] = Fetch::query($sql, "fetchColumn");
  
    $survey["success_rate"] =  $survey["success_numbers"] / $survey["responsed_numbers"];
    
    $survey["faqs"] = Fetch::get("faqs", $survey["id"], "survey_id", "order_num");
    foreach ($survey["faqs"] as $key => $faq) {
      $options = Fetch::get("options", $faq["id"], "faq_id");
      $survey["faqs"][$key]["options"] = $options;
      foreach($options as $key2 => $option) {
        $sql = "SELECT COUNT(*) FROM answers as a JOIN calls as c ON a.call_id = c.id
                WHERE c.reserve_id = {$reserve["id"]}
                AND a.faq_id = {$faq["id"]}
                AND a.option_id = {$option["id"]}";
        $result = Fetch::query($sql, "fetchColumn");
        $survey["faqs"][$key]["options"][$key2]["count"] = $result;
      }
    }
  }

  require_once "./views/pages/result.php";
}
