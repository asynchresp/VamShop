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

class TableBasedController extends ShippingAppController {
	var $uses = array('ShippingMethod');
	var $module_name = 'table_based';

	function settings ()
	{
	}

	function install()
	{

		$new_module = array();
		$new_module['ShippingMethod']['active'] = '1';
		$new_module['ShippingMethod']['name'] = Inflector::humanize($this->module_name);
		$new_module['ShippingMethod']['code'] = $this->module_name;
		$this->ShippingMethod->save($new_module);

		$this->Session->setFlash(__('Module Installed', true));
		$this->redirect('/shipping_methods/admin/');
	}

	function uninstall()
	{

		$module_id = $this->ShippingMethod->findByCode($this->module_name);

		$this->ShippingMethod->del($module_id['ShippingMethod']['id'], true);
			
		$this->Session->setFlash(__('Module Uninstalled', true));
		$this->redirect('/shipping_methods/admin/');
	}

	function calculate ()
	{
		App::import('Model', 'ShippingMethod');
		$this->ShippingMethod =& new ShippingMethod();
		$key_values = $this->ShippingMethod->findByCode($this->module_name);
	
		$data = array();
		if(!empty($key_values['ShippingMethodValue']))
			$data = array_combine(Set::extract($key_values, 'ShippingMethodValue.{n}.key'),
							  Set::extract($key_values, 'ShippingMethodValue.{n}.value'));	
		
		// Calculate the unit of measure depending on what's set in the database
		global $order;
		$units = 0;		
		switch($data['table_based_type'])
		{
			case 'products':
				$units = count($order['OrderProduct']);
			break;
			case 'total':
				$units = $order['Order']['total'];
			break;		
			case 'weight':
				foreach($order['OrderProduct'] AS $product)				
				{
					$units += ($product['weight'] * $product['quantity']);
				}
			break;				
		}
		
		// Loop through the rates value
$newline = 
'
';	
		$rates = str_replace($newline,'',$data['table_based_rates']);
		$rates = explode(',',$rates);
		
		$keyed_rates = array();
		foreach($rates AS $key => $value)
		{
			$temp_rates = explode(':',$value);
			$temp_rate_key = $temp_rates['0'];
			$keyed_rates[$temp_rate_key] = $temp_rates['1'];
		}
		
		$keyed_rates = array_reverse($keyed_rates,true);

		$shipping_price = 0;
		foreach($keyed_rates AS $key => $value)
		{
			if(($key < $units)&&($shipping_price == 0))
			{
				$shipping_price = $value;
			}
		}
		
		return $shipping_price;
	}
	
}

?>