DROP PROCEDURE IF EXISTS sp_GetLeverancierDetails;

DELIMITER $$

CREATE PROCEDURE sp_GetLeverancierDetails(
    IN p_LeverancierId INT
)
BEGIN
    SELECT
        l.Id,
        l.Naam,
        l.Contactpersoon,
        l.Leveranciernummer,
        l.Mobiel,
        l.ContactId,
        c.Straat AS Straatnaam,
        c.Huisnummer,
        c.Postcode,
        c.Stad
    FROM Leverancier l
    INNER JOIN Contact c ON l.ContactId = c.Id
    WHERE l.Id = p_LeverancierId
    LIMIT 1;
END$$

DELIMITER ;
