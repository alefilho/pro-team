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
