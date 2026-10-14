<?php

require_once "crypto_service.php";

/**
 * Contrôleur qui récupère les niveaux de risque.
 */
class Liste_niveaux_risque
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

        // Lecture des niveaux de risque dans la base.
        $objCrypto->liste_niveaux_risque();

        // Transmission du résultat à liste_niveaux_risque.html.
        $this->resultat = $objCrypto->resultat;
        $this->VARS_HTML = $objCrypto->VARS_HTML;

        unset($objCrypto);
    }
}

?>