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
 * Charge les données nécessaires pour construire le formulaire.
 */
        public function liste_referentiels() {
            /*
            * Liste des réseaux.
            */
            $spathSQL = $this->GLOBALS_INI["PATH_HOME"]
                    . $this->GLOBALS_INI["PATH_SQL"]
                    . "select_reseaux.sql";

            $this->resultat["reseaux"] = $this->oBdd->getSelectDatas(
                $spathSQL,
                array()
            );

            /*
            * Liste des niveaux de risque.
            */
            $spathSQL = $this->GLOBALS_INI["PATH_HOME"]
                    . $this->GLOBALS_INI["PATH_SQL"]
                    . "select_niveaux_risque.sql";

            $this->resultat["niveaux_risque"] = $this->oBdd->getSelectDatas(
                $spathSQL,
                array()
            );

            /*
            * Liste des stratégies.
            */
            $spathSQL = $this->GLOBALS_INI["PATH_HOME"]
                    . $this->GLOBALS_INI["PATH_SQL"]
                    . "select_strategies.sql";

            $this->resultat["strategies"] = $this->oBdd->getSelectDatas(
                $spathSQL,
                array()
            );

            /*
            * Liste des notifications.
            */
            $spathSQL = $this->GLOBALS_INI["PATH_HOME"]
                    . $this->GLOBALS_INI["PATH_SQL"]
                    . "select_notifications.sql";

            $this->resultat["notifications"] = $this->oBdd->getSelectDatas(
                $spathSQL,
                array()
            );
        }

        /**
         * Method save_film()
         *
         * Insert a new movie in database
         */

        public function save_crypto() {
            // Chemin vers la requête d'insertion de la cryptomonnaie.
            $spathSQL = $this->GLOBALS_INI["PATH_HOME"]
                    . $this->GLOBALS_INI["PATH_SQL"]
                    . "insert_cryptomonnaie.sql";

            // Enregistrement des informations principales.
            $this->resultat["save_crypto"] = $this->oBdd->treatDatas(
                $spathSQL,
                array(
                    "id_niveau_risque" => $this->VARS_HTML["id_niveau_risque"],
                    "id_reseau"        => $this->VARS_HTML["id_reseau"],
                    "nom_crypto"       => $this->VARS_HTML["nom_crypto"],
                    "symbole_crypto"   => $this->VARS_HTML["symbole_crypto"],
                    "prix"             => $this->VARS_HTML["prix"],
                    "qntte_jetons"     => $this->VARS_HTML["qntte_jetons"],
                    "date_achat"       => $this->VARS_HTML["date_achat"],
                    "note"             => $this->VARS_HTML["note"]
                )
            );

            // Si l'insertion a échoué, on arrête la fonction.
            if ($this->resultat["save_crypto"]["error"] != "") {
                return;
            }

            // Identifiant créé automatiquement par MySQL.
            $idCrypto = (int) $this->oBdd->getLastInsertId();
            $this->resultat["id_crypto_created"] = $idCrypto;

            /*
            * Enregistrement des stratégies.
            *
            * Le formulaire transmet par exemple :
            * "1,3,5"
            */
            $strategies = [];

            if (
                isset($this->VARS_HTML["strategies"])
                && $this->VARS_HTML["strategies"] != ""
            ) {
                $strategies = explode(
                    ",",
                    $this->VARS_HTML["strategies"]
                );
            }

            $spathStrategies = $this->GLOBALS_INI["PATH_HOME"]
                            . $this->GLOBALS_INI["PATH_SQL"]
                            . "insert_strategies_crypto.sql";

            foreach ($strategies as $idStrategie) {
                $idStrategie = (int) $idStrategie;

                // Seuls les identifiants autorisés sont acceptés.
                if (!in_array($idStrategie, [1, 2, 3, 4, 5], true)) {
                    continue;
                }

                $this->resultat["save_strategies"][] =
                    $this->oBdd->treatDatas(
                        $spathStrategies,
                        array(
                            "id_cryptomonnaie" => $idCrypto,
                            "id_strategies"    => $idStrategie
                        )
                    );
            }

            /*
            * Enregistrement des notifications.
            *
            * Le formulaire transmet par exemple :
            * "2,4"
            */
            $notifications = [];

            if (
                isset($this->VARS_HTML["notifications"])
                && $this->VARS_HTML["notifications"] != ""
            ) {
                $notifications = explode(
                    ",",
                    $this->VARS_HTML["notifications"]
                );
            }

            $spathNotifications = $this->GLOBALS_INI["PATH_HOME"]
                                . $this->GLOBALS_INI["PATH_SQL"]
                                . "insert_notification_crypto.sql";

            foreach ($notifications as $idNotification) {
                $idNotification = (int) $idNotification;

                // Seuls les identifiants autorisés sont acceptés.
                if (!in_array($idNotification, [1, 2, 3, 4, 5], true)) {
                    continue;
                }

                $this->resultat["save_notifications"][] =
                    $this->oBdd->treatDatas(
                        $spathNotifications,
                        array(
                            "id_cryptomonnaie" => $idCrypto,
                            "id_notification"  => $idNotification
                        )
                    );
            }
        }

        public function update_crypto(){
            /*
            * Vérification de l'identifiant de la cryptomonnaie.
            */
            $idCrypto = isset($this->VARS_HTML["id_crypto"])
                ? (int) $this->VARS_HTML["id_crypto"]
                : 0;

            if ($idCrypto <= 0) {
                $this->resultat["update_crypto"] = array(
                    "error" => "Identifiant de cryptomonnaie invalide"
                );
                return;
            }

            /*
            * Modification des informations principales.
            */
            $spathUpdateCrypto = $this->GLOBALS_INI["PATH_HOME"]
                            . $this->GLOBALS_INI["PATH_SQL"]
                            . "update_cryptomonnaie.sql";

            $this->resultat["update_crypto"] =
                $this->oBdd->treatDatas(
                    $spathUpdateCrypto,
                    array(
                        "id_cryptomonnaie" => $idCrypto,
                        "id_niveau_risque" => $this->VARS_HTML["id_niveau_risque"],
                        "id_reseau"        => $this->VARS_HTML["id_reseau"],
                        "nom_crypto"       => $this->VARS_HTML["nom_crypto"],
                        "symbole_crypto"   => $this->VARS_HTML["symbole_crypto"],
                        "prix"             => $this->VARS_HTML["prix"],
                        "qntte_jetons"     => $this->VARS_HTML["qntte_jetons"],
                        "date_achat"       => $this->VARS_HTML["date_achat"],
                        "note"             => isset($this->VARS_HTML["note"])
                            ? $this->VARS_HTML["note"]
                            : ""
                    )
                );

            /*
            * Si la modification principale a échoué,
            * on ne modifie pas les relations.
            */
            if ($this->resultat["update_crypto"]["error"] != "") {
                return;
            }

            /*
            * Suppression des anciennes stratégies.
            */
            $spathDeleteStrategies = $this->GLOBALS_INI["PATH_HOME"]
                                . $this->GLOBALS_INI["PATH_SQL"]
                                . "delete_strategies_crypto.sql";

            $this->resultat["delete_strategies"] =
                $this->oBdd->treatDatas(
                    $spathDeleteStrategies,
                    array(
                        "id_cryptomonnaie" => $idCrypto
                    )
                );

            /*
            * Récupération des nouvelles stratégies.
            * Exemple reçu : "1,3,5"
            */
            $strategies = array();

            if (
                isset($this->VARS_HTML["strategies"])
                && $this->VARS_HTML["strategies"] != ""
            ) {
                $strategies = explode(
                    ",",
                    $this->VARS_HTML["strategies"]
                );
            }

            /*
            * Enregistrement des nouvelles stratégies.
            */
            $spathInsertStrategies = $this->GLOBALS_INI["PATH_HOME"]
                                . $this->GLOBALS_INI["PATH_SQL"]
                                . "insert_strategies_crypto.sql";

            foreach ($strategies as $idStrategie) {
                $idStrategie = (int) $idStrategie;

                if (!in_array($idStrategie, array(1, 2, 3, 4, 5), true)) {
                    continue;
                }

                $this->resultat["update_strategies"][] =
                    $this->oBdd->treatDatas(
                        $spathInsertStrategies,
                        array(
                            "id_cryptomonnaie" => $idCrypto,
                            "id_strategies"    => $idStrategie
                        )
                    );
            }

            /*
            * Suppression des anciennes notifications.
            */
            $spathDeleteNotifications = $this->GLOBALS_INI["PATH_HOME"]
                                    . $this->GLOBALS_INI["PATH_SQL"]
                                    . "delete_notification_crypto.sql";

            $this->resultat["delete_notifications"] =
                $this->oBdd->treatDatas(
                    $spathDeleteNotifications,
                    array(
                        "id_cryptomonnaie" => $idCrypto
                    )
                );

            /*
            * Récupération des nouvelles notifications.
            * Exemple reçu : "2,4"
            */
            $notifications = array();

            if (
                isset($this->VARS_HTML["notifications"])
                && $this->VARS_HTML["notifications"] != ""
            ) {
                $notifications = explode(
                    ",",
                    $this->VARS_HTML["notifications"]
                );
            }

            /*
            * Enregistrement des nouvelles notifications.
            */
            $spathInsertNotifications = $this->GLOBALS_INI["PATH_HOME"]
                                    . $this->GLOBALS_INI["PATH_SQL"]
                                    . "insert_notification_crypto.sql";

            foreach ($notifications as $idNotification) {
                $idNotification = (int) $idNotification;

                if (!in_array($idNotification, array(1, 2, 3, 4, 5), true)) {
                    continue;
                }

                $this->resultat["update_notifications"][] =
                    $this->oBdd->treatDatas(
                        $spathInsertNotifications,
                        array(
                            "id_cryptomonnaie" => $idCrypto,
                            "id_notification"  => $idNotification
                        )
                    );
            }
        }
        public function supprime_crypto(){
            $idCrypto = isset($this->VARS_HTML["id_crypto"])
                ? (int) $this->VARS_HTML["id_crypto"]
                : 0;

            if ($idCrypto <= 0) {
                $this->resultat["supprime_crypto"] = array(
                    "error" => "Identifiant de cryptomonnaie invalide"
                );
                return;
            }

            /*
            * Suppression des stratégies associées.
            */
            $spathStrategies = $this->GLOBALS_INI["PATH_HOME"]
                            . $this->GLOBALS_INI["PATH_SQL"]
                            . "delete_strategies_crypto.sql";

            $this->resultat["delete_strategies"] =
                $this->oBdd->treatDatas(
                    $spathStrategies,
                    array(
                        "id_cryptomonnaie" => $idCrypto
                    )
                );

            /*
            * Suppression des notifications associées.
            */
            $spathNotifications = $this->GLOBALS_INI["PATH_HOME"]
                                . $this->GLOBALS_INI["PATH_SQL"]
                                . "delete_notification_crypto.sql";

            $this->resultat["delete_notifications"] =
                $this->oBdd->treatDatas(
                    $spathNotifications,
                    array(
                        "id_cryptomonnaie" => $idCrypto
                    )
                );

            /*
            * Suppression de la cryptomonnaie.
            */
            $spathCrypto = $this->GLOBALS_INI["PATH_HOME"]
                        . $this->GLOBALS_INI["PATH_SQL"]
                        . "delete_cryptomonnaie.sql";

            $this->resultat["supprime_crypto"] =
                $this->oBdd->treatDatas(
                    $spathCrypto,
                    array(
                        "id_cryptomonnaie" => $idCrypto
                    )
                );
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

        /**
         * Récupère tous les réseaux.
         */
        public function liste_reseaux()
        {
            $spathSQL = $this->GLOBALS_INI["PATH_HOME"]
                    . $this->GLOBALS_INI["PATH_SQL"]
                    . "select_reseaux.sql";

            $this->resultat["liste_reseaux"] =
                $this->oBdd->getSelectDatas($spathSQL, array());
        }
    }  
?>