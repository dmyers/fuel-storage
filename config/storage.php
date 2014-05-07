<?php

return array(
	/**
	 * driver - The driver to use for storage.
	 */
	'driver' => 'file',
	
	'amazons3' => array(
		'bucket' => 'your_bucket',
	),
	
	'file' => array(
		'url' => \Uri::create('files'),
		'path' => DOCROOT . 'files',
	),
);
