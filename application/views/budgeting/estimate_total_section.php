<table id="estimate-item-table" class="table display dataTable text-right strong table-responsive">     
    <tr>
        <td><?php echo lang("sub_total"); ?></td>
        <td style="width: 120px;"><?php echo to_currency($estimate_total_summary->estimate_subtotal, $estimate_total_summary->currency_symbol); ?></td>
        <td style="width: 100px;"> </td>
    </tr>


    <?php if ($estimate_total_summary->tax) { ?>
        <tr>
            <td><?php echo lang("VAT"); ?></td>
            <td><?php echo to_currency($estimate_total_summary->tax, $estimate_total_summary->currency_symbol); ?></td>
            <td></td>
        </tr>
    <?php } ?>
    <?php if ($estimate_total_summary->tax2) { ?>
        <tr>
            <td><?php echo $estimate_total_summary->tax_name2; ?></td>
            <td><?php echo to_currency($estimate_total_summary->tax2, $estimate_total_summary->currency_symbol); ?></td>
            <td></td>
        </tr>
    <?php } ?>


    <?php
    $discount_row = "<tr>
                        <td style='padding-top:13px;'>" . lang("discount") . "</td>
                        <td style='padding-top:13px;'>" . to_currency($estimate_total_summary->discount_total, $estimate_total_summary->currency_symbol) . "</td>";
                        
    $discount_change = "<td class='text-center option w100'>" . modal_anchor(get_uri("estimates/discount_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "data-post-estimate_id" => $estimate_id, "title" => lang('edit_discount'))) . "<span class='p20'>&nbsp;&nbsp;&nbsp;</span></td>
                    </tr>";

    if ($estimate_total_summary->estimate_subtotal && (!$estimate_total_summary->discount_total || ($estimate_total_summary->discount_total !== 0 && $estimate_total_summary->discount_type == "before_tax"))) {
        //when there is discount and type is before tax or no discount
        echo $discount_row;
        if($estimate_status == "draft") {
            echo $discount_change;
        }
    }
    ?>

    

    <?php
    if ($estimate_total_summary->discount_total && $estimate_total_summary->discount_type == "after_tax") {
        //when there is discount and type is after tax
        echo $discount_row;
        if($estimate_status == "draft") {
            echo $discount_change;
        }
    }
    ?>

    <tr>
        <td><?php echo lang("total"); ?></td>
        <td><?php echo to_currency($estimate_total_summary->estimate_total, $estimate_total_summary->currency_symbol); ?></td>
        <td></td>
    </tr>
</table>