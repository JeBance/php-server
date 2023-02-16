<?php
$scan = scandir(__DIR__."/classes/");
for ($i = 0; $i < count($scan); $i++) {
	if (($scan[$i] != ".") and ($scan[$i] != "..")) {
		include(__DIR__."/classes/".$scan[$i]);
	}
}

function uID()
{
	$mt = explode(' ', microtime());
	return $mt[1].substr($mt[0], 2, 6);
}

function uIDtoTime($uID = null)
{
	if (!empty($uID)) {
		return substr($uID, 0, 10);
		//return substr($uID, 10, 6);
	} else {
		return false;
	}
}

function getRandomKey()
{
	$length = 64;
	$characters = '0123456789AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	$randomString = hash_hmac('sha256', $randomString, uID());
	return $randomString;
}
?>
