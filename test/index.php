<?php
set_include_path(__DIR__ . '/../src');

spl_autoload_register();

$carrier = new \gajus\nexmore\Dispatcher('cc9d2471', '69c35f29');

var_dump( $carrier->getReceipt(), $carrier );