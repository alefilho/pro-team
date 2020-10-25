<?php
require 'Constants.php';

define('TITLE', 'PRO TEAM');
define('AUTHOR', 'alefilho');

define('LIMIT_PAGE', 10);

define('WS_ACCEPT', 'accept');
define('WS_INFOR', 'infor');
define('WS_ALERT', 'alert');
define('WS_ERROR', 'error');

function __autoload($Class) {
  $cDir = ['Conn','Helpers','Models'];
  $iDir = null;

  foreach ($cDir as $dirName):
    if (!$iDir && file_exists(__DIR__ . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $Class . '.class.php') && !is_dir(__DIR__ . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $Class . '.class.php')):
      include_once (__DIR__ . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $Class . '.class.php');
      $iDir = true;
    endif;
  endforeach;

  if (!$iDir):
    trigger_error("Nao foi possivel incluir {$Class}.class.php", E_USER_ERROR);
    die;
  endif;
}

function PHPErro($ErrNo, $ErrMsg, $ErrFile, $ErrLine) {
  $CssClass = ($ErrNo == E_USER_NOTICE ? WS_INFOR : ($ErrNo == E_USER_WARNING ? WS_ALERT : ($ErrNo == E_USER_ERROR ? WS_ERROR : $ErrNo)));
  echo "<p class=\"trigger {$CssClass}\">";
  echo "<b>Erro na Linha: #{$ErrLine} ::</b> {$ErrMsg}<br>";
  echo "<small>{$ErrFile}</small>";
  echo "<span class=\"ajax_close\"></span></p>";

  if ($ErrNo == E_USER_ERROR):
    die;
  endif;
}

set_error_handler('PHPErro');

function WSErro($ErrMsg, $ErrNo, $ErrDie = null) {
  $CssClass = ($ErrNo == E_USER_NOTICE ? WS_INFOR : ($ErrNo == E_USER_WARNING ? WS_ALERT : ($ErrNo == E_USER_ERROR ? WS_ERROR : $ErrNo)));
  echo "<p class=\"trigger {$CssClass}\">{$ErrMsg}<span class=\"ajax_close\"></span></p>";

  if ($ErrDie):
    die;
  endif;
}

function ToastError($ErrMsg, $ErrNo = null)
{
  $CssClass = ($ErrNo == E_USER_NOTICE ? 'trigger_info' : ($ErrNo == E_USER_WARNING ? 'trigger_alert' : ($ErrNo == E_USER_ERROR ? 'trigger_error' : 'trigger_success')));
  return "<div class='trigger trigger_ajax {$CssClass}'>{$ErrMsg}<span class='ajax_close'></span><span class='ajax_close'></span><div class='trigger_progress'></div></div>";
}

if (!function_exists('getallheaders')) {
  function getallheaders() {
    $headers = [];
    foreach ($_SERVER as $name => $value) {
      if (substr($name, 0, 5) == 'HTTP_') {
        $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
      }
    }
    return $headers;
  }
}
