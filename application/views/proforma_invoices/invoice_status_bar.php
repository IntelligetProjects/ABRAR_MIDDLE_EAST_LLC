<div class="panel panel-default  p15 no-border m0">
    <span class="mr10"><?php echo $invoice_status_label; ?></span>
    <span class="mr10"><?php echo $delivery; ?></span>
    <span class="mr10"><?php echo $approval; ?></span>
    <?php
    $invoice_labels = "";
    if ($invoice_info->labels) {
        $labels = explode(",", $invoice_info->labels);
        foreach ($labels as $label) {
            $invoice_labels .= "<span class='mt0 label label-info large mr10'  title='$label'>" . $label . "</span>";
        }
    }
    echo "<span>" . $invoice_labels . " </span>";
    ?>

    <?php if ($invoice_info->project_id) { ?>
        <span class="ml15 hidden_label" ><?php echo lang("project") . ": " . anchor(get_uri("projects/view/" . $invoice_info->project_id), $invoice_info->project_title); ?></span>
    <?php } ?>

    <span class="ml15"><?php
        echo lang("client") . ": ";
        echo (anchor(get_uri("clients/view/" . $invoice_info->client_id), $invoice_info->company_name));
        ?>
    </span> 

    <!-- <span class="ml15 hidden_label"><?php
        // echo "Quotation Ref" . ": ";
        // echo anchor(get_uri("estimates/view/" . $invoice_info->quotation_id), "#" . $invoice_info->quotation_id);
        ?>
    </span> -->
    <?php if ($invoice_info->recurring_invoice_id) { ?>
        <span class="ml15">
            <?php
            echo lang("created_from") . ": ";
            echo anchor(get_uri("invoices/view/" . $invoice_info->recurring_invoice_id), get_invoice_id($invoice_info->recurring_invoice_id));
            ?>
        </span>
    <?php } ?>

    <?php if ($invoice_info->cancelled_at) { ?>
        <!-- <span class="ml15"><?php echo lang("cancelled_at") . ": " . format_to_relative_time($invoice_info->cancelled_at); ?></span> -->
    <?php } ?>

    <?php if ($invoice_info->cancelled_by) { ?>
       <!--  <span class="ml15"><?php echo lang("cancelled_by") . ": " . get_team_member_profile_link($invoice_info->cancelled_by, $invoice_info->cancelled_by_user); ?></span> -->
    <?php } ?>


    
    <!-- <span style="font-size: 18px; margin-top: -7px; margin-left: 5px; padding: 7px; margin-right: 40px" class="pull-right label label-<?= $class ?>"><?= number_format($revenue, 3) ?> OMR (<?= number_format($percentage, 2) ?>%)</span> 
    <span style="font-size: 18px; margin-top: -3px" class="pull-right">Revenue: </span> -->

</div>