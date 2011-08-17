<?php
/* -----------------------------------------------------------------------------------------
   VamCart - http://vamcart.com
   -----------------------------------------------------------------------------------------
   Portions Copyright:
   Copyright 2007 by Kevin Grandon (kevingrandon@hotmail.com)
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

function smarty_function_shipping_content($params, $template)
{

	if(!isset($params['alias']))
		return;
		
	/*
	 *  Load some necessary vars
	 **/	
	global $config;
	
	App::import('Component', 'Smarty');
		$Smarty =& new SmartyComponent();

	$shipping_content = $Smarty->requestAction( '/shipping/' . $params['alias'] . '/before_process/');	

	$Smarty->display($shipping_content);
	
}

function smarty_help_function_shipping_content() {
	?>
	<h3><?php echo __('What does this tag do?') ?></h3>
	<p><?php echo __('Displays any necessary shipping text before sending the user off to process the order.') ?></p>
	<h3><?php echo __('How do I use it?') ?></h3>
	<p><?php echo __('This tag is called from the confirmation page like:') ?> <code>{shipping_content}</code></p>
	<h3><?php echo __('What parameters does it take?') ?></h3>
	<ul>
		<li><em>(<?php echo __('None') ?>)</em></li>
	</ul>
	<?php
}

function smarty_about_function_shipping_content() {
	?>
	<p><?php echo __('Author: Alexandr Menovchicov &lt;vam@kypi.ru&gt;') ?></p>
	<p><?php echo __('Version:') ?> 0.1</p>
	<p>
	<?php echo __('Change History:') ?><br/>
	<?php echo __('None') ?>
	</p>
	<?php
}
?>