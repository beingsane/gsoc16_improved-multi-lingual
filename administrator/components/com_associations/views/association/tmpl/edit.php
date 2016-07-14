<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_associations
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$this->app->getDocument()->addScriptDeclaration("
	function iframeRef( frameRef ) {
	return frameRef.contentWindow
		? frameRef.contentWindow.document
		: frameRef.contentDocument
	}

	function triggerSave() {
		var inside = iframeRef( document.getElementById('target-association') );
		inside.getElementById('applyBtn').click();
		return false;
	}
");

$this->app->getDocument()->addStyleDeclaration('
	.sidebyside .outer-panel {
		float: left;
		width: 50%;
	}
	.sidebyside .left-panel {
		border-right: 1px solid #999999 !important;
	}
	.sidebyside .right-panel {
		border-left: 1px solid #999999 !important;
	}
	.sidebyside .inner-panel {
		padding: 10px;
	}
	.sidebyside iframe {
		width: 100%;
		height: 1500px;
		border: 0 !important;
}
');
?>

<form action="<?php echo JRoute::_('index.php?option=com_associations&view=association'); ?>"
 method="post" name="adminForm" id="item-form" class="form-validate">

<div class="sidebyside">
	<div class="outer-panel">
		<div class="inner-panel left-panel">
			<h3><?php echo JText::_('COM_ASSOCIATIONS_REFERENCE_ITEM'); ?></h3>
			<iframe src="<?php echo JRoute::_($this->link); ?>" 
				name="<?php echo JText::_('COM_ASSOCIATIONS_TITLE_MODAL'); ?>" height="100%" width="400px" scrolling="no">
			</iframe>
		</div>
	</div>
	<div class="outer-panel">
		<div class="inner-panel right-panel">
			<h3><?php echo JText::_('COM_ASSOCIATIONS_ASSOCIATED_ITEM'); ?></h3>
			<select id="ref-language" name="ref-language">
				<?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', false, true), 'value', 'text'); ?>
			</select>
			<button onclick="return triggerSave();" class="btn btn-small btn-success">
				<span class="icon-apply icon-white"></span>Save
			</button>
			<iframe id="target-association" name="target-association" 
				src="<?php echo JRoute::_($this->link); ?>" 
				name="<?php echo JText::_('COM_ASSOCIATIONS_TITLE_MODAL'); ?>" height="100%" width="400px" scrolling="no"></iframe>
		</div>
	</div>
</div>
</form>