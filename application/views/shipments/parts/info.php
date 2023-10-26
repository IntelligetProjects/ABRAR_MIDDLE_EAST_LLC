<span class="invoice-info-title" style="font-size:20px; font-weight: bold;background-color: <?php echo $color; ?>; color: #fff;">&nbsp;<?php echo get_shipment_id($shipment_info->id); ?>&nbsp;</span>
<br />
<span><?php echo lang("shipment_date") . ": " . format_to_date($shipment_info->date, false); ?></span><br />