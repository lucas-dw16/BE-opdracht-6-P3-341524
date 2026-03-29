-- Step: 01
-- Goal: Create a new database jamin_io_sd_2024a
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            12-09-2025      Arjan de Ruijter            New Database
-- **********************************************************************************/

-- Use database jamin_io_sd_2024a
Use `beopdr6`;


-- Step: 02
-- Goal: Create a new table Allergeen
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            12-09-2025      Arjan de Ruijter            New Tabel
-- 02            07-01-2026      Arjan de Ruijter            Fix drop order
-- **********************************************************************************/

DROP TABLE IF EXISTS ProductPerAllergeen;
DROP TABLE IF EXISTS Allergeen;

CREATE TABLE IF NOT EXISTS Allergeen
(
    Id              SMALLINT         UNSIGNED       NOT NULL    AUTO_INCREMENT
   ,Naam            VARCHAR(30)                     NOT NULL
   ,Omschrijving    VARCHAR(100)                    NOT NULL
   ,IsActief        BIT                             NOT NULL    DEFAULT 1
    ,Opmerking       VARCHAR(250)                        NULL    DEFAULT NULL
    ,DatumAangemaakt DateTime(6)                     NOT NULL    DEFAULT CURRENT_TIMESTAMP(6)
    ,DatumGewijzigd  DateTime(6)                     NOT NULL    DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    ,CONSTRAINT      PK_Allergeen_Id   PRIMARY KEY (Id)
) ENGINE=InnoDB;


-- Step: 03
-- Goal: Fill table Allergeen with data
-- **********************************************************************************

-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            12-09-2025      Arjan de Ruijter            New Data
-- **********************************************************************************/

INSERT INTO Allergeen
(
     Naam
    ,Omschrijving
)
VALUES
     ('Gluten', 'Dit product bevat gluten')
    ,('Gelatine', 'Dit product bevat gelatine')
    ,('AZO-kleurstof', 'Dit product bevat AZO-kleurstof')
    ,('Lactose', 'Dit product bevat lactose')
    ,('Soja', 'Dit product bevat soja');



-- Step 04:
-- Goal: Create a new table Product
-- **************************************************************
-- Version:       Date:       Author:           Description
-- ********       ****        *******           ***********
-- 01             18-10-2024  Arjan de Ruijter  New table
-- 02             10-10-2025  Arjan de Ruijter  Default aangepast
-- 03             07-01-2026  Arjan de Ruijter  Fix drop order
-- **************************************************************
DROP TABLE IF EXISTS ProductPerLeverancier;
DROP TABLE IF EXISTS Magazijn;
DROP TABLE IF EXISTS ProductEinddatumLevering;
DROP TABLE IF EXISTS ProductPerAllergeen;
DROP TABLE IF EXISTS Product;

CREATE TABLE IF NOT EXISTS Product
(
     Id              MEDIUMINT             UNSIGNED        NOT NULL      AUTO_INCREMENT
    ,Naam            VARCHAR(255)                          NOT NULL
    ,Barcode         VARCHAR(13)                           NOT NULL
    ,IsActief        BIT                                   NOT NULL      DEFAULT 1
    ,Opmerking       VARCHAR(250)                              NULL      DEFAULT NULL
    ,DatumAangemaakt Datetime(6)                           NOT NULL      DEFAULT CURRENT_TIMESTAMP(6)
    ,DatumGewijzigd  Datetime(6)                           NOT NULL      DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    ,CONSTRAINT      PK_Product_Id        PRIMARY KEY (Id)
) ENGINE=InnoDB   AUTO_INCREMENT=1;


-- Step: 05
-- Goal: Fill table Product with data
-- ***********************************************************
-- Version:       Date:       Author:           Description
-- ********       ****        *******           ***********
-- 01             18-10-2024  Arjan de Ruijter  Insert Records
-- ***********************************************************

INSERT INTO Product
(
     Naam
    ,Barcode
)
VALUES
     ('Mintnopjes', '8719587231278')
    ,('Schoolkrijt', '8719587326713')
    ,('Honingdrop', '8719587327836')
    ,('Zure Beren', '8719587321441')
    ,('Cola Flesjes', '8719587321237')
    ,('Turtles', '8719587322245')
    ,('Witte Muizen', '8719587328256')
    ,('Reuzen Slangen', '8719587325641')
    ,('Zoute Rijen', '8719587322739')
    ,('Winegums', '8719587327527')
    ,('Drop Munten', '8719587322345')
    ,('Kruis Drop', '8719587322265')
    ,('Zoute Ruitjes', '8719587323256')
    ,("Drop ninja\'s", '8719587323277');

