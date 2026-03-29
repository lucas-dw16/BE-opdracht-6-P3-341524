DROP PROCEDURE IF EXISTS sp_GetProductsBySupplier;

DELIMITER $$

CREATE PROCEDURE sp_GetProductsBySupplier(
    IN p_LeverancierId INT
)
BEGIN
    SELECT
        PROD.Id AS ProductId,
        PROD.Naam,
        PROD.Barcode,
        COALESCE(MAGA.AantalAanwezig, 0) AS AantalInMagazijn,
        MAX(PPLE.DatumLevering) AS LaatsteLevering
    FROM Product AS PROD
    INNER JOIN ProductPerLeverancier AS PPLE
        ON PPLE.ProductId = PROD.Id
    LEFT JOIN Magazijn AS MAGA
        ON MAGA.ProductId = PROD.Id
    WHERE PPLE.LeverancierId = p_LeverancierId
    GROUP BY PROD.Id, PROD.Naam, PROD.Barcode, MAGA.AantalAanwezig
    ORDER BY AantalInMagazijn DESC;
END$$

DELIMITER ;
