DROP PROCEDURE IF EXISTS sp_GetSupplierOverview;

DELIMITER $$

CREATE PROCEDURE sp_GetSupplierOverview()
BEGIN
    SELECT
        L.Id,
        L.Naam,
        L.ContactPersoon,
        L.Leveranciernummer,
        L.Mobiel,
        COUNT(DISTINCT PPLE.ProductId) AS AantalVerschillendeProducten
    FROM Leverancier AS L
    LEFT JOIN ProductPerLeverancier AS PPLE
        ON PPLE.LeverancierId = L.Id
    GROUP BY L.Id, L.Naam, L.ContactPersoon, L.Leveranciernummer, L.Mobiel
    ORDER BY AantalVerschillendeProducten DESC;
END$$

DELIMITER ;
