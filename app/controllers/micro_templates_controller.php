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

class MicroTemplatesController extends AppController {
	var $name = 'MicroTemplates';
	var $view = 'Theme';
	var $layout = 'admin';
	var $theme = 'vamshop';
	
	function admin_create_from_tag ()
	{
		$this->set('current_crumb',__('Enter an alias to use',true));
	}
	
		
	function admin_delete ($id)
	{
		$this->MicroTemplate->del($id);
		$this->Session->setFlash( __('Record deleted.',true));
		$this->redirect('/micro_templates/admin');
	}
	
	function admin_edit ($id = null)
	{
		$this->set('current_crumb', __('Micro Template', true));
		if(empty($this->data))
		{
			$this->data = $this->MicroTemplate->read(null,$id);
			
		}
		else
		{
			// Check if we pressed the cancel button
			if(isset($this->params['form']['cancel']))
			{
				$this->redirect('/micro_templates/admin/');
				die();
			}
			
			// Generate the alias to be safe
			$this->data['MicroTemplate']['alias'] = $this->generateAlias($this->data['MicroTemplate']['alias']);	
		
			$this->MicroTemplate->save($this->data);

			$this->Session->setFlash( __('Micro Template Saved.',true));
			
			if(isset($this->params['form']['apply']))
			{
				if($id == null)
					$id = $this->MicroTemplate->getLastInsertId();
				$this->redirect('/micro_templates/admin_edit/' . $id);
			}
			else
			{
				$this->redirect('/micro_templates/admin');
			}
		}
	}
	
	function admin_new ()
	{
		$this->redirect('/micro_templates/admin_edit/');	
	}
	
	function admin ($ajax = false)
	{
		$this->set('current_crumb', __('Micro Templates Listing', true));
		$this->set('micro_templates',$this->MicroTemplate->findAll());
	}
}
?>