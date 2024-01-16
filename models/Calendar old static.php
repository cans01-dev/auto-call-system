<?php 

class Calendar
{
  public static function calendarInit() {
    $weeks = [];
    for ($i = 0; $i < 6; $i++) {
      $week = array_fill(0, 6, null);
      array_push($weeks, $week);
    }
    return $weeks;
  }

  public static function jweek(int $i) {
    return ["日", "月", "火", "水", "木", "金", "土"][$i];
  }

  public static function firstDayOfWeekInMonth($month, $year) {
    return date("w", mktime(0, 0, 0, $month, 1, $year));
  }
  
  public static function daysInMonth($month, $year) {
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $calendar = [];
    foreach (range(1, $daysInMonth) as $day) {
      $timestamp = mktime(0, 0, 0, $month, $day, $year);
      array_push($calendar, $timestamp);
    }
    return $calendar;
  }  
}

?>