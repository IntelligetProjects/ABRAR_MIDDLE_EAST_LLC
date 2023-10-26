<span class="invoice-info-title" style="font-size:20px; font-weight: bold;background-color: <?php echo $color; ?>; color: #fff;">&nbsp;<?php echo get_material_request_id($purchase_order_info->id); ?>&nbsp;</span>
<br />
<span><?php echo lang("date") . ": " . format_to_date(isset($purchase_order_info->purchase_order_date)?$purchase_order_info->purchase_order_date:null, false); ?></span>
