DROP PROCEDURE IF EXISTS sp_GetProductsWithAllergenen;

DELIMITER $$

CREATE PROCEDURE sp_GetProductsWithAllergenen(
    IN p_AllergeenId INT,
    IN p_page INT,
    IN p_page_size INT
)
BEGIN
    DECLARE v_offset INT;

    -- Default pagination values
    SET p_page = IFNULL(p_page, 1);
    SET p_page_size = IFNULL(p_page_size, 4);

    -- Calculate offset
    SET v_offset = (p_page - 1) * p_page_size;

    -- Get all products with their allergens
    -- If p_AllergeenId is NULL, get all products with any allergen
    -- If p_AllergeenId is specified, filter by that allergen

    SELECT DISTINCT
          P.Id AS ProductId
         ,P.Naam AS ProductNaam
         ,P.Barcode
         ,GROUP_CONCAT(DISTINCT A.Naam ORDER BY A.Naam SEPARATOR ', ') AS Allergenen
         ,M.AantalAanwezig
         ,(SELECT COUNT(DISTINCT P2.Id)
           FROM Product AS P2
           INNER JOIN ProductPerAllergeen AS PPA2 ON PPA2.ProductId = P2.Id
           WHERE P2.IsActief = 1
             AND (p_AllergeenId IS NULL OR PPA2.AllergeenId = p_AllergeenId)
          ) AS TotalCount
    FROM Product AS P
    INNER JOIN ProductPerAllergeen AS PPA ON PPA.ProductId = P.Id
    INNER JOIN Allergeen AS A ON A.Id = PPA.AllergeenId
    LEFT JOIN Magazijn AS M ON M.ProductId = P.Id
    WHERE P.IsActief = 1
      AND (p_AllergeenId IS NULL OR PPA.AllergeenId = p_AllergeenId)
    GROUP BY P.Id, P.Naam, P.Barcode, M.AantalAanwezig
    ORDER BY P.Naam ASC
    LIMIT p_page_size OFFSET v_offset;

END$$

DELIMITER ;
