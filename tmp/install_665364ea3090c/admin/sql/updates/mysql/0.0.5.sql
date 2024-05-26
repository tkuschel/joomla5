-- @package		Depot.SQL MariaDB -- UPDATE to 0.0.5
-- @subpackage	com_depot
-- @author		Thomas Kuschel <thomas@kuschel.at>
-- @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
-- @license		GNU General Public License version 2 or later; see LICENSE.md
-- @since		0.0.5

CREATE TABLE IF NOT EXISTS `#__depot_manufacturer` (
	`id` SERIAL,
	`name_short` CHAR(25) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL
		COMMENT 'unique manufacturer name or abbriviation',
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
