<?php
/**
 * Class SessionHandlerAbstract
 *
 * @filesource   SessionHandlerAbstract.php
 * @created      06.03.2017
 * @package      chillerlan\Session
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Session;

use SessionHandlerInterface;

abstract class SessionHandlerAbstract implements SessionHandlerInterface, SessionInterface{

	/**
	 * @var bool
	 */
	protected $started = false;

	/**
	 * @var \chillerlan\Session\SessionHandlerOptions
	 */
	protected $options;

	/**
	 * hex key
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * SessionHandlerAbstract constructor.
	 *
	 * @param \chillerlan\Session\SessionHandlerOptions $options
	 */
	public function __construct(SessionHandlerOptions $options = null){
		$this->set_options($options);

		session_set_save_handler($this, true);
	}

	/**
	 * @return void
	 */
	public function start(){
		$cookie_params = session_get_cookie_params();

		session_start();
		session_regenerate_id(true);

		setcookie(
			session_name(),
			session_id(),
			time()+$this->options->cookie_lifetime,
			$this->options->cookie_path,
			$cookie_params['domain']
		);
	}

	/**
	 * @return void
	 */
	public function end(){
		session_regenerate_id(true);
		setcookie(session_name(), '', 0, $this->options->cookie_path);
		session_unset();
		session_destroy();
		session_write_close();
	}

	/**
	 * @param string $data
	 *
	 * @return string
	 * @throws \chillerlan\Session\SessionHandlerException
	 */
	public function encrypt(string $data):string {
		$nonce = random_bytes(24);

		if($this->options->use_encryption && function_exists('sodium_crypto_secretbox')){
			return sodium_bin2hex($nonce.sodium_crypto_secretbox($data, $nonce, sodium_hex2bin($this->key)));
		}

		throw new SessionHandlerException('sodium not installed'); // @codeCoverageIgnore
	}

	/**
	 * @param string $box
	 *
	 * @return string
	 * @throws \chillerlan\Session\SessionHandlerException
	 */
	public function decrypt(string $box):string {

		if($this->options->use_encryption && function_exists('sodium_crypto_secretbox_open')){
			$box = sodium_hex2bin($box);

			return sodium_crypto_secretbox_open(substr($box, 24), substr($box, 0, 24), sodium_hex2bin($this->key));
		}

		throw new SessionHandlerException('sodium not installed'); // @codeCoverageIgnore
	}

	/**
	 * @param \chillerlan\Session\SessionHandlerOptions $options
	 *
	 * @return void
	 */
	protected function set_options(SessionHandlerOptions $options = null){
		$this->options = $options ?? new SessionHandlerOptions;

		if(!is_null($this->options->save_path)){
			$this->options->save_path .=
				!in_array(substr($this->options->save_path, -1), ['/', '\\'])
					? DIRECTORY_SEPARATOR
					: '';

			ini_set('session.save_path', $this->options->save_path);
		}

		// @todo http://php.net/manual/session.configuration.php
		ini_set('session.name', $this->options->session_name);

		ini_set('session.gc_maxlifetime', $this->options->gc_maxlifetime);
		ini_set('session.gc_probability', 1);
		ini_set('session.gc_divisor', 100);

		ini_set('session.use_strict_mode', true);
		ini_set('session.use_only_cookies', true);
		ini_set('session.cookie_secure', false);
		ini_set('session.cookie_httponly', true);
		ini_set('session.cookie_lifetime', 0);
#		ini_set('session.referer_check', '');

		if(PHP_VERSION_ID < 70100){
			ini_set('session.hash_bits_per_character', 6);

			if(in_array($this->options->hash_algo, hash_algos())){
				ini_set('session.hash_function', $this->options->hash_algo);
			}
		}
		else{
			ini_set('session.sid_bits_per_character', 6);
			ini_set('session.sid_length', 128);
		}

	}

}
