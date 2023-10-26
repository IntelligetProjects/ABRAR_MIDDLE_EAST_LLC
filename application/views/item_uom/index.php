<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h4> <?php echo lang('item_uom'); ?></h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("item_uom/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_category'), array("class" => "btn btn-default", "title" => lang('add_category'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="category-table" class="table table-hoover" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
    
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#category-table").appTable({
            source: '<?php echo_uri("item_uom/list_data") ?>',
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