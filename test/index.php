<?php
set_include_path(__DIR__ . '/../src');

spl_autoload_register();

$messenger = new \gajus\nexmore\Messenger('cc9d2471', '69c35f29');

var_dump( $messenger->sms('447776413499', 'test', ['nrseint' => 'rsteits', 'from' => 'Gajus']) );

#var_dump( $carrier->getReceipt(), $carrier );