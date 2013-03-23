<?php

namespace Storage;

class Storage_AmazonS3 extends Storage_Driver
{
	protected static $instance;
	
	public static function _init()
	{
		\Package::load('amazons3');
		
		try {
			$instance = \AmazonS3::instance();
		} catch (\AmazonS3Exception $e) {
			return;
		}
		
		self::$instance = $instance;
	}
	
	/**
	 * Loads item from storage driver.
	 * 
	 * @param string $path The path to the item to get.
	 *
	 * @return bool|string
	 */
	public function load($path)
	{
		$data = self::$instance->getObject(null, $path);
		
		return $data->body;
	}
	
	/**
	 * Saves item in storage driver.
	 * 
	 * @param string $path The path to store item at.
	 * @param mixed  $data The data to store.
	 *
	 * @return bool
	 */
	public function save($path, $data)
	{
		return self::$instance->putObject($data, null, $path);
	}
	
	/**
	 * Gets the url to link to item in storage driver.
	 * 
	 * @param string $path The path to the item to link.
	 *
	 * @return string
	 */
	public function url($path)
	{
		return self::$instance->url($path);
	}
}
