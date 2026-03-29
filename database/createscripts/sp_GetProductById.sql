DROP PROCEDURE IF EXISTS sp_GetProductById;

DELIMITER $$

CREATE PROCEDURE sp_GetProductById(
    IN p_ProductId INT
)
BEGIN
    SELECT Id, Naam, Barcode
    FROM Product
    WHERE Id = p_ProductId
    LIMIT 1;
END$$

DELIMITER ;
