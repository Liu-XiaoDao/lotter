<?php

class Controller_User extends Controller_Template_User {

  public function before()
  {
    parent::before();
  }

  public function action_index()
  {
    $view = View::factory('user');
    $page = max(Arr::get($_GET, 'page', 1), 1);
    $res = $this->swn->user->find($this->filters);
    $count = $this->swn->user->count($this->filters);
    $view->bind_global('res', $res); 
    $view->bind_global('count', $count); 
    $this->template->body = $view;
  }

  public function action_delete()
  {
    $_id = $this->request->query('_id');
    $this->swn->user->deleteOne(['_id'=> new MongoDB\BSON\ObjectID($_id)]+$this->filters);
    $this->action_index();
  }

  public function action_active()
  {
    $_id = $this->request->query('_id');
    $data = ['active'=>1, 'time'=>time()];
    $res = $this->swn->user->updateOne(['_id'=> new MongoDB\BSON\ObjectID($_id)], ['$set'=>$data]);
    $this->action_index();
  }

} // End
