-- ************************************************************************************************
-- Doel: Productdetail inclusief allergenen-vlaggen (Ja/Nee)
-- ************************************************************************************************
-- Versie  Datum         Auteur              Beschrijving
-- ******  *********     *****************   ******************************************************
-- 01      10-03-2026    Copilot             Eerste versie
-- 02      24-03-2026    Copilot             Omgezet naar detailrecord voor verwijderpagina
-- ************************************************************************************************

DROP PROCEDURE IF EXISTS sp_GetProductDeliveryDetailsByDateRange;

DELIMITER //

CREATE PROCEDURE sp_GetProductDeliveryDetailsByDateRange(
    IN p_ProductId INT
)
BEGIN
    SELECT
        prod.Id,
        prod.Naam,
        prod.Barcode,
        pel.EinddatumLevering,
        CASE WHEN EXISTS (
            SELECT 1
            FROM ProductPerAllergeen ppa
            WHERE ppa.ProductId = prod.Id
              AND ppa.AllergeenId = 1
              AND ppa.IsActief = 1
        ) THEN 'Ja' ELSE 'Nee' END AS BevatGluten,
        CASE WHEN EXISTS (
            SELECT 1
            FROM ProductPerAllergeen ppa
            WHERE ppa.ProductId = prod.Id
              AND ppa.AllergeenId = 2
              AND ppa.IsActief = 1
        ) THEN 'Ja' ELSE 'Nee' END AS BevatGelatine,
        CASE WHEN EXISTS (
            SELECT 1
            FROM ProductPerAllergeen ppa
            WHERE ppa.ProductId = prod.Id
              AND ppa.AllergeenId = 3
              AND ppa.IsActief = 1
        ) THEN 'Ja' ELSE 'Nee' END AS BevatAzoKleurstof,
        CASE WHEN EXISTS (
            SELECT 1
            FROM ProductPerAllergeen ppa
            WHERE ppa.ProductId = prod.Id
              AND ppa.AllergeenId = 4
              AND ppa.IsActief = 1
        ) THEN 'Ja' ELSE 'Nee' END AS BevatLactose,
        CASE WHEN EXISTS (
            SELECT 1
            FROM ProductPerAllergeen ppa
            WHERE ppa.ProductId = prod.Id
              AND ppa.AllergeenId = 5
              AND ppa.IsActief = 1
        ) THEN 'Ja' ELSE 'Nee' END AS BevatSoja
    FROM
        Product prod
    INNER JOIN ProductEinddatumLevering pel
        ON pel.ProductId = prod.Id
    WHERE
        prod.Id = p_ProductId
        AND prod.IsActief = 1
        AND pel.IsActief = 1
    LIMIT 1;
END //

DELIMITER ;
