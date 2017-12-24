<?php 
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('task_queue', false, true, false, false);

$list_tasks = [
	'Upload Photo to AWS',
	'Generate QRCode Customer',
	'Generate PDF',
	'Send Email'
];
foreach ($list_tasks as $key => $data) {
	$msg = new AMQPMessage($data,
	                        array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
	                      );

	$channel->basic_publish($msg, '', 'task_queue');

	echo " [x] Sent ", $data, "\n";
}

$channel->close();
$connection->close();
