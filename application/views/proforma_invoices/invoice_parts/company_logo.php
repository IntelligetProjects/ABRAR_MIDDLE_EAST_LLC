<?php
if($invoice_info->department == 8) {
	$invoice_logo = "invoice_logo".$invoice_info->department;

} else {
	$invoice_logo = "invoice_logo";
}
?>

<img style = "height: 85px" src="<?php echo get_file_from_setting($invoice_logo, get_setting('only_file_path')); ?>" />