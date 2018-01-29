<?php
/**
 * Trait SessionHandlerOptionsTrait
 *
 * @filesource   SessionHandlerOptionsTrait.php
 * @created      29.01.2018
 * @package      chillerlan\Session
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\Session;

trait SessionHandlerOptionsTrait{

	protected $filename_prefix = 'SESSION_';
	protected $session_name    = 'SESSIONID';
	protected $save_path;
	protected $db_table;
	protected $gc_maxlifetime = 3600;
	protected $hash_algo = 'sha512';
	protected $cookie_lifetime = 60*60*24;
	protected $cookie_path = '/';
	protected $use_encryption = false;
	protected $sessionCryptoKey;

}
