<span class="invoice-info-title" style="font-size:20px; font-weight: bold;background-color: <?php echo $color; ?>; color: #fff;">&nbsp;<?php echo get_delivery_note_id($delivery_note_info->id); ?>&nbsp;</span>
<br />
<span><?php echo lang("date") . ": " . format_to_date($delivery_note_info->delivery_note_date, false); ?></span><br />