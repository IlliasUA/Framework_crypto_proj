<?php

require_once "crypto_service.php";

/**
 * Contrôleur qui récupère les stratégies.
 */
class Liste_strategies
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

        // Lecture des stratégies dans la base.
        $objCrypto->liste_strategies();

        // Transmission du résultat à la vue JSON.
        $this->resultat = $objCrypto->resultat;
        $this->VARS_HTML = $objCrypto->VARS_HTML;

        unset($objCrypto);
    }
}

?>