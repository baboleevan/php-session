<?php
/**
 * @filesource   create_db.php
 * @created      06.03.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

require_once __DIR__.'/../vendor/autoload.php';

use chillerlan\Database\{Database, DatabaseOptions, Drivers\MySQLiDrv};
use chillerlan\DotEnv\DotEnv;

(new DotEnv(__DIR__.'/../config', file_exists(__DIR__.'/../config/.env') ? '.env' : '.env_travis'))->load();

$db = new Database(new DatabaseOptions([
	'driver'       => MySQLiDrv::class,
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
	->query();

exit(true);
