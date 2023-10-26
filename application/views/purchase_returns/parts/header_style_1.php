<table style="color: #444; width: 100%;">
    <tr>
        <td style="width: 45%; vertical-align: top;">
            <?php $this->load->view('purchase_returns/parts/company_logo'); ?>
        </td>
        <td style="width: 20%;">
        </td>
        <td style="width: 35%; vertical-align: top; text-align: right"><?php
            $data = array(
                "supplier_info" => $supplier_info,
                "color" => $color,
                "purchase_return_info" => $purchase_return_info
            );
            $this->load->view('purchase_returns/parts/info', $data);
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
            $this->load->view('purchase_returns/parts/from', $data);
            ?>
        </td>
        <td></td>
        <td><?php
            $this->load->view('purchase_returns/parts/to', $data);
            ?>
        </td>
    </tr>
</table>