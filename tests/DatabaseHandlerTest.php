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

	protected function setUp():void{
		parent::setUp();

		$this->session = new DBSessionHandler(new Database($this->options), $this->options);
	}

}
