<?php
/**
 * @package		Depot.Administrator
 * @subpackage	com_depot
 * @author		Thomas Kuschel <thomas@kuschel.at>
 * @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
 * @license		GNU General Public License version 2 or later; see LICENSE.md
 * @since		0.0.1
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

/** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('table.columns')
	->useScript('multiselect');

$canChange = true;
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'd.ordering';

if ($saveOrder && !empty($this->items)) {
	$saveOrderingUrl = 'index.php?option=com_depot&task=parts.saveOrderAjax&tmpl=component';
	HTMLHelper::_('draggablelist.draggable');
}
?>
<form action="<?= Route::_('index.php?option=com_depot&view=parts'); ?>" method="post" name="adminForm" id="adminForm">

	<?= LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>

	<?php if (empty($this->items)): ?>
		<div class="alert alert-info">
			<span class="icon-info-circle" aria-hidden="true">
			</span>
			<?= Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else: ?>
		<table class="table table-striped table-hover" id="partList">
			<caption class="visually-hidden">
				<?= Text::_('COM_DEPOT_PARTS_TABLE_CAPTION'); ?>,
				<span id="orderedBy">
					<?= Text::_('JGLOBAL_SORTED_BY'); ?>
				</span>,
				<span id="filteredBy">
					<?= Text::_('JGLOBAL_FILTERED_BY'); ?>
				</span>
			</caption>
			<thead>
				<tr>
					<td class="w-1 text-center">
						<?= HTMLHelper::_('grid.checkall'); ?>
					</td>
					<th scope="col" class="w-1 text-center">
						<?= HTMLHelper::_('searchtools.sort', '', 'd.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-sort'); ?>
					</th>
					<th scope="col" class="w-1 text-center">
						<?= HTMLHelper::_('searchtools.sort', 'JSTATUS', 'd.state', $listDirn, $listOrder); ?>
					</th>
					<th scope="col">
						<?= HTMLHelper::_(
							'searchtools.sort',
							'COM_DEPOT_TABLE_HEAD_NAME',
							'd.component_name',
							$listDirn,
							$listOrder
						); ?>
					</th>
					<th>
						<?= HTMLHelper::_(
							'searchtools.sort',
							'COM_DEPOT_TABLE_HEAD_QUANTITY',
							'd.quantity',
							$listDirn,
							$listOrder
						); ?>
					</th>
					<th>
						<?= Text::_('COM_DEPOT_TABLE_HEAD_QUANTITY_EXP') ?>
					</th>
					<th>
						<?= HTMLHelper::_(
							'searchtools.sort',
							'COM_DEPOT_TABLE_HEAD_DESCRIPTION',
							'd.description',
							$listDirn,
							$listOrder
						); ?>
					</th>
					<th>
						<?= HTMLHelper::_(
							'searchtools.sort',
							'COM_DEPOT_TABLE_HEAD_MANUFACTURER',
							'manufacturer',
							$listDirn,
							$listOrder
						); ?>
					</th>
					<th>
						<?= HTMLHelper::_(
							'searchtools.sort',
							'COM_DEPOT_TABLE_HEAD_STOCK',
							'stock_name',
							$listDirn,
							$listOrder
						); ?>
					</th>
					<th scope="col" class="w-1 text-center">
						<?= HTMLHelper::_(
							'searchtools.sort',
							'JGRID_HEADING_ID',
							'd.id',
							$listDirn,
							$listOrder
						); ?>
					</th>
				</tr>
			</thead>
			<tbody <?php if ($saveOrder): ?> class="js-draggable" data-url="<?= $saveOrderingUrl; ?>"
					data-direction="<?= strtolower($listDirn); ?>" data-nested="true" <?php endif; ?>>
				<?php foreach ($this->items as $i => $item):
					$ordering = ($listOrder == 'ordering');
					$canChange = true;
					?>
					<tr class="row<?= $i % 2; ?>" data-draggable-group="0" item-id="<?= $item->id; ?>">
						<th>
							<?= HTMLHelper::_('grid.id', $i, $item->id, false, 'cid', 'cb', $item->component_name); ?>
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
							<div class="small">
								<?= $item->ordering; ?>
							</div>
						</td>
						<td class="text-center">
							<?= HTMLHelper::_('jgrid.published', $item->state, $i, 'parts.', $canChange); ?>
						</td>
						<th>
							<div class="break-word">
								<a href="<?= Route::_('index.php?option=com_depot&task=part.edit&id=' .
									(int) $item->id) ?>" title="<?= Text::_('JACTION_EDIT') ?>">
									<?= $this->escape($item->component_name); ?>
								</a>
							</div>
							<div class="small break-word" role="button" title="<?= $item->package_description ?>">
								<?= $item->package_name; ?>
								<?php if (!empty($item->mounting_style)): ?>
									<div class="small break-word">
										<?= Text::_('COM_DEPOT_FIELD_PACKAGE_MOUNTING_STYLE_LABEL') . ': '; ?>
										<?= Text::alt(
											'COM_DEPOT_LIST_MOUNTING_STYLE_' . $item->mounting_style,
											Text::_('COM_DEPOT_LIST_MOUNTING_STYLE_UNKNOWN')
										); ?>
									</div>
								<?php endif; ?>
							</div>
						</th>
						<td>
							<?= $this->escape($item->quantity); ?>
						</td>
						<td>
							<?= "10^" . $item->quantity_exp; ?>
						</td>
						<td>
							<div class="break-word">
								<?= $this->escape($item->description); ?>
							</div>
						</td>
						<td>
							<a href="<?= Route::_('index.php?option=com_depot&task=manufacturer.edit&id=' .
								(int) $item->id) ?>" title="<?= Text::_('JACTION_EDIT') ?>">
								<?= $this->escape($item->manufacturer); ?>
							</a>
						</td>
						<td>
							<a class="break-word" href="<?= Route::_('index.php?option=com_depot&task=stock.edit&id=' .
								(int) $item->id) ?>" title="<?= Text::_('JACTION_EDIT') ?>">
								<?= $this->escape($item->stock_name); ?>
							</a>
							<div class="small">
								<?= $this->escape($item->owner); ?>
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