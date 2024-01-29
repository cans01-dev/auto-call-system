<?php

class Allow
{
  public static function survey($survey) {
    return $survey["user_id"] = Auth::user()["id"];
  }

  public static function faq($faq) {
    $survey = Fetch::find("surveys", $faq["id"]);
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

  public static function reserves_areas($reserves_areas) {
    $reserve = Fetch::find("reserves_areas", $reserves_areas["reserve_id"]);
    return self::reserve($reserve);
  }

  public static function favorite($favorite) {
    $survey = Fetch::find("surveys", $favorite["survey_id"]);
    return self::survey($survey);
  }

  public static function favorites_areas($favorites_areas) {
    $favorite = Fetch::find("favorites", $favorites_areas["favorite_id"]);
    return self::favorite($favorite);
  }

  public static function sendEmail($sendEmail) {
    return $sendEmail["user_id"] === Auth::user()["id"];
  }
}