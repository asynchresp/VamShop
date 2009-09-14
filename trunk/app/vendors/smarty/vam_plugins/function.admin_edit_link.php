<?php
/* -----------------------------------------------------------------------------------------
   VaM Shop
   http://vamshop.ru
   http://vamshop.com
   Copyright 2009 VaM Shop
   -----------------------------------------------------------------------------------------
   Portions Copyright:
   Copyright 2007 by Kevin Grandon (kevingrandon@hotmail.com)
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

function smarty_function_admin_edit_link($params, &$smarty)
{
	global $content;
	if((!empty($_SESSION['User'])) && ($content['Content']['parent_id'] != '-1'))
	{

		echo '
		<div class="admin_link admin_edit">
			<a href="' . BASE . '/contents/admin_edit/' . $content['Content']['id'] . '">'.__('Edit Content', true).'</a>
		</div>';
	}

}

function smarty_help_function_admin_edit_link() {
	?>
	<h3><?php echo __('What does this tag do?') ?></h3>
	<p><?php echo __('Creates a link to edit the current content page.') ?></p>
	<p><?php echo __('Links are only shown if you have an active admin session and the page is not a core page.') ?></p>
	<h3><?php echo __('How do I use it?') ?></h3>
	<p><?php echo __('Just insert the tag into your template like:') ?> <code>{admin_login_link}</code></p>
	<h3><?php echo __('What parameters does it take?') ?></h3>
	<ul>
		<li><em>(<?php echo __('None') ?>)</em></li>		
	</ul>
	<?php
}

function smarty_about_function_admin_edit_link() {
	?>
	<p><?php echo __('Author: Kevin Grandon &lt;kevingrandon@hotmail.com&gt;') ?></p>
	<p><?php echo __('Version:') ?> 0.1</p>
	<p>
	<?php echo __('Change History:') ?><br/>
	<?php echo __('None') ?>
	</p>
	<?php
}
?>