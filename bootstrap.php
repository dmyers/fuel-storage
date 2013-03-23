<?php

Autoloader::add_core_namespace('Storage');

Autoloader::add_classes(array(
	'Storage\\Storage'          => __DIR__.'/classes/storage.php',
	'Storage\\Storage_Driver'   => __DIR__.'/classes/storage/driver.php',
	'Storage\\Storage_File'     => __DIR__.'/classes/storage/file.php',
	'Storage\\Storage_Amazons3' => __DIR__.'/classes/storage/amazons3.php',
));
