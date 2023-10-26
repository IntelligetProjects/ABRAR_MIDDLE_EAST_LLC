<div id="page-content" class="clearfix">
    <div style="max-width: 1000px; margin: auto;">
        <div class="page-title clearfix mt15">
            <h1><?php echo get_delivery_note_id($delivery_note_info->id); ?>
            </h1>
            <div class="title-button-group">
                <span class="dropdown inline-block mt10">
                    <button style="margin-right: 50px;" class="btn btn-info dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                        <i class='fa fa-cogs'></i> <?php echo lang('actions'); ?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <?php if ($status == "draft" && $can_approve) { ?>
                            <li role="presentation"><?php echo ajax_anchor(get_uri("delivery_notes/update_approval_status/" . $delivery_note_info->id . "/approved"), "<i class='fa fa-check'></i> " . lang('approved'), array("data-reload-on-success" => "1")); ?> </li>
                        <?php } else if ($status == "approved") { ?>
                            <li role="presentation"><?php echo ajax_anchor(get_uri("delivery_notes/update_approval_status/" . $delivery_note_info->id . "/draft"), "<i class='fa fa-close'></i> " . lang('draft'), array("data-reload-on-success" => "1")); ?> </li>
                        <?php } ?>
                        <?php if ($status == "approved") { ?>
                        <li role="presentation"><?php echo anchor(get_uri("delivery_notes/download_pdf/" . $delivery_note_info->id), "<i class='fa fa-download'></i> " . lang('download_pdf'), array("title" => lang('download_pdf'))); ?> </li>
                        <li role="presentation"><?php echo anchor(get_uri("delivery_notes/preview/" . $delivery_note_info->id . "/1"), "<i class='fa fa-search'></i> " . lang('delivery_note_preview'), array("title" => lang('delivery_note_preview'), "target" => "_blank")); ?> </li>
                        <li role="presentation"><?php echo js_anchor("<i class='fa fa-print'></i> " . lang('print'), array('title' => lang('print'), 'id' => 'print-delivery_note-btn')); ?> </li>
                        <?php } ?>
                        <?php if (($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_edit_delivery_notes") == "1") && $status == 'draft') { ?>                       
                        <li role="presentation"><?php echo modal_anchor(get_uri("delivery_notes/modal_form"), "<i class='fa fa-edit'></i> " . lang('edit'), array("title" => lang('edit'), "data-post-id" => $delivery_note_info->id, "role" => "menuitem", "tabindex" => "-1")); ?> </li>
                        <?php } ?>
                    </ul>
                </span>
            </div>
        </div>

        <div id="do-status-bar">
            <?php $this->load->view("delivery_notes/delivery_note_status_bar"); ?>
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
                    $delivery_note_style = get_setting("invoice_style");
                    $data = array(
                        "client_info" => $client_info,
                        "color" => $color,
                        "delivery_note_info" => $delivery_note_info
                    );

                    if ($delivery_note_style === "style_2") {
                        $this->load->view('delivery_notes/delivery_note_parts/header_style_2.php', $data);
                    } else {
                        $this->load->view('delivery_notes/delivery_note_parts/header_style_1.php', $data);
                    }
                    ?>
                </div>

                <div class="table-responsive mt15 pl15 pr15">
                    <table id="delivery_note-item-table" class="display" width="100%">            
                    </table>
                </div>

                <p class="b-t b-info pt10 m15"><?php echo nl2br($delivery_note_info->note); ?></p>

            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function () {
        var showInfo = false;
        if ("<?php echo $status; ?>" == "draft") {
            showInfo = true;
        }
        $("#delivery_note-item-table").appTable({

            source: '<?php echo_uri("delivery_notes/item_list_data/" . $delivery_note_info->id . "/") ?>',
            order: [[0, "asc"]],
            hideTools: true,
            displayLength: 100,
            stateSave: false,
            responsive: false,
            columns: [
                {visible: false, searchable: false},
                {title: '<?php echo lang("item") ?> ', "bSortable": false},
                {title: '<?php echo lang("quantity") ?>', "class": "text-right w15p qty", "bSortable": false},
                {visible: showInfo, searchable: showInfo, title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            onInitComplete: function () {
                //apply sortable
                $("#delivery_note-item-table").find("tbody").attr("id", "delivery_note-item-table-sortable");
                var $selector = $("#delivery_note-item-table-sortable");
                
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
                            url: '<?php echo_uri("delivery_notes/update_item_sort_values") ?>',
                            type: "POST",
                            data: {sort_values: data},
                            success: function () {
                                appLoader.hide();
                            }
                        });
                    }
                });

            },
        });
    });

    $('#delivery_note-item-table').on('click', '[data-action=qty]', function(){
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
                    $('#delivery_note-item-table').DataTable().ajax.reload();
                    appAlert.success(result.message);
                }
            });

     });

    //print delivery_note
    $("#print-delivery_note-btn").click(function () {
        appLoader.show();

        $.ajax({
            url: "<?php echo get_uri('delivery_notes/print_delivery_note/' . $delivery_note_info->id) ?>",
            dataType: 'json',
            success: function (result) {
                if (result.success) {
                    document.body.innerHTML = result.print_view; //add delivery_note's print view to the page

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

    //reload page after finishing print action
    window.onafterprint = function () {
        location.reload();
    };

</script>


