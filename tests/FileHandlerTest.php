<?php
/**
 * Class FileHandlerTest
 *
 * @filesource   FileHandlerTest.php
 * @created      06.03.2017
 * @package      chillerlan\SessionTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SessionTest;

use chillerlan\Session\FileSessionHandler;
use chillerlan\Filereader\Drivers\DiskDriver;

class FileHandlerTest extends HandlerTestAbstract{

	protected function setUp(){
		$this->session = new FileSessionHandler(new DiskDriver);
	}

}
