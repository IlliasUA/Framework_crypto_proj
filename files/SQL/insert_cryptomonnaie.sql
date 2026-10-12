INSERT INTO cryptomonnaie (
    id_niveau_risque,
    id_reseau,
    nom_crypto,
    symbole_crypto,
    prix,
    qntte_jetons,
    date_achat,
    note
) VALUES (
    @id_niveau_risque,
    @id_reseau,
    "@nom_crypto",
    "@symbole_crypto",
    @prix,
    @qntte_jetons,
    "@date_achat",
    "@note"
);