<?php
    require_once "initialize.php";

    class Crypto_service extends Initialize {
    /**
         * public $resultat is used to store all datas needed for HTML Templates
         * @var array
         */
        public $resultat;

        /**
         * Call the parent constructor
         *
         * init variables resultat
         */
        public function __construct()	{
            // Call Parent Constructor
            parent::__construct();

            // init variables resultat
            $this->resultat= [];
        }

        /**
         * Call the parent destructor
         */
        public function __destruct()	{
            // Call Parent destructor
            parent::__destruct();
        }

        /**
         * Method save_film()
         *
         * Insert a new movie in database
         */

        public function save_crypto()	{
            // Here I can Access to :
            // $this->GLOBALS_INI
            // $this->oBdd
            // $this->VARS_HTML
            $spathSQL= $this->GLOBALS_INI["PATH_HOME"] . $this->GLOBALS_INI["PATH_SQL"] . "insert_cryptomonnaie.sql";
            $this->resultat["save_crypto"]= $this->oBdd->treatDatas($spathSQL, array(
                "id_niveau_risque" => $this->VARS_HTML["id_niveau_risque"],
                "id_reseau"        => $this->VARS_HTML["id_reseau"],
                "nom_crypto"       => $this->VARS_HTML["nom_crypto"],
                "symbole_crypto"   => $this->VARS_HTML["symbole_crypto"],
                "prix"             => $this->VARS_HTML["prix"],
                "qntte_jetons"     => $this->VARS_HTML["qntte_jetons"],
                "date_achat"       => $this->VARS_HTML["date_achat"],
                "note"             => $this->VARS_HTML["note"]
            ));
            $this->resultat["id_crypto_created"]= $this->oBdd->getLastInsertId();
        }
        /**
         * Récupère toutes les cryptomonnaies.
         */
        public function liste_crypto()
        {
            $spathSQL = $this->GLOBALS_INI["PATH_HOME"]
                    . $this->GLOBALS_INI["PATH_SQL"]
                    . "select_cryptomonnaie.sql";

            $this->resultat["liste_crypto"] =
                $this->oBdd->getSelectDatas($spathSQL, array());
        }
    }  
?>