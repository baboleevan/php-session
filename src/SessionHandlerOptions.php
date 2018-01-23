<?php
/**
 * Class SessionHandlerOptions
 *
 * @filesource   SessionHandlerOptions.php
 * @created      06.03.2017
 * @package      chillerlan\Session
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Session;

use chillerlan\Traits\{Container, ContainerInterface};

/**
 * @property string $filename_prefix
 * @property string $session_name
 * @property string $save_path
 * @property string $db_table
 * @property int    $gc_maxlifetime
 * @property string $hash_algo
 * @property int    $cookie_lifetime
 * @property string $cookie_path
 * @property string $use_encryption
 * @property string $cryptoKey
 */
class SessionHandlerOptions implements ContainerInterface{
	use Container;

	protected $filename_prefix = 'SESSION_';
	protected $session_name    = 'SESSIONID';
	protected $save_path;
	protected $db_table;
	protected $gc_maxlifetime = 3600;
	protected $hash_algo = 'sha512';
	protected $cookie_lifetime = 60*60*24;
	protected $cookie_path = '/';
	protected $use_encryption = true;
	protected $cryptoKey;
}
