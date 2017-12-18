<?php

class Controller_Lottery extends Controller_Template_User {

  public $template = 'lottery';


  public function before()
  {
    parent::before();
  }

  public function action_index()
  {
    $view = View::factory('lottery/2117');
    $count = $this->swn->user->count($this->filters);
    $this->template->body = $view;
  }

} // End
