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

class UserTagBaseComponent extends Object 
{

	function beforeFilter ()
	{
		
	}

    function startup(&$controller)
	{

    }

	function call_user_tag ($params)
	{
		// Load the model
		App::import('Model', 'UserTag');
			$this->UserTag =& new UserTag();
	
		$tag = $this->UserTag->findByAlias($params['alias']);
		
		eval($tag['UserTag']['content']);
	}
	
	
}
?>