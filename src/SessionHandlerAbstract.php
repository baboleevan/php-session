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

use Defuse\Crypto\{Crypto, Key};
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
	 * @var \Defuse\Crypto\Key
	 */
	protected $key;

	/**
	 * SessionHandlerAbstract constructor.
	 *
	 * @param string                                    $crypto_key
	 * @param \chillerlan\Session\SessionHandlerOptions $options
	 *
	 * @throws \chillerlan\Session\SessionHandlerException
	 */
	public function __construct(string $crypto_key, SessionHandlerOptions $options = null){

		if(!is_file($crypto_key)){
			throw new SessionHandlerException('invalid crypto key file');
		}

		$this->key = Key::loadFromAsciiSafeString(file_get_contents($crypto_key));

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
	 */
	protected function encrypt(string $data):string{
		return Crypto::encrypt($data, $this->key);
	}

	/**
	 * @param string $encrypted_data
	 *
	 * @return string
	 */
	protected function decrypt(string $encrypted_data):string{
		return Crypto::decrypt($encrypted_data, $this->key);
	}

	/**
	 * @param \chillerlan\Session\SessionHandlerOptions $options
	 *
	 * @return void
	 */
	protected function set_options(SessionHandlerOptions $options = null){
		$this->options = $options ?: new SessionHandlerOptions;

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
