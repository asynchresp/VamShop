<?php
/* -----------------------------------------------------------------------------------------
   VaM Cart
   http://vamcart.com
   http://vamcart.ru
   Copyright 2009 VaM Cart
   -----------------------------------------------------------------------------------------
   Portions Copyright:
   Copyright 2007 by Kevin Grandon (kevingrandon@hotmail.com)
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

class CurrenciesController extends AppController {
	var $name = 'Currencies';
	var $components = array('EventBase');

	function pick_currency ()
	{
		$this->Session->write('Customer.currency_id', $_POST['currency_picker']);
		
		// Get the currency data		
		App::import('Model', 'Currency');
		$this->Currency =& new Currency();		
		$currency_data = $this->Currency->find('first', array('conditions' => array('Currency.id' => $_POST['currency_picker'])));
		
		$this->Session->write('Customer.currency_code', $currency_data['Currency']['code']);
		
		$this->EventBase->ProcessEvent('SwitchCurrency');

		// Delete the cache.
		if(file_exists(CACHE . 'cake_vam_currency_output'))
		{
			Cache::delete('vam_currency_output');
		}
		$this->redirect($_SERVER['HTTP_REFERER']);
	}
		
	function admin_change_active_status ($id) 
	{
		$this->changeActiveStatus($id);	
	}
	
	function admin_set_as_default ($currency_id)
	{
		$this->setDefaultItem($currency_id);
	}

	function admin_delete ($currency_id)
	{
		// Get the currency and make sure it's not the default
		$this->Currency->id = $currency_id;
		$currency = $this->Currency->read();
		
		if($currency['Currency']['default'] == 1)
		{
			$this->Session->setFlash( __('Error: Could not delete default record.', true));		
		}
		else
		{
			// Ok, delete the currency
			$this->Currency->del($currency_id);	
			$this->Session->setFlash( __('Record deleted.', true));		
		}
		$this->redirect('/currencies/admin/');
	}
	
	
	function admin_edit ($currency_id = null)
	{
		$this->set('current_crumb', __('Currency Details', true));
		// If they pressed cancel
		if(isset($this->params['form']['cancel']))
		{
			$this->redirect('/currencies/admin/');
			die();
		}
		
		if(empty($this->data))
		{
			$this->data = $this->Currency->read(null,$currency_id);
		}
		else
		{
			$this->Currency->save($this->data);		
			$this->Session->setFlash(__('Record saved.', true));
			$this->redirect('/currencies/admin');
		}		
	}
	
	function admin_new() 
	{
		$this->redirect('/currencies/admin_edit/');
	}
	
	function admin_modify_selected() 	
	{
		$build_flash = "";
		foreach($this->params['data']['Currency']['modify'] AS $value)
		{
			// Make sure the id is valid
			if($value > 0)
			{
				$this->Currency->id = $value;
				$currency = $this->Currency->read();
		
				switch ($this->params['form']['multiaction']) 
				{
					case "delete":
						// Make sure it's not the default currency
						if($currency['Currency']['default'] == 0)
						{
						    $this->Currency->del($value);
							$build_flash .= __('Record deleted.', true) . ' (' . $currency['Currency']['name'] . ')<br />';									
						}
						else
						{	
							$build_flash .= __('Error: Could not delete default record.', true) . ' (' . $currency['Currency']['name'] . ')<br />';								
						}
					break;
					case "activate":
						$currency['Currency']['active'] = 1;
						$this->Currency->save($currency);
						$build_flash .= __('Record activated.', true) . ' (' . $currency['Currency']['name'] . ')<br />';								
					break;					
					case "deactivate":
						// Don't let them deactivate the default currency
						if($currency['Currency']['default'] == 1)
						{
							$build_flash .=  __('Error: Could not deactivate default record.', true) .' (' . $currency['Currency']['name'] . ')<br />';								
						}
						else
						{
							$currency['Currency']['active'] = 0;
							$this->Currency->save($currency);
							$build_flash .= __('Record deactivated.', true) . ' (' . $currency['Currency']['name'] . ')<br />';								
						}
					break;										
				}
			}
		}
		$this->Session->setFlash($build_flash);
		$this->redirect('/currencies/admin/');
	}	
	
	function admin ($ajax = false)
	{
		$this->set('current_crumb', __('Currencies Listing', true));
		$this->set('currency_data',$this->Currency->find('all', array('order' => array('Currency.name ASC'))));
	}	
	
}
?>