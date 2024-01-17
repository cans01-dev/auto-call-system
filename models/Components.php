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

  public static function toast($toast) {
    return <<<EOM
      <!-- toast -->
      <div class="toast-container position-fixed bottom-0 start-50 p-3 translate-middle-x">
        <div class="toast align-items-center text-bg-{$toast[0]} border-0" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div class="toast-body">{$toast[1]}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
      </div>
    EOM;
  }
}


