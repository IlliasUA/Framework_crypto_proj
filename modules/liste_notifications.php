<?php

require_once "crypto_service.php";

/**
 * Contrôleur qui récupère les notifications.
 */
class Liste_notifications
{
    public $resultat;
    public $VARS_HTML;

    public function __construct()
    {
        $this->resultat = array();
        $this->main();
    }

    public function main()
    {
        $objCrypto = new Crypto_service();

        // Lecture des notifications dans la base.
        $objCrypto->liste_notifications();

        // Transmission du résultat à liste_notifications.html.
        $this->resultat = $objCrypto->resultat;
        $this->VARS_HTML = $objCrypto->VARS_HTML;

        unset($objCrypto);
    }
}

?>