<?php

/**
 * Contrôleur de la page de gestion des niveaux de risque.
 */
class Gestion_risques
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
        // Les niveaux de risque seront chargés avec AJAX.
    }
}

?>