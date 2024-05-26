-- @package		Depot.SQL MariaDB -- UPDATE to 0.0.2
-- @subpackage	com_depot
-- @author		Thomas Kuschel <thomas@kuschel.at>
-- @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
-- @license		GNU General Public License version 2 or later; see LICENSE.md
-- @since		0.0.2

CREATE TABLE IF NOT EXISTS `#__depot` (
	`id` SERIAL,
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
	`ordering` INT(11) NOT NULL DEFAULT 0,
	`version` int unsigned NOT NULL DEFAULT 1,
	-- references to other tables:
	`category_id` INT(11) NOT NULL DEFAULT 0,
	`datasheet_id` INT(11) NOT NULL DEFAULT 0,
	`datasheet_alt` VARCHAR(1024) NOT NULL DEFAULT '',
	`manufacturer_id` INT(11) NOT NULL DEFAULT 0,
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
 `ordering`,`state`,`manufacturer_id`) VALUES
 ('1N5404','1n5404','diode, rectifier 3A',9,'2023-09-25 15:00:00',1,1,1),
 ('1N4148','1n4148','diode, general purpose',1234,'2023-09-25 15:15:15',2,1,2);
