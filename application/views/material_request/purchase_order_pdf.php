<div style=" margin: auto;">
    <?php
    $color = get_setting("invoice_color");
    if (!$color) {
        $color = "#2AA384";
    }
    $purchase_order_style = get_setting("invoice_style");
    $data = array(
        "supplier_info" => $supplier_info,
        "color" => $color,
        "purchase_order_info" => $purchase_order_info
    );

    if ($purchase_order_style === "style_2") {
        $this->load->view('material_request/purchase_order_parts/header_style_2.php', $data);
    } else {
        $this->load->view('material_request/purchase_order_parts/header_style_1.php', $data);
    }

    ?>
</div>

<br />

<table class="table-responsive" style="width: 100%; color: #444;">            
    <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
        <th style="width: 35%; border-right: 1px solid #eee;"> <?php echo lang("item"); ?> </th>
        <th style="text-align: center;  width: 15%; border-right: 1px solid #eee;"> <?php echo lang("quantity"); ?></th>
        <th style="text-align: right;  width: 15%; border-right: 1px solid #eee;"> <?php echo lang("rate"); ?></th>
        <th style="text-align: right;  width: 15%; border-right: 1px solid #eee;"> <?php echo lang("tax"); ?></th>
        <th style="text-align: right;  width: 20%; "> <?php echo lang("total"); ?></th>
    </tr>
    <?php
    foreach ($purchase_order_items as $item) {
        ?>
        <?php
        $item->tax_percentage = !empty($item->tax_percentage) ? $item->tax_percentage : 0;
        $item->tax_percentage2 = !empty($item->tax_percentage2) ? $item->tax_percentage2 : 0;
        $tax = $item->total*$item->tax_percentage*0.01 + $item->total*$item->tax_percentage2*0.01;
        ?>
        <tr style="background-color: #f4f4f4; ">
            <td style="width: 35%; border: 1px solid #fff; padding: 10px;"><?php echo $item->title; ?>
                <br />
                <span style="color: #888; font-size: 90%;"><?php echo nl2br($item->description); ?></span>
            </td>
            <td style="text-align: center; width: 15%; border: 1px solid #fff;"> <?php echo $item->quantity . " " . $item->unit_type; ?></td>
            <td style="text-align: right; width: 15%; border: 1px solid #fff;"> <?php echo to_currency($item->rate, "no"); ?></td>
            <td style="text-align: right; width: 15%; border: 1px solid #fff;"> <?php echo to_currency($tax, "no"); ?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff;"> <?php echo to_currency($item->total + $tax, "no"); ?></td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="4" style="text-align: right;"><?php echo lang("sub_total"); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
            <?php echo to_currency($purchase_order_total_summary->purchase_order_subtotal, $purchase_order_total_summary->currency_symbol); ?>
        </td>
    </tr>
    <?php if ($purchase_order_total_summary->tax) { ?>
        <tr>
            <td colspan="4" style="text-align: right;"><?php echo $purchase_order_total_summary->tax_name; ?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo to_currency($purchase_order_total_summary->tax, $purchase_order_total_summary->currency_symbol); ?>
            </td>
        </tr>
    <?php } ?>
    <?php if ($purchase_order_total_summary->tax2) { ?>
        <tr>
            <td colspan="4" style="text-align: right;"><?php echo $purchase_order_total_summary->tax_name2; ?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo to_currency($purchase_order_total_summary->tax2, $purchase_order_total_summary->currency_symbol); ?>
            </td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="4" style="text-align: right;"><?php echo lang("total"); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
            <?php echo to_currency($purchase_order_total_summary->purchase_order_total, $purchase_order_total_summary->currency_symbol); ?>
        </td>
    </tr>

    <?php if ($purchase_order_total_summary->total_paid) { ?>     
        <tr>
            <td colspan="4" style="text-align: right;"><?php echo lang("paid"); ?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo to_currency($purchase_order_total_summary->total_paid, $purchase_order_total_summary->currency_symbol); ?>
            </td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="4" style="text-align: right;"><?php echo lang("balance_due"); ?></td>
        <td style="text-align: right; width: 20%; background-color: <?php echo $color; ?>; color: #fff;">
            <?php echo to_currency($purchase_order_total_summary->balance_due, $purchase_order_total_summary->currency_symbol); ?>
        </td>
    </tr>
</table>
<?php if ($purchase_order_info->note) { ?>
    <br />
    <br />
    <div style="border-top: 2px solid #f2f2f2; color:#444; padding:0 0 20px 0;"><br /><?php echo nl2br($purchase_order_info->note); ?></div>
<?php } else { ?> <!-- use table to avoid extra spaces -->
    <br /><br /><table class="invoice-pdf-hidden-table" style="border-top: 2px solid #f2f2f2; margin: 0; padding: 0; display: block; width: 100%; height: 10px;"></table>
<?php } ?>
<span style="color:#444; line-height: 14px;">
    <?php echo get_setting("invoice_footer"); ?>
</span>

