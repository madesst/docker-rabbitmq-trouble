<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Exception\AMQPRuntimeException;

$connection = connect();
$channel = $connection->channel();
$channel->queue_declare('queue', false, false, false, false);

echo ' * Waiting for messages. To exit press CTRL+C', "\n";

$callback = function($msg){

    $data = json_decode($msg->body, true);
//    echo " * Message received " . date('Y-m-d H:i:s', $data['time']), "\n";

    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

$channel->basic_qos(null, 1, null);
$channel->basic_consume('queue', '', false, false, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

function connect()
{
  try {
    return new AMQPConnection('rabbitmq', 5672, 'guest', 'guest');
  } catch (AMQPRuntimeException $e) {
    sleep(5);
    return connect();	
  }
}
