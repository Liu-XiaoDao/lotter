<?php

class Controller_Welcome extends Controller_Template {

  public $template = 'template';
  
  public function before()
  {
    parent::before();
  }

  public function action_wechat()
  {
    throw new HTTP_Exception_403('请使用微信扫一扫访问');
  }

  public function action_index()
  {
    $view = View::factory('welcome');

    $_id = $this->request->query('_id');
    if ($_id and $res = $this->swn->user->findOne(['_id'=>new MongoDB\BSON\ObjectID($_id)])) {
      $view->bind('res', $res); 
    }
    $count = $this->swn->user->count($this->filters);
    $count_active = $this->swn->user->count(['active'=>1]+$this->filters);
    $view->bind('count', $count);
    $view->bind('count_active', $count_active);
    $this->template->body = $view;
  }


} // End
