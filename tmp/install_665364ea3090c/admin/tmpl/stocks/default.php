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

$wa = $this->document->getWebAssetManager();
$wa->useScript('table.columns')
	->useScript('multiselect');

$user = $this->getCurrentUser();
$userID = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 's.ordering';

if ($saveOrder && !empty($this->items)) {
	$saveOrderingUrl = 'index.php?option=com_depot&task=stocks.saveOrderAjax&tmpl=component';
	HTMLHelper::_('draggablelist.draggable');
}
?>
<form action="<?= Route::_('index.php?option=com_depot&view=stocks'); ?>" method="post" name="adminForm" id="adminForm">

	<?= LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>

	<?php if (empty($this->items)): ?>
		<div class="alert alert-info">
			<span class="icon-info-circle" aria-hidden="true">
			</span>
			<?= Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else: ?>

		<table class="table table-striped table-hover" id="stockList">
			<thead>
				<tr>
					<td>
						<?= HTMLHelper::_('grid.checkall'); ?>
					</td>
					<th scope="col" class="w-1 text-center">
						<?= HTMLHelper::_('searchtools.sort', '', 's.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-sort'); ?>
					</th>
					<th>
						<?= HTMLHelper::_(
							'searchtools.sort',
							'COM_DEPOT_TABLE_HEAD_STOCK',
							's.name',
							$listDirn,
							$listOrder
						); ?>
					</th>
					<th>
						<?= HTMLHelper::_(
							'searchtools.sort',
							'COM_DEPOT_TABLE_HEAD_DESCRIPTION',
							's.description',
							$listDirn,
							$listOrder
						); ?>
					</th>
					<th>
						<?= HTMLHelper::_(
							'searchtools.sort',
							'COM_DEPOT_TABLE_HEAD_OWNER',
							'owner',
							$listDirn,
							$listOrder
						); ?>
					</th>
					<th>
						<?= HTMLHelper::_(
							'searchtools.sort',
							'JGRID_HEADING_ID',
							's.id',
							$listDirn,
							$listOrder
						); ?>
					</th>
				</tr>
			</thead>
			<tbody <?php if ($saveOrder): ?> class="js-draggable" data-url="<?= $saveOrderingUrl; ?>"
					data-direction="<?= strtolower($listDirn); ?>" data-nested="true" <?php endif; ?> 	<?php foreach ($this->items as $i => $item):
								 $ordering = ($listOrder == 'ordering');
								 $canChange = true;
								 ?> <tr
					class="row<?= $i % 2; ?>" data-draggable-group="0" item-id="<?= $item->id; ?>">
					<th>
						<?= HTMLHelper::_('grid.id', $i, $item->id); ?>
					</th>
					<td class="text-center d-none d-md-table-cell">
						<?php
						$iconClass = '';

						if (!$canChange) {
							$iconClass = ' inactive';
						} elseif (!$saveOrder) {
							$iconClass = ' inactive" title="' . Text::_('JORDERINGDISABLED');
						}
						?>
						<span class="sortable-handler <?= $iconClass ?>">
							<span class="icon-ellipsis-v" aria-hidden="true"></span>
						</span>
						<?php if ($saveOrder): ?>
							<input type="text" name="order[]" size="5" value="<?= $item->ordering; ?>"
								class="width-20 text-area-order hidden">
						<?php endif; ?>
					</td>
					<th>
						<a href="<?= Route::_('index.php?option=com_depot&task=stock.edit&id=' .
							(int) $item->id) ?>" title="<?= Text::_('JACTION_EDIT') ?>">
							<?= $this->escape($item->name); ?>
						</a>
					</th>
					<td>
						<?= $this->escape($item->description); ?>
					</td>
					<td>
						<div class="word-break">
							<?php if (!empty($item->owner)): ?>
								<?= $item->owner; ?>
								<div class="small">
									<?= Text::_('JGLOBAL_USERNAME') . ': ' . $item->owner_username; ?>
								</div>
							<?php else: ?>
								<?= Text::_('JGLOBAL_AUTH_USER_NOT_FOUND'); ?>
							<?php endif; ?>
						</div>
					</td>
					<td>
						<?= $item->id ?>
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