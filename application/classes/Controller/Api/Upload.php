<?php

class Controller_Api_Upload extends Controller_Api_Template {

  public function action_index()
  {
    if ($this->request->method() !== Request::POST) {
      $this->redirect('api/error');
    }

    $temp_name = Text::random('hexdec', 10);
    $post = Validation::factory($_FILES);
    $post->rule('photo', 'Upload::valid');
    $post->rule('photo', 'Upload::not_empty');
    $post->rule('photo', 'Upload::type', array(':value', array('jpg', 'png', 'jpeg')));

      if ($post->check()) {
          $filename = Upload::save($post['photo']);
          if ($filename) {
              $file = pathinfo($filename);
              $thumb = $file['dirname'].DIRECTORY_SEPARATOR.$file['filename'].'-thumb.'.$file['extension'];
              Image::factory($filename)->resize(360, 360)->save($thumb);
              $this->ret['code'] = 0;
              $this->ret['thumb'] = URL::site(str_replace(DOCROOT, '', $thumb),null, false);
              $this->ret['url'] = URL::base(). URL::site(str_replace(DOCROOT, '', $filename),null, false);
          }
      }
      else {
          $this->ret['info'] = '上传文件没有通过验证！';
      }
  }

}
