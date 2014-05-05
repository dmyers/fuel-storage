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
		$path = $this->compute_path($path);
		
		if (!is_file($path)) {
			throw new \FuelException('File does not exist at path (' . $path . ')');
		}
		
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
		$fp = fopen($this->compute_path($path), 'w');
		
		if (!$fp) {
			return false;
		}
		
		fwrite($fp, $data);
		fclose($fp);
		
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
		return $this->config('url') . DS . $path;
	}
	
	public function compute_path($path)
	{
		$path = $this->config('path') . DS . $path;
		
		$this->ensure_dir_exists($path);
		
		return $path;
	}
	
	protected function ensure_dir_exists($path)
	{
		$parts = explode('/', $path);
		unset($parts[count($parts)-1]);
		$path = implode('/', $parts);

		if (!is_dir($path)) {
			mkdir($path, 0777, true);
		}
	}
}
