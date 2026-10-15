SELECT COUNT(*) AS total
FROM cryptomonnaie
WHERE id_niveau_risque = @id_niveau_risque;
