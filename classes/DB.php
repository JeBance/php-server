<?php
class DB
{
	private $DB_DIR;

	public function __construct($dbDir)
	{	global $config;
		$this->DB_DIR = $dbDir;
	}

	public function check($TB_DIR = null)
	{
		if (!empty($TB_DIR)) {
			$dir = explode('/', $TB_DIR);
			$return_dir = "";
			for ($i = 0; $i < count($dir); $i++) {
				if (!empty($dir[$i])) {
					if (empty($return_dir)) {
						$return_dir = $dir[$i];
					} else {
						$return_dir .= "/".$dir[$i];
					}
					if (!file_exists($this->DB_DIR.$return_dir."/")) mkdir($this->DB_DIR.$return_dir."/");
				}
			}
			if (!empty($return_dir)) {
				return $return_dir;
			} else {
				return false;
			}
		} return false;
	}

	public function scan($TB_DIR = null)
	{
		if (!empty($TB_DIR)) {
			$dir = $this->check($TB_DIR);
			if ($dir != false) {
				$scan = scandir($this->DB_DIR.$dir."/");
				for ($i = 0; $i < count($scan); $i++) {
					if (($scan[$i] != ".") and ($scan[$i] != "..")) {
						$list[] = $scan[$i];
					}
				}
				if (!empty($list)) {
					return $list;
				} else {
					return false;
				}
			} return false;
		} return false;
	}

	public function get($TB_DIR = null, $ID = null)
	{
		$dir = $this->check($TB_DIR);
		if (($dir != false) and (!empty($ID))) {
			if (file_exists($this->DB_DIR.$dir."/".$ID)) {
				do {
					$data = file_get_contents($this->DB_DIR.$dir."/".$ID);
				} while (empty($data));
				return $data;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function save($TB_DIR = null, $data, $ID = null)
	{
		if (empty($ID)) $ID = uID();
		$dir = $this->check($TB_DIR);
		if ($dir != false) {
			$fcreate = fopen($this->DB_DIR.$dir."/".$ID, "w+");
			fwrite($fcreate, $data);
			fclose($fcreate);
		} else {
			return false;
		}
	}

	public function delete($TB_DIR = null, $ID = null)
	{
		$dir = $this->check($TB_DIR);
		if (($dir != false) and (!empty($ID))) {
			unlink($this->DB_DIR.$dir."/".$ID);
		} else {
			return false;
		}
	}
}
?>
