<table id="purchase_order-item-table" class="table display dataTable text-right strong table-responsive">

    <?php if ($total_summary->tax) { ?>
        <tr>
            <td><?php echo $total_summary->tax_name; ?></td>
            <td style="width: 120px;"><?php echo to_currency($total_summary->tax); ?></td>
        </tr>
    <?php } ?>

    <?php if ($total_summary->discount) { ?>
        <tr>
            <td><?php echo lang("discount"); ?></td>
            <td style="width: 120px;"><?php echo to_currency($total_summary->discount); ?></td>
        </tr>
    <?php } ?>

    <tr>
        <td><?php echo lang("total"); ?></td>
        <td style="width: 120px;"><?php echo to_currency($total_summary->total); ?></td>
    </tr>



</table>