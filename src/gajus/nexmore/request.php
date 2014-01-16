<?php
namespace gajus\nexmore;

class Request {

	private
		/**
		 * @param string
		 */
		$key,
		/**
		 * @param string
		 */
		$secret;
	
	public function __construct ($key, $secret) {
		$this->key = $key;
		$this->secret = $secret;
	}

	public function make ($url, array $parameters) {
		$ch = curl_init();
		
		$parameters = ['api_key' => $this->key, 'api_secret' => $this->secret] + $parameters;

		curl_setopt_array($ch, [
			CURLOPT_URL => $url,
			CURLOPT_TIMEOUT => 5,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYHOST => 2,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_POST => true,
			CURLOPT_USERAGENT => 'Nexmore-PHP/0.0.1',
			CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
			CURLOPT_POSTFIELDS => json_encode($parameters)
		]);
		
		$response = curl_exec($ch);
		
		if (curl_errno($ch)) {
			throw new \ErrorException(curl_error($ch));
		}
		
		curl_close($ch);

		#var_dump(PHP_EOL, $response, PHP_EOL);
		
		return json_decode($response, true);
	}
}