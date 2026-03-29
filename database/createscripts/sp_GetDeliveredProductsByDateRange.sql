-- ************************************************************************************************
-- Doel: Haal producten op die uit het assortiment gaan binnen een datumrange
-- ************************************************************************************************
-- Versie  Datum         Auteur              Beschrijving
-- ******  *********     *****************   ******************************************************
-- 01      10-03-2026    Copilot             Eerste versie
-- 02      24-03-2026    Copilot             Filter op EinddatumLevering + leverancier/stad
-- ************************************************************************************************

DROP PROCEDURE IF EXISTS sp_GetDeliveredProductsByDateRange;

DELIMITER //

CREATE PROCEDURE sp_GetDeliveredProductsByDateRange(
    IN p_StartDatum DATE,
    IN p_EindDatum DATE
)
BEGIN
    SELECT
        prod.Id AS ProductId,
        prod.Naam AS ProductNaam,
        prod.Barcode,
        pel.EinddatumLevering,
        lev.Naam AS LeverancierNaam,
        lev.Contactpersoon,
        c.Stad
    FROM
        ProductEinddatumLevering pel
    INNER JOIN Product prod
        ON pel.ProductId = prod.Id
    LEFT JOIN (
        SELECT pplx.ProductId, MAX(pplx.Id) AS LaatstePplId
        FROM ProductPerLeverancier pplx
        WHERE pplx.IsActief = 1
        GROUP BY pplx.ProductId
    ) AS laatsteLevering
        ON laatsteLevering.ProductId = prod.Id
    LEFT JOIN ProductPerLeverancier ppl
        ON ppl.Id = laatsteLevering.LaatstePplId
    LEFT JOIN Leverancier lev
        ON ppl.LeverancierId = lev.Id
       AND lev.IsActief = 1
    LEFT JOIN Contact c
        ON lev.ContactId = c.Id
       AND c.IsActief = 1
    WHERE
        pel.IsActief = 1
        AND prod.IsActief = 1
        AND (p_StartDatum IS NULL OR pel.EinddatumLevering >= p_StartDatum)
        AND (p_EindDatum IS NULL OR pel.EinddatumLevering <= p_EindDatum)
    ORDER BY
        pel.EinddatumLevering DESC,
        prod.Naam ASC;
END //

DELIMITER ;
