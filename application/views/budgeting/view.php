

<div id="page-content" class="clearfix">
    <div style="margin-left: 20px!important;
    margin-right: 20px !important;margin: auto;">
        <div class="page-title clearfix mt15">
            <h1><?php echo get_budget_id($estimate_info->id); ?></h1>
            <div class="title-button-group">
                <?php if ($estimate_status == "draft") { ?>
                        <?php echo modal_anchor(get_uri("budgeting/item_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_project_budgeting'), array("class" => "btn btn-default", "title" => lang('add_project_budgeting'), "data-post-estimate_id" => $estimate_info->id)); ?>
                        <!-- <?php echo modal_anchor(get_uri("budgeting/items_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_items'), array("class" => "btn btn-default", "title" => lang('add_project_budgeting'), "data-post-estimate_id" => $estimate_info->id)); ?> -->
                <?php } ?>
                <span class="dropdown inline-block" style="margin-right:36px; margin-top: 10px">
                    <button class="btn btn-info dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true" >
                        <i class='fa fa-cogs'></i> <?php echo lang('actions'); ?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <?php
                        if ($estimate_status == "approved" || $estimate_status == "accepted"||$estimate_status == "declined") { ?>
                        <!-- <li role="presentation"><?php echo anchor(get_uri("budgeting/download_pdf/" . $estimate_info->id), "<i class='fa fa-download'></i> " . lang('download_pdf'), array("title" => lang('download_pdf'))); ?> </li>
                        <li role="presentation"><?php echo anchor(get_uri("budgeting/preview/" . $estimate_info->id . "/1"), "<i class='fa fa-search'></i> " . lang('estimate_preview'), array("title" => lang('estimate_preview')), array("target" => "_blank")); ?> </li> -->
                        <?php } ?>
                        <?php
                        if ($estimate_status == "draft") { ?>
                        <li role="presentation"><?php echo modal_anchor(get_uri("budgeting/modal_form"), "<i class='fa fa-edit'></i> " . lang('edit_budgeting'), array("title" => lang('edit_budgeting'), "data-post-id" => $estimate_info->id, "role" => "menuitem", "tabindex" => "-1")); ?> </li>
                        <?php } ?>
                        <!-- <li role="presentation"><?php echo modal_anchor(get_uri("budgeting/modal_form"), "<i class='fa fa-copy'></i> " . lang('clone_estimate'), array("data-post-is_clone" => true, "data-post-id" => $estimate_info->id, "title" => lang('clone_estimate'))); ?></li> -->

                        <!--don't show status changing option for leads-->
                        <?php
                        
                            if ($estimate_status == "draft" && $can_approve) {
                                ?>
                                <li role="presentation"><?php echo ajax_anchor(get_uri("budgeting/update_estimate_status/" . $estimate_info->id . "/approved"), "<i class='fa fa-check-circle'></i> " . lang('approve'), array("data-reload-on-success" => "1")); ?> </li>  
                            <?php } else if ($estimate_status == "approved") { ?>
                                <!-- <li role="presentation"><?php echo modal_anchor(get_uri("budgeting/send_estimate_modal_form/" . $estimate_info->id), "<i class='fa fa-send'></i> " . lang('send_to_client'), array("title" => lang('send_to_client'), "data-post-id" => $estimate_info->id, "role" => "menuitem", "tabindex" => "-1")); ?> </li> -->
                                <li role="presentation"><?php echo ajax_anchor(get_uri("budgeting/update_estimate_status/" . $estimate_info->id . "/accepted"), "<i class='fa fa-check-circle'></i> " . lang('mark_as_accepted'), array("data-reload-on-success" => "1")); ?> </li>
                                <li role="presentation"><?php echo ajax_anchor(get_uri("budgeting/update_estimate_status/" . $estimate_info->id . "/declined"), "<i class='fa fa-times-circle-o'></i> " . lang('mark_as_declined'), array("data-reload-on-success" => "1")); ?> </li>
                            <?php } else if ($estimate_status == "accepted") { ?>
                                <li role="presentation"><?php echo ajax_anchor(get_uri("budgeting/update_estimate_status/" . $estimate_info->id . "/declined"), "<i class='fa fa-times-circle-o'></i> " . lang('mark_as_declined'), array("data-reload-on-success" => "1")); ?> </li>
                            <?php } else if ($estimate_status == "declined") { ?>
                                <li role="presentation"><?php echo ajax_anchor(get_uri("budgeting/update_estimate_status/" . $estimate_info->id . "/accepted"), "<i class='fa fa-check-circle'></i> " . lang('mark_as_accepted'), array("data-reload-on-success" => "1")); ?> </li>
                                <?php
                            }
                        
                        ?>
                        <?php if ($estimate_status != "draft") { ?>
                                <li role="presentation"><?php echo ajax_anchor(get_uri("budgeting/update_estimate_status/" . $estimate_info->id . "/draft"), "<i class='fa fa-circle'></i> " . lang('draft'), array("data-reload-on-success" => "1")); ?> </li>
                        <?php } ?>

                        <!-- <?php if ($client_info->is_lead) { ?>
                            <li role="presentation"><?php echo anchor(get_uri("budgeting/download_pdf/" . $estimate_info->id), "<i class='fa fa-download'></i> " . lang('download_pdf'), array("title" => lang('download_pdf'))); ?> </li>
                            <li role="presentation"><?php echo anchor(get_uri("budgeting/preview/" . $estimate_info->id . "/1"), "<i class='fa fa-search'></i> " . lang('estimate_preview'), array("title" => lang('estimate_preview')), array("target" => "_blank")); ?> </li>
                            <li role="presentation"><?php echo modal_anchor(get_uri("budgeting/send_estimate_modal_form/" . $estimate_info->id), "<i class='fa fa-send'></i> " . lang('send_to_lead'), array("title" => lang('send_to_lead'), "data-post-id" => $estimate_info->id, "data-post-is_lead" => true, "role" => "menuitem", "tabindex" => "-1")); ?> </li>

                        <?php } ?> -->
 </ul>
                </span>
            </div>
        </div>
       
        <div class="mt15">
            <div class="panel panel-default p15 b-t">
                <div class="clearfix p20">
                    <!-- small font size is required to generate the pdf, overwrite that for screen -->
                    <style type="text/css"> .invoice-meta {font-size: 100% !important;}</style>

                    <?php
                    $color = get_setting("estimate_color");
                    if (!$color) {
                        $color = get_setting("invoice_color");
                    }
                    $style = get_setting("invoice_style");
                    ?>
                    
                    <?php
                    $data = array(
                        // "client_info" => $client_info,
                        "color" => $color ? $color : "#2AA384",
                        "estimate_info" => $estimate_info
                    );
                    if ($style === "style_2") {
                        $this->load->view('budgeting/estimate_parts/header_style_2.php', $data);
                    } else {
                        $this->load->view('budgeting/estimate_parts/header_style_1.php', $data);
                    }
                    ?>
                    <h2 style="text-align: center;font-size: 16px;">The Profit is: <?php echo $estimate_info->profit ?>%</h2>
                </div>

                <div class="table-responsive mt15 pl15 pr15">
                    <table id="estimate-item-table" class="display" width="100%">            
                    </table>
                </div>

                <div class="clearfix">
                    <div class="col-sm-8">

                    </div>
                    <div class="pull-right pr15" id="estimate-total-section">
                        <!-- <?php //$this->load->view("budgeting/estimate_total_section"); ?> -->
                    </div>
                </div>

                <p class="b-t b-info pt10 m15"><?php echo nl2br($estimate_info->note); ?></p>

            </div>
        </div>

    </div>
</div>



<script type="text/javascript">
    //RELOAD_VIEW_AFTER_UPDATE = true;
    $(document).ready(function () {
        var profit =<?php echo $estimate_info->profit ?>;
        localStorage.setItem('profit', profit);
        console.log(profit);
        var showInfo = false;
        if ("<?php echo $estimate_status; ?>" == "draft") {
            showInfo = true;
        }
        $("#estimate-item-table").appTable({
            source: '<?php echo_uri("budgeting/item_list_data/" . $estimate_info->id . "/") ?>',
            order: [[0, "asc"]],
            hideTools: true,
            responsive:false,
            stateSave: false,
            displayLength: 100,
            columns: [
                    {title: "<?php echo lang("item") ?> "},
                    {title: "<?php echo lang("actual_uom") ?> "},
                    {title: "<?php echo lang("actual_qty") ?> "},
                    {title: "<?php echo lang("material_cost") ?> "},
                    {title: "<?php echo lang("labour_cost") ?> "},
                    {title: "<?php echo lang("machinery") ?> "},
                    {title: "<?php echo lang("others") ?> "},
                    {title: "<?php echo lang("actual_rate") ?> "},
                    {title: "<?php echo lang("actual_total") ?> "},
                    {title: "<?php echo lang("estimation_rate") ?> "},
                    {title: "<?php echo lang("estimation_total") ?> "},

                
                
               
               
               
                
                
                {visible: showInfo, searchable: showInfo, title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
            onDeleteSuccess: function (result) {
                $("#estimate-total-section").html(result.estimate_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.estimate_id);
                }
            },
            onUndoSuccess: function (result) {
                $("#estimate-total-section").html(result.estimate_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.estimate_id);
                }
            }
        });
    });

    $('#estimate-item-table').on('click', '[data-action=qty]', function(){
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
                    $('#estimate-item-table').DataTable().ajax.reload();
                    $("#estimate-total-section").html(result.estimate_total_view);
                    appAlert.success(result.message);
                }
            });

     });

    updateInvoiceStatusBar = function (estimateId) {
        $.ajax({
            url: "<?php echo get_uri("budgeting/get_estimate_status_bar"); ?>/" + estimateId,
            success: function (result) {
                if (result) {
                    $("#estimate-status-bar").html(result);
                }
            }
        });
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
