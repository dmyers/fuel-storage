<?php

namespace Storage;

class Storage
{
	/**
	 * Namespace to use for configs.
	 *
	 * @var string
	 */
	protected static $_namespace = 'storage';
	
	/**
	 * Loaded registrar driver instance.
	 *
	 * @var Storage
	 */
	protected static $_instance;
	
	/**
	 * Array of loaded instances.
	 *
	 * @var array
	 */
	protected static $_instances = array();
	
	/**
	 * Initializer executed when class is loaded.
	 *
	 * @return void
	 */
	public static function _init()
	{
		\Config::load(self::$_namespace, true);
	}
	
	/**
	 * Gets a new instance of storage class.
	 *
	 * @param string $driver_name The driver name to use.
	 *
	 * @return Storage
	 */
	public static function forge($driver_name = null)
	{
		if (empty($driver_name)) {
			$driver_name = self::config('driver');
		}
		
		$class = 'Storage_' . \Str::ucwords(\Inflector::denamespace($driver_name));
		$driver = new $class();
		$driver->name = $driver_name;
		
		static::$_instances[$driver_name] = $driver;
		is_null(static::$_instance) and static::$_instance = $driver;
		
		return $driver;
	}
	
	/**
	 * Class constructor.
	 *
	 * @return void
	 */
	final private function __construct() {}
	
	/**
	 * Create or return the driver instance.
	 *
	 * @param string $instance The instance to return.
	 *
	 * @return Storage_Driver
	 */
	public static function instance($instance = null)
	{
		if ($instance !== null) {
			if (array_key_exists($instance, static::$_instances)) {
				return static::$_instances[$instance];
			}
		}
		
		if (static::$_instance === null) {
			static::$_instance = static::forge($instance);
		}
		
		return static::$_instance;
	}
	
	/**
	 * Gets an item from storage config.
	 *
	 * @param string $name    The key name to get.
	 * @param string $default The default is no config found.
	 *
	 * @return mixed
	 */
	public static function config($name, $default = null)
	{
		return \Config::get(self::$_namespace . '.' .  $name, $default);
	}
}

class StorageException extends \FuelException {}
