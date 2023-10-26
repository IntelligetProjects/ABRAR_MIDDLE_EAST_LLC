<div style=" margin: auto;">
    <?php
    $color = get_setting("estimate_color");
    if (!$color) {
        $color = get_setting("invoice_color");
    }
    $style = get_setting("invoice_style");
    ?>
    <?php
    $data = array(
        "client_info" => $client_info,
        "color" => $color ? $color : "#2AA384",
        "estimate_info" => $estimate_info
    );
    if ($style === "style_2") {
        $this->load->view('estimates/estimate_parts/header_style_2.php', $data);
    } else {
        $this->load->view('estimates/estimate_parts/header_style_1.php', $data);
    }

    $discount_row = "<tr>
                        <td colspan='3' style='text-align: right;'>" . lang("discount") . "</td>
                        <td style='text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;'> " . to_currency($estimate_total_summary->discount_total, $estimate_total_summary->currency_symbol) . "</td>
                    </tr>";
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
    

    foreach ($estimate_items as $item) {
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
        <td colspan="4" style="text-align: right;"><?php echo lang("total"); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
            <?php echo to_currency($estimate_total_summary->estimate_subtotal); ?>
        </td>
    </tr>
      
    <?php if ($estimate_total_summary->tax) { ?>
        <tr>
            <td colspan="4" style="text-align: right;"><?php echo $estimate_total_summary->tax_name; ?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo to_currency($estimate_total_summary->tax); ?>
            </td>
        </tr>
    <?php } ?>
    <?php if ($estimate_total_summary->tax2) { ?>
        <tr>
            <td colspan="4" style="text-align: right;"><?php echo $estimate_total_summary->tax_name2; ?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo to_currency($estimate_total_summary->tax2); ?>
            </td>
        </tr>
    <?php } ?>
    <?php
    if ($estimate_total_summary->discount_total && $estimate_total_summary->discount_type == "after_tax") {
        echo $discount_row;
    }
    ?> 

    <?php
    if ($estimate_total_summary->discount_total && $estimate_total_summary->discount_type == "before_tax") { ?>
        <tr>
            <td colspan="4" style="text-align: right;"><?php echo lang("discount"); ?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;"><?php echo to_currency($estimate_total_summary->discount_total) ; ?></td>
        </tr>
    <?php } ?>
    
    <tr>
        <td colspan="4" style="text-align: right;"><?php echo lang("total"); ?></td>
        <td style="text-align: right; width: 20%; background-color: <?php echo $color; ?>; color: #fff;">
            <?php echo to_currency($estimate_total_summary->estimate_total); ?>
        </td>
    </tr>
</table>

<br />
<br />
<div style="border-top: 2px solid #f2f2f2; color:#444;">
    <div><?php echo nl2br($estimate_info->note); ?></div>
</div>

<div style="margin-top: 15px;">
    <?php echo get_setting("estimate_footer"); ?>
</div>

