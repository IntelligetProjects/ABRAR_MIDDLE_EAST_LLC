<div id="page-content" class="p20 clearfix">
    <div class="row">
       
            <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "items_levels";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
     

<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h4> <?php echo lang('items_levels'); ?></h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("items_levels/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_level'), array("class" => "btn btn-default", "level_name" => lang('add_level'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="levels-table" class="table table-hoover" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#levels-table").appTable({
            source: '<?php echo_uri("Items_levels/list_data") ?>',
            columns: [
                {title: '<?php echo lang("id") ?>'},
                {title: '<?php echo lang("level_name") ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ]
        });
    });
</script>