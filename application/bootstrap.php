<?php

// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
require SYSPATH.'classes/Kohana/Core'.EXT;
require SYSPATH.'classes/Kohana'.EXT;

/**
 * Set the default time zone.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/timezones
 */
date_default_timezone_set('Asia/Shanghai');

/**
 * Set the default locale.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/function.setlocale
 */
setlocale(LC_ALL, 'en_US.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @link http://kohanaframework.org/guide/using.autoloading
 * @link http://www.php.net/manual/function.spl-autoload-register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Optionally, you can enable a compatibility auto-loader for use with
 * older modules that have not been updated for PSR-0.
 *
 * It is recommended to not enable this unless absolutely necessary.
 */
//spl_autoload_register(array('Kohana', 'auto_load_lowercase'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @link http://www.php.net/manual/function.spl-autoload-call
 * @link http://www.php.net/manual/var.configuration#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

// -- Configuration and initialization -----------------------------------------

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules([
  'application' => APPPATH,                         // Main application module
  'mongodb'     => MODPATH.'mongodb',
  'cache'       => MODPATH.'cache',     // Caching with multiple backends
  'pagination'  => MODPATH.'pagination',
  'pinyin'      => MODPATH.'pinyin',
  'auth'        => MODPATH.'auth',      // Basic authentication
  'image'       => MODPATH.'image',     // Image manipulation
  'core'        => SYSPATH,                         // Core system
]);

/**
 * Set the default language
 */
I18n::lang('zh-cn');

if ( ! function_exists('__'))
{
  /**
   * I18n translate alias function.
   *
   * @deprecated 3.4 Use I18n::translate() instead
   */
  function __($string, array $values = NULL, $lang = 'en-us')
  {
    return I18n::translate($string, $values, $lang);
  }
}

if (isset($_SERVER['SERVER_PROTOCOL']))
{
  // Replace the default protocol.
  HTTP::$protocol = $_SERVER['SERVER_PROTOCOL'];
}

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
if (isset($_SERVER['KOHANA_ENV']))
{
  Kohana::$environment = constant('Kohana::'.strtoupper($_SERVER['KOHANA_ENV']));
}

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - integer  cache_life  lifetime, in seconds, of items cached              60
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 * - boolean  expose      set the X-Powered-By header                        FALSE
 */
Kohana::init(array(
  'base_url'   => '/',
  'index_file' => 'index.php',
  'errors' => true,
  'expose' => true,
));

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);

// Initialize modules
Kohana::init_modules();




/**
 * Cookie Salt
 * @see  http://kohanaframework.org/3.3/guide/kohana/cookies
 * 
 * If you have not defined a cookie salt in your Cookie class then
 * uncomment the line below and define a preferrably long salt.
 */
// Cookie::$salt = NULL;
Cookie::$salt = 'abc123456789';

Route::set('directory', '<directory>(/<controller>(/<action>(/<id>)))', ['directory'=>'(api)'])
  ->defaults(array(
    'directory'  => 'api',
    'controller' => 'welcome',
    'action'     => 'index',
  ));
Route::set('default', '(<controller>(/<action>(/<id>)))')
  ->defaults(array(
    'controller' => 'welcome',
    'action'     => 'index',
  ));
