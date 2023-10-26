<div id="page-content" class="p20 clearfix">
    <div class="row">
        <?php if($accounting == 1) {?>

            <div class="col-sm-12 col-lg-12">

        <?php } else { ?>
            <div id="sidebarSettings" class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "sale_items";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
        <?php } ?>
            <div class="panel panel-default">
                <div class="page-title clearfix">
                    <h4> <?php echo lang('sale_items'); ?></h4>
                    <div class="title-button-group">
                        <?php echo modal_anchor(get_uri("sale_items/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_item'), array("class" => "btn btn-default", "title" => lang('add_item'))); ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="sale_item-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#sale_item-table").appTable({
            source: '<?php echo_uri("sale_items/list_data") ?>',
            columns: [
                {title: '<?php echo lang("title") ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
        });
    });
</script>