<?php

return array(
	/**
	 * driver - The driver to use for storage.
	 */
	'driver' => 'file',
	
	'amazons3' => array(
		'bucket' => 'your_bucket',
		'acl'    => 'public-read',
	),
	
	'file' => array(
		'url' => \Uri::create('files'),
		'path' => DOCROOT . 'files',
	),
);
