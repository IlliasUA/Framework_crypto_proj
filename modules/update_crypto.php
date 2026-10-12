<?php

require_once "crypto_service.php";

class Update_crypto
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
        $objCrypto = new Crypto_service();
        $objCrypto->update_crypto();

        $this->resultat = $objCrypto->resultat;
        $this->VARS_HTML = $objCrypto->VARS_HTML;

        header("Location: gestion_crypto");
        exit;
    }
}