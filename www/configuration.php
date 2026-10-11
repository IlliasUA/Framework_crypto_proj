<?php
/**
 * Class Configuration | file configuration.php
 *
 * In this class, we are getting the config file configuration_dev.ini. 
 * If not found, we are getting the config file configuration_prod.ini.
 *
 * @package JiJou Project
 * @subpackage configuration
 * @author @Afpa Lab Team
 * @copyright  1920-2080 The Afpa Lab Team Group Corporation World Company
 * @version v1.0
 */
Class Configuration	{
	/**
	 * The function getGlobalsINI() is static, so we can access to it from the router, and from initialize.
	 * It returns the content of the file configuration.
	 */
	public static function getGlobalsINI() {
		$directory = __DIR__;
		do {
			// Remonter d'un seul niveau, meme si des dossiers portent le meme nom.
			$directory = dirname($directory);
			$configDirectory = $directory . "/files/";
			if (is_file($configDirectory . "configuration_dev.ini")) {
				return parse_ini_file($configDirectory . "configuration_dev.ini", false);
			} else if (is_file($configDirectory . "configuration_prod.ini")) {
				return parse_ini_file($configDirectory . "configuration_prod.ini", false);
			}
		} while (dirname($directory) !== $directory);
	}
}

?>