-- Update Winegums to inactive (not produced anymore)
UPDATE Product SET IsActief = 0 WHERE Naam = 'Winegums';


-- Step 05b:
-- Goal: Create a new table ProductEinddatumLevering
-- ********************************************************
-- Version:       Date:       Author:           Description
-- ********       ****        *******           ***********
-- 01             24-03-2026  Copilot           New table
-- ********************************************************

CREATE TABLE IF NOT EXISTS ProductEinddatumLevering
(
     Id                   MEDIUMINT       UNSIGNED          NOT NULL      AUTO_INCREMENT
    ,ProductId            MEDIUMINT       UNSIGNED          NOT NULL
    ,EinddatumLevering    DATE                              NOT NULL
    ,IsActief             BIT                               NOT NULL      DEFAULT 1
    ,Opmerking            VARCHAR(250)                          NULL      DEFAULT NULL
    ,DatumAangemaakt      Datetime(6)                       NOT NULL      DEFAULT CURRENT_TIMESTAMP(6)
    ,DatumGewijzigd       Datetime(6)                       NOT NULL      DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    ,CONSTRAINT           PK_ProductEinddatumLevering_Id   PRIMARY KEY (Id)
    ,CONSTRAINT           FK_ProductEinddatumLevering_ProductId_Product_Id  FOREIGN KEY (ProductId) REFERENCES Product(Id)
) ENGINE=InnoDB AUTO_INCREMENT=1;


-- Step 05c:
-- Goal: Fill table ProductEinddatumLevering with data
-- ***********************************************************
-- Version:       Date:       Author:           Description
-- ********       ****        ****************  ***********
-- 01             24-03-2026  Copilot           Insert Records
-- ***********************************************************

INSERT INTO ProductEinddatumLevering
(
     ProductId
    ,EinddatumLevering
)
VALUES
     (1, '2024-06-01')
    ,(2, '2024-05-22')
    ,(3, '2024-05-30')
    ,(4, '2024-05-12')
    ,(7, '2024-05-27')
    ,(10, '2024-05-03')
    ,(11, '2024-02-09')
    ,(14, '2024-01-01');


-- Step 06:
-- Goal: Create a new table Magazijn
-- ********************************************************
-- Version:       Date:       Author:           Description
-- ********       ****        *******           ***********
-- 01             18-10-2024  Arjan de Ruijter  New table
-- ********************************************************

CREATE TABLE IF NOT EXISTS Magazijn
(
     Id                   MEDIUMINT       UNSIGNED          NOT NULL      AUTO_INCREMENT
    ,ProductId            MEDIUMINT       UNSIGNED          NOT NULL
    ,VerpakkingsEenheid   DECIMAL(6,3)                      NOT NULL
    ,AantalAanwezig       INT             UNSIGNED          NULL
    ,IsActief             BIT                               NOT NULL      DEFAULT 1
    ,Opmerking            VARCHAR(250)                          NULL      DEFAULT NULL
    ,DatumAangemaakt      Datetime(6)                       NOT NULL      DEFAULT CURRENT_TIMESTAMP(6)
    ,DatumGewijzigd       Datetime(6)                       NOT NULL      DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    ,CONSTRAINT           PK_Magazijn_Id                    PRIMARY KEY (Id)
    ,CONSTRAINT           FK_Magazijn_ProductId_Product_Id  FOREIGN KEY (ProductId) REFERENCES Product(Id)
) ENGINE=InnoDB   AUTO_INCREMENT=1;

-- Step: 07
-- Goal: Fill table Magazijn with data
-- ***********************************************************
-- Version:       Date:       Author:           Description
-- ********       ****        ****************  ***********
-- 01             18-10-2024  Arjan de Ruijter  Insert Records
-- ***********************************************************

