<?php
/* -----------------------------------------------------------------------------------------
   VamCart - http://vamcart.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2011 VamSoft Ltd.
   License - http://vamcart.com/license.html
   ---------------------------------------------------------------------------------------*/

echo $html->script('jquery/jquery.min', array('inline' => false));
echo $admin->ShowPageHeaderStart($current_crumb, 'backup.png');
?>
<iframe src="<?php echo BASE; ?>/sxd/?sid=<?php echo session_name(); ?>&lang=<?php echo $lang ?>" width="586" height="462" frameborder="0" style="margin:0;"></iframe>
<?php
echo $admin->ShowPageHeaderEnd();