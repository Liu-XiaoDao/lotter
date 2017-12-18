<?php

class Controller_Signin extends Controller_Template {

  public function before()
  {
    parent::before();
  }

  public function action_index()
  {
    $view = View::factory('signin');
    $this->template->body = $view;
  }

  public function action_post()
  {
    if ($this->request->method() == Request::POST) {
      $post = $this->request->post('post');
      $post = Arr::extract($post, ['phone']); 
      $this->filters += ['phone'=>$post['phone']];
      if($this->check_phone($post['phone'])) {
        $data = ['active'=>1, 'time'=>time()];
        $res = $this->swn->user->updateOne($this->filters, ['$set'=>$data]);
        if ($res) {
          $res = $this->swn->user->findOne($this->filters);
          $this->redirect('/?active=1&_id='.(string)$res->_id);
        }
      }
      else {
        throw new HTTP_Exception_404('未发现此手机号码');
      }
    }
    $this->action_index();
  }


  private function check_phone($phone)
  {
    $count = $this->swn->user->count($this->filters); 
    return $count == 1;
  }


} // End
