<table id="purchase_order-item-table" class="table display dataTable text-right strong table-responsive">
    <tr>
        <td><?php echo lang("total"); ?></td>
        <td style="width: 120px;"><?php echo to_currency($total_summary->total); ?></td>
    </tr>

    <?php if ($total_summary->tax) { ?>
        <tr>
            <td><?php echo $total_summary->tax_name; ?></td>
            <td style="width: 120px;"><?php echo to_currency($total_summary->tax); ?></td>
        </tr>
    <?php } ?>


</table>