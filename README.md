# Nexmore

[![Build Status](https://travis-ci.org/gajus/nexmore.png?branch=master)](https://travis-ci.org/gajus/nexmore)
[![Coverage Status](https://coveralls.io/repos/gajus/nexmore/badge.png)](https://coveralls.io/r/gajus/nexmore)

Nexmo offers high volume SMS and Voice APIs via REST and SMPP. Nexmore is [Nexmo RESTful API](https://docs.nexmo.com/) wrapper.

## Documentation

Set Nexmo API credentials:

```php
$messenger = new \gajus\nexmore\Messenger($key, $secret);
```

### Send SMS message

```php
$messenger->sms(string $from, string $to, string $text[, array $parameters]);
```

`sms` method will throw an `InvalidArgumentException` if either of the parameters is unknonw, missing or do not conform to the format requirements.

If at least one message is not delivered `\gajus\nexmore\Error_Exception` exception will be thrown.

```php
try {
	$messenger->sms('Gajus', '447776413499', 'test');
} catch (\InvalidArgumentException $e) {
	// [..]
} catch (\gajus\nexmore\Error_Exception $e) {
	// [..]
} catch (\RuntimeException $e) {
	// [..]
} catch (\Exception $e) {
	// [..]
}
```

### TTS (Text to Speech)

Identical to the `sms` method, except that TTS service does not accept the sender ID (from parameter).

```php
$messenger->tts(string $to, string $text[, array $parameters]);
```

### Inbound Events

Nexmo issues two types of callbacks:

* [Delivery Receipt](https://docs.nexmo.com/index.php/sms-api/handle-delivery-receipt)
* [Inbound Message](https://docs.nexmo.com/index.php/sms-api/handle-inbound-message)

Read the relavent sections of the documentation to learn how to setup the callback URL.

To catch either of these events, use `\gajus\nexmore\Listener` class.

```php
$listener = new \gajus\nexmore\Listener();

$delivery_receipt = $listener->getDeliveryReceipt();
$inbound_message = $listener->getInboundMessage();

if ($delivery_receipt) {
	var_dump($delivery_receipt);
}

if ($inbound_message) {
	var_dump($inbound_message);
}
```

Beware that Nexmore normalises parametr names and converts whatever time input to UNIX timestamp. To understand the re-mapping implementation, refer to the `Listener` class source code.

#### Delivery Receipt

```
array(10) {
  ["sender_id"]=>
  string(11) "12150000025"
  ["recipient_number"]=>
  string(11) "66837000111"
  ["network_code"]=>
  string(5) "52099"
  ["message_id"]=>
  string(16) "000000FFFB0356D2"
  ["status"]=>
  string(9) "delivered"
  ["error_code"]=>
  string(1) "0"
  ["price"]=>
  string(10) "0.02000000"
  ["receipt_timestamp"]=>
  int(1344779940) <== 2012-08-12 13:59:00
  ["message_timestamp"]=>
  int(1344779977) <== 2012-08-12 13:59:37
  ["reference"]=>
  NULL
}
```

#### Inbound message

```
array(7) {
  ["type"]=>
  string(4) "text"
  ["recipient_number"]=>
  string(11) "12108054321"
  ["sender_id"]=>
  string(11) "19150000001"
  ["network_code"]=>
  NULL
  ["message_id"]=>
  string(16) "000000FFFB0356D1"
  ["message_timestamp"]=>
  int(1345408703)
  ["text"]=>
  string(26) "This is an inbound message"
}
```

## To do

* Either to allow direct API calls or create interface for Account features.

## Alternatives

If you don't like Nexmore implementation, please [raise an issue](https://github.com/gajus/Nexmore/issues).

The following are the known alternatives:

* https://github.com/aatishnn/NexmoAlert
* https://github.com/prawnsalad/Nexmo-PHP-lib
* https://github.com/mousems/SimpleNexmoSender

If you know more alternatives, please tell me and I will include them in the above list.