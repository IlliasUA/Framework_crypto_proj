<?php

/**
 * Contrôleur de la page de gestion des réseaux.
 */
class Gestion_reseaux
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
        // Les réseaux seront chargés avec AJAX.
    }
}

?>