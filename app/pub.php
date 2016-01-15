<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exception\AMQPRuntimeException;

$connection = connect();         
$channel = $connection->channel();
$channel->queue_declare('queue', false, false, false, false);

$data = json_encode([
	'time' => time()
]);

for ($i = 1; $i != 0; $i++) {

$msg = new AMQPMessage($data, array('delivery_mode' => 2));
$channel->basic_publish($msg, '', 'queue');
//echo " > Message published " . date('Y-m-d H:i:s', time()), "\n";
usleep(rand(5000, 50000));
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
