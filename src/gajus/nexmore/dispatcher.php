<?php
namespace gajus\nexmore;

class Dispatcher {

	private
		/**
		 * @param string
		 */
		$key,
		/**
		 * @param string
		 */
		$secret;

	/**
	 * Listens to Nexmo Delivery Receipt. All knonw receipt $_GET parameters are mapped to
	 * a $receipt property. Parameter names are canonicalized.
	 *
	 * @param string $key
	 * @param string $secret
	 * @param boolean $debug Debug allows indbound traffic to come from outside of the safe subnet.
	 */
	public function __construct ($key, $secret, $debug = false) {
		$this->key = $key;
		$this->secret = $secret;
	}

	/**
	 * A long SMS is split into chunks of 153 chars (in Unicode content messages 67).
	 *
	 * @see https://help.nexmo.com/entries/24578133-How-multipart-SMS-is-constructed-
	 * @see https://docs.nexmo.com/index.php/sms-api/send-message
	 * @param string $from
	 * @param string $to
	 * @param string $text
	 * @param array $parameters
	 */
	public function sms ($from, $to, $text, array $parameters = []) {
		if (isset($parameters['from']) || isset($parameters['to']) || isset($parameters['text'])) {
			throw new \InvalidArgumentException('$parameters argument includes either of the reserved parameters (from, to or text).');
		}

		$this->validateSenderId($from);
		$this->validateRecipientNumber($to);

		if ($unknown = array_diff(array_keys($parameters), ['type', 'status-report-req', 'client-ref', 'network-code', 'vcard', 'vcal', 'ttl', 'message-class', 'body', 'udh'])) {
			throw new \InvalidArgumentException('Unknown/unsupported parameter(s): ' . implode(', ', $unknonw) . '.');
		}

		// @todo It is not clear whether the limit is referring to the number of bytes or UTF-8 encoded characters.
		if (strlen($text) > 3200) {
			throw new \InvalidArgumentException('"text" message maximum length is 3200 characters.');
		}

		return $this->sendRequest('https://rest.nexmo.com/sms/json', ['from' => $from, 'to' => $to, 'text' => $text] + $parameters);
	}

	/**
	 * @see https://docs.nexmo.com/index.php/voice-api/text-to-speech
	 * @param string $to
	 * @param string $text
	 * @param array $parameters
	 */
	public function tts ($to, $text, array $parameters = []) {
		if (isset($parameters['to']) || isset($parameters['text'])) {
			throw new \InvalidArgumentException('$parameters argument includes either of the reserved parameters (to or text).');
		}

		$this->validateRecipientNumber($to);

		if ($unknown = array_diff(array_keys($parameters), ['lg', 'voice', 'repeat', 'drop_if_machine', 'callback', 'callback_method'])) {
			throw new \InvalidArgumentException('Unknown/unsupported parameter(s): ' . implode(', ', $unknonw) . '.');
		}

		// Should leave the validation for the API.
		// if (isset($parameters['lg']) && !in_array($parameters['lg'], ['de-de']))

		return $this->sendRequest('https://rest.nexmo.com/tts/json', ['to' => $to, 'text' => $text] + $parameters);
	}

	/**
	 * @see https://help.nexmo.com/entries/22836672-Message-Originator-SenderID-length
	 * @param string $sender_id
	 */
	private function validateSenderId ($sender_id) {
		if (!is_string($sender_id)) {
			throw new \InvalidArgumentException('Sender ID is not a string.');
		}

		if (preg_replace('/[^a-z0-9]/i', '', $address) !== $address) {
			throw new \InvalidArgumentException('Sender ID contains unsupported characters.');
		}

		$numeric = preg_replace('/[^0-9]/', '', $sender_id) === $sender_id;

		if ($numeric) {
			if (strlen($sender_id) > 15) {
				throw new \InvalidArgumentException('Numeric sender ID length is more than 15 characters.');
			}
		} else {
			if (strlen($sender_id) > 11) {
				throw new \InvalidArgumentException('Alphanumeric sender ID length is more than 11 characters.');
			}
		}
	}

	/**
	 * @see https://help.nexmo.com/entries/20294031-Originator-From-field-parameter-encoding
	 * @param string $recipient_number
	 */
	private function validateRecipientNumber ($recipient_number) {
		if (!is_string($recipient_number)) {
			throw new \InvalidArgumentException('Recipient number is not a string.');
		}

		if (strpos($recipient_number, '+') === 0 || strpos($recipient_number, '00') === 0) {
			throw new \InvalidArgumentException('Recipient number contains leading "+" or "00".');
		}

		if (preg_replace('/[^0-9]/', '', $recipient_number) !== $recipient_number) {
			throw new \InvalidArgumentException('Recipient number contains unsupported characters.');
		}

		if (strlen($originator) > 15) {
			throw new \InvalidArgumentException('Recipient number length is more than 15 characters.');
		}
	}
}