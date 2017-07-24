<?php
/**
 * @filesource   create_key.php
 * @created      06.03.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

require_once __DIR__.'/../vendor/autoload.php';

if(file_put_contents(__DIR__.'/../config/.key', \Defuse\Crypto\Key::createNewRandomKey()->saveToAsciiSafeString()) === 136){
	echo 'crypto key written';
}
else{
	echo 'error writing crypto key';
}
