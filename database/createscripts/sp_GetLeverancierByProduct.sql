DROP PROCEDURE IF EXISTS sp_GetLeverancierByProduct;

DELIMITER $$

CREATE PROCEDURE sp_GetLeverancierByProduct(
    IN p_ProductId INT
)
BEGIN
    -- Get supplier information for a given product
    SELECT DISTINCT
          L.Id AS LeverancierId
         ,L.Naam AS LeverancierNaam
         ,L.Contactpersoon
         ,L.Leveranciernummer
         ,L.Mobiel
         ,C.Straat
         ,C.Huisnummer
         ,C.Postcode
         ,C.Stad
    FROM Leverancier AS L
    INNER JOIN ProductPerLeverancier AS PPL ON PPL.LeverancierId = L.Id
    LEFT JOIN Contact AS C ON C.Id = L.ContactId
    WHERE PPL.ProductId = p_ProductId
      AND L.IsActief = 1
    LIMIT 1;

END$$

DELIMITER ;
