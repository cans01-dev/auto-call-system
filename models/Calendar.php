<?php 

class Calendar
{
  private $calendar = [];
  private $month, $year;

  public function __construct($month, $year)
  {
    $this->month = $month;
    $this->year = $year;

    # 空の配列を生成
    for ($i = 0; $i < 6; $i++) {
      $week = array_fill(0, 6, null);
      array_push($this->calendar, $week);
    }

    # 月の全ての日のタイムスタンプに
    $week = 0;
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
    foreach (range(1, $daysInMonth) as $day) {
      $timestamp = mktime(0, 0, 0, $this->month, $day, $this->year);
      $w = date("w", $timestamp);
      $this->calendar[$week][$w] = [
        "timestamp" => $timestamp,
        "today" => date("Y-m-d") === date("Y-m-d", $timestamp)
      ];
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

?>