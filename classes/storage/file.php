<?php

namespace Storage;

class Storage_File extends Storage_Driver
{
	public function __construct()
	{
		$path = $this->config('path');
		
		if (!is_dir($path)) {
			mkdir($path, 0777, true);
		}
	}
	
	/**
	 * Check if an item exists in storage driver.
	 *
	 * @param string $path The path to the item to check.
	 *
	 * @return bool
	 */
	public function exists($path)
	{
		return file_exists($this->compute_path($path));
	}
	
	/**
	 * Loads item from storage driver.
	 * 
	 * @param string $path The path to the item to get.
	 *
	 * @return string
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
	 * Gets mime for an item in storage driver.
	 * 
	 * @param string $path The path to the item to get the mime.
	 *
	 * @return string
	 */
	public function mime($path)
	{
		$finfo = new \Finfo(FILEINFO_MIME_TYPE);

		return $finfo->file($this->compute_path($path));
	}
	
	/**
	 * Uploads an item in storage driver.
	 * 
	 * @param string $local The path to get item at.
	 * @param string $path  The path to store item at.
	 *
	 * @return bool
	 */
	public function upload($local, $path)
	{
		return copy($local, $this->compute_path($path));
	}
	
	/**
	 * Downloads an item from storage driver.
	 * 
	 * @param string $path  The path to get item at.
	 * @param string $local The path to store item at.
	 *
	 * @return bool
	 */
	public function download($path, $local)
	{
		return copy($this->compute_path($path), $local);
	}
	
	/**
	 * Delete an item in storage driver.
	 *
	 * @param string $path The path to item to delete.
	 *
	 * @return bool
	 */
	public function delete($path)
	{
		return @unlink($this->compute_path($path));
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
