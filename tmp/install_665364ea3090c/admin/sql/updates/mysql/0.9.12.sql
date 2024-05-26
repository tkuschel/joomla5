-- @package		Depot.SQL MariaDB -- UPDATE to 0.9.12
-- @subpackage	com_depot
-- @author		Thomas Kuschel <thomas@kuschel.at>
-- @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
-- @license		GNU General Public License version 2 or later; see LICENSE.md
-- @since		0.9
ALTER TABLE `#__depot_package`
ADD COLUMN `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `checked_out_time`,
ADD COLUMN `modified_by` INT(10) UNSIGNED NOT NULL DEFAULT 0 AFTER `modified`;