INSERT INTO Magazijn
(
     ProductId
    ,VerpakkingsEenheid
    ,AantalAanwezig
)
VALUES
     (1, 5, 453)
    ,(2, 2.5, 400)
    ,(3, 5, 1)
    ,(4, 1, 800)
    ,(5, 3, 234)
    ,(6, 2, 345)
    ,(7, 1, 795)
    ,(8, 10, 233)
    ,(9, 2.5, 123)
    ,(10, 3, NULL)
    ,(11, 2, 367)
    ,(12, 1, 467)
    ,(13, 5, 20);


-- Step 08:
-- Goal: Create a new table Contact
-- ********************************************************
-- Version:       Date:       Author:           Description
-- ********       ****        *******           ***********
-- 01             07-01-2026  Arjan de Ruijter  New table
-- ********************************************************

DROP TABLE IF EXISTS ProductPerLeverancier;
DROP TABLE IF EXISTS Leverancier;
DROP TABLE IF EXISTS Contact;

CREATE TABLE IF NOT EXISTS Contact
(
     Id                 SMALLINT             UNSIGNED        NOT NULL      AUTO_INCREMENT
    ,Straat             VARCHAR(100)                         NOT NULL
    ,Huisnummer         VARCHAR(10)                          NOT NULL
    ,Postcode           VARCHAR(10)                          NOT NULL
    ,Stad               VARCHAR(60)                          NOT NULL
    ,IsActief           BIT                                  NOT NULL      DEFAULT 1
    ,Opmerking          VARCHAR(250)                             NULL      DEFAULT NULL
    ,DatumAangemaakt Datetime(6)                             NOT NULL      DEFAULT CURRENT_TIMESTAMP(6)
    ,DatumGewijzigd  Datetime(6)                             NOT NULL      DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    ,CONSTRAINT      PK_Contact_Id        PRIMARY KEY (Id)
) ENGINE=InnoDB   AUTO_INCREMENT=1;


-- Step 08b:
-- Goal: Fill table Contact with data
-- ***********************************************************
-- Version:       Date:       Author:           Description
-- ********       ****        ****************  ***********
-- 01             07-01-2026  Arjan de Ruijter  Insert Records
-- ***********************************************************

INSERT INTO Contact
(
     Straat
    ,Huisnummer
    ,Postcode
    ,Stad
)
VALUES
     ('Van Gilslaan', '34', '1045CB', 'Hilvarenbeek')
    ,('Den Dolderpad', '2', '1067RC', 'Utrecht')
    ,('Fredo Raalteweg', '257', '1236OP', 'Nijmegen')
    ,('Bertrand Russellhof', '21', '2034AP', 'Den Haag')
    ,('Leon van Bonstraat', '213', '145XC', 'Lunteren')
    ,('Bea van Lingenlaan', '234', '2197FG', 'Sint Pancras');


-- Step 09:
-- Goal: Create a new table Leverancier
-- ********************************************************
-- Version:       Date:       Author:           Description
-- ********       ****        *******           ***********
-- 01             25-10-2024  Arjan de Ruijter  New table
-- 02             07-01-2026  Arjan de Ruijter  Add ContactId FK
-- ********************************************************

CREATE TABLE IF NOT EXISTS Leverancier
(
     Id                 SMALLINT             UNSIGNED        NOT NULL      AUTO_INCREMENT
    ,Naam               VARCHAR(60)                          NOT NULL
    ,Contactpersoon     VARCHAR(60)                          NOT NULL
    ,Leveranciernummer  VARCHAR(11)                          NOT NULL
    ,Mobiel             VARCHAR(11)                          NOT NULL
    ,ContactId          SMALLINT             UNSIGNED            NULL
    ,IsActief           BIT                                  NOT NULL      DEFAULT 1
    ,Opmerking          VARCHAR(250)                             NULL      DEFAULT NULL
    ,DatumAangemaakt Datetime(6)                             NOT NULL      DEFAULT CURRENT_TIMESTAMP(6)
    ,DatumGewijzigd  Datetime(6)                             NOT NULL      DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    ,CONSTRAINT      PK_Levrancier_Id        PRIMARY KEY (Id)
    ,CONSTRAINT      FK_Leverancier_ContactId_Contact_Id  FOREIGN KEY (ContactId) REFERENCES Contact (Id)
) ENGINE=InnoDB   AUTO_INCREMENT=1;


