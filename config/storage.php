<?php

return array(
	/**
	 * driver - The driver to use for storage.
	 */
	'driver' => 'file',
	
	'file' => array(
		'url' => \Uri::create('files'),
		'path' => DOCROOT . 'files',
	),
);
