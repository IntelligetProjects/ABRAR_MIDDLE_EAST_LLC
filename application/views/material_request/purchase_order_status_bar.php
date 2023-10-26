<div class="panel panel-default  p15 no-border m0">
    <span class="mr10"><?php echo $purchase_order_status_label; ?></span>
    <span class="mr10"><?php echo $approval_status_label; ?></span>
    <span class="mr10"><?php echo $shipment_status_label; ?></span>
    <?php
    $purchase_order_labels = "";
    if ($purchase_order_info->labels) {
        $labels = explode(",", $purchase_order_info->labels);
        foreach ($labels as $label) {
            $purchase_order_labels .= "<span class='mt0 label label-info large mr10'  title='$label'>" . $label . "</span>";
        }
    }
    echo "<span>" . $purchase_order_labels . " </span>";
    ?>

    <?php if ($purchase_order_info->project_id) { ?>
        <span class="ml15"><?php echo lang("project") . ": " . anchor(get_uri("projects/view/" . $purchase_order_info->project_id), $purchase_order_info->project_title); ?></span>
    <?php } ?>

    <span class="ml15"><?php
    if($purchase_order_info->supplier_id!=0){
        echo lang("supplier") . ": ";
        echo (anchor(get_uri("suppliers/view/" . $purchase_order_info->supplier_id), $purchase_order_info->company_name));
    }?>
    </span> 

</div>