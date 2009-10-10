<?php
/* -----------------------------------------------------------------------------------------
   VaM Shop
   http://vamshop.com
   http://vamshop.ru
   Copyright 2009 VaM Shop
   -----------------------------------------------------------------------------------------
   Portions Copyright:
   Copyright 2007 by Kevin Grandon (kevingrandon@hotmail.com)
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

echo $form->create('Content', array('action' => '/contents/admin_modify_selected/', 'url' => '/contents/admin_modify_selected/'));

echo '<table class="contentTable">';

echo $html->tableHeaders(array(	 __('Title', true), __('Type', true), __('Template', true), __('Active', true), __('Show in menu', true), __('Default', true), __('Sort Order', true), __('Action', true), '<input type="checkbox" onclick="checkAll(this)" />'));

foreach ($content_data AS $content)
{

	// Set the name link
	if($content['Content']['count'] > 0)
	{
		// Link to child view
		$name_link = $html->link($html->image('admin/icons/folder.png') . ' ' . $content['ContentDescription']['name'], '/contents/admin/0/' . $content['Content']['id'], null, null, false);
	}
	else
	{
		// Link it to the edit screen
		$name_link = $html->link($content['ContentDescription']['name'], '/contents/admin_edit/' . $content['Content']['id']);
	}
	
	echo $admin->TableCells(
		  array(
				$name_link,
				$content['ContentType']['name'],
				$content['Template']['name'],
				$ajax->link(($content['Content']['active'] == 1?$html->image('admin/icons/true.png', array('alt' => __('True', true))):$html->image('admin/icons/false.png', array('alt' => __('False', true)))), 'null', $options = array('url' => '/contents/admin_change_active_status/' . $content['Content']['id'], 'update' => 'content'), null, false),
				$ajax->link(($content['Content']['show_in_menu'] == 1?$html->image('admin/icons/true.png', array('alt' => __('True', true))):$html->image('admin/icons/false.png', array('alt' => __('False', true)))), 'null', $options = array('url' => '/contents/admin_change_show_in_menu_status/' . $content['Content']['id'], 'update' => 'content'), null, false),
				$admin->DefaultButton($content['Content']),
				$admin->MoveButtons($content['Content'], $content_count),				
				$admin->ActionButton('edit','/contents/admin_edit/' . $content['Content']['id'],__('Edit', true)) . $admin->ActionButton('delete','/contents/admin_delete/' . $content['Content']['id'],__('Delete', true)),
				array($form->checkbox('modify][', array('value' => $content['Content']['id'])), array('align'=>'center'))
		   ));
		   	
}

// Display a link letting the user to go up one level
if(isset($parent_content))
{
	$parent_link = $html->link(__('Up One Level', true),'/contents/admin/' . $parent_content['Content']['parent_id']);
	echo '<tr><td colspan="9">' . $parent_link . '</td></tr>';	
}
echo '</table>';

echo $admin->ActionBar(array('activate'=>__('Activate',true),'deactivate'=>__('Deactivate',true),'show_in_menu'=>__('Show In Menu',true),'hide_from_menu'=>__('Hide From Menu',true),'delete'=>__('Delete',true)));
echo $form->end();
?>