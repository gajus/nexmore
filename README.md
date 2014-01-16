# Nexmore

Nexmo offers high volume SMS and Voice APIs via REST and SMPP. Nexmore is [Nexmo RESTful API](https://docs.nexmo.com/) wrapper.

## Quick Start

### Authentication

```php
$dispatcher = new \gajus\nexmore\Dispatcher('api key', 'api secret');
$response = $dispathcer->send('sms', $to = '447776413499', $text = 'test');
```

`send` method will throw a `InvalidArgumentException` if either of the parameters is unknonw, missing or have do not conform to the requirements.

Response is considered successful if response "status" parameter is not eq. to "error".

```php
if ($response['status'] === 'error') {
	// Message not sent
} else {
	// Message sent
}
```

Other response parameters vary depending on the method used to send the message:

* 


### Inbound Events

Nexmo issues two types of callbacks:

* [Delivery Receipt](https://docs.nexmo.com/index.php/sms-api/handle-delivery-receipt)
* [Inbound Message](https://docs.nexmo.com/index.php/sms-api/handle-inbound-message)

Read the relavent sections of the documentation to learn how to setup the callback URL. Once you have setup the callback URL, you can use `\gajus\nexmore\Inbound` class to listen for these events.

```php
$listener = new \gajus\nexmore\Inbound();

$delivery_receipt = $listener->getDeliveryReceipt();
$inbound_message = $listener->getInboundMessage();

if ($delivery_receipt) {
	var_dump($delivery_receipt);

	// @todo include sample output
}

if ($inbound_message) {
	var_dump($inbound_message);

	// @todo include sample output
}
```

Beware that Nexmore will re-map the response parameters and convert whatever time input to UNIX timestamp.

