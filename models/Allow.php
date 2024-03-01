<?php

class Allow
{
  public static function user($user) {    
    return $user["id"] === Auth::user()["id"];
  }

  public static function survey($survey) {    
    return $survey["user_id"] === Auth::user()["id"];
  }

  public static function ending($ending) {
    $survey = Fetch::find("surveys", $ending["survey_id"]);
    return self::survey($survey);
  }

  public static function faq($faq) {
    $survey = Fetch::find("surveys", $faq["survey_id"]);
    return self::survey($survey);
  }

  public static function option($option) {
    $faq = Fetch::find("faqs", $option["faq_id"]);
    return self::faq($faq);
  }

  public static function reserve($reserve) {
    $survey = Fetch::find("surveys", $reserve["survey_id"]);
    return self::survey($survey);
  }

  public static function call($call) {
    $reserve = Fetch::find("reserves", $call["reserve_id"]);
    return self::reserve($reserve);
  }

  public static function ra($ra) {
    $reserve = Fetch::find("reserves", $ra["reserve_id"]);
    return self::reserve($reserve);
  }

  public static function favorite($favorite) {
    $survey = Fetch::find("surveys", $favorite["survey_id"]);
    return self::survey($survey);
  }

  public static function fa($fa) {
    $favorite = Fetch::find("favorites", $fa["favorite_id"]);
    return self::favorite($favorite);
  }

  public static function sendEmail($sendEmail) {
    return $sendEmail["user_id"] === Auth::user()["id"];
  }
}