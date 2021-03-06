<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_associations
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register('AssociationsHelper', JPATH_ADMINISTRATOR . '/components/com_associations/helpers/associations.php');

JHtml::_('jquery.framework');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$listOrder        = $this->escape($this->state->get('list.ordering'));
$listDirn         = $this->escape($this->state->get('list.direction'));
$canManageCheckin = JFactory::getUser()->authorise('core.manage', 'com_checkin');
$colSpan          = 5;
$iconStates       = array(
	-2 => 'icon-trash',
	0  => 'icon-unpublish',
	1  => 'icon-publish',
	2  => 'icon-archive',
);

JText::script('COM_ASSOCIATIONS_PURGE_CONFIRM_PROMPT');

JFactory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(pressbutton)
	{
		if (pressbutton == "associations.purge")
		{
			if (confirm(Joomla.JText._("COM_ASSOCIATIONS_PURGE_CONFIRM_PROMPT")))
			{
				Joomla.submitform(pressbutton);
			}
			else
			{
				return false;
			}
		}
		else
		{
			Joomla.submitform(pressbutton);
		}
	};
');
?>
<form action="<?php echo JRoute::_('index.php?option=com_associations&view=associations'); ?>" method="post" name="adminForm" id="adminForm">

<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>

<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this, 'options' => array('filterButton' => !is_null($this->itemType)))); ?>
	<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else : ?>
		<table class="table table-striped" id="associationsList">
			<thead>
				<tr>
					<th width="1%" class="nowrap center">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<?php if (!is_null($this->itemType->fields->state)) : ?>
						<th width="1%" class="center nowrap">
							<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'state', $listDirn, $listOrder); $colSpan++; ?>
						</th>
					<?php endif; ?>
					<th class="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_TITLE', 'title', $listDirn, $listOrder); ?>
					</th>
					<th width="15%" class="nowrap">
						<?php echo JText::_('JGRID_HEADING_LANGUAGE'); ?>
					</th>
					<th width="5%" class="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'COM_ASSOCIATIONS_HEADING_ASSOCIATION', 'association', $listDirn, $listOrder); ?>
					</th>
					<?php if (!is_null($this->itemType->fields->menutype)) : ?>
						<th width="10%" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'COM_ASSOCIATIONS_HEADING_MENUTYPE', 'menutype_title', $listDirn, $listOrder); $colSpan++; ?>
						</th>
					<?php endif; ?>
					<?php if (!is_null($this->itemType->fields->access)) : ?>
						<th width="5%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ACCESS', 'access_level', $listDirn, $listOrder); $colSpan++; ?>
						</th>
					<?php endif; ?>
					<th width="1%" class="nowrap hidden-phone">
						<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="<?php echo $colSpan; ?>">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->items as $i => $item) :
				$canEdit    = AssociationsHelper::allowEdit($this->itemType, $item);
				$canCheckin = $canManageCheckin || AssociationsHelper::allowCheckActions($this->itemType, $item);
				?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<?php if (!is_null($this->itemType->fields->state)) : ?>
						<td class="center">
							<span class="<?php echo $iconStates[$this->escape($item->state)]; ?>"></span>
						</td>
					<?php endif; ?>
					<td class="nowrap has-context">
						<?php if (isset($item->level)) : ?>
							<?php echo JLayoutHelper::render('joomla.html.treeprefix', array('level' => $item->level)); ?>
						<?php endif; ?>
						<?php if (isset($item->checked_out) && $item->checked_out) : ?>
							<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'associations.', $canCheckin); ?>
						<?php endif; ?>
						<?php if ($canEdit) : ?>
							<a href="<?php echo JRoute::_($this->editUri . '&id=' . (int) $item->id); ?>">
							<?php echo $this->escape($item->title); ?></a>
						<?php else : ?>
							<span title="<?php echo JText::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->alias)); ?>"><?php echo $this->escape($item->title); ?></span>
						<?php endif; ?>
						<?php if (!is_null($this->itemType->fields->alias)) : ?>
							<span class="small">
								<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>
							</span>
						<?php endif; ?>
						<?php if (!is_null($this->itemType->fields->catid)) : ?>
							<div class="small">
								<?php echo JText::_('JCATEGORY') . ": " . $this->escape($item->category_title); ?>
							</div>
						<?php endif; ?>
					</td>
					<td class="small">
						<?php echo $item->language_title ? JHtml::_('image', 'mod_languages/' . $item->language_image . '.gif', $item->language_title, array('title' => $item->language_title), true) . '&nbsp;' . $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
					</td>
					<td>
						<?php echo AssociationsHelper::getAssociationHtmlList($this->itemType, (int) $item->id, $item->language); ?>
					</td>
					<?php if (!is_null($this->itemType->fields->menutype)) : ?>
						<td class="small">
							<?php echo $this->escape($item->menutype_title); ?>
						</td>
					<?php endif; ?>
					<?php if (!is_null($this->itemType->fields->access)) : ?>
						<td class="small hidden-phone">
							<?php echo $this->escape($item->access_level); ?>
						</td>
					<?php endif; ?>
					<td class="hidden-phone">
						<?php echo $item->id; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

	<?php endif; ?>

		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="boxchecked" value="0"/>
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
