<?php

require_once "crypto_service.php";

class Supprime_crypto
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
        $objCrypto->supprime_crypto();

        $this->resultat = $objCrypto->resultat;
        $this->VARS_HTML = $objCrypto->VARS_HTML;

        header("Location: gestion_crypto");
        exit;
    }
}