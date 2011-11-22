<?php
/* -----------------------------------------------------------------------------------------
   VamCart - http://vamcart.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2011 VamSoft Ltd.
   License - http://vamcart.com/license.html
   ---------------------------------------------------------------------------------------*/

function default_template_content_listing()
{
$template = '<div>
<ul class="listing">
{foreach from=$content_list item=node}
	<li
	{if $node.alias == $content_alias}
		class="active"
	{/if}
	>
	<div><a href="{$node.url}"><img src="{$node.image}" alt="{$node.name}" 
	{if isset($thumbnail_width)}
	 width="{$thumbnail_width}"
	{/if}
	/></a></div>
	<div><a href="{$node.url}">{$node.name}</a></div></li>
{foreachelse}
	<li class="no_items">{lang}No Items Found{/lang}</li>
{/foreach}
</ul>
<div class="clear"></div>
</div>
';		

return $template;
}

function smarty_function_content_listing($params, $template)
{
	global $content;
	
	// Cache the output.
	$cache_name = 'vam_content_listing_output_' . $content['Content']['id'] .  (isset($params['template'])?'_'.$params['template']:'') .  (isset($params['parent'])?'_'.$params['parent']:'') . '_' . $_SESSION['Customer']['language_id'];
	$output = Cache::read($cache_name);
	if($output === false)
	{
		ob_start();
		
	global $config;
		
	// Load some necessary components & models
	App::import('Component', 'Smarty');
		$Smarty =& new SmartyComponent();
	App::import('Component', 'Session');
		$Session =& new SessionComponent();		

	App::import('Model', 'Content');
		$Content =& new Content();		
		$Content->unbindAll();	
		$Content->bindModel(array('hasOne' => array(
				'ContentDescription' => array(
                    'className' => 'ContentDescription',
					'conditions'   => 'language_id = '.$_SESSION['Customer']['language_id']
                ))));
		$Content->bindModel(array('belongsTo' => array(
				'ContentType' => array(
                    'className' => 'ContentType'
					))));			
		$Content->bindModel(array('hasOne' => array(
				'ContentImage' => array(
                    'className' => 'ContentImage',
                    'conditions'=>array('ContentImage.order' => '1')
					))));						
		$Content->bindModel(array('hasOne' => array(
				'ContentLink' => array(
                    'className' => 'ContentLink'
					))));		
		$Content->bindModel(array('hasOne' => array(
				'ContentProduct' => array(
                    'className' => 'ContentProduct'
					))));		
	// Make sure parent is valid, if it's not a number get the correct parent number
	if(!isset($params['parent']))
		$params['parent'] = 0;
	if(!is_numeric($params['parent']))
	{
		$get_content = $Content->findByAlias($params['parent']);
		$params['parent'] = $get_content['Content']['id'];
	}
	
	// Loop through the values in $params['type'] and set some more condiitons
	$allowed_types = array();
	if((!isset($params['type'])) || ($params['type'] == 'all'))
	{
		// Set the default conditions if all or nothing was passed
		App::import('Model', 'ContentType');
		$ContentType =& new ContentType();
		$allowed_types = $ContentType->find('list');	
	}
	else
	{
		$types = explode(',',$params['type']);
		
		foreach($types AS $type)
			$allowed_types[] =  $type;
	}

	if(!isset($params['limit']))
		$params['limit'] = null;
	
	$content_list_data_conditions = array('Content.parent_id' => $params['parent'],'Content.active' => '1','Content.show_in_menu' => '1');
	$Content->recursive = 2;
	$content_list_data = $Content->find('all', array('conditions' => $content_list_data_conditions, 'limit' => $params['limit'], 'order' => array('Content.order ASC')));
	

	// Loop through the content list and create a new array with only what the template needs
	$content_list = array();
	$count = 0;

	foreach($content_list_data AS $raw_data)
	{
		if(in_array(strtolower($raw_data['ContentType']['name']),$allowed_types))
		{
			$content_list[$count]['name']	= $raw_data['ContentDescription']['name'];
			$content_list[$count]['alias']	= $raw_data['Content']['alias'];	
			$content_list[$count]['price']	= $raw_data['ContentProduct']['price'];	
			$content_list[$count]['stock']	= $raw_data['ContentProduct']['stock'];	
			$content_list[$count]['model']	= $raw_data['ContentProduct']['model'];	
			$content_list[$count]['weight']	= $raw_data['ContentProduct']['weight'];	
		
		if (isset($raw_data['ContentImage']['image']) && file_exists(IMAGES . 'content/' . $raw_data['Content']['id'] . '/' . $raw_data['ContentImage']['image'])) {
			$content_list[$count]['icon']	= BASE . '/img/content/' . $raw_data['Content']['id'] . '/' . $raw_data['ContentImage']['image'];
		}
		
			if($raw_data['ContentImage']['image'] != "")
				$image_url = 'content/' . $raw_data['Content']['id'] . '/' . $raw_data['ContentImage']['image'];
			else 
				$image_url = 'noimage.png';
				
			if($config['GD_LIBRARY'] == 0)
				$content_list[$count]['image'] =  BASE . '/img/' . $image_url;
			else
				$content_list[$count]['image'] = BASE . '/images/thumb?src=/' . $image_url . '&w=' . $config['THUMBNAIL_SIZE'];
			
			if($raw_data['ContentType']['name'] == 'link')
			{
				$content_list[$count]['url'] = $raw_data['ContentLink']['url'];
			}
			else
			{
				$content_list[$count]['url']	= BASE . '/' . $raw_data['ContentType']['name'] . '/' . $raw_data['Content']['alias'] . $config['URL_EXTENSION'];
			}
			$count ++;
		}
	}
	$vars = $template->smarty->tpl_vars;
	$vars['content_list'] = $content_list;
	$vars['count'] = $count;

	if($config['GD_LIBRARY'] == 0)
		$vars['thumbnail_width'] = $config['THUMBNAIL_SIZE'];

	$display_template = $Smarty->load_template($params,'content_listing');	
	$Smarty->display($display_template,$vars);
	
		
		// Write the output to cache and echo them
		$output = @ob_get_contents();
		ob_end_clean();	
		Cache::write($cache_name, $output);		
	}
	echo $output;
}

function smarty_help_function_content_listing() {
	?>
	<h3><?php echo __('What does this tag do?') ?></h3>
	<p><?php echo __('Displays a list of content items depending on the parent of those items.') ?></p>
	<h3><?php echo __('How do I use it?') ?></h3>
	<p><?php echo __('Just insert the tag into your template like:') ?> <code>{content_listing}</code></p>
	<h3><?php echo __('What parameters does it take?') ?></h3>
	<ul>
		<li><em><?php echo __('(type)') ?></em> - <?php echo __('Type of content to display. Seperate multiple values with commas, example:') ?> {content_listing type='category,page'}. <?php echo __('Defaults to') ?> 'all'.</li>
		<li><em><?php echo __('(parent)') ?></em> - <?php echo __('The parent of the content items to be shown. Accepts an alias or id, defaults to 0.') ?></li>
		<li><em><?php echo __('(template)') ?></em> - <?php echo __('Useful if you want to override the default content listing template. Setting this will utilize the template that matches this alias.') ?></li>
		<li><em><?php echo __('(limit)') ?></em> - <?php echo __('Useful if you want to limit number of content items to show.') ?></li>
	</ul>
	<?php
}

function smarty_about_function_content_listing() {
}
?>