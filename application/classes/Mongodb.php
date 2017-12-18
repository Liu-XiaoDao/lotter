<?php

class Mongodb  {

	/**
	 * @var  string  default instance name
	 */
	public static $default = 'default';

	/**
	 * @var  array  Database instances
	 */
	public static $instances = array();

	public static function instance($name = NULL, array $config = NULL)
	{
		if ($name === NULL)
		{
			// Use the default instance name
			$name = Mongodb::$default;
		}

		if ( ! isset(Mongodb::$instances[$name]))
		{

                        $db = new MongoDB\Client('mongodb://localhost:27017');
			// Store the database instance
			Mongodb::$instances[$name] = $db;
		}

		return Mongodb::$instances[$name];
	}
}
