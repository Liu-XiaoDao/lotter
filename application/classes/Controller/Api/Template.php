<?php

abstract class Controller_Api_Template extends Controller_Template {

  public $ret = ['code'=>1, 'info'=>''];
  public $auto_render = false;

  public function before()
  {
    parent::before();
    $this->response->headers(array('Content-Type' => 'application/json', 'Cache-Control' => 'no-cache'));
  }

  public function ret($info, $code = 1)
  {
    $this->ret['info'] = $info;
    $this->ret['code'] = $code;
  }

  public function after()
  {
    $this->response->body(json_encode($this->ret));
    parent::after();
  }
  
}
