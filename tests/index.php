<?php
set_include_path(__DIR__ . '/../src' . PATH_SEPARATOR . __DIR__);

spl_autoload_register();

#$messenger = new \gajus\nexmore\Messenger('cc9d2471', '69c35f29');

#var_dump( $messenger->sms('447776413499', 'test', ['nrseint' => 'rsteits', 'from' => 'Gajus']) );

#var_dump( $carrier->getReceipt(), $carrier );

$receipt_input_valid = [
	'msisdn' => '66837000111',
	'to' => '12150000025',
	'network-code' => '52099',
	'messageId' => '000000FFFB0356D2',
	'price' => '0.02000000',
	'status' => 'delivered',
	'scts' => '1208121359',
	'err-code' => '0',
	'message-timestamp' => '2012-08-12 13:59:37'
];

$inbound_message_input_valid = [
	'msisdn' => '19150000001',
	'to' => '12108054321',
	'messageId' => '000000FFFB0356D1',
	'text' => 'This is an inbound message',
	'type' => 'text',
	'message-timestamp' => '2012-08-19 20:38:23'
];

$listener = new \gajus\nexmore\Listener($receipt_input_valid);
var_export( $listener->getDeliveryReceipt() );

#$listener = new \gajus\nexmore\Listener($inbound_message_input_valid);
#bump( $listener->getInboundMessage() );