-- Step: 09b
-- Goal: Fill table Levrancier with data
-- ***********************************************************
-- Version:       Date:       Author:           Description
-- ********       ****        ****************  ***********
-- 01             25-10-2024  Arjan de Ruijter  Insert Records
-- 02             07-01-2026  Arjan de Ruijter  Add ContactId values
-- ***********************************************************

INSERT INTO Leverancier
(
     Naam
    ,Contactpersoon
    ,Leveranciernummer
    ,Mobiel
    ,ContactId
)
VALUES
     ('Venco', 'Bert van Linge', 'L1029384719', '06-28493827', 1)
    ,('Astra Sweets', 'Jasper del Monte', 'L1029284315', '06-39398734', 2)
    ,('Haribo', 'Sven Stalman', 'L1029324748', '06-24383291', 3)
    ,('Basset', 'Joyce Stelterberg', 'L1023845773', '06-48293823', 4)
    ,('De Bron', 'Remco Veenstra', 'L1023857736', '06-34291234', 5)
    ,('Quality Street', 'Johan Nooij', 'L1029234586', '06-23458456', 6)
    ,('Hom Ken Food', 'Hom Ken', 'L1029234599', '06-23458477', NULL);



-- Step 10:
-- Goal: Create a new table ProductPerLeverancier
-- ********************************************************
-- Version:       Date:       Author:           Description
-- ********       ****        *******           ***********
-- 01             25-10-2024  Arjan de Ruijter  New table
-- ********************************************************

CREATE TABLE IF NOT EXISTS ProductPerLeverancier
(
     Id                             MEDIUMINT       UNSIGNED          NOT NULL      AUTO_INCREMENT
    ,LeverancierId                  SMALLINT        UNSIGNED          NOT NULL
    ,ProductId                      MEDIUMINT       UNSIGNED          NOT NULL
    ,DatumLevering                  DATE                              NOT NULL
    ,Aantal                         INT             UNSIGNED          NOT NULL
    ,DatumEerstVolgendeLevering     DATE                                  NULL
    ,IsActief                       BIT                               NOT NULL      DEFAULT 1
    ,Opmerking                      VARCHAR(250)                          NULL      DEFAULT NULL
    ,DatumAangemaakt                Datetime(6)                       NOT NULL      DEFAULT CURRENT_TIMESTAMP(6)
    ,DatumGewijzigd                 Datetime(6)                       NOT NULL      DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    ,CONSTRAINT                     PK_ProductPerLeverancier_Id       PRIMARY KEY (Id)
    ,CONSTRAINT                     FK_ProductPerLeverancier_LeverancierId_Leverancier_Id  FOREIGN KEY (LeverancierId) REFERENCES Leverancier (Id)
    ,CONSTRAINT                     FK_ProductPerLeverancier_ProductId_Product_Id  FOREIGN KEY (ProductId) REFERENCES Product (Id)
) ENGINE=InnoDB   AUTO_INCREMENT=1;



-- Step: 11
-- Goal: Fill table ProductPerLeverancier with data
-- ***********************************************************
-- Version:       Date:       Author:           Description
-- ********       ****        ****************  ***********
-- 01             25-10-2024  Arjan de Ruijter  Insert Records
-- ***********************************************************

INSERT INTO ProductPerLeverancier
(
     LeverancierId
    ,ProductID
    ,DatumLevering
    ,Aantal
    ,DatumEerstVolgendeLevering
)
VALUES
 (1, 1, '2023-04-09', 23, '2023-04-16')
,(1, 1, '2023-04-18', 21, '2023-04-25')
,(1, 2, '2023-04-09', 12, '2023-04-16')
,(1, 3, '2023-04-10', 11, '2023-04-17')
,(2, 4, '2023-04-14', 16, '2023-04-21')
,(2, 4, '2023-04-21', 23, '2023-04-28')
,(2, 5, '2023-04-14', 45, '2023-04-21')
,(2, 6, '2023-04-14', 30, '2023-04-21')
,(3, 7, '2023-04-12', 12, '2023-04-19')
,(3, 7, '2023-04-19', 23, '2023-04-26')
,(3, 8, '2023-04-10', 12, '2023-04-17')
,(3, 9, '2023-04-11', 1, '2023-04-18')
,(4, 10, '2023-04-16', 24, '2023-04-30')
,(5, 11, '2023-04-10', 47, '2023-04-17')
,(5, 11, '2023-04-19', 60, '2023-04-26')
,(5, 12, '2023-04-11', 45, NULL)
,(5, 13, '2023-04-12', 23, NULL)
,(7, 14, '2023-04-14', 20, NULL);


