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

echo '<table class="contentTable">';

echo $html->tableHeaders(array( __('Name', true), __('Default', true),  __('Order', true), __('Action', true)));

foreach ($order_status_data AS $order_status)
{
	echo $admin->TableCells(
		  array(
				$html->link($order_status['OrderStatusDescription']['name'], '/order_status/admin_edit/' . $order_status['OrderStatus']['id']),
				$admin->DefaultButton($order_status['OrderStatus']),
				$admin->MoveButtons($order_status['OrderStatus'], $order_status_count),
				$admin->ActionButton('edit','/order_status/admin_edit/' . $order_status['OrderStatus']['id'],__('Edit', true)) . $admin->ActionButton('delete','/order_status/admin_delete/' . $order_status['OrderStatus']['id'],__('Delete', true))
		   ));
		   	
}

echo '</table>';

echo $admin->CreateNewLink();

?>