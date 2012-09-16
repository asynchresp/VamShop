<?php
/* -----------------------------------------------------------------------------------------
   VamCart - http://vamcart.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2011 VamSoft Ltd.
   License - http://vamcart.com/license.html
   ---------------------------------------------------------------------------------------*/

class MicroTemplatesController extends AppController {
	var $name = 'MicroTemplates';
	
	function admin_create_from_tag ()
	{
		$this->set('current_crumb',__('Enter an alias to use',true));
		$this->set('title_for_layout', __('Enter an alias to use', true));
	}
	
		
	function admin_delete ($id)
	{
		$this->MicroTemplate->delete($id);
		$this->Session->setFlash( __('Record deleted.',true));
		$this->redirect('/micro_templates/admin');
	}
	
	function admin_edit ($id = null)
	{
		$this->set('current_crumb', __('Micro Template', true));
		$this->set('title_for_layout', __('Micro Template', true));
		if(empty($this->data))
		{
			$this->data = $this->MicroTemplate->read(null,$id);
			
		}
		else
		{
			// Check if we pressed the cancel button
			if(isset($this->params['form']['cancelbutton']))
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
		$this->set('title_for_layout', __('Micro Templates Listing', true));
		$this->set('micro_templates',$this->MicroTemplate->find('all'));
	}
	function download_export()
	{
		$images = array();
		$z = new ZipArchive();
		$res = $z->open('./files/micro_templates.zip', ZIPARCHIVE::OVERWRITE);

		$micro_templates = $this->MicroTemplate->find('all');

		$output  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
		$output .= '<micro_templates>' . "\n";

		foreach ($micro_templates as $key => $template) {
			$output .= '  <micro_template alias="' . $template['MicroTemplate']['alias'] . '" tag_name="' . $template['MicroTemplate']['tag_name'] . '">' . "\n";
			$output .= '    <![CDATA[';
			$output .= $template['MicroTemplate']['template'];
			$output .= ']]>' . "\n";
			$output .= '  </micro_template>' . "\n";
		}

		$output .= '</micro_templates>' . "\n";
		$z->addFromString('micro_templates.xml', $output);

		$z->close();

		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="micro_templates.zip"');
		readfile('./files/micro_templates.zip');
		@unlink('./files/micro_templates.zip');
		die();
	}

}
?>