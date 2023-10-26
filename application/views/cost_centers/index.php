<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div id="sidebarSettings" class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "cost_centers";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <div class="panel panel-default">
                <div class="page-title clearfix">
                    <h4> <?php echo lang('cost_centers'); ?></h4>
                    <div class="title-button-group">
                        <?php echo modal_anchor(get_uri("cost_centers/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_cost_center'), array("class" => "btn btn-default", "title" => lang('add_cost_center'))); ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="cost_centers-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#cost_centers-table").appTable({
            source: '<?php echo_uri("cost_centers/list_data") ?>',
            columns: [
                {title: '<?php echo lang("name"); ?>'},
                {title: '<?php echo lang("currency"); ?>'},
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