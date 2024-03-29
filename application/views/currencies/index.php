<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div id="sidebarSettings" class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "currencies";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <div class="panel panel-default">
                <div class="page-title clearfix">
                    <h4> <?php echo lang('currencies'); ?></h4>
                    <?php if ($can_add) { ?>
                    <div  class="title-button-group">
                        <?php echo modal_anchor(get_uri("currencies/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_currency'), array("class" => "btn btn-default", "title" => lang('add_currency'))); ?>
                    </div>
                    <?php }?>
                </div>
                <div class="table-responsive">
                    <table id="currencies-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#currencies-table").appTable({
            source: '<?php echo_uri("currencies/list_data") ?>',
            columns: [
                {title: '<?php echo lang("name"); ?>'},
                {title: '<?php echo lang("symbol"); ?>'},
                {title: '<?php echo lang("currency_rate"); ?>'},
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