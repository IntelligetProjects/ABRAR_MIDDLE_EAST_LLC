<div style=" margin: auto;">
    <?php
    
      if($invoice_info->discount_amount_type=="percentage"){
      $invoice_total_summary->discount_total=$invoice_info->discount_amount/100 * $invoice_total_summary->invoice_subtotal;
  }
  
    if($invoice_info->department == 8) {
        $color = get_setting("invoice_color".$invoice_info->department);
        if (!$color) {
            $color = "#000000";
        }
    } else {
        $color = get_setting("invoice_color");
        if (!$color) {
            $color = "#2AA384";
        }
    }


    $invoice_style = get_setting("invoice_style");
    $data = array(
        "client_info" => $client_info,
        "color" => $color,
        "invoice_info" => $invoice_info
    );

    if ($invoice_style === "style_2") {
        $this->load->view('invoices/invoice_parts/header_style_2.php', $data);
    } else {
        $this->load->view('invoices/invoice_parts/header_style_1.php', $data);
    }

    $discount_row = '<tr>
                        <td colspan="5" style="text-align: right;">' . lang("discount") ." ".$invoice_total_summary->currency_symbol. '</td>
                        <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;">' . number_format($invoice_total_summary->discount_total, 3, ".", ",") . '</td>
                    </tr>';

    ?>
    
    
     <?php
   $limit=1;$offset=0;
   $query = $this->db->get_where('taxes', array('id' => 1), $limit);
   $row = $query->row_array();
   $taxx=intval($row['percentage']);
   ?>
</div>

<br />

