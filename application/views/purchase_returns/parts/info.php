<span class="invoice-info-title" style="font-size:20px; font-weight: bold;background-color: <?php echo $color; ?>; color: #fff;">&nbsp;<?php echo get_purchase_return_id($purchase_return_info->id); ?>&nbsp;</span>
<br />
<span><?php echo lang("purchase_return_date") . ": " . format_to_date($purchase_return_info->date, false); ?></span><br />