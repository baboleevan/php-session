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
use chillerlan\Traits\DotEnv;

$env = (new DotEnv(__DIR__.'/../config'))->load();

$session = new DBSessionHandler(
	new SessionHandlerOptions([
		'crypto_key' => __DIR__.'/../config/.key',
		'db_table'   => 'sessions',
	]),
	new Connection(new DBOptions([
		'driver'       => MySQLiDriver::class,
		'querybuilder' => MySQLQueryBuilder::class,
		'host'         => $env->get('DB_HOST'),
		'port'         => $env->get('DB_PORT'),
		'database'     => $env->get('DB_DATABASE'),
		'username'     => $env->get('DB_USERNAME'),
		'password'     => $env->get('DB_PASSWORD'),
	]))
);


$session->start();

$_SESSION['foo'] = 'whatever';

var_dump($_SESSION);
var_dump(session_id());

$session->end();

var_dump($_SESSION);

exit;
