<?php

function get_signature($secret_key, $params) {
    $keys = array_keys($params);
    sort($keys);
    $chunks = array();
    foreach ($keys as $k) {
        if ($params[$k] && ($k != 'signature')) {
            $chunks[] = $k . '=' . base64_encode($params[$k]);
        }
    }
    return double_sha1($secret_key, implode('&', $chunks));
}

function double_sha1($secret_key, $data) {
    return sha1($secret_key . sha1($secret_key . $data));
}

$secret_key = 'C0FFEE';
$params = Array(
    "merchant" => 43210,
    "amount" => '174.7',
    "currency" => 'RUB',
    "description" => 'Заказ №73138754',
    "order_id" => '73138754',
    "success_url" => 'http://myshop.ru/success/',
    "fail_url" => 'http://myshop.ru/fail/',
    "cancel_url" => 'http://myshop.ru/cart/',
    "signature" => '',
    "unix_timestamp" => '1399461194',
    "meta" => '{"tracking": 1234}',
    "salt" => '00000000000000000000000000000000',
);

$signature = get_signature($secret_key, $params);
echo "signature = '$signature'\n";
assert($signature == 'cb3743cc37d87f5a4255fc3a99c223c0e869c145');
