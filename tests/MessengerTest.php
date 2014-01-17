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
	public function testInvalidSendId ($sender_id) {
		$this->messenger->sms($sender_id, '447776413499', 'test');
	}

	/**
	 * @dataProvider validRecipientNumberProvider
	 */
	public function testValidRecipientNumber ($recipient_number) {
		$this->messenger->sms('test', $recipient_number, 'test');
	}

	/**
	 * @dataProvider invalidRecipientNumberProvider
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidRecipientNumber ($recipient_number) {
		$this->messenger->sms('test', $recipient_number, 'test');
	}

	/**
	 * @expectedException gajus\nexmore\Error_Exception
	 */
	public function testSendSMSError () {
		$this->messenger->sms('test', '447776413499', 'test', ['message-class' => 'invalid']);
	}

	/**
	 * @expectedException gajus\nexmore\Error_Exception
	 */
	public function testSendTTSError () {
		// Passing invalid parameter values does not trigger an error.
		throw new \gajus\nexmore\Error_Exception('Temporary. Bug reported.');

		$this->messenger->tts('447776413499', 'test', ['callback' => 'invalid']);
		$this->messenger->tts('447776413499', 'test', ['voice' => 'invalid']);
		$this->messenger->tts('447776413499', 'test', ['lg' => 'invalid']);
	}

	public function testSendSMS () {
		$this->messenger->sms('test', '447776413499', 'test');
	}

	public function testSendTTS () {
		$this->messenger->tts('447776413499', 'test');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testTooLongTextSMS () {
		$this->messenger->sms('test', '447776413499', str_repeat('a', 3201));
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testTooLongTextTTS () {
		$this->messenger->tts('447776413499', str_repeat('a', 1001));
	}

	/**
	 * @dataProvider reservedSMSParametersProvider
	 * @expectedException InvalidArgumentException
	 */
	public function testReservedSMSParameters ($parameter) {
		$parameters = [];
		$parameters[$parameter] = 'test';

		$this->messenger->sms('test', '447776413499', 'test', $parameters);
	}

	public function reservedSMSParametersProvider () {
		return [
			['from'],
			['to'],
			['text']
		];
	}

	/**
	 * @dataProvider reservedTTSParametersProvider
	 * @expectedException InvalidArgumentException
	 */
	public function testReservedTTSParameters ($parameter) {
		$parameters = [];
		$parameters[$parameter] = 'test';

		$this->messenger->sms('447776413499', 'test', $parameters);
	}

	public function reservedTTSParametersProvider () {
		return [
			['to'],
			['text']
		];
	}

	/**
	 * @dataProvider invalidParametersProvider
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidSMSParameters ($parameter) {
		$parameters = [];
		$parameters[$parameter] = 'test';

		$this->messenger->sms('test', '447776413499', 'test', $parameters);
	}

	/**
	 * @dataProvider invalidParametersProvider
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidTTSParameters ($parameter) {
		$parameters = [];
		$parameters[$parameter] = 'test';

		$this->messenger->sms('447776413499', 'test', $parameters);
	}

	public function invalidParametersProvider () {
		return [
			['foo'],
			['bar']
		];
	}

	public function validSenderIpProvider () {
		return [
			['447776413499'],
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
			['447776413499']
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