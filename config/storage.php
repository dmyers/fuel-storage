<?php

return array(
	/**
	 * driver - The driver to use for storage.
	 */
	'driver' => 'file',
	
	'file' => array(
		'url' => \Uri::forge('files'),
		'path' => DOCROOT . 'files',
	),
);
