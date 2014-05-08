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
	 * Check if an item exists in storage driver.
	 *
	 * @param string $path The path to the item to check.
	 *
	 * @return bool
	 */
	abstract public function exists($path);
	
	/**
	 * Loads item from storage driver.
	 * 
	 * @param string $path The path to the item to get.
	 *
	 * @return string
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
	 * Gets mime for an item in storage driver.
	 * 
	 * @param string $path The path to the item to get the mime.
	 *
	 * @return string
	 */
	abstract public function mime($path);
	
	/**
	 * Uploads an item in storage driver.
	 * 
	 * @param string $path The path to store item at.
	 * @param string $path The path to get item at.
	 *
	 * @return bool
	 */
	abstract public function upload($path, $local);
	
	/**
	 * Downloads an item from storage driver.
	 * 
	 * @param string $path The path to get item at.
	 * @param string $path The path to store item at.
	 *
	 * @return bool
	 */
	abstract public function download($path, $local);
	
	/**
	 * Delete an item in storage driver.
	 *
	 * @param string $path The path to item to delete.
	 *
	 * @return bool
	 */
	abstract public function delete($path);
	
	/**
	 * Gets the url to link to item in storage driver.
	 * 
	 * @param string $path The path to the item to link.
	 *
	 * @return string
	 */
	abstract public function url($path);
	
	/**
	 * Renders an item to the browser from storage driver.
	 * 
	 * @param string $path The path to the item to render.
	 *
	 * @return string
	 */
	public function render($path)
	{
		$contents = Storage::load($path);
		
		$mime = Storage::mime($path);
		
		return \Response::forge($contents, 200, array(
			'Content-Type' => $mime,
		));
	}
}
