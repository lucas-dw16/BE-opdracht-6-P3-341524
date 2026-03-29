DROP PROCEDURE IF EXISTS sp_UpdateLeverancier;

DELIMITER $$

CREATE PROCEDURE sp_UpdateLeverancier(
    IN p_LeverancierId INT,
    IN p_Naam VARCHAR(60),
    IN p_Contactpersoon VARCHAR(60),
    IN p_Leveranciernummer VARCHAR(11),
    IN p_Mobiel VARCHAR(11),
    IN p_ContactId INT,
    IN p_Straat VARCHAR(100),
    IN p_Huisnummer VARCHAR(10),
    IN p_Postcode VARCHAR(10),
    IN p_Stad VARCHAR(60)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Door een technische storing is het niet mogelijk de wijziging door te voeren. Probeer het op een later moment nog eens';
    END;

    START TRANSACTION;

    -- Update Leverancier table
    UPDATE Leverancier
    SET Naam = p_Naam,
        Contactpersoon = p_Contactpersoon,
        Leveranciernummer = p_Leveranciernummer,
        Mobiel = p_Mobiel,
        ContactId = p_ContactId
    WHERE Id = p_LeverancierId;

    -- Update Contact table
    UPDATE Contact
    SET Straat = p_Straat,
        Huisnummer = p_Huisnummer,
        Postcode = p_Postcode,
        Stad = p_Stad
    WHERE Id = p_ContactId;

    COMMIT;

    SELECT 'De wijzigingen zijn doorgevoerd' AS Message;
END$$

DELIMITER ;
