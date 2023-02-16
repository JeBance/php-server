<?php
// Значения переменных DB, DB_BACKUP и site приведены здесь для примера.
// База данных представлена в виде каталогов с файлами.
// Название каталога - это fingerprint получателя.
// Название файла - это microtime времени получения сообщения сервером.
// Содержимое файла - это сообщение, зашифрованное открытым ключом получателя.
// На сайте api.jebance.ru каталог DB расположен в RAM
// и синхронизируется при перезагрузке с DB_BACKUP во избежание потери данных.
$config =	[
			'DB' => "/mnt/ramdisk/api.jebance.ru/",
			'DB_BACKUP' => "/mnt/ramdisk_backup/api.jebance.ru/",
			'site' => "https://api.jebance.ru/",
			'fingerprint' => 'FINGERPRINT_YOUR_SERVER',
			'passphrase' => '',
			'publicKey' => 'ARMORED_PUBLIC_KEY_YOUR_SERVER',
			'privateKey' => 'ARMORED_PRIVATE_KEY_YOUR_SERVER' ];
?>
