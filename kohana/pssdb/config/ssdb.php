<?php

return array
(
    'default' => array
    (
        'connection' => array(
            /**
             * The following options are available:
             *
             * string   hostname    server hostname,
             * string   port        server port
             * string   password    server password
             * boolean  persistent  use persistent connections?
             *
             */
            // 'sock'   => '/tmp/ssdb.sock',
            'hostname'   => '127.0.0.1',
            'port'       => 8888,
            'password'   => FALSE,
            'persistent' => FALSE,
        )
    ),
   'cache' => array
    (
        'connection' => array(
            /**
             * The following options are available:
             *
             * string   hostname    server hostname,
             * string   port        server port
             * string   password    server password
             * boolean  persistent  use persistent connections?
             *
             */
            // 'sock'   => '/tmp/ssdb.sock',
            'hostname'   => '127.0.0.1',
            'port'       => 9609,
            'password'   => FALSE,
            'persistent' => FALSE,
        )
    ),
);
