<?php
class MessengerTest extends PHPUnit_Framework_TestCase {

	private
		$messenger;

	public function setUp () {
		$api_url = 'https://dev.anuary.com/21704afc-2677-582c-966b-26c5f933e510/tests/callback/?path=';
		$this->messenger = new \Gajus\Nexmore\Messenger('dummy', 'dummy', $api_url, $api_url);
	}

	/**
	 * Consider using https://jtreminio.com/2013/03/unit-testing-tutorial-part-3-testing-protected-private-methods-coverage-reports-and-crap/
	 * @dataProvider validSenderIpProvider
	 */
	public function testValidSenderId ($sender_id) {
		$this->messenger->sms($sender_id, '447776413499', 'test');
	}

	public function validSenderIpProvider () {
		return [
			['447776413499'],
			['abcabcabcab'], // 11 characters
			['Test Test']
		];
	}

	/**
	 * @dataProvider invalidSenderIdProvider
	 * @expectedException Gajus\Nexmore\Exception\InvalidArgumentException
	 */
	public function testInvalidSendId ($sender_id) {
		$this->messenger->sms($sender_id, '447776413499', 'test');
	}

	public function invalidSenderIdProvider () {
		return [
			[123], // not a string,
			['#$'], // unsupported character
			['123123123123123123'], // numeric longer than 15 characters
			['abcabcabcabcabcabc'] // alphanumeric longer than 11 characters
		];
	}

	/**
	 * @dataProvider validRecipientNumberProvider
	 */
	public function testValidRecipientNumber ($recipient_number) {
		$this->messenger->sms('test', $recipient_number, 'test');
	}

	public function validRecipientNumberProvider () {
		return [
			['447776413499']
		];
	}

	/**
	 * @dataProvider invalidRecipientNumberProvider
	 * @expectedException Gajus\Nexmore\Exception\InvalidArgumentException
	 */
	public function testInvalidRecipientNumber ($recipient_number) {
		$this->messenger->sms('test', $recipient_number, 'test');
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

	/**
	 * @expectedException Gajus\Nexmore\Exception\ErrorException
	 */
	public function testSendSMSError () {
		$this->messenger->sms('test', '447776413499', 'test', ['message-class' => 'invalid']);
	}

	/**
	 * @expectedException Gajus\Nexmore\Exception\ErrorException
	 */
	public function testSendTTSError () {
		// Passing invalid parameter values does not trigger an error.
		throw new \gajus\nexmore\Exception\ErrorException('Temporary. Bug reported.');

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
	 * @expectedException Gajus\Nexmore\Exception\InvalidArgumentException
	 * @expectedExceptionMessage "text" message maximum length is 3200 characters.
	 */
	public function testTooLongTextSMS () {
		$this->messenger->sms('test', '447776413499', str_repeat('a', 3201));
	}

	/**
	 * @expectedException Gajus\Nexmore\Exception\InvalidArgumentException
	 * @expectedExceptionMessage "text" message maximum length is 1000 characters.
	 */
	public function testTooLongTextTTS () {
		$this->messenger->tts('447776413499', str_repeat('a', 1001));
	}

	/**
	 * @dataProvider reservedSMSParametersProvider
	 * @expectedException Gajus\Nexmore\Exception\InvalidArgumentException
	 * @expectedExceptionMessage $parameters argument includes either of the reserved parameters (from, to or text).
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
	 * @expectedException Gajus\Nexmore\Exception\InvalidArgumentException
	 * @expectedExceptionMessage Recipient number contains unsupported characters.
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
	 * @expectedException Gajus\Nexmore\Exception\InvalidArgumentException
	 * @expectedExceptionMessage Unknown/unsupported parameter(s).
	 */
	public function testInvalidSMSParameters ($parameter) {
		$parameters = [];
		$parameters[$parameter] = 'test';

		$this->messenger->sms('test', '447776413499', 'test', $parameters);
	}

	/**
	 * @dataProvider invalidParametersProvider
	 * @expectedException Gajus\Nexmore\Exception\InvalidArgumentException
	 * @expectedExceptionMessage Recipient number contains unsupported characters.
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
}