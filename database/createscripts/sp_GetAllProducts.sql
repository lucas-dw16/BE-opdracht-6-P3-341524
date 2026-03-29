

DROP PROCEDURE IF EXISTS sp_GetAllProducts;

DELIMITER $$

CREATE PROCEDURE sp_GetAllProducts()
BEGIN

    SELECT PROD.Id
		  ,PROD.Naam
          ,PROD.Barcode
          ,MAGA.VerpakkingsEenheid
          ,MAGA.AantalAanwezig

	FROM Product AS PROD

    INNER JOIN Magazijn AS MAGA

    ON PROD.Id = MAGA.ProductId;


END$$

DELIMITER ;
