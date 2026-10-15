<?php

require_once "crypto_service.php";

/**
 * Contrôleur d'ajout d'un réseau.
 */
class Save_reseau
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

        // Ajout du réseau dans la base.
        $objCrypto->save_reseau();

        $this->resultat = $objCrypto->resultat;
        $this->VARS_HTML = $objCrypto->VARS_HTML;

        unset($objCrypto);

        /*
        * Lors d'un appel classique du formulaire,
        * retour vers la page de gestion.
        *
        * Lors d'un appel AJAX avec bJSON = 1,
        * route.php chargera la vue JSON.
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