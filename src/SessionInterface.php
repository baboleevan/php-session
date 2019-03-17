<?php
/**
 * Interface SessionInterface
 *
 * @filesource   SessionInterface.php
 * @created      24.07.2017
 * @package      chillerlan\Session
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Session;

use SessionHandlerInterface;

interface SessionInterface extends SessionHandlerInterface{

	const SESSION_NONCE = "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x02";

	/**
	 * @return \chillerlan\Session\SessionInterface
	 */
	public function start():SessionInterface;

	/**
	 * @return \chillerlan\Session\SessionInterface
	 */
	public function end():SessionInterface;

	/**
	 * @return bool
	 */
	public function active():bool;

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function get(string $name);

	/**
	 * @param string $name
	 * @param        $value
	 *
	 * @return \chillerlan\Session\SessionInterface
	 */
	public function set(string $name, $value):SessionInterface;

	/**
	 * @param string $name
	 *
	 * @return \chillerlan\Session\SessionInterface
	 */
	public function unset(string $name):SessionInterface;

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function isset(string $name):bool;

	/**
	 * @param string|null $newID
	 *
	 * @return string
	 */
	public function id(string $newID = null):string;
}
