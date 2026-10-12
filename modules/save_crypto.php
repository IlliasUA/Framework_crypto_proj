<?php

require_once "crypto_service.php";

class Save_crypto
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
        $obj_crypto->save_crypto();

        $this->resultat = $obj_crypto->resultat;
        $this->VARS_HTML = $obj_crypto->VARS_HTML;

        header("Location: gestion_crypto");
        exit;
    }
}