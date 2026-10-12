SELECT
    id_cryptomonnaie,
    id_niveau_risque,
    id_reseau,
    nom_crypto,
    symbole_crypto,
    prix,
    qntte_jetons,
    date_achat,
    note
FROM cryptomonnaie
ORDER BY nom_crypto;