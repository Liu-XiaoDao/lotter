<?php

class Controller_Register extends Controller_Template {

  public function action_index()
  {
    $view = View::factory('register');
    $count = $this->swn->user->count();
    $this->template->body = $view;
  }


  public function action_post()
  {
    if ($this->request->method() == Request::POST) {
      $post = $this->request->post('post');
      $post = Arr::extract($post, 
        ['username', 'pre', 'id', 'phone', 'photo', 'cropx', 'cropy', 'cropw', 'croph']); 
      $swn_id = $post['pre'].$post['id'];
      if($this->check_phone($post['phone']) 
        && $this->check_id($swn_id) 
        && $post['username'] 
        && $post['photo'] 
        && $post['cropw'] 
        && $post['croph'] 
        && $post['phone']) {
        $thumb = DOCROOT.urldecode($post['photo']);
        $file = pathinfo($thumb);
        $photo = $file['dirname'].DIRECTORY_SEPARATOR.substr($file['filename'], 0, -6).'.'.$file['extension']; 
        if (is_file($thumb) and is_file($photo)) {
          list($thumb_width, $thumb_height) = getimagesize($thumb);
          list($width, $height) = getimagesize($photo);
          $ratio = $width/$thumb_width;
          $x = $post['cropx']*$ratio;
          $y = $post['cropy']*$ratio;
          $w = $post['cropw']*$ratio;
          $h = $post['croph']*$ratio;
          try {
            Image::factory($photo)->crop($w, $h, $x, $y)->resize(512,512)->save($thumb);
						unset($post['cropx'], $post['cropy'], $post['cropw'], $post['croph']);
          }
          catch (Exception $e) {
            throw new HTTP_Exception_503($e); 
          }
          $post['year'] = $this->year;
          $swn_user = $this->swn->company->findOne(['id'=>$swn_id]); 
          $post['age'] = Arr::get($swn_user, 'age', 0);
          $res = $this->swn->user->insertOne($post);
          $_id = (string) $res->getInsertedId();
          $this->redirect('/?_id='.$_id);
        }
      }
    }
    $this->action_index();
  }
  
  private function check_id($id)
  {
    if ($id == 'XXX-20170020') {
      return true;
    }
    $res = $this->swn->company->findOne(['id'=>$id]); 
    if(empty($res)) {
      throw new HTTP_Exception_403(sprintf('该工牌号码 %s 不存在！', $id));
    }
    $user = $this->swn->user->findOne(['id'=>$id]+$this->filters); 
    if($user) {
      throw new HTTP_Exception_403(sprintf('该工牌号码 %s 已被 %s 注册！', $id, $user->username));
    }
    return true;
  }

  private function check_phone($phone)
  {
    $count = $this->swn->user->count(['phone'=>$phone]+$this->filters); 
    if($count) {
      throw new HTTP_Exception_403(sprintf('该电话号码 %s 已注册！', $phone));
    }
    return true;
  }


} // End
