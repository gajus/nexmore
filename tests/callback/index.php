<?php
// Unfortunately Nexmo does not provide test end points.
// Poor man's implementaion to cover the most basic test cases.

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	throw new \Exception('Request method is not POST.');
}

$input = json_decode(file_get_contents('php://input'), true);

#header('Content-Type: application/json'); echo json_encode($input); exit;

switch ($_GET['path']) {
	case '/sms/json':

		if (isset($input['message-class']) && $input['message-class'] == 'invalid') {
			$response = [
				'message-count' => 1,
				'messages' => [
					[
						'status' => '20',
					    'error-text' => 'bad message-class param'
					]
				]
			];
		} else {
			$response = [
				'message-count' => 1,
				'messages' => [
					[
						'status' => '0',
						'message-id' => '00000123',
					    'to' => '44123456789',
					    'remaining-balance' => '1.10',
					    'message-price' => '0.05',
					    'network' => '23410'
					]
				]
			];
		}

	break;

	case '/tts/json':

		$response = [
			'call-id' => 1,
			'to' => '447776413499',
			'status' => '0'
			#'error-text' => 
		];

	break;
}

header('Content-Type: application/json');

#var_dump(['get' => $_GET, 'post' => $_POST]);

echo json_encode($response);