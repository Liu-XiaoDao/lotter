<?php 
return array(
  'ssdb' => array(
      /**
       * Database settings for session storage.
       *
       * string   group  configuation group name
       * string   db  session db name
       * integer  gc     number of requests before gc is invoked
       */
      'group'   => 'default',
      'gc'      => 600
    )
);
