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

use chillerlan\Database\DatabaseOptionsTrait;
use chillerlan\Database\Drivers\MySQLiDrv;
use chillerlan\DotEnv\DotEnv;
use chillerlan\Session\SessionHandlerOptionsTrait;
use chillerlan\Session\SessionInterface;
use chillerlan\Settings\SettingsContainerAbstract;
use PHPUnit\Framework\TestCase;
use SessionHandlerInterface;

abstract class HandlerTestAbstract extends TestCase{

	/**
	 * @var \chillerlan\Session\SessionInterface
	 */
	protected $session;

	/**
	 * @var \chillerlan\Settings\SettingsContainerInterface
	 */
	protected $options;

	protected function setUp():void{
		$env = (new DotEnv(__DIR__.'/../config', file_exists(__DIR__.'/../config/.env') ? '.env' : '.env_travis'))->load();

		$options = [
			// SessionHandlerOptions
			'db_table'         => 'sessions',
			'sessionCryptoKey' => '000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f',
			// DatabaseOptions
			'driver'       => MySQLiDrv::class,
			'host'     => $env->get('DB_HOST'),
			'port'     => $env->get('DB_PORT'),
			'database' => $env->get('DB_DATABASE'),
			'username' => $env->get('DB_USERNAME'),
			'password' => $env->get('DB_PASSWORD'),
		];


		$this->options = new class($options) extends SettingsContainerAbstract{
			use DatabaseOptionsTrait, SessionHandlerOptionsTrait;
		};

	}
	/**
	 * @runInSeparateProcess
	 */
	public function testInstance(){
		$this->assertInstanceOf(SessionInterface::class, $this->session);
		$this->assertInstanceOf(SessionHandlerInterface::class, $this->session);
	}
}
