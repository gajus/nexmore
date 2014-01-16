<?php
class MessengerTest extends PHPUnit_Framework_TestCase {

	private
		$messenger;

	public function setUp () {
		$this->messenger = new \gajus\nexmore\Messenger('dummy', 'dummy', 'https://dev.anuary.com/21704afc-2677-582c-966b-26c5f933e510/tests/callback/?path=');
	}

	/**
	 * @dataProvider validSenderIpProvider
	 */
	public function testValidSenderId ($sender_id) {
		$this->messenger->sms($sender_id, '447776413499', 'test');
	}

	/**
	 * @dataProvider invalidSenderIdProvider
	 * @expectedException InvalidArgumentException
	 */
	#public function testInvalidSendId ($sender_id) {
	#	$this->messenger->sms($sender_id, '447776413499', 'test');
	#}

	/**
	 * @dataProvider validRecipientNumberProvider
	 */
	#public function testValidRecipientNumber ($recipient_number) {
	#	$this->messenger->sms('test', $recipient_number, 'test');
	#}

	/**
	 * @dataProvider invalidRecipientNumberProvider
	 * @expectedException InvalidArgumentException
	 */
	#public function testInvalidRecipientNumber ($recipient_number) {
	#	$this->messenger->sms('test', $recipient_number, 'test');
	#}

	public function validSenderIpProvider () {
		return [
			['123123123123123'], // 15 characters
			['abcabcabcab'], // 11 characters
			['Test Test']
		];
	}

	public function invalidSenderIdProvider () {
		return [
			[123], // not a string,
			['#$'], // unsupported character
			['123123123123123123'], // numeric longer than 15 characters
			['abcabcabcabcabcabc'] // alphanumeric longer than 11 characters
		];
	}

	public function validRecipientNumberProvider () {
		return [
			['123123123123123']
		];
	}

	public function invalidRecipientNumberProvider () {
		return [
			[123], // not a string
			['+44'], // cannot start with +
			['00'], // cannot start with 00
			['1a23'], // contains not numbers
			['123123123123123123'] // longer than 15
		];
	}
}