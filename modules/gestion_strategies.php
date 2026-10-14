<?php

/**
 * Contrôleur de la page de gestion des stratégies.
 */
class Gestion_strategies
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
        // Les stratégies seront chargées avec AJAX.
    }
}

?>