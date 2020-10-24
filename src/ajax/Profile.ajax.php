<?php
session_start();
require '../../_app/Config.inc.php';

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
    case 'changePass':
      $act = $_SESSION['userlogin']['use_password'];
      $old_pwd = md5($PostData['old_pwd']);
      $new_password = md5($PostData['new_password']);
      $new_password2 = md5($PostData['new_password2']);

      if($old_pwd == $act){
        if($new_password2 == $new_password){
          unset($Up);
          $Up['use_password'] = $new_password;
          $Update->ExeUpdate("users", $Up, "WHERE use_id = :id", "id={$_SESSION['userlogin']['use_id']}");
          $_SESSION['userlogin']['use_password'] = $new_password;

          $jSON['trigger'] = ToastError("Senhas alterado com sucesso!");
          $jSON['reset']['#FormChangePass'] = true;
          $jSON['modal']['#alterarSenha'] = 'hide';
        }else{
          $jSON['trigger'] = ToastError("As senhas digitadas não estão iguais!", E_USER_WARNING);
        }
      }else{
        $jSON['trigger'] = ToastError("Senha atual não corresponde!", E_USER_WARNING);
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
