<?php
namespace gajus\nexmore;

class Listener {

	private
		/**
		 * @param array
		 */
		$input,
		/**
		 * Whether to check IP of the input source.
		 * Set to false if $input array is provided to the __construct.
		 *
		 * @param boolean
		 */
		$restrict_source = true;

	/**
	 * Listens to Nexmo Delivery Receipt. All knonw receipt $_GET parameters are mapped to
	 * a $receipt property. Parameter names are canonicalized.
	 *
	 * @param string $key
	 * @param string $secret
	 * @param boolean $debug Debug allows indbound traffic to come from outside of the safe subnet.
	 */
	public function __construct (array $input = null) {
		if ($input === null) {
			$this->input = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
		} else {
			$this->restrict_source = false;
			$this->input = $input;
		}
	}

	/**
	 * "err-code" and "client-ref" are excluded from the catch condition because
	 * "err-code" might be "0" and "client-ref" if an optional parameter.
	 *
	 * @see https://docs.nexmo.com/index.php/sms-api/handle-delivery-receipt
	 * @return null|array
	 */
	public function getDeliveryReceipt () {
		if (isset($this->input['to'], $this->input['network-code'], $this->input['messageId'], $this->input['msisdn'], $this->input['status'], $this->input['price'], $this->input['scts'], $this->input['message-timestamp'])) {
			$this->sourceIdentity();

			return [
				'sender_id' => $this->input['to'],
				'recipient_number' => $this->input['msisdn'],
				'network_code' => $this->input['network-code'],
				'message_id' => $this->input['messageId'],
				'status' => $this->input['status'],
				'error_code' => isset($this->input['err-code']) ? $this->input['err-code'] : null,
				'price' => $this->input['price'],
				'receipt_timestamp' => \DateTime::createFromFormat('ymdGi', $this->input['scts'])->getTimestamp(),
				'message_timestamp' => \DateTime::createFromFormat('Y-m-d H:i:s', $this->input['message-timestamp'])->getTimestamp(),
				'reference' => isset($this->input['client-ref']) ? $this->input['client-ref'] : null
			];
		}
	}

	/**
	 * "msisdn" and "network-code" are excluded from the catch condition because they are optional parameters.
	 *
	 * @see https://docs.nexmo.com/index.php/sms-api/handle-inbound-message
	 * @return null|array
	 */
	public function getInboundMessage () {
		if (isset($this->input['type'], $this->input['to'], $this->input['messageId'], $this->input['message-timestamp'])) {
			$this->sourceIdentity();

			$inbound_message = [
				'type' => $this->input['type'],
				'recipient_number' => $this->input['to'],
				'sender_id' => isset($this->input['msisdn']) ? $this->input['msisdn'] : null,
				'network_code' => $this->input['network-code'],
				'message_id' => $this->input['messageId'],
				'message_timestamp' => \DateTime::createFromFormat('Y-m-d H:i:s', $this->input['message-timestamp'])->getTimestamp()
			];

			if (isset($this->input['concat'], $this->input['concat-ref'], $this->input['concat-total'], $this->input['concat-part'])) {
				$inbound_message['concatenated'] = $this->input['concat'];
				$inbound_message['concatenated_reference'] = $this->input['concat-ref'];
				$inbound_message['concatenated_total'] = $this->input['concat-total'];
				$inbound_message['concatenated_part'] = $this->input['concat-part'];
			} else if (isset($this->input['data'], $this->input['udh'])) {
				$inbound_message['data'] = $this->input['data'];
				$inbound_message['udh'] = $this->input['udh'];
			} else if (isset($this->input['text'])) {
				$inbound_message['text'] = $this->input['text'];
			} else {
				throw new \Exception('Invalid callback.');
			}

			return $inbound_message;
		}
	}

	/**
	 * @see https://help.nexmo.com/entries/23181071-Source-IP-subnet-for-incoming-traffic-in-REST-API
	 */
	private function sourceIdentity () {
		if (!$this->restrict_source) {
			return;
		}

		$nexmo_server_ips = ['174.36.197.193', '174.36.197.194', '174.36.197.195', '174.36.197.196', '174.36.197.197', '174.36.197.198', '174.36.197.199', '174.36.197.200', '174.36.197.201', '174.36.197.202', '174.36.197.203', '174.36.197.204', '174.36.197.205', '174.36.197.206', '119.81.44.1', '119.81.44.2', '119.81.44.3', '119.81.44.4', '119.81.44.5', '119.81.44.6', '119.81.44.7', '119.81.44.8', '119.81.44.9', '119.81.44.10', '119.81.44.11', '119.81.44.12', '119.81.44.13', '119.81.44.14'];

		if (!in_array($_SERVER['REMOTE_ADDR'], $this->inbound_ips)) {
			throw new \Exception('Remote address (' . $_SERVER['REMOTE_ADDR'] . ') not authorised to perform this operation.');
		}
	}
}