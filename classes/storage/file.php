<?php

namespace Storage;

class Storage_File extends Storage_Driver
{
	/**
	 * Loads item from storage driver.
	 * 
	 * @param string $path The path to the item to get.
	 *
	 * @return bool|string
	 */
	public function load($path)
	{
		return file_get_contents($path);
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
		$fp = fopen($path, 'w');
		
		if (!$fp) {
			return false;
		}
		
		fwrite($fp, $data);
		fclose();
		
		return true;
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
		return \Uri::forge($path);
	}
}