<table class="table-responsive" style="width: 100%; color: #444;">            
    <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
        <th style="text-align: center;  width: 10%; border-right: 1px solid #eee;"> <?php echo lang("sr"); ?></th>
        <th style="width: 35%; border-right: 1px solid #eee;"> <?php echo lang("item"); ?> </th>
        <th style="text-align: center;  width: 10%; border-right: 1px solid #eee;"> <?php echo lang("qty"); ?></th>
        <th style="text-align: right;  width: 15%; border-right: 1px solid #eee;"> <?php echo lang("unit")." ". lang("rate"); ?></th>
        <th style="text-align: right;  width: 15%; border-right: 1px solid #eee;"> <?php echo lang("tax"); ?></th>
        <th style="text-align: right;  width: 15%; "> <?php echo lang("total"); ?></th>
    </tr>
    <?php
    $sr = 1;
    foreach ($invoice_items as $item) {
        ?>
        <?php
        $item->tax_percentage = !empty($item->tax_percentage) ? $item->tax_percentage : 0;
        $item->tax_percentage2 = !empty($item->tax_percentage2) ? $item->tax_percentage2 : 0;
        $tax = $item->total*$item->tax_percentage*0.01 + $item->total*$item->tax_percentage2*0.01;
        ?>
        <tr style="background-color: #f4f4f4; ">
            <td style="text-align: center; width: 10%; border: 1px solid #fff;"> <?php echo $sr; ?></td>
            <td style="width: 35%; border: 1px solid #fff; padding: 10px;"><?php echo $item->title; ?>
                <br />
                <span style="color: #888; font-size: 90%;"><?php echo nl2br($item->description); ?></span>
            </td>
            <td style="text-align: center; width: 10%; border: 1px solid #fff;"> <?php echo $item->quantity . " " . $item->unit_type; ?></td>
            <td style="text-align: right; width: 15%; border: 1px solid #fff;"> <?php echo number_format($item->rate, 3, ".", ","); ?></td>
            <td style="text-align: right; width: 15%; border: 1px solid #fff;"> <?php echo number_format($tax, 3, ".", ","); ?></td>
            <td style="text-align: right; width: 15%; border: 1px solid #fff;"> <?php echo number_format($item->total + $tax, 3, ".", ","); ?></td>
        </tr>
        <?php $sr++; ?>
    <?php } ?>
    <tr>
        <td colspan="5" style="text-align: right;"><?php echo lang("sub_total"); ?> <?php echo $invoice_total_summary->currency_symbol; ?></td>
        <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;">
            <?php echo number_format($invoice_total_summary->invoice_subtotal, 3, ".", ","); ?>
        </td>
    </tr>
    <!-- <?php
    if ($invoice_total_summary->discount_total && $invoice_total_summary->discount_type == "before_tax") {
        echo $discount_row;
    }
    ?> -->    
    
     <?php
    if ($invoice_total_summary->discount_total) {
        echo $discount_row;
    }
    ?>
    
    <tr>
        <td colspan="5" style="text-align: right;"><?php echo lang("total"); ?> <?php echo $invoice_total_summary->currency_symbol; ?></td>
        <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;">
          <?php echo  number_format($invoice_total_summary->invoice_subtotal - $invoice_total_summary->discount_total , 3, ".", ",");?>
        </td>
    </tr>
    <?php if ($invoice_total_summary->tax) { ?>
        <tr>
            <td colspan="5" style="text-align: right;"><?php echo $invoice_total_summary->tax_name; ?> <?php echo $invoice_total_summary->currency_symbol; ?></td>
            <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php //echo number_format($invoice_total_summary->tax, 3, ".", ","); ?>
                <?php $tot=$invoice_total_summary->invoice_subtotal - $invoice_total_summary->discount_total; 
            $invoice_total_summary->tax =$tot*$taxx/100 ;?>
            <?php echo number_format($invoice_total_summary->tax, 3, ".", ","); ?>
            </td>
        </tr>
    <?php } ?>
    <?php if ($invoice_total_summary->tax2) { ?>
        <tr>
            <td colspan="5" style="text-align: right;"><?php echo $invoice_total_summary->tax_name2; ?></td>
            <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo number_format($invoice_total_summary->tax2, 3, ".", ","); ?>
            </td>
        </tr>
    <?php } ?>
    
     <tr>
            <td colspan="5" style="text-align: right;"><?php echo lang("Grand Total "); ?> <?php echo $invoice_total_summary->currency_symbol; ?></td>
            <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;">
            <?php echo  number_format($invoice_total_summary->invoice_subtotal - $invoice_total_summary->discount_total + $invoice_total_summary->tax2 + $invoice_total_summary->tax, 3, ".", ","); ?>
           <?php $gtotal=$invoice_total_summary->invoice_subtotal - $invoice_total_summary->discount_total + $invoice_total_summary->tax2 + $invoice_total_summary->tax ?>
            </td>
        </tr>
        
   
    <?php if ($invoice_total_summary->net_refund) { ?>     
        <tr>
            <td colspan="5" style="text-align: right;"><?php echo lang("paid"); ?> <?php echo $invoice_total_summary->currency_symbol; ?></td>
            <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo number_format($invoice_total_summary->net_payemnt, 3, ".", ","); ?>
            </td>
        </tr>
        <tr>
            <td colspan="5" style="text-align: right;"><?php echo lang("refunded"); ?> <?php echo $invoice_total_summary->currency_symbol; ?></td>
            <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo number_format($invoice_total_summary->net_refund, 3, ".", ","); ?>
            </td>
        </tr>
    <?php } ?>
    <?php if ($invoice_total_summary->total_paid || $invoice_total_summary->net_refund) { ?>     
        <tr>
            <?php if($invoice_uncollected_cheques != 0) {
                $paid = lang("total_paid")."*";
            } else { 
                $paid = lang("total_paid");
            } ?>
            <td colspan="5" style="text-align: right;"><?php echo $paid; ?> <?php echo $invoice_total_summary->currency_symbol; ?></td>
            <td style="text-align: right; width: 15%; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo number_format($invoice_total_summary->total_paid, 3, ".", ","); ?>
            </td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="5" style="text-align: right;"><?php echo lang("balance_due"); ?> <?php echo $invoice_total_summary->currency_symbol; ?></td>
        <td style="text-align: right; width: 15%; background-color: <?php echo $color; ?>; color: #fff;">
            <?php //echo number_format($invoice_total_summary->balance_due, 3, ".", ","); ?>
              <?php $invoice_total_summary->balance_due=$gtotal-$invoice_total_summary->total_paid ?>
              <?php echo number_format($invoice_total_summary->balance_due, 3, ".", ","); ?>
        </td>
    </tr>
</table>
<?php if($invoice_uncollected_cheques != 0) { ?>
        <br />
        <div style="color: red;text-align: right"><?php echo "*The Total Amount of Uncollected Cheques is".": ".to_currency($invoice_uncollected_cheques); ?></div>
    <?php } ?>
<?php if ($invoice_info->note) { ?>
    <br />
    <br />
    <div style="border-top: 2px solid #f2f2f2; color:#444; padding:0 0 20px 0;"><br />
    <div style="text-align: left; font-size: 120%; color: <?= $color ?>;"><?php echo lang("notes"); ?>:</div>
    <?php echo nl2br($invoice_info->note); ?>
    </div>
<?php } else { ?> <!-- use table to avoid extra spaces -->
    <br /><br /><table class="invoice-pdf-hidden-table" style="border-top: 2px solid #f2f2f2; margin: 0; padding: 0; display: block; width: 100%; height: 10px;"></table>
<?php } ?>
<span style="color:#444; line-height: 14px;">
    <?php echo get_setting("invoice_footer"); ?>
</span>

