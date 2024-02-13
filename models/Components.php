<?php 

class Components
{
  public static function h2($text) {
    return <<<EOM
      <h2 class="display-4 mb-4">{$text}</h2>
    EOM;
  }

  public static function h3($text) {
    return <<<EOM
      <h3 class="mb-3">{$text}</h3>
    EOM;
  }

  public static function h4($text) {
    return <<<EOM
      <h4 class="mb-2">{$text}</h4>
    EOM;
  }

  public static function hr($my=5) {
    return <<<EOM
      <hr class="my-{$my}">
    EOM;
  }

  public static function modalOpenButton($id) {
    return <<<EOM
      <button type="button" class="btn btn-outline-dark w-100" data-bs-toggle="modal" data-bs-target="#{$id}">
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

  public static function modal($id, $title, $children) {
    $children = str_replace("CSRF", csrf(), $children);
    $children = str_replace("METHOD_PUT", method("PUT"), $children);
    $children = str_replace("METHOD_DELETE", method("DELETE"), $children);
    return <<<EOM
      <!-- {$id} -->
      <div class="modal fade" id="{$id}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">{$title}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            {$children}
          </div>
        </div>
      </div>
    </div>  
    EOM;
  }

  public static function noContent($children) {
    return <<<EOM
      <div class="text-center py-2 rounded border mb-2">{$children}</div>
    EOM;
  }

}


