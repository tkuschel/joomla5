<?php
/**
 * @package		Depot.Administrator
 * @subpackage	com_depot
 * @author		Thomas Kuschel <thomas@kuschel.at>
 * @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
 * @license		GNU General Public License version 2 or later; see LICENSE.md
 * @since		0.9.3
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Layout\LayoutHelper;

?>
<form action="<?= Route::_('index.php?option=com_depot&view=manufacturers'); ?>" method="post" name="adminForm"
	id="adminForm">

	<?= LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>

	<?php if (empty($this->items)): ?>
		<div class="alert alert-info">
			<span class="icon-info-circle" aria-hidden="true">
			</span>
			<?= Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else: ?>

		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>
						<?= HTMLHelper::_('grid.checkall'); ?>
					</th>
					<th>
						<?= HTMLHelper::_(
							'searchtools.sort',
							'JGRID_HEADING_ID',
							'm.id',
							$this->listDirn,
							$this->listOrder
						); ?>
					</th>
					<th>
						<?= HTMLHelper::_(
							'searchtools.sort',
							'COM_DEPOT_TABLE_HEAD_MANUFACTURER_ACRONYM',
							'm.name_short',
							$this->listDirn,
							$this->listOrder
						); ?>
					</th>
					<th>
						<?= HTMLHelper::_(
							'searchtools.sort',
							'COM_DEPOT_TABLE_HEAD_MANUFACTURER',
							'm.name_long',
							$this->listDirn,
							$this->listOrder
						); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->items as $i => $item): ?>
					<tr>
						<td>
							<?= HTMLHelper::_('grid.id', $i, $item->id); ?>
						</td>
						<td>
							<?= $item->id ?>
						</td>
						<td>
							<a href="<?= Route::_('index.php?option=com_depot&task=manufacturer.edit&id=' .
								(int) $item->id) ?>" title="<?= Text::_('JACTION_EDIT') ?>">
								<?= $this->escape($item->name_short); ?>
							</a>
						</td>
						<td>
							<?= $this->escape($item->name_long); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?= $this->pagination->getListFooter(); ?>
	<?php endif; ?>

	<input type="hidden" name="task" value="">
	<input type="hidden" name="boxchecked" value="0">
	<?= HTMLHelper::_('form.token'); ?>
</form>