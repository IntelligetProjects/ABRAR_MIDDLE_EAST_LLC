<table style="color: #444; width: 100%;">
    <tr class="invoice-preview-header-row">
        <?php $data = array(
                "client_info" => $client_info,
                "color" => $color,
                "invoice_info" => $invoice_info
            );?>
        <td style="width: 45%; vertical-align: top;">
            <?php $this->load->view('proforma_invoices/invoice_parts/company_logo', $data); ?>
        </td>
        <td class="hidden-invoice-preview-row" style="width: 20%;"></td>
        <td class="invoice-info-container invoice-header-style-one" style="width: 35%; vertical-align: top; text-align: left;"><?php
            $this->load->view('proforma_invoices/invoice_parts/invoice_info', $data);
            ?>
        </td>
    </tr>
    <tr>
        <td style="padding: 5px;"></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td><?php
            $this->load->view('proforma_invoices/invoice_parts/bill_from', $data);
            ?>
        </td>
        <td></td>
        <td><?php
            $this->load->view('proforma_invoices/invoice_parts/bill_to', $data);
            ?>
        </td>
    </tr>
</table>