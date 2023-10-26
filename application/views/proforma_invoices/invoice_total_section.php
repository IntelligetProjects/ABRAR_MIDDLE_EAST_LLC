<table id="invoice-item-table" class="table display dataTable text-right strong table-responsive">
    <tr>
        <td><?php echo lang("sub_total"); ?></td>
        <td style="width: 120px;"><?php echo to_currency($invoice_total_summary->invoice_subtotal, $invoice_total_summary->currency_symbol); ?></td>
        <td style="width: 100px;"> </td>
    </tr>
   <?php
   $limit=1;$offset=0;
   $query = $this->db->get_where('taxes', array('id' => 1), $limit);
   $row = $query->row_array();
   $taxx=intval($row['percentage']);
   
//   var_dump($invoice_info);
if(isset($invoice_info)){
  if($invoice_info->discount_amount_type=="percentage"){
      $invoice_total_summary->discount_total=$invoice_info->discount_amount/100 * $invoice_total_summary->invoice_subtotal;
  }
}
   ?>

    <?php
    $discount_row = "<tr>
                        <td style='padding-top:13px;'>" . lang("discount") . "</td>
                        <td style='padding-top:13px;'>" . to_currency($invoice_total_summary->discount_total, $invoice_total_summary->currency_symbol) . "</td>";
    $discount_change = "
                        <td class='text-center option w100'>" . modal_anchor(get_uri("invoices/discount_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "data-post-invoice_id" => $invoice_id, "title" => lang('edit_discount'))) . "<span class='p20'>&nbsp;&nbsp;&nbsp;</span></td>
                    </tr>";



    /*if ($invoice_total_summary->invoice_subtotal && (!$invoice_total_summary->discount_total || ($invoice_total_summary->discount_total !== 0 && $invoice_total_summary->discount_type == "before_tax"))) {
        //when there is discount and type is before tax or no discount
        echo $discount_row;
        if($approval_status == "not_approved") {
            echo $discount_change;
        }
    }*/
    ?>


  <?php
    //if ($invoice_total_summary->discount_total) {
        //when there is discount and type is after tax
        echo $discount_row;
        if($approval_status == "not_approved") {
            echo $discount_change;
        }
    //}
    ?> 
    <tr>
        <td><?php echo lang("total"); ?></td>
        <!--<td style="width: 120px;"><?php echo to_currency($invoice_total_summary->total_after_discount, $invoice_total_summary->currency_symbol); ?></td>-->
        <td style="width: 120px;"><?php echo to_currency($invoice_total_summary->invoice_subtotal - $invoice_total_summary->discount_total, $invoice_total_summary->currency_symbol); ?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <?php //echo $invoice_total_summary->tax_name. "- ".$invoice_total_summary->tax_name; die('stop')?>
    <?php if ($invoice_total_summary->tax) { ?>
        <tr>
            <td><?php echo $invoice_total_summary->tax_name; ?></td>
            <!--<td><?php //echo to_currency($invoice_total_summary->tax, $invoice_total_summary->currency_symbol); ?></td>-->
            <?php $tot=$invoice_total_summary->invoice_subtotal - $invoice_total_summary->discount_total; 
            $invoice_total_summary->tax =$tot*$taxx/100 ;?>
            <td><?php echo to_currency($invoice_total_summary->tax, $invoice_total_summary->currency_symbol); ?></td>
            <td></td>
        </tr>
    <?php } ?>
  
    <?php if ($invoice_total_summary->tax2) { ?>
        <tr>
            <td><?php echo $invoice_total_summary->tax_name2; ?></td>
            <td><?php echo to_currency($invoice_total_summary->tax2, $invoice_total_summary->currency_symbol); ?></td>
            <td></td>
        </tr>
    <?php } ?>
        <tr>
        <!--<td><?php echo lang("total_after_VAT"); ?></td>-->
        <td><?php echo lang("Grand Total "); ?></td>
        <!--<td style="width: 120px;"><?php echo to_currency($invoice_total_summary->invoice_subtotal + $invoice_total_summary->tax2 + $invoice_total_summary->tax, $invoice_total_summary->currency_symbol); ?></td>-->
        <td style="width: 120px;"><?php echo to_currency($invoice_total_summary->invoice_subtotal - $invoice_total_summary->discount_total + $invoice_total_summary->tax2 + $invoice_total_summary->tax, $invoice_total_summary->currency_symbol); ?></td>
       <?php $gtotal=$invoice_total_summary->invoice_subtotal - $invoice_total_summary->discount_total + $invoice_total_summary->tax2 + $invoice_total_summary->tax ?>
        <td style="width: 100px;"> </td>
    </tr>
  

    
    <?php if ($invoice_total_summary->net_refund) { ?>
        <tr>
            <td><?php echo lang("paid") ?></td>
            <td><?php echo to_currency($invoice_total_summary->net_payemnt, $invoice_total_summary->currency_symbol); ?></td>
            <td></td>
        </tr>
        <tr>
            <td><?php echo lang("refunded") ?></td>
            <td><?php echo to_currency($invoice_total_summary->net_refund, $invoice_total_summary->currency_symbol); ?></td>
            <td></td>
        </tr>
    <?php } ?>
    <?php if ($invoice_total_summary->total_paid || $invoice_total_summary->net_refund) { ?>
        <tr>
            <?php if($invoice_uncollected_cheques != 0) {
                $paid = lang("paid")."*";
            } else { 
                $paid = lang("paid");
            } ?>
            <td><?php echo $paid ?></td>
            <td><?php echo to_currency($invoice_total_summary->total_paid, $invoice_total_summary->currency_symbol); ?></td>
            <td></td>
        </tr>
    <?php } ?>
    <tr>
        <td><?php echo lang("balance_due"); ?></td>
        <?php $invoice_total_summary->balance_due=$gtotal-$invoice_total_summary->total_paid ?>
        <td><?php echo to_currency($invoice_total_summary->balance_due, $invoice_total_summary->currency_symbol); ?></td>
        <td></td>
    </tr>
</table>