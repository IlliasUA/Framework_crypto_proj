<?php
/**
 * Class Gestion_exemple | file Gestion_exemple.php
 *
 * In this class, we show the interface "Gestion_exemple.html".
 * With this interface, we'll be able to add, edit, list, delete examples
 *
 * @package JiJou Project
 * @subpackage configuration
 * @author @Afpa Lab Team
 * @copyright  1920-2080 The Afpa Lab Team Group Corporation World Company
 * @version v1.0
 */
class Gestion_exemple	{
	
	/**
	 * public $resultat is used to store all datas needed for HTML Templates
	 * @var array
	 */
	public $resultat;

	/**
	 * init variables resultat
	 *
	 * execute main function
	 */
	public function __construct()	{
		// init variables resultat
		$this->resultat= [];

		// execute main function
		$this->main();
	}

	/**
	 * Get interface to gestion of movies
	 */
	function main()	{
	}
}

?>
