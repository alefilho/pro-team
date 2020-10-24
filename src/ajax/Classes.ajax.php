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
      $id = isset($PostData['id']) ? $PostData['id'] : null;
      unset($PostData['id']);

      $w = "";
      if (!empty($id)) {
        $w = "AND cla_id <> {$id}";
      }

      $Read->FullRead("SELECT cla_id FROM classes WHERE cla_key = '{$PostData['cla_key']}' {$w}");
      if ($Read->getResult()) {
        $jSON['trigger'] = ToastError("Chave de acesso já cadastrada, favor escolha outra!", E_USER_WARNING);
        echo json_encode($jSON);
        return;
      }

      if (!empty($id)) {
        $Update->ExeUpdate("classes", $PostData, "WHERE cla_id = :id", "id={$id}");
      }else {
        $PostData['cla_createdat'] = date("Y-m-d H:i:s");
        $PostData['cla_iduser'] = $_SESSION['userlogin']['use_id'];

        $Create->ExeCreate("classes", $PostData);
      }

      if ($Create->getResult() || $Update->getResult()) {
        $jSON['trigger'] = ToastError("Classe salva com sucesso");
        $jSON['location'] = BASE . '/panel.php?page=classes/index';
        $jSON['reset']['#ClassForm'] = true;
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
