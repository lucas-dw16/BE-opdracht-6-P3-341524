DROP PROCEDURE IF EXISTS sp_AddDelivery;

DELIMITER $$

CREATE PROCEDURE sp_AddDelivery(
    IN p_LeverancierId INT,
    IN p_ProductId INT,
    IN p_Aantal INT,
    IN p_DatumLevering DATETIME,
    IN p_DatumEerstVolgendeLevering DATETIME
)
BEGIN
    DECLARE v_isActief TINYINT DEFAULT 1;
    DECLARE v_productNaam VARCHAR(255);
    DECLARE v_leverancierNaam VARCHAR(255);
    DECLARE v_errorMessage VARCHAR(500);

    -- Validate quantity
    IF p_Aantal IS NULL OR p_Aantal <= 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Ongeldig aantal geleverd (moet > 0)';
    END IF;

    -- Check product active flag (if column exists)
    -- If the column does not exist this SELECT will return NULL and COALESCE will default to 1.
    SELECT COALESCE(IsActief, 1), Naam INTO v_isActief, v_productNaam FROM Product WHERE Id = p_ProductId LIMIT 1;

    IF v_isActief = 0 THEN
        -- Get supplier name
        SELECT Naam INTO v_leverancierNaam FROM Leverancier WHERE Id = p_LeverancierId LIMIT 1;

        -- Build error message with product and supplier names
        SET v_errorMessage = CONCAT('Het product ', v_productNaam, ' van de leverancier ', v_leverancierNaam, ' wordt niet meer geproduceerd');
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = v_errorMessage;
    END IF;

    -- Insert delivery record
    INSERT INTO ProductPerLeverancier (LeverancierId, ProductId, DatumLevering, Aantal, DatumEerstVolgendeLevering)
    VALUES (p_LeverancierId, p_ProductId, p_DatumLevering, p_Aantal, p_DatumEerstVolgendeLevering);

    -- Update magazijn: if exists update AantalAanwezig, otherwise insert
    IF EXISTS (SELECT 1 FROM Magazijn WHERE ProductId = p_ProductId) THEN
        UPDATE Magazijn
        SET AantalAanwezig = COALESCE(AantalAanwezig, 0) + p_Aantal
        WHERE ProductId = p_ProductId;
    ELSE
        INSERT INTO Magazijn (ProductId, VerpakkingsEenheid, AantalAanwezig)
        VALUES (p_ProductId, NULL, p_Aantal);
    END IF;

    -- Optionally return updated magazijn row
    SELECT m.ProductId, m.VerpakkingsEenheid, m.AantalAanwezig
    FROM Magazijn m
    WHERE m.ProductId = p_ProductId;

END$$

DELIMITER ;
