<?php 

/**
 * Database-based session class.
 *
 * @package    Kohana/Ssdb
 * @category   Session
 * @author     wxg4dev
 */
 
class Session_Pssdb extends Session {


  // Garbage collection requests
  protected $_gc = 600;

  // The current session id
  protected $_session_id;

  // The old session id
  protected $_update_id;

  public function __construct(array $config = NULL, $id = NULL)
  {
    if ( ! isset($config['group']))
    {
      // Use the default group
      $config['group'] = Pssdb::$default;
    }

    $this->_ssdb = Pssdb::instance($config['group']);

    if (isset($config['gc']))
    {
      // Set the gc chance
      $this->_gc = (int) $config['gc'];
    }

    parent::__construct($config, $id);
  }

  public function id()
  {
    return $this->_session_id;
  }

  protected function _read($id = NULL)
  {
    if ($id OR $id = Cookie::get($this->_name))
    {
      $result = $this->_ssdb->get($id);

      if ($result !== FALSE)
      {
        // Set the current session id
        $this->_session_id = $this->_update_id = $id;

        // Return the contents
        return $result;
      }
    }

    // Create a new session id
    $this->_regenerate();

    return NULL;
  }

  protected function _regenerate()
  {
    do
    {
      // Create a new session id
      $id = str_replace('.', '-', uniqid(NULL, TRUE));
      
      // Create the query to find an ID
      $result = $this->_ssdb->get($id);
    }
    while ($result);

    return $this->_session_id = $id;
  }

  protected function _write()
  {
    $this->_ssdb->setx($this->_session_id, $this->__toString(), $this->_gc);
    
    // The update and the session id are now the same
    $this->_update_id = $this->_session_id;

    // Update the cookie with the new session id
    Cookie::set($this->_name, $this->_session_id, $this->_lifetime);

    return TRUE;
  }

  /**
   * @return  bool
   */
  protected function _restart()
  {
    $this->_regenerate();

    return TRUE;
  }

  protected function _destroy()
  {
    if ($this->_update_id === NULL)
    {
      // Session has not been created yet
      return TRUE;
    }

    try
    {
      // Execute the query
      $this->_ssdb->del($this->_update_id);

      // Delete the cookie
      Cookie::delete($this->_name);
    }
    catch (Exception $e)
    {
      // An error occurred, the session has not been deleted
      return FALSE;
    }

    return TRUE;
  }
  
  /**
   * Serializes the session data.
   *
   * @param   array  $data  data
   * @return  string
   */
  protected function _serialize($data)
  {
      return serialize($data);
  }

  /**
   * Unserializes the session data.
   *
   * @param   string  $data  data
   * @return  array
   */
  protected function _unserialize($data)
  {
      return unserialize($data);
  }


} // End Kohana_Session_Ssdb
