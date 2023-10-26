<table style="color: #444; width: 100%;">

    <tr class="invoice-preview-header-row">
        <td style="width: 45%; vertical-align: top;">
            <?php $this->load->view('invoices/invoice_parts/company_logo'); ?>
        </td>
        <td class="hidden-invoice-preview-row" style="width: 20%;"></td>
        <td class="invoice-info-container invoice-header-style-one" style="width: 35%; vertical-align: top; text-align: right"><?php
            $data = array(
                "client_info" => $client_info,
                "color" => $color,
                "invoice_info" => $invoice_info
            );
            $this->load->view('invoices/invoice_parts/invoice_info', $data);
            ?>
        </td>
    </tr>
   
    <!-- <tr>
        <td style="padding: 5px;"></td>
        <td></td>
        <td></td>
    </tr> -->
    <?php if( $this->db->dbprefix=='Tadqeeq'){ ?>
  <div class="boxx" style="background: #f6f7fb;
    padding: 10px;
    text-align: center;
    /* margin-bottom: 10px; */
    /* font-size: 14px; */
    font-weight: 600;
    display: flow-root;">
    <!-- <div style="float:left"><?php echo get_setting("company_name"); ?></div> -->
    <!-- <div style="float:right">TAX INVOICE</div> -->
    TAX INVOICE
</div>
<?php } ?>
    <tr>
        <td><?php
            $this->load->view('invoices/invoice_parts/bill_from', $data);
            ?>
        </td>
        <td></td>
        <td><?php
            $this->load->view('invoices/invoice_parts/bill_to', $data);
            ?>
        </td>
    </tr>
</table>