<?php

require(realpath(__DIR__ . '/../vendor/autoload.php'));

use Http\Client\Socket\Client;
use GuzzleHttp\Psr7\Request;

$PORT_OR_SOCKET = '/var/run/docker/comm.sock';

$client = new Client(null, [
  'remote_socket' => "unix://{$PORT_OR_SOCKET}"
]);

$start_time = microtime( true );
$request = new Request('GET', '/');
$response = $client->sendRequest($request);
$body = $response->getBody();
$time_diff_in_ms = (microtime( true ) - $start_time) * 1000;

echo $body . "\n";
echo (int) $time_diff_in_ms . 'ms';
