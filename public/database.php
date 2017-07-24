<?php
/**
 * @filesource   database.php
 * @created      06.03.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

require_once __DIR__.'/../vendor/autoload.php';

use chillerlan\Database\Drivers\Native\MySQLiDriver;
use chillerlan\Database\Options as DBOptions;
use chillerlan\Database\Connection;
use chillerlan\Database\Query\Dialects\MySQLQueryBuilder;
use chillerlan\Session\DBSessionHandler;
use chillerlan\Session\SessionHandlerOptions;
use Dotenv\Dotenv;

(new Dotenv(__DIR__.'/../config'))->load();

$session = new DBSessionHandler(
	__DIR__.'/../config/.key',
	new SessionHandlerOptions([
		'crypto_key' => __DIR__.'/../config/.key',
		'db_table'   => 'sessions',
	]),
	new Connection(new DBOptions([
		'driver'       => MySQLiDriver::class,
		'querybuilder' => MySQLQueryBuilder::class,
		'host'         => getenv('DB_HOST'),
		'port'         => getenv('DB_PORT'),
		'database'     => getenv('DB_DATABASE'),
		'username'     => getenv('DB_USERNAME'),
		'password'     => getenv('DB_PASSWORD'),
	]))
);


$session->start();

$_SESSION['foo'] = 'whatever';

var_dump($_SESSION);
var_dump(session_id());

$session->end();

var_dump($_SESSION);

exit;
