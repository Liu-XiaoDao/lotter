<?php

class Controller_Template_User extends Controller_Template {

  public $lottery;
  public function before()
  {
    parent::before();
    $browser = Text::user_agent(Arr::get($_SERVER, 'HTTP_USER_AGENT', ''), 'browser');
    if ($browser != 'Chrome') {
        throw new HTTP_Exception_403('请用谷歌浏览器访问');
    }
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
      header('WWW-Authenticate: Basic realm="'.$this->year.'"');
      header('HTTP/1.0 401 Unauthorized');
      $this->response->body('请认证访问');
      exit;
    } 
    else {
      $user   = Arr::get($_SERVER, 'PHP_AUTH_USER');
      $passpw = Arr::get($_SERVER,'PHP_AUTH_PW');
      if(Auth::instance()->login($user, $passpw) == false) {
        throw new HTTP_Exception_403('认证错误');
      }
      $this->lottery = $user;
    }
  }

} // End
