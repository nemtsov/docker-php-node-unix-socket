<?php

require( realpath( __DIR__ . '/../vendor/autoload.php' ) );

use Http\Client\Common\Plugin\ContentLengthPlugin;
use Http\Client\Common\Plugin\StopwatchPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\Socket\Client;
use Http\Discovery\MessageFactoryDiscovery;
use Symfony\Component\Stopwatch\Stopwatch;

$PORT_OR_SOCKET = '/var/run/docker/comm.sock';

$start_time = microtime( true );
//-----------------------

$stopwatch = new Stopwatch();

$message_factory = MessageFactoryDiscovery::find();

$client = new PluginClient(
    new Client( $message_factory, [
        'remote_socket' => "unix://{$PORT_OR_SOCKET}"
    ] ),
    [
        new ContentLengthPlugin(),
        new StopwatchPlugin( $stopwatch )
    ]
);

$request  = $message_factory->createRequest( 'POST', '/', [], 'HELLO' );
$response = $client->sendRequest( $request );
$body     = $response->getBody();


//-----------------------
$time_diff_in_ms = (microtime( true ) - $start_time) * 1000;

echo $body . "\n";
echo (int) $time_diff_in_ms . 'ms';

foreach ($stopwatch->getSections() as $section) {
    foreach ($section->getEvents() as $name => $event) {
        echo sprintf('Request %s took %s ms and used %s bytes of memory', $name, $event->getDuration(), $event->getMemory());
    }
}
