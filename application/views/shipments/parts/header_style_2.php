<table style="color: #444; width: 100%;">
    <tr>
        <td style="width: 40%; vertical-align: top;"><?php
            $data = array(
                "client_info" => $client_info,
                "color" => $color,
                "delivery_note_info" => $delivery_note_info
            );
            $this->load->view('delivery_notes/delivery_note_parts/delivery_note_info', $data);
            ?>
        </td>
        <td style="width: 20%;">
        </td>
        <td style="width: 40%; vertical-align: top;">
            <?php $this->load->view('delivery_notes/delivery_note_parts/company_logo'); ?>
        </td>
    </tr>
    <tr>
        <td style="padding: 5px;"></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td><?php
            $this->load->view('delivery_notes/delivery_note_parts/delivery_note_to', $data);
            ?>
        </td>
        <td></td>
        <td><?php
            $this->load->view('delivery_notes/delivery_note_parts/delivery_note_from', $data);
            ?>
        </td>
    </tr>
</table>