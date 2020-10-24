<?php
require '../../_app/Config.inc.php';
require '../functions/transfer-credits-by-order.php';
require '../functions/order-customer.php';

header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json; charset=UTF-8");

$PostData = file_get_contents("php://input");

$Cr['method'] = $_SERVER['REQUEST_METHOD'];
$Cr['input'] = $PostData;
$Cr['datetime'] = date("Y-m-d H:i:s");
$Cr['file'] = 'mercadopago';
$Cr['url'] = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$Cr['headers'] = json_encode(getallheaders());

$Create = new Create;
$Create->ExeCreate("_callbacks", $Cr);

$PostData = json_decode($PostData, true);

if (empty($_GET["id"]) || empty($_GET["topic"]) || empty($_GET["reseller"])) {
  return;
}

if ($_GET["topic"] != 'payment') {
  return;
}

$_GET["reseller"] = (int) $_GET["reseller"];

$Read = new Read;
$Read->FullRead("SELECT
    id,
    key_one,
    key_two
  FROM
    _payment_config
  WHERE
    user_id = {$_GET["reseller"]}
    AND gateway_id = 1"
);

if ($Read->getResult()) {
  $gateway = $Read->getResult()[0];

  $Headers[] = 'Content-Type: application/json';

  $url = MERCADOPAGO_URI. "/payments/{$_GET["id"]}?access_token={$gateway['key_two']}";
  $Curl = new Curl($url, 'GET', null, $Headers);

  if ($Curl->getResponse()['info']['http_code'] == 200) {
    if ($Curl->getResponse()['response']['status'] == 'approved') {
      $Up['datetime_payment'] = date("Y-m-d H:i:s");
      $Up['payment_status_id'] = 3; // 1 -	Processando | 2 -	Aguardando Pagamento | 3 -	Concluido | 4 -	Cancelado | 5 -	Rejeitdato
      $Up['status_id'] = 2; // 1 - Aberto | 2 -Fechado
      $Up['authorization'] = $_GET["id"];

      $Update = new Update;
      $Update->ExeUpdate("_orders", $Up, "WHERE id = :id", "id={$Curl->getResponse()['response']['external_reference']}");

      $Read->FullRead("SELECT id, customer_id, reseller_id FROM _orders WHERE id = {$Curl->getResponse()['response']['external_reference']}");
      if ($Read->getResult()) {
        $order = $Read->getResult()[0];

        if (!empty($order['customer_id'])) {
          orderCustomer($order['id']);
        }elseif (!empty($order['reseller_id'])) {
          transferCreditsByOrder($order['id'], "Movimentacao automatica compra MercadoPago");
        }
      }
    }
  }
}
?>
