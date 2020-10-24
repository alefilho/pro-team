<?php
require '../../_app/Config.inc.php';
require '../functions/transfer-credits-by-order.php';
require '../functions/order-customer.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// $PostData = file_get_contents("php://input");
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

$Cr['method'] = $_SERVER['REQUEST_METHOD'];
$Cr['input'] = json_encode($PostData);
$Cr['datetime'] = date("Y-m-d H:i:s");
$Cr['file'] = 'paghiper';
$Cr['url'] = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$Cr['headers'] = json_encode(getallheaders());

$Create = new Create;
$Create->ExeCreate("_callbacks", $Cr);

if (empty($PostData['apiKey'])) {
  return;
}

$Read = new Read;
$Read->FullRead("SELECT
    id,
    key_one,
    key_two
  FROM
    _payment_config
  WHERE
    key_one = '{$PostData['apiKey']}'
    AND gateway_id = 3"
);

if ($Read->getResult()) {
  $gateway = $Read->getResult()[0];

  $Headers[] = 'Content-Type: application/json';

  $PostData['token'] = $gateway['key_two'];

  $url = MERCADOPAGO_URI. "/transaction/notification/";
  $Curl = new Curl($url, 'POST', json_encode($PostData), $Headers);

  if ($Curl->getResponse()['info']['http_code'] == 200 || $Curl->getResponse()['info']['http_code'] == 201) {
    if ($Curl->getResponse()['response']['status_request']['status'] == 'paid') {
      $Up['datetime_payment'] = date("Y-m-d H:i:s");
      $Up['payment_status_id'] = 3; // 1 -	Processando | 2 -	Aguardando Pagamento | 3 -	Concluido | 4 -	Cancelado | 5 -	Rejeitdato
      $Up['status_id'] = 2; // 1 - Aberto | 2 -Fechado
      $Up['authorization'] = $_GET["id"];

      $Update = new Update;
      $Update->ExeUpdate("_orders", $Up, "WHERE invoice_id = :id", "id={$Curl->getResponse()['response']['status_request']['order_id']}");

      $Read->FullRead("SELECT id, customer_id, reseller_id FROM _orders WHERE invoice_id = '{$Curl->getResponse()['response']['status_request']['order_id']}'");
      if ($Read->getResult()) {
        $order = $Read->getResult()[0];

        if (!empty($order['customer_id'])) {
          orderCustomer($order['id']);
        }elseif (!empty($order['reseller_id'])) {
          transferCreditsByOrder($order['id'], "Movimentacao automatica compra PagHiper");
        }
      }
    }
  }
}
?>
