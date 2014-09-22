<?php

function get_signature($secret_key, $params) {
    $keys = array_keys($params);
    sort($keys);
    $chunks = array();
    foreach ($keys as $k) {
        $v = (string) $params[$k];
        if (($v !== '') && ($k != 'signature')) {
            $chunks[] = $k . '=' . base64_encode($v);
        }
    }
    return double_sha1($secret_key, implode('&', $chunks));
}

function double_sha1($secret_key, $data) {
    return sha1($secret_key . sha1($secret_key . $data));
}

$secret_key = 'C0FFEE';
$params = array(
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
assert($signature == '163807001c1997be21352ea53fa6e5f4e988483d');

assert(get_signature($secret_key, array('testing' => 'x')) == '22b6416d3d9ece6d969d40c62097f1b1878b0b89');
assert(get_signature($secret_key, array('testing' => '0')) == 'c869d507973a539f73e5c5fe08017001171cfd1e');
assert(get_signature($secret_key, array('testing' => 0)) == 'c869d507973a539f73e5c5fe08017001171cfd1e');
