<table id="purchase_order-item-table" class="table display dataTable text-right strong table-responsive">
    <tr>
        <td><?php echo lang("sub_total"); ?></td>
        <td style="width: 120px;"><?php echo to_currency($purchase_order_total_summary->purchase_order_subtotal, $purchase_order_total_summary->currency_symbol); ?></td>
        <td style="width: 100px;"> </td>
    </tr>

    <?php if ($purchase_order_total_summary->tax) { ?>
        <tr>
            <td><?php echo $purchase_order_total_summary->tax_name; ?></td>
            <td style="width: 120px;"><?php echo to_currency($purchase_order_total_summary->tax, $purchase_order_total_summary->currency_symbol); ?></td>
            <td style="width: 100px;"> </td>
        </tr>
    <?php } ?>

    <?php if ($purchase_order_total_summary->total_paid) { ?>
        <tr>
            <td><?php echo lang("paid"); ?></td>
            <td><?php echo to_currency($purchase_order_total_summary->total_paid, $purchase_order_total_summary->currency_symbol); ?></td>
            <td></td>
        </tr>
    <?php } ?>
    <tr>
        <td><?php echo lang("balance_due"); ?></td>
        <td><?php echo to_currency($purchase_order_total_summary->balance_due, $purchase_order_total_summary->currency_symbol); ?></td>
        <td></td>
    </tr>
</table>