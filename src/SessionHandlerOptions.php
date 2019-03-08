<?php
/**
 * Class SessionHandlerOptions
 *
 * @filesource   SessionHandlerOptions.php
 * @created      06.03.2017
 * @package      chillerlan\Session
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\Session;

use chillerlan\Settings\SettingsContainerAbstract;

/**
 * @property string $filename_prefix
 * @property string $session_name
 * @property string $save_path
 * @property string $db_table
 * @property int    $gc_maxlifetime
 * @property string $hash_algo
 * @property int    $cookie_lifetime
 * @property string $cookie_path
 * @property bool   $use_encryption
 * @property string $sessionCryptoKey
 */
class SessionHandlerOptions extends SettingsContainerAbstract{
	use SessionHandlerOptionsTrait;
}
