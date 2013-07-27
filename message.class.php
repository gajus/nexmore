<?php
namespace ay\nexmo;

class Message {
	private
		$url,
		$key,
		$secret;
	
	public function __construct ($url, $key, $secret) {
		$this->url = $url;
		$this->key = $key;
		$this->secret = $secret;
	}
	
	public function send ($from, $to, $message) {
		return $this->sendRequest(['from' => $from, 'to' => $to, 'text' => $message]);
	}
	
	private function sendRequest (array $parameters) {
		$ch = curl_init();
		
		if (!isset($parameters['from'])) {
			throw new \ErrorException('Originator name is required.');
		}
		
		$parameters = ['api_key' => $this->key, 'api_secret' => $this->secret] + $parameters;

		curl_setopt_array($ch, [
			CURLOPT_URL => $this->url,
			CURLOPT_TIMEOUT => 5,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYHOST => 2,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_POST => true,
			CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
			CURLOPT_POSTFIELDS => json_encode($parameters)
		]);
		
		$response = curl_exec($ch);
		
		if (curl_errno($ch)) {
			throw new \ErrorException(curl_error($ch));
		}
		
		curl_close($ch);
		
		return json_decode($response, true);
	}
}