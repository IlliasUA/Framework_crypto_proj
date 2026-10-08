<?php
/**
 * Class Index | file Index.php
 *
 * In this class, we show the interface "Index.html".
 * With this interface, we'll be able to add a new movie
 *
 * @package JiJou Project
 * @subpackage Index
 * @author @Afpa Lab Team
 * @copyright  1920-2080 The Afpa Lab Team Group Corporation World Company
 * @version v1.0
 */
class Index	{
	
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
	 * Login in
	 */
	function main()	{
	}
}

?>
