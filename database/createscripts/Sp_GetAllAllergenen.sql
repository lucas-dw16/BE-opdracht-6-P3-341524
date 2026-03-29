DROP PROCEDURE IF EXISTS Sp_GetAllAllergenen;

DELIMITER $$

CREATE PROCEDURE Sp_GetAllAllergenen(
    IN p_page INT,
    IN p_page_size INT
)
BEGIN
    DECLARE v_offset INT;

    -- Default values
    SET p_page = IFNULL(p_page, 1);
    SET p_page_size = IFNULL(p_page_size, 999);

    -- Calculate offset
    SET v_offset = (p_page - 1) * p_page_size;

    -- Get paginated results (or all with default large page size)
    SELECT ALGE.Id
          ,ALGE.Naam
          ,ALGE.Omschrijving
    FROM  Allergeen AS ALGE
    ORDER BY ALGE.Naam
    LIMIT p_page_size OFFSET v_offset;

END$$

DELIMITER ;
