<?php 

class Calendar
{
  private $calendar = [];
  private $month, $year;

  public function __construct($month, $year, $schedules=[])
  {
    $this->month = $month;
    $this->year = $year;

    # 空の配列を生成
    for ($i = 0; $i < 6; $i++) {
      $week = array_fill(0, 7, null);
      array_push($this->calendar, $week);
    }

    # 月の全ての日のタイムスタンプに
    $days = [];
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
    foreach (range(1, $daysInMonth) as $day) {
      $timestamp = mktime(0, 0, 0, $this->month, $day, $this->year);
      array_push($days, new Day(
        mktime(0, 0, 0, $this->month, $day, $this->year),
        date("Y-m-d") === date("Y-m-d", $timestamp)
      ));
    }

    foreach ($schedules as $date => $schedule) {
      $days[$date - 1]->setSchedule($schedule);
    }
      
    $week = 0;
    foreach ($days as $day) {
      $w = date("w", $day->timestamp);
      $this->calendar[$week][$w] = $day;
      if ($w === "6") $week++;
    }

    # 不要な週を消す
    if ($week < 5) {
      array_splice($this->calendar, 5, 5);
      if ($week < 4) {
        array_splice($this->calendar, 4, 4);
      }
    }
  }

  public function getCalendar() {
    return $this->calendar;
  }

  public function getCurrent() {{
    return mktime(0, 0, 0, $this->month, 1, $this->year);
  }}

  public function getPrev() {{

    return mktime(0, 0, 0, $this->month - 1, 1, $this->year);
  }}

  public function getNext() {{
    return mktime(0, 0, 0, $this->month + 1, 1, $this->year);
  }}

  public static function jweek(int $i) {
    return ["日", "月", "火", "水", "木", "金", "土"][$i];
  }
}

class Day
{
  public $timestamp, $today, $schedule;

  public function __construct($timestamp, $today) {
    $this->timestamp = $timestamp;
    $this->today = $today;
  }

  public function setSchedule($schedule) {
    $this->schedule = $schedule;
  }

  public function getSchedule()
  {
    if ($schedule = $this->schedule) {
      return $schedule;
    } else {
      return false;
    }
  }
}