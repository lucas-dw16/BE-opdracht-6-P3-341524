DROP PROCEDURE IF EXISTS sp_GetProductAllergenen;

DELIMITER $$

CREATE PROCEDURE sp_GetProductAllergenen(
    IN p_ProductId INT
)
BEGIN
    SELECT A.Id, A.Naam, A.Omschrijving
    FROM Allergeen A
    INNER JOIN ProductPerAllergeen PPA ON PPA.AllergeenId = A.Id
    WHERE PPA.ProductId = p_ProductId
    ORDER BY A.Naam ASC;
END$$

DELIMITER ;
