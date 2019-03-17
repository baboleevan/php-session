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
use chillerlan\Settings\SettingsContainerInterface;
use Psr\Log\LoggerInterface;

class DBSessionHandler extends SessionHandlerAbstract{

	/**
	 * @var \chillerlan\Database\Database
	 */
	protected $db;

	/**
	 * DBSessionHandler constructor.
	 *
	 * @param \chillerlan\Database\Database                  $db
	 * @param \chillerlan\Settings\SettingsContainerInterface $options
	 * @param \Psr\Log\LoggerInterface|null                  $logger
	 */
	public function __construct(Database $db, SettingsContainerInterface $options = null, LoggerInterface $logger = null){
		parent::__construct($options, $logger);

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
			->where('time', \time() - $maxlifetime, '<')
			->query();

		return true;
	}

	/** @inheritdoc */
	public function open($save_path, $name):bool{
		return true;
	}

	/**
	 * @param string $session_id
	 *
	 * @return string
	 * @throws \chillerlan\Session\SessionHandlerException
	 */
	public function read($session_id):string{

		if(empty($session_id)){
			throw new SessionHandlerException('invalid session id');
		}

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

	/**
	 * @inheritdoc
	 * @throws \chillerlan\Session\SessionHandlerException
	 */
	public function write($session_id, $session_data):bool{

		if(empty($session_id)){
			throw new SessionHandlerException('invalid session id');
		}

		$q = $this->db->insert
			->into($this->options->db_table, 'REPLACE', 'id')
			->values([
				'id'   => $session_id,
				'time' => \time(),
				'data' => $this->options->use_encryption ? $this->encrypt($session_data) : $session_data,
			])
			->query();

		return (bool)$q;
	}

}
