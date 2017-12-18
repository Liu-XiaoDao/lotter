<?php

abstract class Kohana_Pssdb {
    /**
     * @var Ssdb_Client    A singleton instance
     */
    public static $instances = array();

    /**
     * @var  string  default instance name
     */
    public static $default = 'default';
    
    // Instance name
    protected $_instance;
    
    /**
     * Construct
     *
     * @param   string  $redis_group
     * @throws  Redis_Exception
     */
    protected function __construct($name, $config)
    {
        // Init the redis client
        $connected = TRUE;
        try
        {
            $this->_instance  = new SSDB($config['connection']['hostname'], $config['connection']['port']);
        }
        catch (Exception $e)
        {
            $connected = FALSE;
        }


        if ( ! $connected)
        {
            throw new Exception('Can not connect to ssdb server.');
        }

    }

    /**
     * Returns a singleton instance of the class
     *
     * @return  Ssdb_Client
     */
    public static function instance($name = NULL, array $config = NULL)
    {
      if ($name === NULL)
      {
        // Use the default instance name
        $name = Pssdb::$default;
      }

      if ( ! isset(Pssdb::$instances[$name]))
      {
        if ($config === NULL)
        {
          // Load the configuration for this database
          $config = Kohana::$config->load('ssdb')->$name;
        }
        // Create the database connection instance
        $driver = new Pssdb($name, $config);
        // Store the database instance
        Pssdb::$instances[$name] = $driver->get_instance();
      }
      return Pssdb::$instances[$name];
    }


    public function get_instance()
    {
      return $this->_instance;
    }
    
}
