<?php

require_once "crypto_service.php";

/**
 * Contrôleur qui récupère la liste des cryptomonnaies.
 */
class Liste_reseaux
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
        $obj = new Crypto_service();

        // Lecture des cryptomonnaies dans la base.
        $obj->liste_reseaux();

        // Transmission du résultat à liste_crypto.html.
        $this->resultat = $obj->resultat;
        $this->VARS_HTML = $obj->VARS_HTML;

        unset($obj);
    }
}

?>