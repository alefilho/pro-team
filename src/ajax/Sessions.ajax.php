<?php
session_start();
require_once '../../_app/Config.inc.php';
require_once '../../_app/Library/PHPMailer/PHPmailer.php';
require_once '../../_app/Library/PHPMailer/SMTP.php';

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

      $PostData['ses_endat'] = (!empty($PostData['ses_endat']) ? Check::Data($PostData['ses_endat']) : null);

      if (!empty($id)) {
        $Update->ExeUpdate("sessions", $PostData, "WHERE ses_id = :id", "id={$id}");
      }else {
        $PostData['ses_createdat'] = date("Y-m-d H:i:s");

        $Create->ExeCreate("sessions", $PostData);

        if ($Create->getResult()) {
          $jSON['location'] = BASE . '/panel.php?page=sessions/form&id=' . $Create->getResult();
          $jSON['reset']['#SessionForm'] = true;
        }
      }

      if ($Create->getResult() || $Update->getResult()) {
        $jSON['trigger'] = ToastError("Sessão salva com sucesso");
      }
      break;

    case 'saveTopic':
      $id = isset($PostData['id']) ? $PostData['id'] : null;
      unset($PostData['id']);

      if (!empty($id)) {
        $Update->ExeUpdate("sessions_topics", $PostData, "WHERE top_id = :id", "id={$id}");
      }else {
        $Create->ExeCreate("sessions_topics", $PostData);
        if ($Create->getResult()) {
          $id = $Create->getResult();
        }
      }

      if ($Update->getResult() || $Create->getResult()) {
        $jSON['trigger'] = ToastError("Salvo com sucesso");
        $jSON['reset']['#TopicForm'] = true;

        $jSON['content']['.tbody_topics'] = "";
        $SQL = "SELECT
            top_id,
            top_description
          FROM
            sessions_topics
          WHERE
            top_idsession = {$PostData['top_idsession']}
        ";

        $Read->FullRead($SQL);
        if ($Read->getResult()) {
          foreach ($Read->getResult() as $key => $value) {
            $jSON['content']['.tbody_topics'] .= "<tr class='single_topic' id='{$value['top_id']}'>
              <td>
                <div class='dropdown'>
                  <a class='btn btn-sm btn-icon-only text-light' href='#' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                    <i class='fas fa-ellipsis-v'></i>
                  </a>
                  <div class='dropdown-menu dropdown-menu-right dropdown-menu-arrow' style=''>
                    <button ajaxfile='Sessions' ajaxaction='getTopic' ajaxdata='id={$value['top_id']}' class='dropdown-item j_ajax_generic'>Editar</button>
                  </div>
                </div>
              </td>
              <td>{$value['top_id']}</td>
              <td>{$value['top_description']}</td>
            </tr>";
          }
        }
      }
      break;

    case 'getTopic':
      $Read->FullRead("SELECT * FROM sessions_topics WHERE top_id = {$PostData['id']}");
      if ($Read->getResult()) {
        $jSON['form']['#TopicForm'] = $Read->getResult()[0];
        $jSON['form']['#TopicForm']['id'] = $jSON['form']['#TopicForm']['top_id'];
      }
      break;

    case 'sendEmail':
      $Read->FullRead("SELECT
          mem_id,
          mem_email,
          mem_name
        FROM
          members
          LEFT JOIN classes ON cla_id = mem_idclass
        WHERE
          cla_iduser = {$_SESSION['userlogin']['use_id']}"
      );

      if ($Read->getResult()) {
        foreach ($Read->getResult() as $key => $value) {
          $body = "Olá <b>{$value['mem_name']}</b>, </br></br> {$_SESSION['userlogin']['use_name']} criou uma nova sessão de feedback!";
          $body .= "</br></br> Para acessar clique no link a baixo:";
          $body .= "</br></br><a href='" . BASE_MEMBER . "/panel.php?page=sessions/teams&id={$PostData['id']}'> " . BASE_MEMBER . "/panel.php?page=sessions/teams&id={$PostData['id']}</a>";
          $body .= "</br></br> Atenciosamente,</br>Equipe " . TITLE;

          $Mailer = new PHPMailer();
          $Mailer->IsSMTP();
          $Mailer->CharSet = 'UTF-8';
          $Mailer->SMTPDebug = 0;
          $Mailer->Port = MAIL_PORT; //Indica a porta de conexão
          $Mailer->Host = MAIL_HOST;//Endereço do Host do SMTP
          $Mailer->SMTPAuth = true; //define se haverá ou não autenticação
          $Mailer->Username = MAIL_USER; //Login de autenticação do SMTP
          $Mailer->Password = MAIL_PASS; //Senha de autenticação do SMTP
          $Mailer->FromName = TITLE; //Nome que será exibido
          $Mailer->From = MAIL_FROM; //Obrigatório ser a mesma caixa
          $Mailer->IsHTML(true);
          $Mailer->AddAddress($value['mem_email'], $value['mem_name']);
          $Mailer->Subject = TITLE . ' - Nova Sessão Feedback';
          $Mailer->Body = $body;

          if ($Mailer->Send()) {
            $jSON['trigger'] = ToastError("E-mail(s) enviado(s) com sucesso!");
          }
        }
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
