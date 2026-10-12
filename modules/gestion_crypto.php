<?php
require_once "crypto_service.php";
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
        $obj_crypto = new Crypto_service();
        $obj_crypto->liste_crypto();

        $this->resultat = $obj_crypto->resultat;
        $this->VARS_HTML = $obj_crypto->VARS_HTML;

        unset($obj_crypto);
    }
}

?>
