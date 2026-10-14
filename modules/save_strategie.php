<?php

require_once "crypto_service.php";

/**
 * Contrôleur d'ajout d'une stratégie.
 */
class Save_strategie
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

        // Ajout de la stratégie dans la base.
        $objCrypto->save_strategie();

        $this->resultat = $objCrypto->resultat;
        $this->VARS_HTML = $objCrypto->VARS_HTML;

        unset($objCrypto);

        // Retour à la page de gestion des stratégies.
        header("Location: gestion_strategies");
        exit;
    }
}

?>