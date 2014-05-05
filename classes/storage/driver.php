<?php

namespace Storage;

abstract class Storage_Driver
{
	public function config($key, $default = null)
	{
		$name = get_class($this);
		$name = str_replace('Storage\Storage_', '', $name);
		$name = \Str::lower($name);
		
		return Storage::config($name . '.' . $key, $default);
	}
	
	/**
	 * Loads item from storage driver.
	 * 
	 * @param string $path The path to the item to get.
	 *
	 * @return bool|string
	 */
	abstract public function load($path);
	
	/**
	 * Saves item in storage driver.
	 * 
	 * @param string $path The path to store item at.
	 * @param mixed  $data The data to store.
	 *
	 * @return bool
	 */
	abstract public function save($path, $data);
	
	/**
	 * Gets the url to link to item in storage driver.
	 * 
	 * @param string $path The path to the item to link.
	 *
	 * @return string
	 */
	abstract public function url($path);
}
