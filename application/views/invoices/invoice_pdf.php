<style>
    tr {
        font-size: 13px;
    }

    td {
        line-height: 18px !important;
    }

    p {
        line-height: 1px !important;
    }
</style>
<div style=" margin: auto;">
    <?php
    $color = get_setting("invoice_color");
    if (!$color) {
        $color = "#2AA384";
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
                        <td colspan="4" style="text-align: right;">' . lang("discount") . '</td>
                        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">' . to_currency($invoice_total_summary->discount_total, $invoice_total_summary->currency_symbol) . '</td>
                    </tr>';

    $total_section_align = is_arabic_personal_language() ? "left" : "right";

    ?>
</div>

<br />

<table class="table-responsive" style="width: 100%; color: #444; margin-top:10px;">
    <?php if ($this->db->dbprefix == 'Tadqeeq') { ?>
        <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
            <th style="width: 45%; border-right: 1px solid #eee;"> <?php echo lang("item"); ?> </th>
            <th style="text-align: center;  width: 5%; border-right: 1px solid #eee;"> <?php echo lang("QTY"); ?></th>
            <th style="text-align: right;  width: 10%; border-right: 1px solid #eee;"> <?php echo  lang("rate"); ?></th>
            <th style="text-align: right;  width: 13%; border-right: 1px solid #eee;"> <?php echo lang("discount"); ?></th>
            <th style="text-align: right;  width: 5%; border-right: 1px solid #eee;"> <?php echo lang("tax"); ?></th>
            <th style="text-align: right;  width: 10%;  border-right: 1px solid #eee;"> <?php echo lang("total"); ?></th>
            <th style="text-align: right;  width: 15%; "> <?php echo lang("certificate_no"); ?></th>
        </tr>
    <?php } else { ?>
        <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
            <th style="width: 45%; border-right: 1px solid #eee;"> <?php echo lang("item"); ?> </th>
            <th style="text-align: center;  width: 10%; border-right: 1px solid #eee;"> <?php echo lang("QTY"); ?></th>
            <th style="text-align: right;  width: 11%; border-right: 1px solid #eee;"> <?php echo  $this->db->dbprefix == 'Integrated_Banners_' ? lang("price") : lang("rate"); ?></th>
            <th style="text-align: right;  width: 13%; border-right: 1px solid #eee;"> <?php echo lang("discount"); ?></th>
            <th style="text-align: right;  width: 10%; border-right: 1px solid #eee;"> <?php echo lang("tax"); ?></th>
            <th style="text-align: right;  width: 11%;  border-right: 1px solid #eee;"> <?php echo lang("total"); ?></th>
        </tr>
    <?php } ?>
    <?php
    foreach ($invoice_items as $item) {
        if ($item->discount_amount && $item->discount_amount_type == 'percentage') {
            $discount = $item->total * $item->discount_amount / 100;
        } else {
            $discount = $item->discount_amount ? $item->discount_amount : 0;
        }
    ?>
        <?php
        $item->tax_percentage = !empty($item->tax_percentage) ? $item->tax_percentage : 0;
        $item->tax_percentage2 = !empty($item->tax_percentage2) ? $item->tax_percentage2 : 0;
        // $tax = $item->total*$item->tax_percentage*0.01 + $item->total*$item->tax_percentage2*0.01;
        $sub = $item->total - $discount;
        $tax = $sub * $item->tax_percentage * 0.01 + $item->total * $item->tax_percentage2 * 0.01;
        ?>
        <?php if ($this->db->dbprefix == 'Tadqeeq') { ?>
            <tr style="background-color: #f4f4f4; ">
                <td style="width: 45%; border: 1px solid #fff; padding: 10px;"><?php echo $item->title; ?>
                    <br />
                    <span style="color: #888; font-size: 90%;"><?php echo nl2br($item->description); ?></span>
                </td>
                <td style="text-align: center; width: 5%; border: 1px solid #fff;"> <?php echo $item->quantity . " " . $item->unit_type; ?></td>
                <td style="text-align: right; width: 10%; border: 1px solid #fff;"> <?php echo to_currency($item->rate, "no"); ?></td>
                <td style="text-align: right; width: 13%; border: 1px solid #fff;"> <?php echo to_currency($discount, "no"); ?></td>
                <td style="text-align: right; width: 5%; border: 1px solid #fff;"> <?php echo to_currency($tax, "no"); ?></td>
                <td style="text-align: right; width: 10%; border: 1px solid #fff;"> <?php echo to_currency($item->total - $discount + $tax, "no"); ?></td>
                <td style="text-align: right; width: 15%; border: 1px solid #fff;"> <?php echo  $item->certificate_no ?></td>
            </tr>
        <?php } else { ?>
            <tr style="background-color: #f4f4f4; ">
                <td style="width: 45%; border: 1px solid #fff; padding: 10px;"><?php echo $item->title; ?>
                    <br />
                    <span style="color: #888; font-size: 90%;"><?php echo nl2br($item->description); ?></span>
                </td>
                <td style="text-align: center; width: 10%; border: 1px solid #fff;"> <?php echo $item->quantity . " " . $item->unit_type; ?></td>
                <td style="text-align: right; width: 11%; border: 1px solid #fff;"> <?php echo to_currency($item->rate, "no"); ?></td>
                <td style="text-align: right; width: 13%; border: 1px solid #fff;"> <?php echo to_currency($discount, "no"); ?></td>
                <td style="text-align: right; width: 10%; border: 1px solid #fff;"> <?php echo to_currency($tax, "no"); ?></td>
                <td style="text-align: right; width: 11%; border: 1px solid #fff;"> <?php echo to_currency($item->total - $discount + $tax, "no"); ?></td>
            </tr>
        <?php } ?>
    <?php } ?>


</table>
<table class="table-responsive" style="color: #444; margin-top:10px;">
    <tr>
        <td colspan="4" style="<?php echo 'text-align: '.$total_section_align.';'?>"><?php echo lang("sub_total"); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
            <?php echo to_currency($invoice_total_summary->invoice_subtotal, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>

    <?php if ($invoice_total_summary->item_discount) { ?>
        <tr>
            <td colspan="4" style="<?php echo 'text-align: '.$total_section_align.';'?>"><?php echo lang("discount"); ?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo to_currency($invoice_total_summary->item_discount, $invoice_total_summary->currency_symbol); ?>
            </td>
        </tr>
    <?php } ?>

    <?php if ($invoice_total_summary->tax_after_discount) { ?>
        <tr>
            <td colspan="4" style="<?php echo 'text-align: '.$total_section_align.';'?>"><?php echo $invoice_total_summary->tax_name; ?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo to_currency($invoice_total_summary->tax_after_discount, $invoice_total_summary->currency_symbol); ?>
            </td>
        </tr>
    <?php } ?>
    <?php if ($invoice_total_summary->tax2) { ?>
        <tr>
            <td colspan="4" style="<?php echo 'text-align: '.$total_section_align.';'?>"><?php echo $invoice_total_summary->tax_name2; ?></td>
            <td style="text-align: right; width: 25%; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo to_currency($invoice_total_summary->tax2, $invoice_total_summary->currency_symbol); ?>
            </td>
        </tr>

        <?php
        if ($invoice_total_summary->discount_total && $invoice_total_summary->discount_type == "before_tax") {
            echo $discount_row;
        }
        ?>
        <?php
        if ($invoice_total_summary->discount_total && $invoice_total_summary->discount_type == "after_tax") {
            echo $discount_row;
        }
        ?>
    <?php } ?>
    <tr>
        <td colspan="4" style="<?php echo 'text-align: '.$total_section_align.';'?>"><?php echo lang("grand_total"); ?></td>
        <td style="text-align: right; width: 20%; background-color: <?php echo $color; ?>; color: #fff;">
            <?php echo to_currency($invoice_total_summary->invoice_total, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>
    <tr>
        <td colspan="4" style="<?php echo 'text-align: '.$total_section_align.';'?>"><?php echo lang("paid"); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
            <?php echo to_currency($invoice_total_summary->total_paid ? $invoice_total_summary->total_paid : 0, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>
    <tr>
        <td colspan="4" style="<?php echo 'text-align: '.$total_section_align.';'?>"><?php echo lang("balance_due"); ?></td>
        <td style="text-align: right; width: 20%; background-color: <?php echo $color; ?>; color: #fff;">
            <?php echo to_currency($invoice_total_summary->balance_due, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>
</table>
<?php if ($invoice_info->note) { ?>
    <br />
    <br />
    <div style="border-top: 2px solid #f2f2f2; color:#444; padding:0 0 20px 0;"><br /><?php echo nl2br($invoice_info->note); ?></div>
<?php } else { ?> <!-- use table to avoid extra spaces -->
    <br /><br />
    <table class="invoice-pdf-hidden-table" style="border-top: 2px solid #f2f2f2; margin: 0; padding: 0; display: block; width: 100%; height: 10px;"></table>
<?php } ?>
<span style="color:#444; line-height: 0px;">

    <span style="color:#444; line-height: 0px;">
        <?php echo get_setting("invoice_footer"); ?>
    </span>
    <?php if ($this->db->dbprefix == 'Tadqeeq') { ?>
        <div id="stamp" style="display:none">
            <div class="sign" style="display:inline-block"><img style="width: 145px;
            /* position: absolute; */
            bottom: 200px;
            right: 290px;" src="/Tadqeeq/assets/images/sign.png"></div>
            <div class="stamp" style="display:inline-block"><img style="width: 300px;
            /* position: absolute; */
            bottom: 160px;
            right: 60px;" src="/Tadqeeq/assets/images/stamp.png"></div>
        </div>
    <?php } ?>


    <!-- payment  -->
    <?php if ($invoice_payments) { ?>

        <h4><?php echo lang("Invoice_payment_list"); ?></h4>
        <table class="table-responsive" style="margin-top:5px; width: 100%; color: #444;border: 1px solid <?php echo $color; ?>">
            <tr style="font-weight: bold; background-color: #f3f3f3; color: #000; padding:5px ">
                <th style="width: 25%; border: 1px solid <?php echo $color; ?>"> <?php echo lang("payment_date"); ?> </th>
                <th style="text-align: center;  width: 25%;border: 1px solid <?php echo $color; ?>"> <?php echo lang("payment_method"); ?></th>
                <th style="text-align: right;  width: 15%;border: 1px solid <?php echo $color; ?>"> <?php echo lang("note"); ?></th>
                <th style="text-align: right;  width: 15%;border: 1px solid <?php echo $color; ?>>"> <?php echo lang("status"); ?></th>
                <th style="text-align: right;  width: 20%;border: 1px solid <?php echo $color; ?> "> <?php echo lang("amount"); ?></th>
            </tr>

            <?php
            foreach ($invoice_payments as $payment) {
            ?>
                <?php
                if ($payment->payment_method_id == 1) {
                    $method = lang("cash");
                } else if ($payment->payment_method_id == 4) {
                    $method = lang("bank");
                } else if ($payment->payment_method_id == 5) {
                    $method = lang("cheque");
                }

                ?>
                <tr style="background-color: #fff; ">
                    <td style="text-align: center; width: 25%; border: 1px solid <?php echo $color; ?>;"> <?php echo $payment->payment_date ?></td>
                    <td style="text-align: center; width: 25%; border: 1px solid <?php echo $color; ?>;"> <?php echo  $method ?></td>
                    <td style="text-align: right; width: 15%; border: 1px solid <?php echo $color; ?>;"> <?php echo $payment->note ?></td>
                    <td style="text-align: right; width: 15%; border: 1px solid <?php echo $color; ?>;"> <?php echo $payment->status ?></td>
                    <td style="text-align: right; width: 20%; border: 1px solid <?php echo $color; ?>;"> <?php echo to_currency($payment->amount); ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>