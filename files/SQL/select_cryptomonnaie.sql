SELECT
    c.id_cryptomonnaie,
    c.id_niveau_risque,
    c.id_reseau,
    c.nom_crypto AS nom,
    c.symbole_crypto AS symbole,
    COALESCE(r.nom_blockchain, '') AS reseau,
    c.prix,
    c.qntte_jetons AS quantite,
    DATE_FORMAT(c.date_achat, '%Y-%m-%d') AS dateAchat,
    COALESCE(c.note, '') AS notes,

    COALESCE(
        (
            SELECT GROUP_CONCAT(
                sc.id_strategies
                ORDER BY sc.id_strategies
            )
            FROM strategies_crypto AS sc
            WHERE sc.id_cryptomonnaie = c.id_cryptomonnaie
        ),
        ''
    ) AS id_strategies,

    COALESCE(
        (
            SELECT GROUP_CONCAT(
                nc.id_notification
                ORDER BY nc.id_notification
            )
            FROM notification_crypto AS nc
            WHERE nc.id_cryptomonnaie = c.id_cryptomonnaie
        ),
        ''
    ) AS id_notifications

FROM cryptomonnaie AS c
LEFT JOIN reseau AS r
    ON r.id_reseau = c.id_reseau
ORDER BY c.nom_crypto;