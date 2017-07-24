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

interface SessionInterface{

	/**
	 * @return void
	 */
	public function start();

	/**
	 * @return void
	 */
	public function end();

}
