<?php
/**
 * Class DatabaseHandlerTest
 *
 * @filesource   DatabaseHandlerTest.php
 * @created      06.03.2017
 * @package      chillerlan\SessionTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SessionTest;

use chillerlan\Database\Drivers\Native\MySQLiDriver;
use chillerlan\Database\Options as DBOptions;
use chillerlan\Database\Connection;
use chillerlan\Database\Query\Dialects\MySQLQueryBuilder;
use chillerlan\Session\DBSessionHandler;
use chillerlan\Session\SessionHandlerOptions;
use chillerlan\Traits\DotEnv;

class DatabaseHandlerTest extends HandlerTestAbstract{

	protected function setUp(){
		$env = (new DotEnv(__DIR__.'/../config'))->load();

		$this->session = new DBSessionHandler(
			new SessionHandlerOptions([
				'db_table'   => 'sessions',
			]),
			new Connection(new DBOptions([
				'driver'       => MySQLiDriver::class,
				'querybuilder' => MySQLQueryBuilder::class,
				'host'     => $env->get('DB_HOST'),
				'port'     => $env->get('DB_PORT'),
				'database' => $env->get('DB_DATABASE'),
				'username' => $env->get('DB_USERNAME'),
				'password' => $env->get('DB_PASSWORD'),
			]))
		);

	}

}
