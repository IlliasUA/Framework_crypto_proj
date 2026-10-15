<?php

require_once "crypto_service.php";

/**
 * Contrôleur de modification d'un réseau.
 */
class Update_reseau
{
    public $resultat;
    public $VARS_HTML;

    public function __construct()
    {
        $this->resultat = array();
        $this->VARS_HTML = array();

        $this->main();
    }

    public function main()
    {
        $objCrypto = new Crypto_service();

        $objCrypto->update_reseau();

        $this->resultat = $objCrypto->resultat;
        $this->VARS_HTML = $objCrypto->VARS_HTML;

        unset($objCrypto);

        /*
         * Sans bJSON, retour classique vers la page.
         * Avec bJSON, route.php chargera la réponse JSON.
         */
        if (
            !isset($this->VARS_HTML["bJSON"])
            || $this->VARS_HTML["bJSON"] != 1
        ) {
            header("Location: gestion_reseaux");
            exit;
        }
    }
}

?>