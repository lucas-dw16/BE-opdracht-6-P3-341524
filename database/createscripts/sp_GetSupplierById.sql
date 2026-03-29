DROP PROCEDURE IF EXISTS sp_GetSupplierById;

DELIMITER $$

CREATE PROCEDURE sp_GetSupplierById(
    IN p_SupplierId INT
)
BEGIN
    SELECT Id, Naam, Contactpersoon AS ContactPersoon, Leveranciernummer, Mobiel
    FROM Leverancier
    WHERE Id = p_SupplierId
    LIMIT 1;
END$$

DELIMITER ;
