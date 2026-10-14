<?php
/**
 * Class Gestion_crypto | file Gestion_crypto.php
 *
 * In this class, we show the interface "Gestion_crypto.html".
 * With this interface, we'll be able to add, edit, list, delete examples
 *
 * @package JiJou Project
 * @subpackage configuration
 * @author @Afpa Lab Team
 * @copyright  1920-2080 The Afpa Lab Team Group Corporation World Company
 * @version v1.0
 */


class Gestion_crypto
{
    public $resultat;
    public $VARS_HTML;

    public function __construct()
    {
        $this->resultat = [];
        $this->main();
    }

    public function main()
    {
        // Les données sont chargées avec AJAX.
    }
}

?>
