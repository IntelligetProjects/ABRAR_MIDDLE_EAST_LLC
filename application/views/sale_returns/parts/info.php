
<?php 
if($this->db->dbprefix=='Integrated_Banners_'){ ?>
<span class="invoice-info-title" style="font-size:20px; font-weight: bold;background-color: <?php echo $color; ?>; color: #fff;">&nbsp;<?php echo lang('credit_note').'#'.$sale_return_info->id; ?>&nbsp;</span>
<br />
<span><?php echo lang("credit_note_date") . ": " . format_to_date($sale_return_info->date, false); ?></span><br />
<?php }else{ ?>
    <span class="invoice-info-title" style="font-size:20px; font-weight: bold;background-color: <?php echo $color; ?>; color: #fff;">&nbsp;<?php echo get_sale_return_id($sale_return_info->id); ?>&nbsp;</span>
<br />
<span><?php echo lang("sale_return_date") . ": " . format_to_date($sale_return_info->date, false); ?></span><br />
<?php } ?>