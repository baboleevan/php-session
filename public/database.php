<?php
/**
 * @filesource   database.php
 * @created      06.03.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

require_once __DIR__.'/../vendor/autoload.php';

use chillerlan\Database\Database;
use chillerlan\Database\DatabaseOptionsTrait;
use chillerlan\Database\Drivers\MySQLiDrv;
use chillerlan\Session\DBSessionHandler;
use chillerlan\Session\SessionHandlerOptionsTrait;
use chillerlan\Traits\ContainerAbstract;
use chillerlan\Traits\DotEnv;

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


$options = new class($options) extends ContainerAbstract{
	use DatabaseOptionsTrait, SessionHandlerOptionsTrait;
};

$session = new DBSessionHandler($options, new Database($options));

$session->start();

$_SESSION['foo'] = 'whatever';

var_dump($_SESSION);
var_dump(session_id());

$session->end();

var_dump($_SESSION);

exit;
