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

use chillerlan\Database\Connection;

class DBSessionHandler extends SessionHandlerAbstract{

	/**
	 * @var \chillerlan\Database\Connection
	 */
	protected $db;

	/**
	 * DBSessionHandler constructor.
	 *
	 * @param string                             $crypto_key
	 * @param \chillerlan\Session\HandlerOptions $options
	 * @param \chillerlan\Database\Connection    $db
	 */
	public function __construct(string $crypto_key, HandlerOptions $options = null, Connection $db){
		parent::__construct($crypto_key, $options);

		$this->db = $db;
		$this->db->connect();
	}

	/**
	 * Close the session
	 *
	 * @link  http://php.net/manual/en/sessionhandlerinterface.close.php
	 * @return bool <p>
	 * The return value (usually TRUE on success, FALSE on failure).
	 * Note this value is returned internally to PHP for processing.
	 * </p>
	 * @since 5.4.0
	 */
	public function close():bool{
		return true;
	}

	/**
	 * Destroy a session
	 *
	 * @link  http://php.net/manual/en/sessionhandlerinterface.destroy.php
	 *
	 * @param string $session_id The session ID being destroyed.
	 *
	 * @return bool <p>
	 * The return value (usually TRUE on success, FALSE on failure).
	 * Note this value is returned internally to PHP for processing.
	 * </p>
	 * @since 5.4.0
	 */
	public function destroy($session_id):bool{

		$this->db->delete
			->from($this->options->db_table)
			->where('id', $session_id)
			->execute();

		return true;
	}

	/**
	 * Cleanup old sessions
	 *
	 * @link  http://php.net/manual/en/sessionhandlerinterface.gc.php
	 *
	 * @param int $maxlifetime <p>
	 *                         Sessions that have not updated for
	 *                         the last maxlifetime seconds will be removed.
	 *                         </p>
	 *
	 * @return bool <p>
	 * The return value (usually TRUE on success, FALSE on failure).
	 * Note this value is returned internally to PHP for processing.
	 * </p>
	 * @since 5.4.0
	 */
	public function gc($maxlifetime):bool{

		$this->db->delete
			->from($this->options->db_table)
			->where('time', time() - $maxlifetime, '<')
			->execute();

		return true;
	}

	/**
	 * Initialize session
	 *
	 * @link  http://php.net/manual/en/sessionhandlerinterface.open.php
	 *
	 * @param string $save_path The path where to store/retrieve the session.
	 * @param string $name      The session name.
	 *
	 * @return bool <p>
	 * The return value (usually TRUE on success, FALSE on failure).
	 * Note this value is returned internally to PHP for processing.
	 * </p>
	 * @since 5.4.0
	 */
	public function open($save_path, $name):bool{
		return true;
	}

	/**
	 * Read session data
	 *
	 * @link  http://php.net/manual/en/sessionhandlerinterface.read.php
	 *
	 * @param string $session_id The session id to read data for.
	 *
	 * @return string <p>
	 * Returns an encoded string of the read data.
	 * If nothing was read, it must return an empty string.
	 * Note this value is returned internally to PHP for processing.
	 * </p>
	 * @throws \chillerlan\Session\SessionHandlerException
	 * @since 5.4.0
	 */
	public function read($session_id):string{

		$q = $this->db->select
			->cols(['data'])
			->from([$this->options->db_table])
			->where('id', $session_id)
			->execute();

		try{

			if(!$q || !isset($q[0])){
				return '';
			}

			return $this->decrypt($q[0]->data);
		}
		catch(\Exception $e){
			throw new SessionHandlerException($e->getMessage());
		}

	}

	/**
	 * Write session data
	 *
	 * @link  http://php.net/manual/en/sessionhandlerinterface.write.php
	 *
	 * @param string $session_id The session id.
	 * @param string $session_data <p>
	 *                             The encoded session data. This data is the
	 *                             result of the PHP internally encoding
	 *                             the $_SESSION superglobal to a serialized
	 *                             string and passing it as this parameter.
	 *                             Please note sessions use an alternative serialization method.
	 *                             </p>
	 *
	 * @return bool <p>
	 * The return value (usually TRUE on success, FALSE on failure).
	 * Note this value is returned internally to PHP for processing.
	 * </p>
	 * @since 5.4.0
	 */
	public function write($session_id, $session_data):bool{

		$sql = 'REPLACE INTO `'.$this->options->db_table.'` (`id`, `time`, `data`) VALUES (?,?,?)';

		$q = $this->db->prepared($sql, [
			$session_id,
			time(),
			$this->encrypt($session_data),
		]);

		return (bool)$q;
	}

}