-- Step 12:
-- Goal: Create a new table ProductPerAllergeen
-- ********************************************************
-- Version:       Date:       Author:           Description
-- ********       ****        *******           ***********
-- 01             19-11-2024  Arjan de Ruijter  New table
-- ********************************************************

CREATE TABLE IF NOT EXISTS ProductPerAllergeen
(
     Id                             MEDIUMINT       UNSIGNED          NOT NULL      AUTO_INCREMENT
    ,ProductId                      MEDIUMINT       UNSIGNED          NOT NULL
    ,AllergeenId                    SMALLINT        UNSIGNED          NOT NULL
    ,IsActief                       BIT                               NOT NULL      DEFAULT 1
    ,Opmerking                      VARCHAR(250)                          NULL      DEFAULT NULL
    ,DatumAangemaakt                Datetime(6)                       NOT NULL      DEFAULT CURRENT_TIMESTAMP(6)
    ,DatumGewijzigd                 Datetime(6)                       NOT NULL      DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    ,CONSTRAINT           PK_ProductPerAllergeen_Id  PRIMARY KEY (Id)
    ,CONSTRAINT           FK_ProductPerAllergeen_ProductId_Product_Id  FOREIGN KEY (ProductId) REFERENCES Product (Id)
    ,CONSTRAINT           FK_ProductPerAllergeen_AllergeenId_Allergeen_Id  FOREIGN KEY (AllergeenId) REFERENCES Allergeen (Id)
) ENGINE=InnoDB   AUTO_INCREMENT=1;



-- Step: 13
-- Goal: Fill table ProductPerAllergeen with data
-- ***********************************************************
-- Version:       Date:       Author:           Description
-- ********       ****        ****************  ***********
-- 01             19-11-2024  Arjan de Ruijter  Insert Records
-- ***********************************************************

INSERT INTO ProductPerAllergeen
(
     ProductId
    ,AllergeenId
)
VALUES
  (1, 2)
 ,(1, 1)
 ,(1, 3)
 ,(3, 4)
 ,(6, 5)
 ,(9, 2)
 ,(9, 5)
 ,(10, 2)
 ,(12, 4)
 ,(13, 1)
 ,(14, 5)
 ,(13, 4)
 ,(13, 5);


-- Step 14:
-- Goal: Stored procedure voor veilig verwijderen product uit assortiment
-- ***********************************************************
-- Version:       Date:       Author:           Description
-- ********       ****        ****************  ***********
-- 01             24-03-2026  Copilot           User story 1 scenario 02/03
-- ***********************************************************

DROP PROCEDURE IF EXISTS sp_DeleteProductFromAssortiment;

DELIMITER $$

CREATE PROCEDURE sp_DeleteProductFromAssortiment(
    IN p_ProductId INT,
    IN p_HuidigeDatum DATE
)
BEGIN
    DECLARE v_Einddatum DATE;

    SELECT pel.EinddatumLevering
    INTO v_Einddatum
    FROM ProductEinddatumLevering pel
    WHERE pel.ProductId = p_ProductId
      AND pel.IsActief = 1
    LIMIT 1;

    IF v_Einddatum IS NULL THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Product niet gevonden of geen einddatum levering bekend';
    END IF;

    IF p_HuidigeDatum < v_Einddatum THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Product kan niet worden verwijdert, datum van vandaag ligt voor einddatum levering';
    END IF;

    UPDATE Product
    SET IsActief = 0
    WHERE Id = p_ProductId;

    UPDATE Magazijn
    SET IsActief = 0
    WHERE ProductId = p_ProductId;

    UPDATE ProductPerLeverancier
    SET IsActief = 0
    WHERE ProductId = p_ProductId;

    UPDATE ProductPerAllergeen
    SET IsActief = 0
    WHERE ProductId = p_ProductId;

    UPDATE ProductEinddatumLevering
    SET IsActief = 0
    WHERE ProductId = p_ProductId;

    SELECT 1 AS Success, 'Product is succesvol verwijdert' AS Message;
END$$

DELIMITER ;

