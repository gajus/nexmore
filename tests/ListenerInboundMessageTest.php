<?php
class ListenerInboundMessageTest extends PHPUnit_Framework_TestCase {
	/**
	 * @dataProvider unsafeIpProvider
	 * @expectedException UnexpectedValueException
	 */
	public function testUnsafeCallbackSource ($ip) {
		$_SERVER['REMOTE_ADDR'] = $ip;

		$_GET = [
			'msisdn' => '19150000001',
			'to' => '12108054321',
			'messageId' => '000000FFFB0356D1',
			'text' => 'This is an inbound message',
			'type' => 'text',
			'message-timestamp' => '2012-08-19 20:38:23'
		];

		$listener = new \gajus\nexmore\Listener();

		$listener->getInboundMessage();
	}

	/**
	 * @dataProvider safeIpProvider
	 */
	public function testSafeCallbackSource ($ip) {
		$_SERVER['REMOTE_ADDR'] = $ip;

		$_GET = [
			'msisdn' => '19150000001',
			'to' => '12108054321',
			'messageId' => '000000FFFB0356D1',
			'text' => 'This is an inbound message',
			'type' => 'text',
			'message-timestamp' => '2012-08-19 20:38:23'
		];

		$listener = new \gajus\nexmore\Listener();

		$listener->getInboundMessage();
	}

	/**
	 * @dataProvider unsafeIpProvider
	 * @dataProvider safeIpProvider
	 */
	public function testEmptyCallback ($ip) {
		$_SERVER['REMOTE_ADDR'] = $ip;

		$listener = new \gajus\nexmore\Listener();

		$this->assertNull($listener->getInboundMessage());
	}

	/**
	 * @dataProvider validCallbackProvider
	 */
	public function testValidCallback ($input, $expected) {
		$listener = new \gajus\nexmore\Listener($input);

		$response = $listener->getInboundMessage();

		$this->assertSame($expected, $response);
	}

	public function validCallbackProvider () {
		return [
			[
				// Specific Parameters for Text Inbound
				[
					'msisdn' => '19150000001',
					'to' => '12108054321',
					'messageId' => '000000FFFB0356D1',
					'text' => 'This is an inbound message',
					'type' => 'text',
					'message-timestamp' => '2012-08-19 20:38:23'
				],
				[
					'type' => 'text',
					'recipient_number' => '12108054321',
					'sender_id' => '19150000001',
					'network_code' => NULL,
					'message_id' => '000000FFFB0356D1',
					'message_timestamp' => 1345408703,
					'text' => 'This is an inbound message'
				]
			],
			[
				// Specific Parameters for long 'concatenated' Inbound
				[
					'msisdn' => '19150000001',
					'to' => '12108054321',
					'messageId' => '000000FFFB0356D1',
					'concat' => 'true',
					'concat-ref' => 'test01',
					'concat-total' => '2',
					'concat-part' => '1',
					'text' => 'This is an inbound message',
					'type' => 'text',
					'message-timestamp' => '2012-08-19 20:38:23'
				],
				[
					'type' => 'text',
					'recipient_number' => '12108054321',
					'sender_id' => '19150000001',
					'network_code' => NULL,
					'message_id' => '000000FFFB0356D1',
					'message_timestamp' => 1345408703,
					'text' => 'This is an inbound message',
					'concatenated' => 'true',
					'concatenated_reference' => 'test01',
					'concatenated_total' => '2',
					'concatenated_part' => '1'
				]
			]
		];
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