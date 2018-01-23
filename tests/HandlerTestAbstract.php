<?php
/**
 * Class HandlerTestAbstract
 *
 * @filesource   HandlerTestAbstract.php
 * @created      06.03.2017
 * @package      chillerlan\SessionTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SessionTest;

use chillerlan\Session\SessionInterface;
use PHPUnit\Framework\TestCase;
use SessionHandlerInterface;

abstract class HandlerTestAbstract extends TestCase{

	/**
	 * @var \chillerlan\Session\SessionInterface
	 */
	protected $session;

	/**
	 * @runInSeparateProcess
	 */
	public function testInstance(){
		$this->assertInstanceOf(SessionInterface::class, $this->session);
		$this->assertInstanceOf(SessionHandlerInterface::class, $this->session);
	}
}
