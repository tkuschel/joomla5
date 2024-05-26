<?php
/**
 * @package		Depot.Administrator
 * @subpackage	com_depot
 * @author		Thomas Kuschel <thomas@kuschel.at>
 * @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
 * @license		GNU General Public License version 2 or later; see LICENSE.md
 * @since		0.0.3
 */
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('form.validate')
	->useScript('keepalive');

?>
<form action="<?= Route::_('index.php?option=com_depot&view=manufactuerer&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" name="adminForm" id="item-form" class="form-validate">
	<?= HtmlHelper::_('uitab.startTabSet', 'myTab', ['active' => 'details']); ?>
	<?= HTMLHelper::_(
		'uitab.addTab',
		'myTab',
		'details',
		empty($this->item->id) ? Text::_('COM_DEPOT_TAB_NEW_MANUFACTURER') :
		Text::_('COM_DEPOT_TAB_EDIT_MANUFACTURER')
	); ?>
	<fieldset id="fieldset-details" class="options-form">
		<legend>
			<?= Text::_('COM_DEPOT_LEGEND_MANUFACTURER_DETAILS') ?>
		</legend>
		<div class="row">
			<div class="col-12 col-lg-6">
				<?= $this->form->renderFieldset('details'); ?>
			</div>
			<div class="col-12 col-lg-6">
				<?= $this->form->getInput('description'); ?>
			</div>
		</div>
	</fieldset>
	<?= HtmlHelper::_('uitab.endTab'); ?>

	<?= HTMLHelper::_('uitab.addTab', 'myTab', 'statistics', Text::_('COM_DEPOT_TAB_STATISTICS')); ?>
	<fieldset class="options-form">
		<legend>
			<?= Text::_('COM_DEPOT_LEGEND_STATISTICS') ?>
		</legend>
		<div class="row">
			<div class="col-12 col-lg-9">
				<?= $this->form->renderFieldset('statistics'); ?>
			</div>
		</div>
	</fieldset>
	<?= HTMLHelper::_('uitab.endTab'); ?>

	<?= HtmlHelper::_('uitab.endTabSet'); ?>

	<input type="hidden" name="task" value="manufacturer.edit" />
	<?= HTMLHelper::_('form.token'); ?>
</form>