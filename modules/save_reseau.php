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

        // Retour à la page de gestion des réseaux.
        header("Location: gestion_reseaux");
        exit;
    }
}

?>