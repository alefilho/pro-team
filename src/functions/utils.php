<?php
function get_string_between($string, $start, $end)
{
  $ret = null;
  $string = ' ' . $string;

  while (true) {
    $ini = strpos($string, $start);
    if ($ini == 0) break;
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;

    $ret[] = substr($string, $ini, $len);

    $string = str_replace($start.substr($string, $ini, $len).$end,"", $string);
  }

  return $ret;
}

function getAuthorizationHeader()
{
  $headers = null;
  if (isset($_SERVER['Authorization'])) {
    $headers = trim($_SERVER["Authorization"]);
  }else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
    $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
  }elseif (function_exists('apache_request_headers')) {
    $requestHeaders = apache_request_headers();
    // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
    $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
    //print_r($requestHeaders);
    if (isset($requestHeaders['Authorization'])) {
      $headers = trim($requestHeaders['Authorization']);
    }
  }
  return $headers;
}

function http_response_error(array $message)
{
  echo json_encode([
    "statusCode" => http_response_code(),
    "timestamp" => date("Y-m-d H:m:i"),
    "method" => $_SERVER['REQUEST_METHOD'],
    "path" => $_SERVER['REQUEST_URI'],
    "data" => $message
  ]);

  die;
}

function triggerNoPermission()
{
  return '<div style="padding: 25px;"><div class="alert alert-default" role="alert">
  ' . getMessageNoPermission() . '
  </div></div>';
}

function triggerRegisterNotFound()
{
  return '<div style="padding: 25px;"><div class="alert alert-default" role="alert">
  ' . getMessageRegisterNotFound() . '
  </div></div>';
}

function getMessageNoPermission()
{
  return "<strong>Ooppss!</strong> Sem permissão para essa ação";
}

function getMessageRegisterNotFound()
{
  return "<strong>Ooppss!</strong> Registro não encontrado";
}
