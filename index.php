<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
putenv("GNUPGHOME=/tmp");

include('config.php');
include('functions.php');

$result = [ 'result' => 'error', 'error' => 'bad request' ];

if ((php_sapi_name() != 'cli') and (!empty($_REQUEST))) {

	if ((isset($_GET['ping'])) || ((isset($_POST['request'])) and ($_POST['request'] == 'ping'))) {
		$result = [ 'result' => 'pong' ];

	} elseif ((isset($_GET['getServerPublicKey'])) || ((isset($_POST['request'])) and ($_POST['request'] == 'getServerPublicKey'))) {
		$result = [ 'result' => 'ok', 'serverPublicKey' => $config['publicKey'] ];

	} elseif ((isset($_POST['request'])) and (!empty($_POST['request']))) {
		$gpg = new GPG();
		$request = $gpg->decrypt($_POST['request']);
		if ($request) {
			$_POST = $request;
			$response = false;
			switch ($_POST['request']) {
				case 'Hello!':
					$response = [ 'response' => 'Hi!' ];
					break;

				case 'getNewMessages':
					$DB = new DB($config['DB']);
					$messages = $DB->scan($_POST['info']['sendersFingerprint']);
					if (!empty($messages)) {
						$response = [];
						for ($i = 0; $i < count($messages); $i++) {
							$message = $DB->get($_POST['info']['sendersFingerprint'], $messages[$i]);
							$response[$messages[$i]] = $message;
							$DB->delete($_POST['info']['sendersFingerprint'], $messages[$i]);
						}
					}
					break;

				case 'sendMessage':
					if ((isset($_POST['to'])) and (!empty($_POST['to'])) and (preg_match("/^[a-z0-9]{40}$/i", $_POST['to']))
					and (isset($_POST['message'])) and (!empty($_POST['message']))) {
						$uID = uID();
						$DB = new DB($config['DB']);
						$DB->save($_POST['to'], $_POST['message'], $uID);
						$response = [ 'response' => $uID ];
					}
					break;

				default:
					$result = [ 'result' => 'ok' ];
					$result['decrypt'] = $_POST;
					break;
			}

			if ($response) $response = $gpg->encrypt($response);
			if ($response) $result = [ 'result' => 'ok', 'response' => $response ];
		}
	}
}

echo json_encode($result);
?>
