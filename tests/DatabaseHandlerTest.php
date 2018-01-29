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

use chillerlan\Database\Database;
use chillerlan\Session\DBSessionHandler;

class DatabaseHandlerTest extends HandlerTestAbstract{

	protected function setUp(){
		parent::setUp();

		$this->session = new DBSessionHandler($this->options, new Database($this->options));
	}

}
