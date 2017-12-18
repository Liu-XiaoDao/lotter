<?php

return array(

	'driver'       => 'File',
	'hash_method'  => 'sha256',
	'hash_key'     => NULL,
	'lifetime'     => 1209600,
	'session_type' => Session::$default,
	'session_key'  => 'auth_user',

	// Username/password combinations for the Auth File driver
	'users' => array(
		'swn' => '25f91192af970b5bd535ae379e4294e3eb9ebbfaf8b2e2b813d07294839c214f',
	),

);
