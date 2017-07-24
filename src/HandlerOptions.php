<?php
/**
 * Class HandlerOptions
 *
 * @filesource   HandlerOptions.php
 * @created      06.03.2017
 * @package      chillerlan\Session
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Session;

/**
 * @property string $filename_prefix
 * @property string $session_name
 * @property string $save_path
 * @property string $db_table
 * @property int    $gc_maxlifetime
 * @property string $hash_algo
 * @property int    $cookie_lifetime
 * @property string $cookie_path
 */
class HandlerOptions{

	protected $filename_prefix = 'SESSION_';
	protected $session_name    = 'SESSIONID';
	protected $save_path;
	protected $db_table;
	protected $gc_maxlifetime = 3600;
	protected $hash_algo = 'sha512';
	protected $cookie_lifetime = 60*60*24;
	protected $cookie_path = '/';

	/**
	 * Boa constructor.
	 *
	 * @param array $properties
	 */
	public function __construct(array $properties = []){

		foreach($properties as $key => $value){
			$this->__set($key, $value);
		}

	}

	/**
	 * David Getter
	 *
	 * @param string $property
	 *
	 * @return mixed
	 */
	public function __get(string $property){

		if(property_exists($this, $property)){
			return $this->{$property};
		}

		return false;
	}

	/**
	 * Jet-setter
	 *
	 * @param string $property
	 * @param mixed  $value
	 *
	 * @return void
	 */
	public function __set(string $property, $value){

		if(property_exists($this, $property)){
			$this->{$property} = $value;
		}

	}

}
