<?php
namespace ay\nexmo;

class Message {
	
}

#$parameters = ['api_key' => '', 'api_secret' => '69c35f29', 'from' => 'MAMA', 'to' => '+447583246352', 'text' => 'System notification test.'];
		
// @see https://mandrillapp.com/api/docs/messages.JSON.html


$ch = curl_init();

curl_setopt_array($ch, [
	CURLOPT_URL => 'https://rest.nexmo.com/sms/json',
	CURLOPT_TIMEOUT => 5,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_SSL_VERIFYHOST => 2,
	CURLOPT_SSL_VERIFYPEER => true,
	CURLOPT_POST => true,
	CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
	CURLOPT_POSTFIELDS => json_encode($parameters)
]);

$response = curl_exec($ch);

ay($response);

if (curl_errno($ch)) {			
	throw new Error_Exception(error_get_last()['message']);
}

curl_close($ch);

return json_decode($response, true);