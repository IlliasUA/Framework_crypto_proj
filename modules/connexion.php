<?php
/**
 * Class Connexion | file connexion.php
 *
 * In this class, we show the interface "connexion.html".
 * With this interface, we'll be able to connect a user to the application
 *
 * @package JiJou Project
 * @subpackage Connexion
 * @author @Afpa Lab Team
 * @copyright  1920-2080 The Afpa Lab Team Group Corporation World Company
 * @version v1.0
 */
class Connexion	{
	
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
	 * Get interface to Connexion
	 */
	function main()	{
		$_SESSION["id_utilisateur"]= 1;
		header('location: gestion_exemple');
		exit;
	}
}

?>
