<?php

require_once "crypto_service.php";

/**
 * Contrôleur d'ajout d'un niveau de risque.
 */
class Save_risque
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

        // Ajout du niveau de risque dans la base.
        $objCrypto->save_risque();

        $this->resultat = $objCrypto->resultat;
        $this->VARS_HTML = $objCrypto->VARS_HTML;

        unset($objCrypto);

        // Retour à la page de gestion des risques.
        header("Location: gestion_risques");
        exit;
    }
}

?>