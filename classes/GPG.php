<?php
class GPG
{
	private $publicKey;
	private $privateKey;
	private $passphrase;
	
	public $fingerprint;

	public function __construct()
	{	global $config;
		$this->publicKey = $config['publicKey'];
		$this->privateKey = $config['privateKey'];
		$this->passphrase = $config['passphrase'];
		$this->fingerprint = $config['fingerprint'];
	}

	// функция decrypt принимает зашифрованную JSON строку, расшифровывает и возвращает массив
	public function decrypt($message = null)
	{
		$result = false;
		if (!empty($message)) {
			$res = gnupg_init();
			gnupg_import($res, $this->privateKey);
			gnupg_adddecryptkey($res, $this->fingerprint, $this->passphrase);
			$plaintext = "";
			$info = gnupg_decryptverify($res, $message, $plaintext);
			$array = json_decode($plaintext, true);
			if (is_array($array)) {
				$result = $array;
				if ((isset($info[0]['fingerprint'])) and (!empty($info[0]['fingerprint']))) {
					$result['info']['sendersFingerprint'] = $info[0]['fingerprint'];
					$result['info']['timestamp'] = $info[0]['timestamp'];
				}
			} else {
				$result = false;
			}
		}
		return $result;
	}

	// функция encrypt принимает массив, конвертирует в JSON и возвращает зашифрованную строку
	public function encrypt($message = null)
	{
		$result = false;
		if ((!empty($message)) and (is_array($message))) {
			$res = gnupg_init();
			gnupg_import($res, $this->privateKey);
			$rtv = gnupg_setsignmode($res, GNUPG_SIG_MODE_NORMAL);
			$rtv = gnupg_addsignkey($res, $this->fingerprint, $this->passphrase);
			$rtv = gnupg_addencryptkey($res, $this->fingerprint);
			$message = json_encode($message);
			$result = gnupg_encryptsign($res, $message);
		}
		return $result;
	}
}
?>
