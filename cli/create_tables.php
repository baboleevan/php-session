<?php
/**
 * @filesource   create_db.php
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
use Dotenv\Dotenv;

(new Dotenv(__DIR__.'/../config'))->load();

$db = new Connection(new DBOptions([
	'driver'       => MySQLiDriver::class,
	'querybuilder' => MySQLQueryBuilder::class,
	'host'     => getenv('DB_HOST'),
	'port'     => getenv('DB_PORT'),
	'database' => getenv('DB_DATABASE'),
	'username' => getenv('DB_USERNAME'),
	'password' => getenv('DB_PASSWORD'),
]));

$db->connect();

$db->create
	->table('sessions')
	->primaryKey('id')
	->varchar('id', 128, null, false)
	->int('time', 10, null, true, 'UNSIGNED')
	->text('data')
	->execute();
