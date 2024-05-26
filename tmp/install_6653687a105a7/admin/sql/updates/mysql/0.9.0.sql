-- @package		Depot.SQL MariaDB -- UPDATE to 0.9.0
-- @subpackage	com_depot
-- @author		Thomas Kuschel <thomas@kuschel.at>
-- @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
-- @license		GNU General Public License version 2 or later; see LICENSE.md
-- @since		0.9.0

CREATE TABLE IF NOT EXISTS `#__depot_stock` (
	`id` SERIAL,
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
)	ENGINE=InnoDB
	AUTO_INCREMENT=0
	DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__depot_stock` (`name`, `location`, `description`, `state`, `access`) VALUES
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
