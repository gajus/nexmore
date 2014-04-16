<?php
class ListenerDeliveryReceiptTest extends PHPUnit_Framework_TestCase {
	/**
	 * @dataProvider unsafeIpProvider
	 * @expectedException Gajus\Nexmore\Exception\UnexpectedValueException
	 * @expectedExceptionMessage Remote address is not authorised to perform this operation.
	 */
	public function testUnsafeCallbackSource ($ip) {
		$_SERVER['REMOTE_ADDR'] = $ip;

		$_GET = [
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

		$listener = new \Gajus\Nexmore\Listener();

		$listener->getDeliveryReceipt();
	}

	/**
	 * @dataProvider safeIpProvider
	 */
	public function testSafeCallbackSource ($ip) {
		$_SERVER['REMOTE_ADDR'] = $ip;

		$_GET = [
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

		$listener = new \Gajus\Nexmore\Listener();

		$listener->getDeliveryReceipt();
	}

	/**
	 * @dataProvider unsafeIpProvider
	 * @dataProvider safeIpProvider
	 */
	public function testEmptyCallback ($ip) {
		$_SERVER['REMOTE_ADDR'] = $ip;

		$listener = new \Gajus\Nexmore\Listener();

		$this->assertNull($listener->getDeliveryReceipt());
	}

	public function testCallbackDataFormat () {
		$input = [
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

		$listener = new \Gajus\Nexmore\Listener($input);

		$response = $listener->getDeliveryReceipt();

		$expected = [
			'sender_id' => '12150000025',
			  'recipient_number' => '66837000111',
			  'network_code' => '52099',
			  'message_id' => '000000FFFB0356D2',
			  'status' => 'delivered',
			  'error_code' => '0',
			  'price' => '0.02000000',
			  'receipt_timestamp' => 1344779940,
			  'message_timestamp' => 1344779977,
			  'reference' => NULL,
		];

		$this->assertSame($expected, $response);
	}

	public function unsafeIpProvider () {
		return [
			[
				'127.0.0.1',
				'213.205.227.129'
			]
		];
	}

	public function safeIpProvider () {
		return [
			[
				'174.36.197.193',
				'174.36.197.194',
				'174.36.197.195',
				'174.36.197.196',
				'174.36.197.197',
				'174.36.197.198',
				'174.36.197.199',
				'174.36.197.200',
				'174.36.197.201',
				'174.36.197.202',
				'174.36.197.203',
				'174.36.197.204',
				'174.36.197.205',
				'174.36.197.206',
				'119.81.44.1',
				'119.81.44.2',
				'119.81.44.3',
				'119.81.44.4',
				'119.81.44.5',
				'119.81.44.6',
				'119.81.44.7',
				'119.81.44.8',
				'119.81.44.9',
				'119.81.44.10',
				'119.81.44.11',
				'119.81.44.12',
				'119.81.44.13',
				'119.81.44.14'
			]
		];
	}
}