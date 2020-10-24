<?php
require '../../_app/Config.inc.php';
require '../functions/transfer-credits-by-order.php';
require '../functions/order-customer.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$PostData = file_get_contents("php://input");

$Cr['method'] = $_SERVER['REQUEST_METHOD'];
$Cr['input'] = $PostData;
$Cr['datetime'] = date("Y-m-d H:i:s");
$Cr['file'] = 'picpay';
$Cr['url'] = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$Cr['headers'] = json_encode(getallheaders());

$Create = new Create;
$Create->ExeCreate("_callbacks", $Cr);

$PostData = json_decode($PostData, true);

if (empty($PostData['referenceId'])) {
  return;
}

$Read = new Read;
$Read->FullRead("SELECT * FROM _orders WHERE invoice_id = '{$PostData['referenceId']}'");

if ($Read->getResult()) {
  $order = $Read->getResult()[0];

  $userId = $order['owner_id'];

  $Read->FullRead("SELECT
      id,
      key_one,
      key_two
    FROM
      _payment_config
    WHERE
      user_id = {$userId}
      AND gateway_id = 2"
  );

  if ($Read->getResult()) {
    $gateway = $Read->getResult()[0];

    $Headers[] = 'Content-Type: application/json';
    $Headers[] = 'x-picpay-token: ' . $gateway['key_one'];

    $url = PICPAY_URI . "/payments/{$PostData['referenceId']}/status";
    $Curl = new Curl($url, 'GET', null, $Headers);

    if ($Curl->getResponse()['info']['http_code'] == 200) {
      if ($Curl->getResponse()['response']['status'] == 'paid') {
        $Up['datetime_payment'] = date("Y-m-d H:i:s");
        $Up['payment_status_id'] = 3; // 1 -	Processando | 2 -	Aguardando Pagamento | 3 -	Concluido | 4 -	Cancelado | 5 -	Rejeitdato
        $Up['status_id'] = 2; // 1 - Aberto | 2 -Fechado
        $Up['authorization'] = $PostData['authorizationId'];

        $Update = new Update;
        $Update->ExeUpdate("_orders", $Up, "WHERE invoice_id = :id", "id={$PostData['referenceId']}");

        if (!empty($order['customer_id'])) {
          orderCustomer($order['id']);
        }elseif (!empty($order['reseller_id'])) {
          transferCreditsByOrder($order['id'], "Movimentacao automatica compra PicPay");
        }
      }
    }
  }
}
?>
