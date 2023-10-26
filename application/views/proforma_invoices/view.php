
<div id="page-content" class="clearfix my_document_data">
    <div style="max-width: 1000px; margin: auto;">
        <div class="page-title clearfix mt15">
            <h1><?php echo get_advance_invoice_id($invoice_info->id); 
                if ($invoice_info->revised) {
                    echo " (Rev #" . $invoice_info->revised . ")"; 
                }

            ?>
                <?php
                if ($invoice_info->recurring) {
                    $recurring_status_class = "text-primary";
                    if ($invoice_info->no_of_cycles_completed > 0 && $invoice_info->no_of_cycles_completed == $invoice_info->no_of_cycles) {
                        $recurring_status_class = "text-danger";
                    }
                    ?>
                    <span class="label ml15 b-a "><span class="<?php echo $recurring_status_class; ?>"><?php echo lang('recurring'); ?></span></span>
                <?php } ?>
            </h1>
            <div class="title-button-group">
                
                <?php if ($delivery_status !== "sold" && $invoice_status !== "cancelled" && $can_add_do && $approval_status == 'approved') { ?>
                <!-- <?php echo modal_anchor(get_uri("proforma_invoices/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('issue_invoice'), array("class" => "btn btn-default", "title" => lang('issue_invoice'), "data-post-invoice_id" => $invoice_info->id)); ?> -->
                <?php echo modal_anchor(get_uri("proforma_invoices/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('new'), array("class" => "btn btn-default", "title" => lang('advance_payment_invoice'), "data-post-invoice_id" => $invoice_info->id)); ?>
                <?php } ?>

                <!-- download pdf -->
                <?php if ($approval_status == "approved") { 
                    echo anchor(get_uri("proforma_invoices/download_pdf/" . $invoice_info->id), "<i class='fa fa-download'></i> " . lang('download_pdf'), array("title" => lang('download_pdf'), "class"=>"btn btn-success")); 
                } ?>

                <!-- not approved -->
               <!--  <?php if ($can_approve && $approval_status == "approved") {
                    echo ajax_anchor(get_uri("proforma_invoices/update_approval_status/" . $invoice_info->id . "/not_approved"), "<i class='fa fa-close'></i> " . lang('draft'), array("data-reload-on-success" => "1", "class"=>"btn btn-danger")); 
                } ?> -->

                <?php if ($can_approve && $approval_status == "approved" && $invoice_status !== "cancelled") {
                     echo js_anchor("<i class='fa fa-check-close'></i> " . lang('draft'), array('title' => lang('darft'), 'id' => 'not_approved', "class"=>"btn btn-success")); 
                } ?>


                <!-- approve --> 
                 <?php if ($approval_status == "not_approved" && $can_approve) { ?>
                           <?php echo js_anchor("<i class='fa fa-check-circle'></i> " . lang('approve'), array('title' => lang('approve'), 'id' => 'approved', "class"=>"btn btn-success")); ?> 
                <?php } ?>



                <span class="dropdown inline-block mt10" style="margin-right:36px; margin-top: 10px;">
                    <button class="btn btn-info dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                        <i class='fa fa-cogs'></i> <?php echo lang('actions'); ?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                       <!--  <?php if ($approval_status == "not_approved" && $can_approve) { ?>
                            <li role="presentation"><?php echo js_anchor("<i class='fa fa-check-circle'></i> " . lang('approved'), array('title' => lang('approve'), 'id' => 'approved')); ?> </li>
                        <?php } ?> -->

                        <?php if ($approval_status == "approved") { ?>
                            <!-- <?php //echo modal_anchor(get_uri("proforma_invoices/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('create_invoice'), array("class" => "btn btn-default mb0", "title" => lang('create_invoice'))); ?> -->
                            <?php if ($invoice_status !== "cancelled") { ?>
                            <!-- <li role="presentation"><?php echo modal_anchor(get_uri("proforma_invoices/send_invoice_modal_form/" . $invoice_info->id), "<i class='fa fa-envelope-o'></i> " . lang('email_invoice_to_client'), array("title" => lang('email_invoice_to_client'), "data-post-id" => $invoice_info->id, "role" => "menuitem", "tabindex" => "-1")); ?> </li> -->
                            <?php } ?>
                            <!-- <li role="presentation"><?php echo anchor(get_uri("proforma_invoices/download_pdf/" . $invoice_info->id), "<i class='fa fa-download'></i> " . lang('download_pdf'), array("title" => lang('download_pdf'))); ?> </li> -->
                            <!-- <li role="presentation"><?php echo anchor(get_uri("proforma_invoices/preview/" . $invoice_info->id . "/1"), "<i class='fa fa-search'></i> " . lang('invoice_preview'), array("title" => lang('invoice_preview'), "target" => "_blank")); ?> </li>
                            <li role="presentation"><?php echo js_anchor("<i class='fa fa-print'></i> " . lang('print_invoice'), array('title' => lang('print_invoice'), 'id' => 'print-invoice-btn')); ?> </li> -->
                            <?php if ($can_approve) { ?>
                            <!-- <li role="presentation" class="divider"></li> -->
                           <!--  <li role="presentation"><?php echo ajax_anchor(get_uri("proforma_invoices/update_approval_status/" . $invoice_info->id . "/not_approved"), "<i class='fa fa-close'></i> " . lang('disapprove'), array("data-reload-on-success" => "1")); ?> </li> -->
                            <?php } ?>
                        <?php } ?>

                            <?php if ($invoice_status !== "cancelled" && $approval_status == "not_approved") { ?>
                            <li role="presentation"><?php echo modal_anchor(get_uri("proforma_invoices/modal_form"), "<i class='fa fa-edit'></i> " . lang('edit_invoice'), array("title" => lang('edit_invoice'), "data-post-id" => $invoice_info->id, "role" => "menuitem", "tabindex" => "-1")); ?> </li>
                            <?php } ?>

                            <?php if ($invoice_status !== "cancelled" && $approval_status == "approved" && $delivery_status !== "sold" && $can_approve) { ?>
                            <li role="presentation"><?php echo js_anchor("<i class='fa fa-close'></i> " . lang('mark_invoice_as_cancelled'), array('title' => lang('mark_invoice_as_cancelled'), "data-action-url" => get_uri("proforma_invoices/update_invoice_status/" . $invoice_info->id . "/cancelled"), "data-action" => "delete-confirmation", "data-reload-on-success" => "1")); ?> </li>
                            <?php } ?>
                            
                            <?php if ($invoice_status == "draft" && $invoice_status !== "cancelled" && $approval_status == "not_approved") { ?>
                                <li role="presentation"><?php echo ajax_anchor(get_uri("proforma_invoices/update_invoice_status/" . $invoice_info->id . "/not_paid"), "<i class='fa fa-check'></i> " . lang('mark_invoice_as_not_paid'), array("data-reload-on-success" => "1")); ?> </li>
                            <?php } else if (($invoice_status == "not_paid" || $invoice_status == "overdue" || $invoice_status == "partially_paid") && $approval_status == "not_approved") { ?>
                               <!--  <li role="presentation"><?php echo js_anchor("<i class='fa fa-close'></i> " . lang('mark_invoice_as_cancelled'), array('title' => lang('mark_invoice_as_cancelled'), "data-action-url" => get_uri("proforma_invoices/update_invoice_status/" . $invoice_info->id . "/cancelled"), "data-action" => "delete-confirmation", "data-reload-on-success" => "1")); ?> </li> -->
                            <?php } ?>

                      <!--   <li role="presentation"><?php echo modal_anchor(get_uri("proforma_invoices/modal_form"), "<i class='fa fa-copy'></i> " . lang('clone_invoice'), array("data-post-is_clone" => true, "data-post-id" => $invoice_info->id, "title" => lang('clone_invoice'))); ?></li> -->
                        <?php if ($this->login_user->is_admin || get_array_value($this->login_user->permissions,'can_create_purchase_orders') == "1") { ?>
                           <!--  <li role="presentation"><?php echo modal_anchor(get_uri("purchase_orders/modal_form/"), "<i class='fa fa-refresh'></i> " . lang('purchase_order'), array("title" => lang("purchase_order"), "data-post-invoice_id" => $invoice_info->id)); ?> </li> -->
                        <?php } ?>

                    </ul>
                </span>
                  
            </div>
        </div>

        <div id="invoice-status-bar">
            <?php $this->load->view("proforma_invoices/invoice_status_bar"); ?>
        </div>

        <?php
        if ($invoice_info->recurring) {
            $this->load->view("proforma_invoices/invoice_recurring_info_bar");
        }
        ?>

        <div class="mt15">
            <div class="panel panel-default p15 b-t">
                <div class="clearfix p20 header_info_table">
                    <!-- small font size is required to generate the pdf, overwrite that for screen -->
                    <style type="text/css"> .invoice-meta {font-size: 100% !important;}</style>

                    <?php
                    $color = get_setting("invoice_color");
                    if (!$color) {
                        $color = "#2AA384";
                    }
                    $invoice_style = get_setting("invoice_style");
                    $data = array(
                        "client_info" => $client_info,
                        "color" => $color,
                        "invoice_info" => $invoice_info
                    );

                    if ($invoice_style === "style_2") {
                        $this->load->view('proforma_invoices/invoice_parts/header_style_2.php', $data);
                    } else {
                        $this->load->view('proforma_invoices/invoice_parts/header_style_1.php', $data);
                    }
                    ?>
                </div>

                <div>
                    <?php if ($approval_status == "not_approved" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions,'can_create_items') == "1")) { 
                             echo modal_anchor(get_uri("proforma_invoices/item_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_item'), array("class" => "btn btn-default", "title" => lang('add_item'), "data-post-invoice_id" => $invoice_info->id)); 
                     } ?>
                    <?php if ($approval_status == "not_approved") { 
                         echo modal_anchor(get_uri("proforma_invoices/items_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_items'), array("class" => "btn btn-default", "title" => lang('add_items'), "data-post-invoice_id" => $invoice_info->id)); 
                    } ?>
                </div>

                <div class="table-responsive mt15 pl15 pr15">
                    <table id="invoice-item-table" class="display" width="100%">            
                    </table>
                </div>

                <div class="clearfix">
                    <div class="pull-right pr15" id="invoice-total-section">
                        <?php $this->load->view("proforma_invoices/invoice_total_section", array("invoice_id" => $invoice_info->id)); ?>
                    <?php //die('hiii') ?>
                    <?php if($invoice_uncollected_cheques != 0) { ?>
                        <div class="m15" style="color: red"><?php echo "*The Total Amount of Uncollected Cheques is".": ".to_currency($invoice_uncollected_cheques); ?></div>
                    <?php } ?>
                    </div>
                </div>

                <div style="margin: 15px; padding-top: 10px">
                    <p style="display: inline-block; font-size: 20px; font-weight: bold">Notes</p>
                    <button style="margin-left: 15px" class="btn btn-primary" id="note_button">Edit Note</button>
                </div>

                
                <div id="notes" class="m15"><?php echo ($invoice_info->note); ?></div>


                <div id="edit_note">
                    <?php echo form_open(get_uri("proforma_invoices/save_note"), array("id" => "edit-note-form", "class" => "general-form", "role" => "form")); ?>
                    <input type="hidden" name="invoice_note_id" value="<?php echo $invoice_info->id; ?>" />
                    <textarea id="note_textarea" name="note_textarea" style="height: 280px" class="form-control">
                        <?php echo nl2br($invoice_info->note) ?>
                    </textarea>
                    <div style="width: 100%; text-align: right;">
                        <button type="submit" style="margin: 15px; width: 100px" id="save" class="btn btn-success">save</button>
                    </div>
                    <?php echo form_close(); ?>
                </div>

            </div>
        </div>


        <?php if ($can_add_payment) { ?>
            <?php if ($invoice_info->recurring) { ?>
                <ul id="invoice-view-tabs" data-toggle="ajax-tab" class="nav nav-tabs" role="tablist">
                    <li><a  role="presentation" href="#" data-target="#invoice-payments"> <?php echo lang('payments'); ?></a></li>
                    <li><a  role="presentation" href="<?php echo_uri("proforma_invoices/sub_invoices/" . $invoice_info->id); ?>" data-target="#sub-invoices"> <?php echo lang('sub_invoices'); ?></a></li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active" id="invoice-payments">
                        <div class="panel panel-default">
                            <div class="tab-title clearfix">
                                <h4> <?php echo lang('invoice_payment_list'); ?></h4>
                            </div>
                            <div class="table-responsive">
                                <table id="invoice-payment-table" class="display" cellspacing="0" width="100%">            
                                </table>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="sub-invoices"></div>
                </div>
            <?php } else { ?>

                <div class="panel panel-default">
                    <div class="tab-title clearfix">
                        <h4> <?php echo lang('invoice_payment_list'); ?></h4>
                         <div class="title-button-group">
                            <?php echo modal_anchor(get_uri("proforma_invoice_payments/payment_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_payment'), array("class" => "btn btn-default", "title" => lang('add_payment'), "data-post-invoice_id" => $invoice_info->id)); ?> 
                        </div>                    
                    </div>
                    <div class="table-responsive">
                        <table id="invoice-payment-table" class="display" cellspacing="0" width="100%">            
                        </table>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>

        <?php //$this->load->view("invoices/invoice_expense") ?>
    </div>
</div>

<?php 
$status = array(array("id"=>"draft", "text"=>lang("draft")));
if($can_approve) {
    $status[] = array("id"=>"approved", "text"=>lang("approved"));
    $status[] = array("id"=>"cancelled", "text"=>lang("cancelled"));
    $status[] = array("id"=>"refunded", "text"=>lang("refunded"));
}
?>

<script type="text/javascript">
    $(document).ready(function () {
        var showInfo = false;
        if ("<?php echo $approval_status; ?>" == "not_approved") {
            showInfo = true;
        }
        $("#invoice-item-table").appTable({
            source: '<?php echo_uri("proforma_invoices/item_list_data/" . $invoice_info->id . "/") ?>',
            order: [[0, "asc"]],
            hideTools: true,
            displayLength: 100,
            stateSave: false,
            columns: [
                {visible: false, searchable: false},
                {title: '<?php echo lang("item") ?> ', "bSortable": false},
                {title: '<?php echo lang("quantity") ?>', "class": "text-right w15p qty", "bSortable": false},
                // {title: '<?php echo lang("sold") ?>', "class": "text-right w15p d_qty", "bSortable": false},
                {visible: false, searchable: false},
                {title: '<?php echo lang("rate") ?>', "class": "text-right w15p", "bSortable": false},
                {title: '<?php echo lang("tax") ?>', "class": "text-right w15p", "bSortable": false},
                {title: '<?php echo lang("total") ?>', "class": "text-right w15p total", "bSortable": false},
                {visible: showInfo, searchable: showInfo, title: '<i class="fa fa-bars"></i>', "class": "text-center option w100", "bSortable": false}
            ],
            onInitComplete: function () {
                //apply sortable
                $("#invoice-item-table").find("tbody").attr("id", "invoice-item-table-sortable");
                var $selector = $("#invoice-item-table-sortable");

                Sortable.create($selector[0], {
                    animation: 150,
                    chosenClass: "sortable-chosen",
                    ghostClass: "sortable-ghost",
                    handle: ".move-icon",
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
                            url: '<?php echo_uri("proforma_invoices/update_item_sort_values") ?>',
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
                $("#invoice-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            },
            onUndoSuccess: function (result) {
                $("#invoice-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            }
        });

        $("#edit_note").hide();
        $("#note_button").click(function(){
            $("#notes").hide();
            $("#edit_note").show();
            $(this).hide();
        });

         
       // initWYSIWYGEditor("#note_textarea", {height: 200});

        $("#edit-note-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                if (result.success) {
                    appAlert.success(result.message, {duration: 10000});
                    $("#notes").html(result.note);
                    $("#notes").show();
                    $("#note_button").show();
                    $("#edit_note").hide();
                } else {
                    appAlert.error(result.message);
                }
            }
        });

        $("#invoice-payment-table").appTable({
            source: '<?php echo_uri("proforma_invoice_payments/payment_list_data/" . $invoice_info->id . "/") ?>',
            order: [[0, "asc"]],
            columns: [
                {title: '<?php echo "id" ?>'},
                {targets: [0], visible: false, searchable: false},
                {visible: false, searchable: false},
                {visible: false, searchable: false},
                {title: '<?php echo lang("payment_date") ?> ', "class": "w15p", "iDataSort": 1},
                {title: '<?php echo lang("payment_method") ?>', "class": "w15p"},
                {title: '<?php echo lang("note") ?>', 'class':'w100'},
                {title: '<?php echo lang("status") ?>'},
                {title: '<?php echo lang("amount") ?>', "class": "text-right w15p"},
                {visible: false, searchable: false},
                /*{visible: false, searchable: false},*/
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            
            onDeleteSuccess: function (result) {
                $("#invoice-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            },
            onUndoSuccess: function (result) {
                $("#invoice-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
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
                    url: '<?php echo_uri("proforma_invoice_payments/update_status") ?>/0' + '/' + $(this).attr('data-id'),
                    showbuttons: false,
                    source: <?php echo json_encode($status) ?>,
                    success: function (response, newValue) {
                        if (response.success) {
                            $("#invoice-payment-table").DataTable().ajax.reload();
                            //location.reload();
                        }
                    }
                });
                $(this).editable("show");
            });

        //modify the delete confirmation texts
        $("#confirmationModalTitle").html("<?php echo lang('cancel') . "?"; ?>");
        $("#confirmDeleteButton").html("<i class='fa fa-times'></i> <?php echo lang("cancel"); ?>");
    });

    $('#invoice-item-table').on('click', '[data-action=qty]', function(){
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
                    //$table.closest('tr').find('.qty').html(result.quantity);
                    //$table.closest('tr').find('.total').html(result.total);
                    $('#invoice-item-table').DataTable().ajax.reload();
                    $("#invoice-total-section").html(result.invoice_total_view);
                    appAlert.success(result.message);
                }
            });

     });

    updateInvoiceStatusBar = function (invoiceId) {
        $.ajax({
            url: "<?php echo get_uri("proforma_invoices/get_invoice_status_bar"); ?>/" + invoiceId,
            success: function (result) {
                if (result) {
                    $("#invoice-status-bar").html(result);
                }
            }
        });
    };

    //print invoice
    $("#print-invoice-btn").click(function () {
        appLoader.show();

        $.ajax({
            url: "<?php echo get_uri('proforma_invoices/print_invoice/' . $invoice_info->id) ?>",
            dataType: 'json',
            success: function (result) {
                if (result.success) {
                    document.body.innerHTML = result.print_view; //add invoice's print view to the page

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

    //check invoice balance Due
    $("#approved").click(function () {
        appLoader.show();
        $.ajax({
            url: "<?php echo get_uri('proforma_invoices/update_approval_status/' . $invoice_info->id . '/approved') ?>",
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


    //check invoice balance Due
    $("#not_approved").click(function () {
        appLoader.show();
        $.ajax({
            url: "<?php echo get_uri('proforma_invoices/update_approval_status/' . $invoice_info->id . '/not_approved') ?>",
            dataType: 'json',
            success: function (result) {
                if (result.success) {
                    alert(result.revised);
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

<?php
//required to send email 

load_css(array(
    "assets/js/summernote/summernote.css",
));
load_js(array(
    "assets/js/summernote/summernote.min.js",
));
?>

