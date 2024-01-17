<?php 

class Components
{
  public static function h2($text) {
    return <<<EOM
      <h2 class="display-1 pt-4 mb-5">{$text}</h2>
    EOM;
  }

  public static function h3($text) {
    return <<<EOM
      <h3 class="display-6 mb-4">{$text}</h3>
    EOM;
  }

  public static function h4($text) {
    return <<<EOM
      <h4>{$text}</h4>
    EOM;
  }

  public static function hr($my=5) {
    return <<<EOM
      <hr class="my-{$my}">
    EOM;
  }

  public static function modalOpenButton($modalId) {
    return <<<EOM
      <button type="button" class="btn btn-outline-dark w-100" data-bs-toggle="modal" data-bs-target="#{$modalId}">
        <i class="fa-solid fa-plus"></i>
      </button>
    EOM;
  }
}


?>