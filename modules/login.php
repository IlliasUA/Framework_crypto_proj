<?php
/**
 * Class Login | file Login.php
 *
 * In this class, we show the interface "Login.html".
 * With this interface, we'll be able to add a new movie
 *
 * @package JiJou Project
 * @subpackage Login
 * @author @Afpa Lab Team
 * @copyright  1920-2080 The Afpa Lab Team Group Corporation World Company
 * @version v1.0
 */
class Login	{
	
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
	 * Get interface to Login
	 */
	function main()	{
	}
}

?>
