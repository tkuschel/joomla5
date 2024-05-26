-- @package		Depot.SQL MariaDB
-- @subpackage	com_depot
-- @author		Thomas Kuschel <thomas@kuschel.at>
-- @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
-- @license		GNU General Public License version 2 or later; see LICENSE.md
-- @since		0.0.2

DROP TABLE IF EXISTS `#__depot`;
CREATE TABLE `#__depot` (
	`id` SERIAL,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	`component_name` VARCHAR(1024) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL
		COMMENT 'unique component name (ASCII characters only)',
	`alias` VARCHAR(1024) NOT NULL DEFAULT '',
	`description` VARCHAR(4000) NOT NULL DEFAULT '',
	`quantity` INT(10) UNSIGNED NOT NULL DEFAULT 0,
	`quantity_exp` INT(11) NOT NULL DEFAULT 0
		COMMENT 'Exponent of the quantity (10^x of the number, usually 0 i.e. 10⁰)',
	`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created_by` INT(10) UNSIGNED NOT NULL DEFAULT 0,
	`checked_out` INT(11) NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified_by` INT(10) UNSIGNED NOT NULL DEFAULT 0,
	`path` VARCHAR(400) NOT NULL DEFAULT '',
	`state` TINYINT(4) NOT NULL DEFAULT 0
		COMMENT 'Published=1,Unpublished=0,Archived=2,Trashed=-2',
	`access` TINYINT(4) NOT NULL DEFAULT 0,
	`params` VARCHAR(1024) NOT NULL DEFAULT '',
	`image` VARCHAR(1024) NOT NULL DEFAULT '',
	`version` int unsigned NOT NULL DEFAULT 1,
	-- references to other tables:
	`category_id` INT(11) NOT NULL DEFAULT 0,
	`datasheet_id` INT(11) NOT NULL DEFAULT 0,
	`datasheet_alt` VARCHAR(1024) NOT NULL DEFAULT '',
	`manufacturer_id` INT(11) NOT NULL DEFAULT 0,
	`package_id` INT(11) NOT NULL DEFAULT 0,
	`stock_id` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`),
	KEY `idx_state` (`state`),
	KEY `idx_stock_id` (`stock_id`),
	KEY `idx_manufacturer` (`manufacturer_id`),
	UNIQUE KEY `aliasindex` (`alias`,`manufacturer_id`,`stock_id`)
)	ENGINE=InnoDB
	AUTO_INCREMENT=0
	DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__depot` (`component_name`,`alias`,`description`,`quantity`,`created`,
 `ordering`,`state`,`manufacturer_id`,`stock_id`,`package_id`) VALUES
 ('1N5404','1n5404','diode, rectifier 3A',9,'2023-09-25 15:00:00',1,1,1,1,9),
 ('1N4148','1n4148','diode, general purpose',1234,'2023-09-25 15:15:15',2,1,2,1,8),
 ('R_120R','r_120r','resistor, metalic',46,'2023-11-15 23:40:00',3,1,1,2,11);

DROP TABLE IF EXISTS `#__depot_manufacturer`;
CREATE TABLE `#__depot_manufacturer` (
	`id` SERIAL,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	`name_short` CHAR(25) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL
		COMMENT 'unique manufacturer name or abbriviation',
	`alias` VARCHAR(127) NOT NULL DEFAULT '',
	`name_long` VARCHAR(1024) NOT NULL DEFAULT '',
	`url` VARCHAR(1024) NOT NULL DEFAULT '',
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created_by` INT(10) UNSIGNED NOT NULL DEFAULT 0,
	`checked_out` INT(11) NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified_by` INT(10) UNSIGNED NOT NULL DEFAULT 0,
	`description` VARCHAR(4000) NOT NULL DEFAULT '',
	`state` TINYINT(4) NOT NULL DEFAULT 0,
	`image` VARCHAR(1024) NOT NULL DEFAULT '',
	`access` TINYINT(4) NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`),
	UNIQUE KEY `name_short` (`name_short`)
)	ENGINE=InnoDB
	AUTO_INCREMENT=0
	DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__depot_manufacturer` (`name_short`, `name_long`, `url`,
 `description`, `image`, `state`) VALUES
('TSC','Taiwan Semiconductor','https://www.taiwansemi.com',
 'Diodes, ECAD Models, ICs, MOSFETs, Protection Devices, AEC-Q qualified','',1),
('ST','STMicroelectronics','https://www.st.com',
 'Microprocessors, Audio ICs, OPamps, Diodes, Memories, MEMS, NFCs, Transistors, Wireless, Automotive electronics, etc.','',1);

DROP TABLE IF EXISTS `#__depot_stock`;
CREATE TABLE `#__depot_stock` (
	`id` SERIAL,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	`name` VARCHAR(1024) NOT NULL DEFAULT '',
	`alias` VARCHAR(1024) NOT NULL DEFAULT '',
	`owner_id` INT(10) UNSIGNED NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created_by` INT(10) UNSIGNED NOT NULL DEFAULT 0,
	`checked_out` INT(11) NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified_by` INT(10) UNSIGNED NOT NULL DEFAULT 0,
	`description` VARCHAR(4000) NOT NULL DEFAULT '',
	`params` VARCHAR(1024) NOT NULL DEFAULT '',
	`location` VARCHAR(1024) NOT NULL DEFAULT '',
	`latitude` DECIMAL(9,7) NOT NULL DEFAULT 48.31738798930856,
	`longitude` DECIMAL(10,7) NOT NULL DEFAULT 16.313504251028924,
	`state` TINYINT(4) NOT NULL DEFAULT 0,
	`access` TINYINT(4) NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`),
	UNIQUE KEY `nameindex` (`name`,`owner_id`)
 )
	ENGINE=InnoDB
	AUTO_INCREMENT=0
	DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__depot_stock`(`name`, `location`, `description`, `state`, `access`) VALUES
('Semiconductors workshop cabinet depot', 'Tom''s office, Martinstr. 58a, 3400 Klosterneuburg',
 'MARS Svratka Workshop Depot 5x12 (60) compartments à 54 x 35 x 140 mm',1,0),
('Resistors workshop cabinet depot', 'Tom''s office, Martinstr. 58a, 3400 Klosterneuburg',
 'MARS Svratka Workshop Depot 2x5x12 + 1x5x7+3 (158) compartments à 54 x 35 x 140 mm',1,0),
('Capacitors/Inductors workshop cabinet depot', 'Tom''s office, Martinstr. 58a, 3400 Klosterneuburg',
 'MARS Svratka Workshop Depot 5x12 (60) compartments à 54 x 35 x 140 mm',1,0),
('Plugs/Sockets/other workshop cabinet depot', 'Tom''s office, Martinstr. 58a, 3400 Klosterneuburg',
 'MARS Svratka Workshop Depot 5x12 (60) compartments à 54 x 35 x 140 mm',1,0),
('SMD cabinet', 'Tom''s office, Martinstr. 58a, 3400 Klosterneuburg',
 'SMD cabinet, conductive, 6-times cabinet with inlays, 6 x 7*6 (252) round boxes, each ø 27 x 13 mm',1,0);

DROP TABLE IF EXISTS `#__depot_package`;
CREATE TABLE `#__depot_package` (
	`id` SERIAL,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	`name` VARCHAR(400) NOT NULL DEFAULT '',
	`description` VARCHAR(4000) NOT NULL DEFAULT '',
	`alias` VARCHAR(400) NOT NULL DEFAULT '',
	`image` VARCHAR(1024) NOT NULL DEFAULT '',
	`state` TINYINT(4) NOT NULL DEFAULT 0
		COMMENT 'Published=1,Unpublished=0,Archived=2,Trashed=-2',
	`mounting_style_id` TINYINT(4) NOT NULL DEFAULT 0
		COMMENT 'Unknown=0,Through_Hole=1,SMD/SMT=2',
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created_by` INT(10) UNSIGNED NOT NULL DEFAULT 0,
	`checked_out` INT(11) NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified_by` INT(10) UNSIGNED NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`)
)	ENGINE = InnoDB
	AUTO_INCREMENT=0
	DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__depot_package` (`name`,`description`,`state`,`mounting_style_id`) VALUES
	('R_0603_1608Metric','1.6 x 0.8 mm',1,1),
	('R_0805_2012Metric','2.0 x 1.2 mm',1,1),
	('DIP8','dual inline package 8 pins',1,2),
	('DIP14','dual inline package 14 pins',1,2),
	('DIP16','dual inline package 16 pins',1,2),
	('DIP20','dual inline package 20 pins',1,2),
	('DIP28_SMALL','dual inline package 28 pins, small',1,2),
	('DO-35','DO-204-AH, SOD27, 3.05-5.08 x ø1.53-ø2.28 mm diode axial package',1,2),
	('DO-201AD','DO-27, 7.2-9.5 x ø4.8-ø5.3 mm diode axial package',1,2),
	('QFN-48-1EP_7x7mm_P0.5mm_EP5.6x5.6mm','Ultra thin Fine pitch Quad Flat Package No-leads, 7 x 7 mm, 0.5 mm pitch, 48 pins',1,1),
	('R_TH_0207','axial 6xø2.3mm typ. 0.25W',1,2),
	('R_TH_0104','axial 3.3xø1.8mm typ 0.125W',1,2),
	('R_TH_0309','axial 9xø3.2mm typ. 0.5W',1,2),
	('R_TH_0412','axial 11.5xø4.5mm typ. 1W',1,2),
	('R_TH_0516','axial 15.5xø5mm typ. 2W',1,2);

DROP TABLE IF EXISTS `#__depot_mounting_style`;
CREATE TABLE `#__depot_mounting_style` (
	`id` SERIAL,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	`title` VARCHAR(100) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`)
)	ENGINE = InnoDB
	AUTO_INCREMENT=0
	DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__depot_mounting_style` (`id`,`title`,`ordering`) VALUES
	-- (0,'UNKNOWN',0),
	(1,'SMD',1),
	(2,'THD',2),
	(3,'CHASSIS_MOUNT',3),
	(4,'PRESS_FIT',4),
	(5,'SCREW_MOUNT',5);
