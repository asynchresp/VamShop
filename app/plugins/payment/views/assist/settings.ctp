<?php
/* -----------------------------------------------------------------------------------------
   VamCart - http://vamcart.com
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

echo $form->inputs(array(
	'legend' => null,
	'assist.assist_shop_id' => array(
	'label' => __('Assist Shop ID', true),
	'type' => 'text',
	'value' => $data['PaymentMethodValue'][0]['value']
	)
	
));
?>