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

use chillerlan\Logger\Output\NullLogger;
use chillerlan\Settings\SettingsContainerInterface;
use Psr\Log\{LoggerAwareInterface, LoggerAwareTrait, LoggerInterface};

abstract class SessionHandlerAbstract implements SessionInterface, LoggerAwareInterface{
	use LoggerAwareTrait;

	/**
	 * @var bool
	 */
	protected $started = false;

	/**
	 * @var \chillerlan\Session\SessionHandlerOptions
	 */
	protected $options;

	/**
	 * SessionHandlerAbstract constructor.
	 *
	 * @param \chillerlan\Settings\SettingsContainerInterface $options
	 * @param \Psr\Log\LoggerInterface|null                  $logger
	 */
	public function __construct(SettingsContainerInterface $options = null, LoggerInterface $logger = null){
		$this->options = $options ?? new SessionHandlerOptions;
		$this->logger  = $logger ?? new NullLogger;

		$this->setOptions($options);

		session_set_save_handler($this, true);
	}

	/** @inheritdoc */
	public function start():SessionInterface{
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

		return $this;
	}

	/** @inheritdoc */
	public function end():SessionInterface{

		if(session_status() === PHP_SESSION_ACTIVE){
			session_regenerate_id(true);
			setcookie(session_name(), '', 0, $this->options->cookie_path);
			session_unset();
			session_destroy();
			session_write_close();
		}

		return $this;
	}

	/** @inheritdoc */
	public function active():bool{
		return session_status() === PHP_SESSION_ACTIVE;
	}

	/** @inheritdoc */
	public function get(string $name){
		return $_SESSION[$name] ?? null;
	}

	/** @inheritdoc */
	public function set(string $name, $value):SessionInterface{
		$_SESSION[$name] = $value;

		return $this;
	}

	/** @inheritdoc */
	public function unset(string $name):SessionInterface{
		unset($_SESSION[$name]);

		return $this;
	}

	/** @inheritdoc */
	public function isset(string $name):bool{
		return isset($_SESSION[$name]);
	}

	/**
	 * @param string $data
	 *
	 * @return string
	 * @throws \chillerlan\Session\SessionHandlerException
	 */
	protected function encrypt(string &$data):string {

		if(function_exists('sodium_crypto_secretbox')){
			$box = sodium_crypto_secretbox($data, $this::SESSION_NONCE, sodium_hex2bin($this->options->sessionCryptoKey));

			sodium_memzero($data);

			return sodium_bin2hex($box);
		}

		throw new SessionHandlerException('sodium not installed'); // @codeCoverageIgnore
	}

	/**
	 * @param string $box
	 *
	 * @return string
	 * @throws \chillerlan\Session\SessionHandlerException
	 */
	protected function decrypt(string $box):string {

		if(function_exists('sodium_crypto_secretbox_open')){
			return sodium_crypto_secretbox_open(sodium_hex2bin($box), $this::SESSION_NONCE, sodium_hex2bin($this->options->sessionCryptoKey));
		}

		throw new SessionHandlerException('sodium not installed'); // @codeCoverageIgnore
	}

	/**
	 * @param \chillerlan\Settings\SettingsContainerInterface $options
	 *
	 * @return \chillerlan\Session\SessionInterface
	 */
	public function setOptions(SettingsContainerInterface $options):SessionInterface{

		// end an active session before setting new options
		if($this->active()){
			$this->end();
		}

		if(is_writable($options->save_path)){
			ini_set('session.save_path', $options->save_path);
		}

		// @todo http://php.net/manual/session.configuration.php
		ini_set('session.name', $options->session_name);

		ini_set('session.gc_maxlifetime', $options->gc_maxlifetime);
		ini_set('session.gc_probability', '1');
		ini_set('session.gc_divisor', '100');

		ini_set('session.use_strict_mode', 'true');
		ini_set('session.use_only_cookies', 'true');
		ini_set('session.cookie_secure', 'false'); // @todo
		ini_set('session.cookie_httponly', 'true');
		ini_set('session.cookie_lifetime', '0');
#		ini_set('session.referer_check', '');

		ini_set('session.sid_bits_per_character', '6');
		ini_set('session.sid_length', '128');

		return $this;
	}

}
