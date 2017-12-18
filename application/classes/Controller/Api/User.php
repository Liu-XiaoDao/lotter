<?php

class Controller_Api_User extends Controller_Api_Template {

  public function before()
  {
    parent::before();
    if (Auth::instance()->logged_in() == false) {
      $this->redirect('api/error');
    }
  }
  public function action_index()
  {
    $count = $this->swn->user->count($this->filters);
    $res = $this->swn->user->aggregate([['$sample'=>['size'=>1000000000]],['$match'=>$this->filters]]);
    $user = iterator_to_array($res);
    $this->ret($user, 0);
    $this->ret['count'] = $count;
  }

  public function action_lottery()
  {
    $phone = $this->request->query('phone');
    $lottery = $this->request->query('lottery');
    $this->swn->user->updateOne(['phone'=>$phone]+$this->filters, ['$set'=>['lottery'=>$lottery]]); 
  }

  public function action_test()
  { 
    $count = max($this->request->query('count'), 1);
    $user = [];
    for($i=0; $i<$count; $i++) {
      $user[] = [
        'username'=>'测试-'.$i,
        'age'=>rand(0,1),
        'phone'=>12345678900+$i,
        'photo'=>'/upload/test.jpg'
        //'photo'=>'/upload/test.jpg?t='.Text::random(null, 10)
      ];
    }
    $this->ret($user, 0);
    $this->ret['count'] = $count;
  }

  public function action_export()
  {
    $f = '/home/swn-lottery/swn-office.csv';
    $h = fopen($f,"r");
    $index = 1;
    while(! feof($h))
    {
      $u = fgetcsv($h);
      if (!(isset($u[1]) and $u[1])) { continue; }
      $age = 0;
      if ($index < 246) {
        $age = 1;
      }
      $data = ['username'=>$u[1], 'age'=>$age, 'id'=>$u[6]];
      $this->swn->company->insertOne($data);
      $index++;
    }
    fclose($h);
  }

}
