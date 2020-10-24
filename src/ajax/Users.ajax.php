<?php
session_start();
require_once '../../_app/Config.inc.php';

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$File = explode('.', basename($_SERVER['PHP_SELF']))[0];

//VALIDA AÇÃO
if ($PostData && $PostData['AjaxFile'] == $File):
  //PREPARA OS DADOS
  $Case = $PostData['AjaxAction'];
  unset($PostData['AjaxAction'], $PostData['AjaxFile']);

  // AUTO INSTANCE OBJECT READ
  if (empty($Read)):
    $Read = new Read;
  endif;

  // AUTO INSTANCE OBJECT CREATE
  if (empty($Create)):
    $Create = new Create;
  endif;

  // AUTO INSTANCE OBJECT UPDATE
  if (empty($Update)):
    $Update = new Update;
  endif;

  // AUTO INSTANCE OBJECT DELETE
  if (empty($Delete)):
    $Delete = new Delete;
  endif;

  //SELECIONA AÇÃO
  switch ($Case):
    case 'save':
      $Read->FullRead("SELECT use_id FROM users WHERE use_email = '{$PostData['use_email']}'");
      if ($Read->getResult()) {
        $jSON['trigger'] = ToastError("Email já cadastrado", E_USER_WARNING);
        echo json_encode($jSON);
        return;
      }

      $PostData['use_password'] = md5($PostData['use_password']);
      $PostData['use_createdat'] = date("Y-m-d H:i:s");

      $Create->ExeCreate("users", $PostData);

      if ($Create->getResult()) {
        $jSON['trigger'] = ToastError("Cadastro realizado com sucesso");
        $jSON['location'] = BASE . '/index.php';
        $jSON['reset']['#SignUp'] = true;
      }
      break;
  endswitch;

  //RETORNA O CALLBACK
  if ($jSON):
    echo json_encode($jSON);
  else:
    $jSON['trigger'] = ToastError("Erro desconhecido, contate o desenvolvedor", E_USER_ERROR);
    echo json_encode($jSON);
  endif;
else:
  //ACESSO DIRETO
  die('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
endif;
