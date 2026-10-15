<?php

require_once "crypto_service.php";

/**
 * Contrôleur d'ajout d'une notification.
 */
class Save_notification
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

        // Ajout de la notification dans la base.
        $objCrypto->save_notification();

        $this->resultat = $objCrypto->resultat;
        $this->VARS_HTML = $objCrypto->VARS_HTML;

        unset($objCrypto);

        if (
            !isset($this->VARS_HTML["bJSON"])
            || $this->VARS_HTML["bJSON"] != 1
        ) {
            header("Location: gestion_notifications");
            exit;
        }
    }
}

?>
