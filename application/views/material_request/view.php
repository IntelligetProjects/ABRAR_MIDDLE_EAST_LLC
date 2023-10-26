<div id="page-content" class="clearfix">
    <div style="max-width: 1000px; margin: auto;">
        <div class="page-title clearfix mt15">
            <h1><?php echo get_material_request_id($purchase_order_info->id); ?>
            </h1>
            <div class="title-button-group">
                <?php if ($approval_status == "not_approved") { ?>
                <?php echo modal_anchor(get_uri("material_request/item_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_item'), array("class" => "btn btn-default", "title" => lang('add_item'), "data-post-material_request_id" => $purchase_order_info->id)); ?>
                <?php echo modal_anchor(get_uri("material_request/items_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_items'), array("class" => "btn btn-default", "title" => lang('add_items'), "data-post-material_request_id" => $purchase_order_info->id)); ?>
                <?php } ?>
                <?php if ($can_add_payment) { ?>
                <?php //echo modal_anchor(get_uri("purchase_order_payments/payment_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_payment'), array("class" => "btn btn-default", "title" => lang('add_payment'), "data-post-material_request_id" => $purchase_order_info->id)); ?>
                <?php } ?>
                <?php if ($shipment_status !== "delivered" && $approval_status == 'approved') { ?>
                <?php echo modal_anchor(get_uri("shipments/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_shipment'), array("class" => "btn btn-default", "title" => lang('add_shipment'), "data-post-material_request_id" => $purchase_order_info->id)); ?>
                <?php } ?>
                <span class="dropdown inline-block mt10" style="margin-right:36px; margin-top: 10px;">
                    <button class="btn btn-info dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                        <i class='fa fa-cogs'></i> <?php echo lang('actions'); ?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <?php if ($approval_status == "not_approved" && $can_approve) { ?>
                            <li role="presentation"><?php echo js_anchor("<i class='fa fa-check-circle'></i> " . lang('approve'), array('title' => lang('approve'), 'id' => 'approved')); ?> </li>
                        <?php } ?>

                        <?php if ($approval_status == "approved") { ?>
                        <li role="presentation"><?php echo anchor(get_uri("material_request/download_pdf/" . $purchase_order_info->id), "<i class='fa fa-download'></i> " . lang('download_pdf'), array("title" => lang('download_pdf'))); ?> </li>
                        <li role="presentation"><?php echo anchor(get_uri("material_request/preview/" . $purchase_order_info->id . "/1"), "<i class='fa fa-search'></i> " . lang('material_request_preview'), array("title" => lang('material_request_preview'), "target" => "_blank")); ?> </li>
                        <li role="presentation"><?php echo js_anchor("<i class='fa fa-print'></i> " . lang('print'), array('title' => lang('print'), 'id' => 'print-purchase_order-btn')); ?> </li>
                        <?php } ?>
                        <?php if ($approval_status == "not_approved") { ?>
                    
                        <li role="presentation"><?php echo modal_anchor(get_uri("material_request/modal_form"), "<i class='fa fa-edit'></i> " . lang('edit_material_request'), array("title" => lang('edit_material_request'), "data-post-id" => $purchase_order_info->id, "role" => "menuitem", "tabindex" => "-1")); ?> </li>
                        <?php } ?>
                        
                        <?php if ($purchase_order_status == "draft") { ?>
                            <li role="presentation"><?php echo ajax_anchor(get_uri("material_request/update_purchase_order_status/" . $purchase_order_info->id . "/not_paid"), "<i class='fa fa-close'></i> " . lang('mark_as_not_paid'), array("data-reload-on-success" => "1")); ?> </li>
                        <?php } ?>

                    </ul>
                </span>
                
                
            </div>
        </div>

        <div id="purchase_order-status-bar">
            <?php $this->load->view("material_request/purchase_order_status_bar"); ?>
        </div>


        <div class="mt15">
            <div class="panel panel-default p15 b-t">
                <div class="clearfix p20">
                    <!-- small font size is required to generate the pdf, overwrite that for screen -->
                    <style type="text/css"> .invoice-meta {font-size: 100% !important;}</style>

                    <?php
                    $color = get_setting("invoice_color");
                    if (!$color) {
                        $color = "#2AA384";
                    }
                    $purchase_order_style = get_setting("invoice_style");
                    $data = array(
                        "supplier_info" => $supplier_info,
                        "color" => $color,
                        "purchase_order_info" => $purchase_order_info
                    );

                    if ($purchase_order_style === "style_2") {
                        $this->load->view('material_request/purchase_order_parts/header_style_2.php', $data);
                    } else {
                        $this->load->view('material_request/purchase_order_parts/header_style_1.php', $data);
                    }
                    ?>
                </div>

                <div class="table-responsive mt15 pl15 pr15">
                    <table id="purchase_order-item-table" class="display" width="100%">            
                    </table>
                </div>

                <div class="clearfix">
                   
                </div>

                <p class="b-t b-info pt10 m15"><?php echo nl2br($purchase_order_info->note); ?></p>

            </div>
        </div>

    
    </div>
</div>

<?php 
$status = array(array("id"=>"draft", "text"=>lang("draft")));
if($can_approve) {
    $status[] = array("id"=>"approved", "text"=>lang("approved"));
}
?>

<script type="text/javascript">
    $(document).ready(function () {
        var showInfo = false;
        if ("<?php echo $approval_status; ?>" == "not_approved") {
            showInfo = true;
        }
        $("#purchase_order-item-table").appTable({
            source: '<?php echo_uri("material_request/item_list_data/" . $purchase_order_info->id . "/") ?>',
            order: [[0, "asc"]],
            hideTools: true,
            displayLength: 100,
            stateSave: false,
            responsive: false,
            columns: [
                {visible: false, searchable: false},
                {title: '<?php echo lang("item") ?> ', "bSortable": false},
                {title: '<?php echo lang("quantity") ?>', "class": " qty", "bSortable": false},
                {visible: false, searchable: false},
                {visible: false, searchable: false},
                {visible: false, searchable: false},
                {visible: false, searchable: false},
                {visible: showInfo, searchable: showInfo, title: '<i class="fa fa-bars"></i>', "class": "text-center option w100", "bSortable": false}
            ],
            onInitComplete: function () {
                //apply sortable
                $("#purchase_order-item-table").find("tbody").attr("id", "purchase_order-item-table-sortable");
                var $selector = $("#purchase_order-item-table-sortable");
                
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
                Sortable.create($selector[0], {
                    animation: 150,
                    chosenClass: "sortable-chosen",
                    ghostClass: "sortable-ghost",
                    onUpdate: function (e) {
                        appLoader.show();
                        //prepare sort indexes 
                        var data = "";
                        $.each($selector.find(".item-row"), function (index, ele) {
                            if (data) {
                                data += ",";
                            }

                            data += $(ele).attr("data-id") + "-" + index;
                        });

                        //update sort indexes
                        $.ajax({
                            url: '<?php echo_uri("Invoices/update_item_sort_values") ?>',
                            type: "POST",
                            data: {sort_values: data},
                            success: function () {
                                appLoader.hide();
                            }
                        });
                    }
                });

            },
            onDeleteSuccess: function (result) {
                $("#purchase_order-total-section").html(result.purchase_order_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.material_request_id);
                }
            },
            onUndoSuccess: function (result) {
                $("#purchase_order-total-section").html(result.purchase_order_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.material_request_id);
                }
            }
        });

        $("#purchase_order-payment-table").appTable({
            source: '<?php echo_uri("purchase_order_payments/payment_list_data/" . $purchase_order_info->id . "/") ?>',
            order: [[0, "asc"]],
            columns: [
                {targets: [0], visible: false, searchable: false},
                {visible: false, searchable: false},
                {visible: false, searchable: false},
                {title: '<?php echo lang("payment_date") ?> ', "class": "w15p", "iDataSort": 1},
                {title: '<?php echo lang("payment_method") ?>', "class": "w15p"},
                {title: '<?php echo lang("note") ?>'},
                {title: '<?php echo lang("status") ?>'},
                {title: '<?php echo lang("amount") ?>', "class": "text-right w15p"},
                {visible: false, searchable: false},
                {visible: false, searchable: false},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
            onDeleteSuccess: function (result) {
                $("#purchase_order-total-section").html(result.purchase_order_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.material_request_id);
                }
            },
            onUndoSuccess: function (result) {
                $("#purchase_order-total-section").html(result.purchase_order_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.material_request_id);
                }
            }
        });

        $('body').on('click', '[data-act=update-status]', function () {
            $(this).editable({
                type: "select2",
                pk: 1,
                name: 'status',
                ajaxOptions: {
                    type: 'post',
                    dataType: 'json'
                },
                value: $(this).attr('data-value'),
                url: '<?php echo_uri("purchase_order_payments/update_status") ?>/0' + '/' + $(this).attr('data-id'),
                showbuttons: false,
                source: <?php echo json_encode($status) ?>,
                success: function (response, newValue) {
                    if (response.success) {
                        $("#purchase_order-payment-table").DataTable().ajax.reload();
                        location.reload();
                    }
                }
            });
            $(this).editable("show");
        });

        //modify the delete confirmation texts
        $("#confirmationModalTitle").html("<?php echo lang('cancel') . "?"; ?>");
        $("#confirmDeleteButton").html("<i class='fa fa-times'></i> <?php echo lang("cancel"); ?>");
    });

    updateInvoiceStatusBar = function (purchase_orderId) {
        $.ajax({
            url: "<?php echo get_uri("material_request/get_purchase_order_status_bar"); ?>/" + purchase_orderId,
            success: function (result) {
                if (result) {
                    $("#purchase_order-status-bar").html(result);
                }
            }
        });
    };

    $('#purchase_order-item-table').on('click', '[data-action=qty]', function(){
            var  url = $(this).attr('data-action-url'),
                id = $(this).attr('data-id'),
                qty = $(this).attr('data-qty');
           var  $table = $(this);

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {id: id},
                success: function (result) {
                    $('#purchase_order-item-table').DataTable().ajax.reload();
                    $("#purchase_order-total-section").html(result.purchase_order_total_view);
                    appAlert.success(result.message);
                }
            });

     });

    //print purchase_order
    $("#print-purchase_order-btn").click(function () {
        appLoader.show();

        $.ajax({
            url: "<?php echo get_uri('material_request/print_purchase_order/' . $purchase_order_info->id) ?>",
            dataType: 'json',
            success: function (result) {
                if (result.success) {
                    document.body.innerHTML = result.print_view; //add purchase_order's print view to the page

                    setTimeout(function () {
                        window.print();
                    }, 100);
                } else {
                    appAlert.error(result.message);
                }

                appLoader.hide();
            }
        });
    });

        //check 
    $("#approved").click(function () {
        appLoader.show();
        $.ajax({
            url: "<?php echo get_uri('material_request/update_approval_status/' . $purchase_order_info->id . '/approved') ?>",
            dataType: 'json',
            success: function (result) {
                if (result.success) {
                    location.reload();
                } else {
                    appAlert.error(result.message);
                }

                appLoader.hide();
            }
        });
    });

    //reload page after finishing print action
    window.onafterprint = function () {
        location.reload();
    };

</script>

