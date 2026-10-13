<?php

require_once "crypto_service.php";

/**
 * Contrôleur qui récupère la liste des cryptomonnaies.
 */
class Liste_crypto
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

        // Lecture des cryptomonnaies dans la base.
        $objCrypto->liste_crypto();

        // Transmission du résultat à liste_crypto.html.
        $this->resultat = $objCrypto->resultat;
        $this->VARS_HTML = $objCrypto->VARS_HTML;

        unset($objCrypto);
    }
}

?>