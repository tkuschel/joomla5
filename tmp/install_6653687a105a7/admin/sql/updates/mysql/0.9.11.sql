-- @package		Depot.SQL MariaDB -- UPDATE to 0.9.11
-- @subpackage	com_depot
-- @author		Thomas Kuschel <thomas@kuschel.at>
-- @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
-- @license		GNU General Public License version 2 or later; see LICENSE.md
-- @since		0.9
ALTER TABLE `#__depot_package`
ADD COLUMN `ordering` INT(11) NOT NULL DEFAULT 0 AFTER `modified_by`;
