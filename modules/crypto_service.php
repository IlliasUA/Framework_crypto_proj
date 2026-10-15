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

        /**
         * Récupère tous les niveaux de risque.
         */
        public function liste_niveaux_risque()
        {
            $spathSQL = $this->GLOBALS_INI["PATH_HOME"]
                    . $this->GLOBALS_INI["PATH_SQL"]
                    . "select_niveaux_risque.sql";

            $this->resultat["liste_niveaux_risque"] =
                $this->oBdd->getSelectDatas(
                    $spathSQL,
                    array()
                );
        }

        public function liste_strategies()
        {
            $spathSQL = $this->GLOBALS_INI["PATH_HOME"]
                    . $this->GLOBALS_INI["PATH_SQL"]
                    . "select_strategies.sql";

            $this->resultat["liste_strategies"] =
                $this->oBdd->getSelectDatas(
                    $spathSQL,
                    array()
                );
        }

        public function liste_notifications()
        {
            $spathSQL = $this->GLOBALS_INI["PATH_HOME"]
                    . $this->GLOBALS_INI["PATH_SQL"]
                    . "select_notifications.sql";

            $this->resultat["liste_notifications"] =
                $this->oBdd->getSelectDatas(
                    $spathSQL,
                    array()
                );
        }

        /**
         * Ajoute un nouveau réseau.
         */
        public function save_reseau()
        {
            $nomBlockchain = isset($this->VARS_HTML["nom_blockchain"])
                ? trim($this->VARS_HTML["nom_blockchain"])
                : "";

            if ($nomBlockchain == "") {
                $this->resultat["save_reseau"] = array(
                    "error" => "Le nom du réseau est obligatoire."
                );

                return;
            }

            $spathSQL = $this->GLOBALS_INI["PATH_HOME"]
                    . $this->GLOBALS_INI["PATH_SQL"]
                    . "insert_reseau.sql";

            $this->resultat["save_reseau"] =
                $this->oBdd->treatDatas(
                    $spathSQL,
                    array(
                        "nom_blockchain" => $nomBlockchain
                    )
                );

            if ($this->resultat["save_reseau"]["error"] == "") {
                $this->resultat["id_reseau_created"] =
                    $this->oBdd->getLastInsertId();
            }
        }

        /**
         * Ajoute un nouveau type de notification.
         */
        public function save_notification()
        {
            $typeNotification =
                isset($this->VARS_HTML["type_notification"])
                    ? trim($this->VARS_HTML["type_notification"])
                    : "";

            if ($typeNotification == "") {
                $this->resultat["save_notification"] = array(
                    "error" =>
                        "Le type de notification est obligatoire."
                );

                return;
            }

            $spathSQL = $this->GLOBALS_INI["PATH_HOME"]
                    . $this->GLOBALS_INI["PATH_SQL"]
                    . "insert_notification.sql";

            $this->resultat["save_notification"] =
                $this->oBdd->treatDatas(
                    $spathSQL,
                    array(
                        "type_notification" => $typeNotification
                    )
                );

            if (
                $this->resultat["save_notification"]["error"] == ""
            ) {
                $this->resultat["id_notification_created"] =
                    $this->oBdd->getLastInsertId();
            }
        }

        /**
         * Ajoute une nouvelle stratégie.
         */
        public function save_strategie()
        {
            $nomStrategie =
                isset($this->VARS_HTML["nom_strategies"])
                    ? trim($this->VARS_HTML["nom_strategies"])
                    : "";

            if ($nomStrategie == "") {
                $this->resultat["save_strategie"] = array(
                    "error" =>
                        "Le nom de la stratégie est obligatoire."
                );

                return;
            }

            $spathSQL = $this->GLOBALS_INI["PATH_HOME"]
                    . $this->GLOBALS_INI["PATH_SQL"]
                    . "insert_strategie.sql";

            $this->resultat["save_strategie"] =
                $this->oBdd->treatDatas(
                    $spathSQL,
                    array(
                        "nom_strategies" => $nomStrategie
                    )
                );

            if (
                $this->resultat["save_strategie"]["error"] == ""
            ) {
                $this->resultat["id_strategie_created"] =
                    $this->oBdd->getLastInsertId();
            }
        }

        /**
         * Ajoute un nouveau niveau de risque.
         */
        public function save_risque()
        {
            $nomRisque =
                isset($this->VARS_HTML["risque"])
                    ? trim($this->VARS_HTML["risque"])
                    : "";

            if ($nomRisque == "") {
                $this->resultat["save_risque"] = array(
                    "error" =>
                        "Le niveau de risque est obligatoire."
                );

                return;
            }

            $spathSQL = $this->GLOBALS_INI["PATH_HOME"]
                    . $this->GLOBALS_INI["PATH_SQL"]
                    . "insert_risque.sql";

            $this->resultat["save_risque"] =
                $this->oBdd->treatDatas(
                    $spathSQL,
                    array(
                        "risque" => $nomRisque
                    )
                );

            if (
                $this->resultat["save_risque"]["error"] == ""
            ) {
                $this->resultat["id_risque_created"] =
                    $this->oBdd->getLastInsertId();
            }
        }

        /**
         * Modifie un réseau existant.
         */
        public function update_reseau()
        {
            $idReseau = isset($this->VARS_HTML["id_reseau"])
                ? (int) $this->VARS_HTML["id_reseau"]
                : 0;

            $nomBlockchain =
                isset($this->VARS_HTML["nom_blockchain"])
                    ? trim($this->VARS_HTML["nom_blockchain"])
                    : "";

            if ($idReseau <= 0) {
                $this->resultat["update_reseau"] = array(
                    "error" => "L'identifiant du réseau est invalide."
                );

                return;
            }

            if ($nomBlockchain == "") {
                $this->resultat["update_reseau"] = array(
                    "error" => "Le nom du réseau est obligatoire."
                );

                return;
            }

            $spathSQL = $this->GLOBALS_INI["PATH_HOME"]
                . $this->GLOBALS_INI["PATH_SQL"]
                . "update_reseau.sql";

            $this->resultat["update_reseau"] =
                $this->oBdd->treatDatas(
                    $spathSQL,
                    array(
                        "id_reseau" => $idReseau,
                        "nom_blockchain" => $nomBlockchain
                    )
                );
        }

        /**
         * Supprime un réseau s'il n'est utilisé par aucune cryptomonnaie.
         */
        public function supprime_reseau()
        {
            $idReseau = isset($this->VARS_HTML["id_reseau"])
                ? (int) $this->VARS_HTML["id_reseau"]
                : 0;

            if ($idReseau <= 0) {
                $this->resultat["supprime_reseau"] = array(
                    "error" => "L'identifiant du réseau est invalide."
                );

                return;
            }

            /*
            * Vérification des cryptomonnaies qui utilisent ce réseau.
            */
            $spathCountSQL = $this->GLOBALS_INI["PATH_HOME"]
                . $this->GLOBALS_INI["PATH_SQL"]
                . "count_cryptomonnaies_reseau.sql";

            $utilisations = $this->oBdd->getSelectDatas(
                $spathCountSQL,
                array(
                    "id_reseau" => $idReseau
                )
            );

            $totalUtilisations = isset($utilisations[0]["total"])
                ? (int) $utilisations[0]["total"]
                : 0;

            if ($totalUtilisations > 0) {
                $this->resultat["supprime_reseau"] = array(
                    "error" =>
                        "Ce réseau ne peut pas être supprimé car il est utilisé par "
                        . $totalUtilisations
                        . " cryptomonnaie(s)."
                );

                return;
            }

            /*
            * Suppression du réseau.
            */
            $spathDeleteSQL = $this->GLOBALS_INI["PATH_HOME"]
                . $this->GLOBALS_INI["PATH_SQL"]
                . "delete_reseau.sql";

            $this->resultat["supprime_reseau"] =
                $this->oBdd->treatDatas(
                    $spathDeleteSQL,
                    array(
                        "id_reseau" => $idReseau
                    )
                );
        }

        /**
         * Modifie un type de notification existant.
         */
        public function update_notification()
        {
            $idNotification = isset($this->VARS_HTML["id_notification"])
                ? (int) $this->VARS_HTML["id_notification"]
                : 0;

            $typeNotification =
                isset($this->VARS_HTML["type_notification"])
                    ? trim($this->VARS_HTML["type_notification"])
                    : "";

            if ($idNotification <= 0) {
                $this->resultat["update_notification"] = array(
                    "error" => "L'identifiant de la notification est invalide."
                );
                return;
            }

            if ($typeNotification == "") {
                $this->resultat["update_notification"] = array(
                    "error" => "Le type de notification est obligatoire."
                );
                return;
            }

            $spathSQL = $this->GLOBALS_INI["PATH_HOME"]
                . $this->GLOBALS_INI["PATH_SQL"]
                . "update_notification.sql";

            $this->resultat["update_notification"] =
                $this->oBdd->treatDatas(
                    $spathSQL,
                    array(
                        "id_notification" => $idNotification,
                        "type_notification" => $typeNotification
                    )
                );
        }

        /**
         * Supprime une notification si elle n'est liée à aucune cryptomonnaie.
         */
        public function supprime_notification()
        {
            $idNotification = isset($this->VARS_HTML["id_notification"])
                ? (int) $this->VARS_HTML["id_notification"]
                : 0;

            if ($idNotification <= 0) {
                $this->resultat["supprime_notification"] = array(
                    "error" => "L'identifiant de la notification est invalide."
                );
                return;
            }

            $spathCountSQL = $this->GLOBALS_INI["PATH_HOME"]
                . $this->GLOBALS_INI["PATH_SQL"]
                . "count_cryptomonnaies_notification.sql";

            $utilisations = $this->oBdd->getSelectDatas(
                $spathCountSQL,
                array(
                    "id_notification" => $idNotification
                )
            );

            $totalUtilisations = isset($utilisations[0]["total"])
                ? (int) $utilisations[0]["total"]
                : 0;

            if ($totalUtilisations > 0) {
                $this->resultat["supprime_notification"] = array(
                    "error" =>
                        "Cette notification ne peut pas être supprimée car elle est utilisée par "
                        . $totalUtilisations
                        . " cryptomonnaie(s)."
                );
                return;
            }

            $spathDeleteSQL = $this->GLOBALS_INI["PATH_HOME"]
                . $this->GLOBALS_INI["PATH_SQL"]
                . "delete_notification.sql";

            $this->resultat["supprime_notification"] =
                $this->oBdd->treatDatas(
                    $spathDeleteSQL,
                    array(
                        "id_notification" => $idNotification
                    )
                );
        }

        /**
         * Modifie une stratégie existante.
         */
        public function update_strategie()
        {
            $idStrategie = isset($this->VARS_HTML["id_strategies"])
                ? (int) $this->VARS_HTML["id_strategies"]
                : 0;

            $nomStrategie = isset($this->VARS_HTML["nom_strategies"])
                ? trim($this->VARS_HTML["nom_strategies"])
                : "";

            if ($idStrategie <= 0) {
                $this->resultat["update_strategie"] = array(
                    "error" => "L'identifiant de la stratégie est invalide."
                );
                return;
            }

            if ($nomStrategie == "") {
                $this->resultat["update_strategie"] = array(
                    "error" => "Le nom de la stratégie est obligatoire."
                );
                return;
            }

            $spathSQL = $this->GLOBALS_INI["PATH_HOME"]
                . $this->GLOBALS_INI["PATH_SQL"]
                . "update_strategie.sql";

            $this->resultat["update_strategie"] =
                $this->oBdd->treatDatas(
                    $spathSQL,
                    array(
                        "id_strategies" => $idStrategie,
                        "nom_strategies" => $nomStrategie
                    )
                );
        }

        /**
         * Supprime une stratégie si elle n'est liée à aucune cryptomonnaie.
         */
        public function supprime_strategie()
        {
            $idStrategie = isset($this->VARS_HTML["id_strategies"])
                ? (int) $this->VARS_HTML["id_strategies"]
                : 0;

            if ($idStrategie <= 0) {
                $this->resultat["supprime_strategie"] = array(
                    "error" => "L'identifiant de la stratégie est invalide."
                );
                return;
            }

            $spathCountSQL = $this->GLOBALS_INI["PATH_HOME"]
                . $this->GLOBALS_INI["PATH_SQL"]
                . "count_cryptomonnaies_strategie.sql";

            $utilisations = $this->oBdd->getSelectDatas(
                $spathCountSQL,
                array(
                    "id_strategies" => $idStrategie
                )
            );

            $totalUtilisations = isset($utilisations[0]["total"])
                ? (int) $utilisations[0]["total"]
                : 0;

            if ($totalUtilisations > 0) {
                $this->resultat["supprime_strategie"] = array(
                    "error" =>
                        "Cette stratégie ne peut pas être supprimée car elle est utilisée par "
                        . $totalUtilisations
                        . " cryptomonnaie(s)."
                );
                return;
            }

            $spathDeleteSQL = $this->GLOBALS_INI["PATH_HOME"]
                . $this->GLOBALS_INI["PATH_SQL"]
                . "delete_strategie.sql";

            $this->resultat["supprime_strategie"] =
                $this->oBdd->treatDatas(
                    $spathDeleteSQL,
                    array(
                        "id_strategies" => $idStrategie
                    )
                );
        }

        /**
         * Modifie un niveau de risque existant.
         */
        public function update_risque()
        {
            $idRisque = isset($this->VARS_HTML["id_niveau_risque"])
                ? (int) $this->VARS_HTML["id_niveau_risque"]
                : 0;

            $nomRisque = isset($this->VARS_HTML["risque"])
                ? trim($this->VARS_HTML["risque"])
                : "";

            if ($idRisque <= 0) {
                $this->resultat["update_risque"] = array(
                    "error" => "L'identifiant du niveau de risque est invalide."
                );
                return;
            }

            if ($nomRisque == "") {
                $this->resultat["update_risque"] = array(
                    "error" => "Le niveau de risque est obligatoire."
                );
                return;
            }

            $spathSQL = $this->GLOBALS_INI["PATH_HOME"]
                . $this->GLOBALS_INI["PATH_SQL"]
                . "update_risque.sql";

            $this->resultat["update_risque"] =
                $this->oBdd->treatDatas(
                    $spathSQL,
                    array(
                        "id_niveau_risque" => $idRisque,
                        "risque" => $nomRisque
                    )
                );
        }

        /**
         * Supprime un niveau de risque s'il n'est utilisé par aucune cryptomonnaie.
         */
        public function supprime_risque()
        {
            $idRisque = isset($this->VARS_HTML["id_niveau_risque"])
                ? (int) $this->VARS_HTML["id_niveau_risque"]
                : 0;

            if ($idRisque <= 0) {
                $this->resultat["supprime_risque"] = array(
                    "error" => "L'identifiant du niveau de risque est invalide."
                );
                return;
            }

            $spathCountSQL = $this->GLOBALS_INI["PATH_HOME"]
                . $this->GLOBALS_INI["PATH_SQL"]
                . "count_cryptomonnaies_risque.sql";

            $utilisations = $this->oBdd->getSelectDatas(
                $spathCountSQL,
                array(
                    "id_niveau_risque" => $idRisque
                )
            );

            $totalUtilisations = isset($utilisations[0]["total"])
                ? (int) $utilisations[0]["total"]
                : 0;

            if ($totalUtilisations > 0) {
                $this->resultat["supprime_risque"] = array(
                    "error" =>
                        "Ce niveau de risque ne peut pas être supprimé car il est utilisé par "
                        . $totalUtilisations
                        . " cryptomonnaie(s)."
                );
                return;
            }

            $spathDeleteSQL = $this->GLOBALS_INI["PATH_HOME"]
                . $this->GLOBALS_INI["PATH_SQL"]
                . "delete_risque.sql";

            $this->resultat["supprime_risque"] =
                $this->oBdd->treatDatas(
                    $spathDeleteSQL,
                    array(
                        "id_niveau_risque" => $idRisque
                    )
                );
        }

    }  
?>
