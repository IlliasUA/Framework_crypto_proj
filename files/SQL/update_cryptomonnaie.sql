UPDATE cryptomonnaie
SET
    id_niveau_risque = @id_niveau_risque,
    id_reseau = @id_reseau,
    nom_crypto = "@nom_crypto",
    symbole_crypto = "@symbole_crypto",
    prix = @prix,
    qntte_jetons = @qntte_jetons,
    date_achat = "@date_achat",
    note = "@note"
WHERE id_cryptomonnaie = @id_cryptomonnaie;