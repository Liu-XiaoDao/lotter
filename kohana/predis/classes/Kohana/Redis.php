<?php

abstract class Kohana_Redis {

  /**
   * @var  string  default instance name
   */
  public static $default = 'default';

  /**
   * @var  array  Redis instances
   */
  public static $instances = array();

  public static function instance($group = NULL, array $config = NULL)
  {
    if ($group === NULL)
    {
      // Use the default instance name
      $group = Predis::$default;
    }

    if ( ! isset(Predis::$instances[$group]))
    {
      if ($config === NULL)
      {

        // Load the configuration for this redis
        $config = Kohana::$config->load('redis')->$group;
      }

      // Create the redis connection instance
      $redis = new Predis($config);

      // Store the redis instance
      Predis::$instances[$group] = $redis;
    }

    return Predis::$instances[$group];
  }

  // Raw server connection
  protected $_connection;
  protected $_redis;


  // Configuration array
  protected $_config;

  /**
   * Stores the redis configuration locally and name the instance.
   *
   * [!!] This method cannot be accessed directly, you must use [Redis::instance].
   *
   * @return  void
   */
  public function __construct(array $config)
  {
    $connected = TRUE;
    try
    {
      $this->_redis = new Redis();
      $this->_connection  = $this->_redis->connect($config['connection']['hostname'], $config['connection']['port'], $config['connection']['timeout']);
      if ($this->_connection) {
        $this->_redis->select($config['db']);
      }
    }
    catch (Exception $e)
    {
        $connected = FALSE;
    }

    if ( ! $connected)
    {
        throw new Exception('Can not connect to redis server.');
    }
  }


  public function publish($channel, $msg)
  {
    $this->_redis->publish($channel, $msg);
  }

  public function __destruct()
  {
    $this->disconnect();
  }

  public function disconnect()
  {
  
  }

  public function __toString()
  {
    return $this->_connection;
  }


} 
