<?php
/**
 * Class DBSessionHandler
 *
 * @filesource   DBSessionHandler.php
 * @created      06.03.2017
 * @package      chillerlan\Session
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Session;

use chillerlan\Database\Database;
use chillerlan\Traits\ContainerInterface;

class DBSessionHandler extends SessionHandlerAbstract{

	/**
	 * @var \chillerlan\Database\Database
	 */
	protected $db;

	/**
	 * DBSessionHandler constructor.
	 *
	 * @param \chillerlan\Traits\ContainerInterface $options
	 * @param \chillerlan\Database\Database         $db
	 */
	public function __construct(ContainerInterface $options = null, Database $db){
		parent::__construct($options);

		$this->db = $db->connect();
	}

	/** @inheritdoc */
	public function close():bool{
		return true;
	}

	/** @inheritdoc */
	public function destroy($session_id):bool{

		$this->db->delete
			->from($this->options->db_table)
			->where('id', $session_id)
			->query();

		return true;
	}

	/** @inheritdoc */
	public function gc($maxlifetime):bool{

		$this->db->delete
			->from($this->options->db_table)
			->where('time', time() - $maxlifetime, '<')
			->query();

		return true;
	}

	/** @inheritdoc */
	public function open($save_path, $name):bool{
		return true;
	}

	/** @inheritdoc */
	public function read($session_id):string{

		$q = $this->db->select
			->cols(['data'])
			->from([$this->options->db_table])
			->where('id', $session_id)
			->query();

		try{

			if(!$q || !isset($q[0])){
				return '';
			}

			return $this->options->use_encryption ? $this->decrypt($q[0]->data) : $q[0]->data;
		}
		catch(\Exception $e){
			throw new SessionHandlerException($e->getMessage());
		}

	}

	/** @inheritdoc */
	public function write($session_id, $session_data):bool{

		$q = $this->db->insert
			->into($this->options->db_table, 'REPLACE', 'id')
			->values([
				'id'   => $session_id,
				'time' => time(),
				'data' => $this->options->use_encryption ? $this->encrypt($session_data) : $session_data,
			])
			->query();

		return (bool)$q;
	}

}
