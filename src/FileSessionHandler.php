<?php
/**
 * Class FileSessionHandler
 *
 * @filesource   FileSessionHandler.php
 * @created      06.03.2017
 * @package      chillerlan\Session
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Session;

use chillerlan\Filereader\Drivers\FSDriverInterface;
use chillerlan\Traits\ContainerInterface;

class FileSessionHandler extends SessionHandlerAbstract{

	/**
	 * @var \chillerlan\Filereader\Drivers\FSDriverInterface
	 */
	protected $filereader;

	/**
	 * FileSessionHandler constructor.
	 *
	 * @param \chillerlan\Traits\ContainerInterface            $options
	 * @param \chillerlan\Filereader\Drivers\FSDriverInterface $FSdriver
	 */
	public function __construct(ContainerInterface $options = null, FSDriverInterface $FSdriver){
		parent::__construct($options);

		// use the internal DiskDriver if no other filereader was given
		$this->filereader = $FSdriver;
	}

	/** @inheritdoc */
	public function close():bool{
		return true;
	}

	/** @inheritdoc */
	public function destroy($session_id):bool{
		$file = $this->options->save_path.$this->options->filename_prefix.$session_id;

		if($this->filereader->fileExists($file)) {
			$this->filereader->deleteFile($file);
		}

		return true;
	}

	/** @inheritdoc */
	public function gc($maxlifetime):bool{
		$files = $this->filereader->findFiles($this->options->save_path.$this->options->filename_prefix.'*');

		if(is_array($files)){

			foreach($files as $file){

				if($this->filereader->fileModifyTime($file) + $maxlifetime < time() && $this->filereader->fileExists($file)){
					$this->filereader->deleteFile($file);
				}

			}

		}

		return true;
	}

	/** @inheritdoc */
	public function open($save_path, $name):bool{

		if(!$this->filereader->isWritable($save_path)){
			return false;
		}

		return true;
	}

	/** @inheritdoc */
	public function read($session_id):string{
		return $this->filereader->fileContents($this->options->save_path.$this->options->filename_prefix.$session_id);
	}

	/** @inheritdoc */
	public function write($session_id, $session_data):bool{
		return $this->filereader->write($this->options->save_path.$this->options->filename_prefix.$session_id, $session_data);
	}
}
