<?php
/**
 * @package		Depot.Administrator
 * @subpackage	com_depot
 * @author		Thomas Kuschel <thomas@kuschel.at>
 * @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
 * @license		GNU General Public License version 2 or later; see LICENSE.md
 * @since		0.9.7
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

/** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('table.columns')
	->useScript('multiselect');

$user = $this->getCurrentUser();
$userID = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'p.ordering';

if ($saveOrder && !empty($this->items)) {
	$saveOrderingUrl = 'index.php?option=com_depot&task=packages.saveOrderAjax&tmpl=component';
	HTMLHelper::_('draggablelist.draggable');
}
?>
<form action="<?= Route::_('index.php?option=com_depot&view=packages'); ?>" method="post" name="adminForm"
	id="adminForm">

	<?= LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>

	<?php if (empty($this->items)): ?>
		<div class="alert alert-info">
			<span class="icon-info-circle" aria-hidden="true">
			</span>
			<?= Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else: ?>
		<table class="table table-striped table-hover" id="packageList">
			<caption class="visually-hidden">
				<?= Text::_('COM_DEPOT_PACKAGES_TABLE_CAPTION'); ?>,
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
						<?= HTMLHelper::_('searchtools.sort', '', 'p.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-sort'); ?>
					</th>
					<th scope="col" class="w-1 text-center">
						<?= HTMLHelper::_('searchtools.sort', 'JSTATUS', 'p.state', $listDirn, $listOrder); ?>
					</th>
					<th>
						<?= HTMLHelper::_(
							'searchtools.sort',
							'COM_DEPOT_TABLE_HEAD_PACKAGE_NAME',
							'p.name',
							$listDirn,
							$listOrder
						); ?>
					</th>
					<th>
						<?= HTMLHelper::_(
							'searchtools.sort',
							'COM_DEPOT_TABLE_HEAD_DESCRIPTION',
							'p.description',
							$listDirn,
							$listOrder
						); ?>
					</th>
					<th>
						<?= HTMLHelper::_(
							'searchtools.sort',
							'COM_DEPOT_TABLE_HEAD_MOUNTING_STYLE',
							'p.mounting_style',
							$listDirn,
							$listOrder
						); ?>
					</th>
					<th>
						<?= HTMLHelper::_(
							'searchtools.sort',
							'JGRID_HEADING_ID',
							'p.id',
							$listDirn,
							$listOrder
						); ?>
					</th>
				</tr>
			</thead>
			<tbody <?php if ($saveOrder):
				?> class="js-draggable" data-url="<?= $saveOrderingUrl; ?>"
					data-direction="<?= strtolower($listDirn); ?>" data-nested="true" <?php
			endif; ?>>
				<?php foreach ($this->items as $i => $item):
					$ordering = ($listOrder == 'ordering');
					$canChange = true;
					?>
					<tr class="row<?= $i % 2; ?>" data-draggable-group="0" item-id="<?= $item->id; ?>">
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
							<div class="small">
								<?= $item->ordering; ?>
							</div>
						</td>
						<td class="text-center">
							<?= HTMLHelper::_('jgrid.published', $item->state, $i, 'packages.', $canChange); ?>
						</td>
						<th>
							<a href="<?= Route::_('index.php?option=com_depot&task=package.edit&id=' .
								(int) $item->id) ?>" title="<?= Text::_('JACTION_EDIT') ?>">
								<?= $this->escape($item->name); ?>
							</a>
						</th>
						<td>
							<?= $this->escape($item->description); ?>
						</td>
						<td>
							<?= $this->escape($item->mounting_style); ?>
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