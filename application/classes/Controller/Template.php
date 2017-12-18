<?php

class Controller_Template extends Kohana_Controller_Template {

  public $auto_render = true;
  public $template = 'template';
  
  public $db;
  public $year = 0;
  public $swn;
  public $cache;
  public $session;
  public $filters;
  
  public function before()
  {
    parent::before();
    
    $this->year = date('Y', time()+3*30*24*60*60);
    $this->core = Kohana::$config->load('core');
    if (isset($this->core[$this->year]) === false) {
      throw new HTTP_Exception_404();
    }

    $this->filters = ['year'=>$this->year];
    $this->db   = Mongodb::instance();
    $this->swn  = $this->db->swn;;
    $this->session = Session::instance();
    $this->cache = Cache::instance();
    if ($this->auto_render === true) {
        $this->template->bind_global('year', $this->year);
        $this->template->bind_global('core', $this->core);
    }
  }
  
  public function after()
  {
    parent::after();
  }



} // End
